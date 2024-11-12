<?php

namespace App\Laravel\Controllers\Portal;

use App\Laravel\Models\{UserKYC,User,Role,UserInfo,Department,Course,Yearlevel,AuditTrail};

use App\Laravel\Requests\PageRequest;

use App\Laravel\Notifications\{UserAccountApproved,UserAccountRejected};

use Carbon,DB,Str,Helper,Mail;

class UsersKYCController extends Controller{
    protected $data;

    public function __construct(){
        parent::__construct();
        array_merge($this->data?:[], parent::get_data());
        $this->data['page_title'] .= " - Roles";
        $this->data['roles'] = ['' => "Select Role"] + Role::where('status', 'active')->pluck('name', 'name')->toArray();
        $this->data['departments'] = ['' => "Select Department"] + Department::pluck('dept_code', 'id')->toArray();
        $this->data['courses'] = ['' => "Select Courses"] + Course::pluck('course_code', 'id')->toArray();
        $this->data['yearlevels'] = ['' => "Select Yearlevel"] + Yearlevel::pluck('yearlevel_name', 'id')->toArray();
        $this->data['page_title'] .= " - Registration";        
        $this->per_page = env("DEFAULT_PER_PAGE", 10);
    }

    public function index(PageRequest $request){
        $this->data['page_title'] .= " - List of Appplications";

        $this->data['keyword'] = strtolower($request->get('keyword'));
        $this->data['selected_role'] = strtolower($request->input('role'));
        $this->data['selected_department'] = strtolower($request->input('department'));
        $this->data['selected_course'] = strtolower($request->input('course'));
        $this->data['selected_yearlevel'] = strtolower($request->input('yearlevel'));

        $first_record = UserKYC::orderBy('created_at', 'ASC')->first();
        $start_date = $request->get('start_date', now()->startOfMonth());
        if ($first_record) {
            $start_date = $request->get('start_date', $first_record->created_at->format("Y-m-d"));
        }

        $this->data['start_date'] = Carbon::parse($start_date)->format("Y-m-d");
        $this->data['end_date'] = Carbon::parse($request->get('end_date', now()))->format("Y-m-d");
        $this->data['roles'] = ['' => "All"] + Role::where('status', 'active')->pluck('name', 'name')->toArray();
        $this->data['departments'] = ['' => "All"] + Department::pluck('dept_code', 'id')->toArray();
        $this->data['courses'] = ['' => "All"] + Course::pluck('course_code', 'id')->toArray();
        $this->data['yearlevels'] = ['' => "All"] + Yearlevel::pluck('yearlevel_name', 'id')->toArray();

        $this->data['record'] = UserKYC::with(['processor', 'department', 'course', 'yearlevel'])->where(function ($query) {
            if (strlen($this->data['keyword']) > 0) {
                $query->whereRaw("LOWER(CONCAT(firstname, ' ', lastname)) LIKE '%{$this->data['keyword']}%'")
                    ->orWhereRaw("LOWER(id_number) LIKE '%{$this->data['keyword']}%'");
            }
        })
        ->where(function ($query) {
            if (strlen($this->data['selected_role']) > 0) {
                $query->where('role', $this->data['selected_role']);
            }
        }) 
        ->where(function ($query) {
            if (strlen($this->data['selected_department']) > 0) {
                $query->where('department_id', $this->data['selected_department']);
            }
        })
        ->where(function ($query) {
            if (strlen($this->data['selected_course']) > 0) {
                $query->where('course_id', $this->data['selected_course']);
            }
        })
        ->where(function ($query) {
            if (strlen($this->data['selected_yearlevel']) > 0) {
                $query->where('yearlevel_id', $this->data['selected_yearlevel']);
            }
        })
        ->where(function ($query) {
            return $query->where(function ($q) {
                if(strlen($this->data['start_date']) > 0) {
                    return $q->whereDate('created_at', '>=', Carbon::parse($this->data['start_date'])->format("Y-m-d"));
                }
            })->where(function ($q) {
                if(strlen($this->data['end_date']) > 0) {
                    return $q->whereDate('created_at', '<=', Carbon::parse($this->data['end_date'])->format("Y-m-d"));
                }
            });
        })
        ->where('status', 'pending')
        ->orderBy('created_at','DESC')
        ->paginate($this->per_page);

        return view('portal.users-kyc.index', $this->data);
    }

    public function approved(PageRequest $request){
        $this->data['page_title'] .= " - List of Appplications";

        $this->data['keyword'] = strtolower($request->get('keyword'));
        $this->data['selected_role'] = strtolower($request->input('role'));
        $this->data['selected_department'] = strtolower($request->input('department'));
        $this->data['selected_course'] = strtolower($request->input('course'));
        $this->data['selected_yearlevel'] = strtolower($request->input('yearlevel'));

        $first_record = UserKYC::orderBy('created_at', 'ASC')->first();
        $start_date = $request->get('start_date', now()->startOfMonth());
        if ($first_record) {
            $start_date = $request->get('start_date', $first_record->created_at->format("Y-m-d"));
        }

        $this->data['start_date'] = Carbon::parse($start_date)->format("Y-m-d");
        $this->data['end_date'] = Carbon::parse($request->get('end_date', now()))->format("Y-m-d");
        $this->data['roles'] = ['' => "All"] + Role::where('status', 'active')->pluck('name', 'name')->toArray();
        $this->data['departments'] = ['' => "All"] + Department::pluck('dept_code', 'id')->toArray();
        $this->data['courses'] = ['' => "All"] + Course::pluck('course_code', 'id')->toArray();
        $this->data['yearlevels'] = ['' => "All"] + Yearlevel::pluck('yearlevel_name', 'id')->toArray();

        $this->data['record'] = UserKYC::with(['processor', 'department', 'course', 'yearlevel'])->where(function ($query) {
            if (strlen($this->data['keyword']) > 0) {
                $query->whereRaw("LOWER(CONCAT(firstname, ' ', lastname)) LIKE '%{$this->data['keyword']}%'")
                    ->orWhereRaw("LOWER(id_number) LIKE '%{$this->data['keyword']}%'");
            }
        })
        ->where(function ($query) {
            if (strlen($this->data['selected_role']) > 0) {
                $query->where('role', $this->data['selected_role']);
            }
        }) 
        ->where(function ($query) {
            if (strlen($this->data['selected_department']) > 0) {
                $query->where('department_id', $this->data['selected_department']);
            }
        })
        ->where(function ($query) {
            if (strlen($this->data['selected_course']) > 0) {
                $query->where('course_id', $this->data['selected_course']);
            }
        })
        ->where(function ($query) {
            if (strlen($this->data['selected_yearlevel']) > 0) {
                $query->where('yearlevel_id', $this->data['selected_yearlevel']);
            }
        })
        ->where(function ($query) {
            return $query->where(function ($q) {
                if(strlen($this->data['start_date']) > 0) {
                    return $q->whereDate('created_at', '>=', Carbon::parse($this->data['start_date'])->format("Y-m-d"));
                }
            })->where(function ($q) {
                if(strlen($this->data['end_date']) > 0) {
                    return $q->whereDate('created_at', '<=', Carbon::parse($this->data['end_date'])->format("Y-m-d"));
                }
            });
        })
        ->where('status', 'approved')
        ->orderBy('created_at','DESC')
        ->paginate($this->per_page);

        return view('portal.users-kyc.approved', $this->data);
    }

    public function rejected(PageRequest $request){
        $this->data['page_title'] .= " - List of Appplications";

        $this->data['keyword'] = strtolower($request->get('keyword'));
        $this->data['selected_role'] = strtolower($request->input('role'));
        $this->data['selected_department'] = strtolower($request->input('department'));
        $this->data['selected_course'] = strtolower($request->input('course'));
        $this->data['selected_yearlevel'] = strtolower($request->input('yearlevel'));

        $first_record = UserKYC::orderBy('created_at', 'ASC')->first();
        $start_date = $request->get('start_date', now()->startOfMonth());
        if ($first_record) {
            $start_date = $request->get('start_date', $first_record->created_at->format("Y-m-d"));
        }

        $this->data['start_date'] = Carbon::parse($start_date)->format("Y-m-d");
        $this->data['end_date'] = Carbon::parse($request->get('end_date', now()))->format("Y-m-d");
        $this->data['roles'] = ['' => "All"] + Role::where('status', 'active')->pluck('name', 'name')->toArray();
        $this->data['departments'] = ['' => "All"] + Department::pluck('dept_code', 'id')->toArray();
        $this->data['courses'] = ['' => "All"] + Course::pluck('course_code', 'id')->toArray();
        $this->data['yearlevels'] = ['' => "All"] + Yearlevel::pluck('yearlevel_name', 'id')->toArray();

        $this->data['record'] = UserKYC::with(['processor', 'department', 'course', 'yearlevel'])->where(function ($query) {
            if (strlen($this->data['keyword']) > 0) {
                $query->whereRaw("LOWER(CONCAT(firstname, ' ', lastname)) LIKE '%{$this->data['keyword']}%'")
                    ->orWhereRaw("LOWER(id_number) LIKE '%{$this->data['keyword']}%'");
            }
        })
        ->where(function ($query) {
            if (strlen($this->data['selected_role']) > 0) {
                $query->where('role', $this->data['selected_role']);
            }
        }) 
        ->where(function ($query) {
            if (strlen($this->data['selected_department']) > 0) {
                $query->where('department_id', $this->data['selected_department']);
            }
        })
        ->where(function ($query) {
            if (strlen($this->data['selected_course']) > 0) {
                $query->where('course_id', $this->data['selected_course']);
            }
        })
        ->where(function ($query) {
            if (strlen($this->data['selected_yearlevel']) > 0) {
                $query->where('yearlevel_id', $this->data['selected_yearlevel']);
            }
        })
        ->where(function ($query) {
            return $query->where(function ($q) {
                if(strlen($this->data['start_date']) > 0) {
                    return $q->whereDate('created_at', '>=', Carbon::parse($this->data['start_date'])->format("Y-m-d"));
                }
            })->where(function ($q) {
                if(strlen($this->data['end_date']) > 0) {
                    return $q->whereDate('created_at', '<=', Carbon::parse($this->data['end_date'])->format("Y-m-d"));
                }
            });
        })
        ->where('status', 'rejected')
        ->orderBy('created_at','DESC')
        ->paginate($this->per_page);

        return view('portal.users-kyc.rejected', $this->data);
    }

    public function update_status(PageRequest $request,$id = null,$status = "pending"){
        $user_kyc = UserKYC::find($id);

        if(!$user_kyc){
            session()->flash('notification-status', "failed");
            session()->flash('notification-msg', "Record not found.");
            return redirect()->back();
        }

        DB::beginTransaction();
        try{
            $user_kyc->status = $status;
            $user_kyc->processor_id = $this->data['auth']->id;
            $user_kyc->process_at = Carbon::now();
            $user_kyc->save();

            $audit_trail = new AuditTrail;
            $audit_trail->user_id = $this->data['auth']->id;
            $audit_trail->process = "VERIFY_USER";
            $audit_trail->ip = $this->data['ip'];
            $audit_trail->remarks = "{$this->data['auth']->name} was verified the user and set status to {$user_kyc->status}.";
            $audit_trail->type = "USER_ACTION";
            $audit_trail->save();

            if($user_kyc->status == "rejected"){
                if(env('MAIL_SERVICE', false)){
                    $data = [
                        'name' => "{$user_kyc->firstname} {$user_kyc->middlename} {$user_kyc->lastname} {$user_kyc->suffix}",
                        'status' => strtoupper($user_kyc->status),
                        'email' => $user_kyc->email,
                        'date_time' => $user_kyc->updated_at->format('m/d/Y h:i A'),
                    ];
                    Mail::to($user_kyc->email)->send(new UserAccountRejected($data));
                }
            }

            if($user_kyc->status == "approved"){
                $user_info = new UserInfo;
                $user_info->firstname = $user_kyc->firstname;
                $user_info->middlename = $user_kyc->middlename;
                $user_info->lastname = $user_kyc->lastname;
                $user_info->suffix = $user_kyc->suffix;
                $user_info->birthdate = $user_kyc->birthdate;
                $user_info->contact_number = $user_kyc->contact_number;
                $user_info->email = $user_kyc->email;
                $user_info->address = $user_kyc->address;
                $user_info->id_number = $user_kyc->id_number;
                $user_info->role = $user_kyc->role;
                $user_info->department_id = $user_kyc->department_id;
                $user_info->course_id = $user_kyc->course_id;
                $user_info->yearlevel_id = $user_kyc->yearlevel_id;
                
                if($user_info->save()){
                    $password = Str::random(8);
                    
                    $user = new User;
                    $user->user_info_id = $user_info->id;
                    $user->name = "{$user_info->firstname} {$user_info->middlename} {$user_info->lastname} {$user_info->suffix}";
                    $user->username = $user_info->id_number;
                    $user->email = $user_info->email;
                    $user->status = "active";
                    $user->password = bcrypt($password);
                    $user->save();

                    $role = Role::where('name', $user_info->role)->where('guard_name','web')->first();
                    $user->assignRole($role);

                    if(env('MAIL_SERVICE', false)){
                        $data = [
                            'username' => $user->username,
                            'name' => $user->name,
                            'email' => $user->email,
                            'date_time' => $user->created_at->format('m/d/Y h:i A'),
                            'password' => $password
                        ];
                        Mail::to($user->email)->send(new UserAccountApproved($data));
                    }
                }
            }
               
            DB::commit();

            session()->flash('notification-status', "success");
            session()->flash('notification-msg', "User application has been {$user_kyc->status}.");
        }catch(\Exception $e){
            DB::rollBack();

            session()->flash('notification-status', "failed");
            session()->flash('notification-msg', "Server Error: Code #{$e->getLine()}");
            return redirect()->back();
        }
        
        return redirect()->route('portal.users_kyc.index');
    }

    public function show(PageRequest $request,$id = null){
        $this->data['page_title'] .= " - Information";
        $this->data['user_kyc'] = UserKYC::with(['processor', 'department', 'course', 'yearlevel'])->find($id);

        if(!$this->data['user_kyc']){
            session()->flash('notification-status', "failed");
            session()->flash('notification-msg', "Record not found.");
            return redirect()->route('portal.users_kyc.index');
        }

        return view('portal.users-kyc.show', $this->data);
    }
}
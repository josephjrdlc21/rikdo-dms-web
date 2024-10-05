<?php

namespace App\Laravel\Controllers\Portal;

use App\Laravel\Models\{UserKYC,User,Role,UserInfo,Department,Course,Yearlevel};

use App\Laravel\Requests\PageRequest;
//use App\Laravel\Requests\Portal\UserKYCRequest;

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
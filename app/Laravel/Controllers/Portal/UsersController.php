<?php

namespace App\Laravel\Controllers\Portal;

use App\Laravel\Models\{User,Role,UserInfo,Department,Course,Yearlevel};

use App\Laravel\Requests\PageRequest;
use App\Laravel\Requests\Portal\UserRequest;

use App\Laravel\Notifications\{UserAccountCreatedSuccess,UserAccountResetPasswordSuccess,UserAccountUpdated,UserAccountChangeStatus,UserAccountRemoved};

use Carbon,DB,Str,Helper,Mail;

class UsersController extends Controller{
    protected $data;

    public function __construct(){
        parent::__construct();
        array_merge($this->data?:[], parent::get_data());
        $this->data['statuses'] = ['' => "Select Status", 'active' => "Active", 'inactive' => "Inactive"];
        $this->data['roles'] = ['' => "Select Role"] + Role::where('status', 'active')->pluck('name', 'name')->toArray();
        $this->data['departments'] = ['' => "Select Department"] + Department::pluck('dept_code', 'id')->toArray();
        $this->data['courses'] = ['' => "Select Courses"] + Course::pluck('course_code', 'id')->toArray();
        $this->data['yearlevels'] = ['' => "Select Yearlevel"] + Yearlevel::pluck('yearlevel_name', 'id')->toArray();
        $this->data['page_title'] .= " - Account Management";
        $this->per_page = env("DEFAULT_PER_PAGE", 10);
    }

    public function index(PageRequest $request){
        $this->data['page_title'] .= " - List of Users";

        $this->data['keyword'] = strtolower($request->get('keyword'));
        $this->data['selected_status'] = strtolower($request->input('status'));
        $this->data['selected_role'] = strtolower($request->input('role'));
        $this->data['selected_department'] = strtolower($request->input('department'));
        $this->data['selected_course'] = strtolower($request->input('course'));
        $this->data['selected_yearlevel'] = strtolower($request->input('yearlevel'));

        $first_record = User::where('id','!=',1)->orderBy('created_at', 'ASC')->first();
        $start_date = $request->get('start_date', now()->startOfMonth());
        if ($first_record) {
            $start_date = $request->get('start_date', $first_record->created_at->format("Y-m-d"));
        }

        $this->data['start_date'] = Carbon::parse($start_date)->format("Y-m-d");
        $this->data['end_date'] = Carbon::parse($request->get('end_date', now()))->format("Y-m-d");
        $this->data['statuses'] = ['' => "All", 'active' => "Active", 'inactive' => "Inactive"];
        $this->data['roles'] = ['' => "All"] + Role::where('status', 'active')->pluck('name', 'name')->toArray();
        $this->data['departments'] = ['' => "All"] + Department::pluck('dept_code', 'id')->toArray();
        $this->data['courses'] = ['' => "All"] + Course::pluck('course_code', 'id')->toArray();
        $this->data['yearlevels'] = ['' => "All"] + Yearlevel::pluck('yearlevel_name', 'id')->toArray();

        $this->data['record'] = User::with(['user_info', 'user_info.department', 'user_info.course', 'user_info.yearlevel'])->where(function ($query) {
            if (strlen($this->data['keyword']) > 0) {
                $query->whereRaw("LOWER(name) LIKE '%{$this->data['keyword']}%'")
                    ->orWhereHas('user_info', function ($q) {
                        $q->whereRaw("LOWER(id_number) LIKE '%{$this->data['keyword']}%'");
                    });
            }
        })
        ->where(function ($query) {
            if (strlen($this->data['selected_status']) > 0) {
                $query->where('status', $this->data['selected_status']);
            }
        })
        ->whereHas('user_info', function ($query) {
            if (strlen($this->data['selected_role']) > 0) {
                $query->where('role', $this->data['selected_role']);
            }
        })
        ->whereHas('user_info', function ($query) {
            if (strlen($this->data['selected_department']) > 0) {
                $query->where('department_id', $this->data['selected_department']);
            }
        })
        ->whereHas('user_info', function ($query) {
            if (strlen($this->data['selected_course']) > 0) {
                $query->where('course_id', $this->data['selected_course']);
            }
        })
        ->whereHas('user_info', function ($query) {
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
        ->where('id','!=',1)
        ->orderBy('created_at','DESC')
        ->paginate($this->per_page);

        return view('portal.users.index', $this->data);
    }

    public function create(PageRequest $request){
        $this->data['page_title'] .= " - Create User";

        if(!$request->session()->has('current_progress')){
            $request->session()->put('current_progress', '1');
        }

        if(!$request->session()->has('max_progress')){
            $request->session()->put('max_progress', '1');
        }

        $current_progress = $request->session()->get('current_progress');

        switch ($current_progress){
            case '1':
                return view('portal.users.create-personal-info', $this->data);
                break;
            case '2':
                return view('portal.users.create-credential', $this->data);
                break;
            case '3':
                return view('portal.users.account-created', $this->data);
                break;
            default:
                return redirect()->route('portal.users.index');
                break;
        }
    }

    public function store(UserRequest $request){
        $current_progress = $request->session()->get('current_progress', '1');
        $max_progress = $request->session()->get('max_progress', '1');
        
        switch ($current_progress){
            case '1':
                session()->put('personal_info.firstname', strtoupper($request->input('firstname')));
                session()->put('personal_info.middlename', strtoupper($request->input('middlename')));
                session()->put('personal_info.lastname', strtoupper($request->input('lastname')));
                session()->put('personal_info.suffix', strtoupper($request->input('suffix')));
                session()->put('personal_info.birthdate', $request->input('birthdate'));
                session()->put('personal_info.contact', Helper::format_phone($request->input('contact')));
                session()->put('personal_info.email', strtolower($request->input('email')));
                session()->put('personal_info.address', strtoupper($request->input('address')));

                $request->session()->put('current_progress', '2');
                $request->session()->put('max_progress', max($max_progress, '2'));

                return redirect()->route('portal.users.create');
                break;
            case '2':
                session()->put('credential.id_number', $request->input('id_number'));
                session()->put('credential.role', $request->input('role'));
                session()->put('credential.department', $request->input('department'));
                session()->put('credential.course', $request->input('course'));
                session()->put('credential.yearlevel', $request->input('yearlevel'));

                DB::beginTransaction();
                try{
                    $user = new UserInfo;
                    $user->firstname = session()->get('personal_info.firstname');
                    $user->middlename = session()->get('personal_info.middlename');
                    $user->lastname = session()->get('personal_info.lastname');
                    $user->suffix = session()->get('personal_info.suffix');
                    $user->birthdate = session()->get('personal_info.birthdate');
                    $user->contact_number = session()->get('personal_info.contact');
                    $user->email = session()->get('personal_info.email');
                    $user->address = session()->get('personal_info.address');
                    $user->id_number = session()->get('credential.id_number');
                    $user->role = session()->get('credential.role');
                    $user->department_id = session()->get('credential.department');
                    $user->course_id = session()->get('credential.course');
                    $user->yearlevel_id = session()->get('credential.yearlevel');
                    
                    if($user->save()){
                        $password = Str::random(8);

                        $cred = new User;
                        $cred->user_info_id = $user->id;
                        $cred->name = "{$user->firstname} {$user->middlename} {$user->lastname} {$user->suffix}";
                        $cred->username = $user->id_number;
                        $cred->email = $user->email;
                        $cred->status = "active";
                        $cred->password = bcrypt($password);
                        $cred->save();

                        $role = Role::where('name', $user->role)->where('guard_name','web')->first();
                        $cred->assignRole($role);

                        if(env('MAIL_SERVICE', false)){
                            $data = [
                                'username' => $cred->username,
                                'name' => $cred->name,
                                'email' => $cred->email,
                                'date_time' => $cred->created_at->format('m/d/Y h:i A'),
                                'password' => $password
                            ];
                            Mail::to($cred->email)->send(new UserAccountCreatedSuccess($data));
                        }
                    }

                    DB::commit();

                    session()->forget('personal_info');
                    session()->forget('credential');

                    $request->session()->put('current_progress', '3');
                    $request->session()->put('max_progress', max($max_progress, '3'));
                }catch(\Exception $e){
                    DB::rollback();
            
                    session()->flash('notification-status', "failed");
                    session()->flash('notification-msg', "Server Error: Code #{$e->getLine()}");
                    return redirect()->back();
                }

                return redirect()->route('portal.users.create');
                break;      
            default:
                return redirect()->route('portal.users.index');
                break;
        }
    }

    public function edit(PageRequest $request,$id = null){
        $this->data['page_title'] .= " - Edit User";
        $this->data['user'] = User::with(['user_info', 'user_info.department', 'user_info.course', 'user_info.yearlevel'])->find($id);

        if(!$this->data['user']){
			session()->flash('notification-status', "failed");
			session()->flash('notification-msg', "Record not found.");
			return redirect()->route('portal.users.index');
		}

        if(!$request->session()->has('current_progress')){
            $request->session()->put('current_progress', '1');
        }

        if(!$request->session()->has('max_progress')){
            $request->session()->put('max_progress', '2');
        }

        $current_progress = $request->session()->get('current_progress');

        switch ($current_progress){
            case '1':
                return view('portal.users.edit-personal-info', $this->data);
                break;
            case '2':
                return view('portal.users.edit-credential', $this->data);
                break;
            case '3':
                return view('portal.users.account-updated', $this->data);
                break;
            default:
                return redirect()->route('portal.users.index');
                break;
        }
    }

    public function update(UserRequest $request,$id = null){
        $user = User::find($id);

        if(!$user){
            session()->flash('notification-status', "failed");
            session()->flash('notification-msg', "Record not found.");
            return redirect()->route('portal.users.index');
        }

        $user_info = UserInfo::with(['department', 'course', 'yearlevel'])->where('id', $user->user_info_id)->first();

        $current_progress = $request->session()->get('current_progress', '1');
        $max_progress = $request->session()->get('max_progress', '2');
        
        switch ($current_progress){
            case '1':
                session()->put('personal_info.firstname', strtoupper($request->input('firstname')) ?? $user_info->firstname);
                session()->put('personal_info.middlename', strtoupper($request->input('middlename')) ?? $user_info->middlename);
                session()->put('personal_info.lastname', strtoupper($request->input('lastname')) ?? $user_info->lastname);
                session()->put('personal_info.suffix', strtoupper($request->input('suffix')) ?? $user_info->suffix);
                session()->put('personal_info.birthdate', $request->input('birthdate') ?? $user_info->birthdate);
                session()->put('personal_info.contact', Helper::format_phone($request->input('contact')) ?? $user_info->contact_number);
                session()->put('personal_info.email', strtolower($request->input('email')) ?? $user_info->email);
                session()->put('personal_info.address', strtoupper($request->input('address')) ?? $user_info->address);

                $request->session()->put('current_progress', '2');
                $request->session()->put('max_progress', max($max_progress, '2'));

                return redirect()->route('portal.users.edit', [$id]);
                break;
            case '2':
                session()->put('credential.id_number', $request->input('id_number') ?? $user_info->id_number);
                session()->put('credential.role', $request->input('role') ?? $user_info->role);
                session()->put('credential.department', $request->input('department') ?? $user_info->department->id);
                session()->put('credential.course', $request->input('course') ?? $user_info->course->id);
                session()->put('credential.yearlevel', $request->input('yearlevel') ?? $user_info->yearlevel->id);

                DB::beginTransaction();
                try{
                    $user_info->firstname = session()->get('personal_info.firstname') ?? $user_info->firstname;
                    $user_info->middlename = session()->get('personal_info.middlename') ?? $user_info->middlename;
                    $user_info->lastname = session()->get('personal_info.lastname') ?? $user_info->lastname;
                    $user_info->suffix = session()->get('personal_info.suffix' ?? $user_info->suffix);
                    $user_info->birthdate = session()->get('personal_info.birthdate') ?? $user_info->birthdate;
                    $user_info->contact_number = session()->get('personal_info.contact') ?? $user_info->contact_number;
                    $user_info->email = session()->get('personal_info.email') ?? $user_info->email;
                    $user_info->address = session()->get('personal_info.address') ?? $user_info->address;
                    $user_info->id_number = session()->get('credential.id_number') ?? $user_info->id_number;
                    $user_info->role = session()->get('credential.role') ?? $user_info->role;
                    $user_info->department_id = session()->get('credential.department') ?? $user_info->department->id;
                    $user_info->course_id = session()->get('credential.course') ?? $user_info->course->id;
                    $user_info->yearlevel_id = session()->get('credential.yearlevel') ?? $user_info->yearlevel->id;
                    
                    if($user_info->save()){
                        $user->name = "{$user_info->firstname} {$user_info->middlename} {$user_info->lastname} {$user_info->suffix}";
                        $user->username = $user_info->id_number;
                        $user->email = $user_info->email;
                        $user->save();

                        $role = Role::where('name', $user_info->role)->where('guard_name','web')->first();
                        $user->syncRoles($role);

                        if(env('MAIL_SERVICE', false)){
                            $data = [
                                'name' => $user->name,
                                'email' => $user->email,
                                'date_time' => $user->updated_at > $user_info->updated_at ? $user->updated_at->format('m/d/Y h:i A') : $user_info->updated_at->format('m/d/Y h:i A'),
                            ];
                            Mail::to($user->email)->send(new UserAccountUpdated($data));
                        }
                    }

                    DB::commit();

                    session()->forget('personal_info');
                    session()->forget('credential');

                    $request->session()->put('current_progress', '3');
                    $request->session()->put('max_progress', max($max_progress, '3'));
                }catch(\Exception $e){
                    DB::rollback();
            
                    session()->flash('notification-status', "failed");
                    session()->flash('notification-msg', "Server Error: Code #{$e->getLine()}");
                    return redirect()->back();
                }

                return redirect()->route('portal.users.edit', [$id]);
                break;      
            default:
                return redirect()->route('portal.users.index');
                break;
        }
    }

    public function update_password(PageRequest $request,$id = null){
        $user = User::find($id);

        if(!$user){
            session()->flash('notification-status', "failed");
            session()->flash('notification-msg', "Record not found.");
            return redirect()->route('portal.users.index');
        }

        DB::beginTransaction();
        try{
            $password = Str::random(8);

            $user->password = bcrypt($password);
            $user->save();

            if(env('MAIL_SERVICE', false)){
                $data = [
                    'name' => $user->name,
                    'email' => $user->email,
                    'date_time' => $user->updated_at->format('m/d/Y h:i A'),
                    'password' => $password
                ];
                Mail::to($user->email)->send(new UserAccountResetPasswordSuccess($data));
            }

            DB::commit();

            session()->flash('notification-status', "success");
            session()->flash('notification-msg', "User password has been reset. New password was sent to email.");
        }catch(\Exception $e){
            DB::rollback();
    
            session()->flash('notification-status', "failed");
            session()->flash('notification-msg', "Server Error: Code #{$e->getLine()}");
            return redirect()->back();
        }

        return redirect()->route('portal.users.index');
    }

    public function update_status(PageRequest $request,$id = null){
        $user = User::find($id);

        if(!$user){
            session()->flash('notification-status', "failed");
            session()->flash('notification-msg', "Record not found.");
            return redirect()->route('portal.users.index');
        }

        DB::beginTransaction();
        try{
            $user->status = $user->status === 'active' ? 'inactive' : 'active';
            $user->save();

            if(env('MAIL_SERVICE', false)){
                $data = [
                    'name' => $user->name,
                    'status' => strtoupper($user->status),
                    'email' => $user->email,
                    'date_time' => $user->updated_at->format('m/d/Y h:i A'),
                ];
                Mail::to($user->email)->send(new UserAccountChangeStatus($data));
            }

            DB::commit();

            session()->flash('notification-status', "success");
            session()->flash('notification-msg', "User status has been set to {$user->status}.");
        }catch(\Exception $e){
            DB::rollback();
    
            session()->flash('notification-status', "failed");
            session()->flash('notification-msg', "Server Error: Code #{$e->getLine()}");
            return redirect()->back();
        }

        return redirect()->route('portal.users.index');
    }

    public function show(PageRequest $request,$id = null){
        $this->data['page_title'] .= " - Information";
        $this->data['user'] = User::with(['user_info', 'user_info.department', 'user_info.course', 'user_info.yearlevel'])->find($id);

        if(!$this->data['user']){
            session()->flash('notification-status', "failed");
            session()->flash('notification-msg', "Record not found.");
            return redirect()->route('portal.users.index');
        }

        return view('portal.users.show', $this->data);
    }

    public function destroy(PageRequest $request,$id = null){
        $user = User::find($id);

        if(!$user){
            session()->flash('notification-status', "failed");
            session()->flash('notification-msg', "Record not found.");
            return redirect()->route('portal.users.index');
        }
        
        DB::beginTransaction();
        try{
            $user_info = UserInfo::find($user->user_info_id);
            $user_info->delete();

            $user->delete();

            if(env('MAIL_SERVICE', false)){
                $data = [
                    'name' => $user->name,
                    'email' => $user->email,
                    'date_time' => $user->deleted_at->format('m/d/Y h:i A'),
                ];
                Mail::to($user->email)->send(new UserAccountRemoved($data));
            }

            DB::commit();

            session()->flash('notification-status', "success");
            session()->flash('notification-msg', "User has been deleted.");        
            return redirect()->route('portal.users.index');
        }catch(\Exception $e){
            DB::rollback();
    
            session()->flash('notification-status', "failed");
            session()->flash('notification-msg', "Server Error: Code #{$e->getLine()}");
            return redirect()->back();
        }

        session()->flash('notification-status', "warning");
        session()->flash('notification-msg', "Unable to delete user.");
        return redirect()->back();
    }

    public function cancel(PageRequest $request){
        session()->forget('personal_info');
        session()->forget('credential');
        session()->forget('current_progress');
        session()->forget('max_progress');

        session()->flash('notification-status', "info");
        session()->flash('notification-msg', "Create or Update user account has been cancelled.");
        return redirect()->route('portal.users.index');
    }

    public function success(PageRequest $request){
        session()->forget('personal_info');
        session()->forget('credential');
        session()->forget('current_progress');
        session()->forget('max_progress');

        session()->flash('notification-status', "success");
        session()->flash('notification-msg', "User account has been created or been updated.");
        return redirect()->route('portal.users.index');
    }

    public function step_back(PageRequest $request,$step = null,$id = null){
        $max_progress = $request->session()->get('max_progress', '1');

        if(in_array($step, ['1', '2', '3']) && $step <= $max_progress){
            session()->put('current_progress', $step);
        }

        if(!is_null($id)){
            return redirect()->route('portal.users.edit', [$id]);
        }

        return redirect()->route('portal.users.create');
    }
}
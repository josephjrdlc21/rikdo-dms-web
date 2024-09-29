<?php

namespace App\Laravel\Controllers\Portal;

use App\Laravel\Models\{User,Role,UserInfo,Department,Course,Yearlevel};

use App\Laravel\Requests\PageRequest;
//use App\Laravel\Requests\Portal\UserRequest;

use Carbon,DB,Str,Helper,Mail;

class UsersController extends Controller{
    protected $data;

    public function __construct(){
        parent::__construct();
        array_merge($this->data?:[], parent::get_data());
        $this->data['statuses'] = ['' => "-- Select Status -- ", 'active' => "Active", 'inactive' => "Inactive"];
        $this->data['roles'] = ['' => "-- Select Role --"] + Role::where('status', 'active')->pluck('name', 'name')->toArray();
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
                return $query
                    ->whereRaw("LOWER(name) LIKE '%{$this->data['keyword']}%'")
                    ->orWhereHas('user_info', function ($q) {
                        $q->whereRaw("LOWER(id_number) LIKE '%{$this->data['keyword']}%'");
                    });
            }
        })
        ->where(function ($query) {
            if (strlen($this->data['selected_status']) > 0) {
                return $query->where('status', $this->data['selected_status']);
            }
        })
        ->whereHas('user_info', function ($query) {
            if (strlen($this->data['selected_role']) > 0) {
                $query->where('role', $this->data['selected_role']);
            }
        })
        ->whereHas('user_info', function ($query) {
            if (strlen($this->data['selected_department']) > 0) {
                $query->whereHas('department', function ($q) {
                    $q->where('department_id', $this->data['selected_department']);
                });
            }
        })
        ->whereHas('user_info', function ($query) {
            if (strlen($this->data['selected_course']) > 0) {
                $query->whereHas('course', function ($q) {
                    $q->where('course_id', $this->data['selected_course']);
                });
            }
        })
        ->whereHas('user_info', function ($query) {
            if (strlen($this->data['selected_yearlevel']) > 0) {
                $query->whereHas('yearlevel', function ($q) {
                    $q->where('yearlevel_id', $this->data['selected_yearlevel']);
                });
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
}
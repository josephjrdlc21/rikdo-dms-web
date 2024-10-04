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
        $this->data['statuses'] = ['' => "Select Status", 'active' => "Active", 'inactive' => "Inactive"];
        $this->data['roles'] = ['' => "Select Role"] + Role::where('status', 'active')->pluck('name', 'name')->toArray();
        $this->data['departments'] = ['' => "Select Department"] + Department::pluck('dept_code', 'id')->toArray();
        $this->data['courses'] = ['' => "Select Courses"] + Course::pluck('course_code', 'id')->toArray();
        $this->data['yearlevels'] = ['' => "Select Yearlevel"] + Yearlevel::pluck('yearlevel_name', 'id')->toArray();
        $this->data['page_title'] .= " - Registration";        
        $this->per_page = env("DEFAULT_PER_PAGE", 10);
    }

    public function index(PageRequest $request){
        
    }
}
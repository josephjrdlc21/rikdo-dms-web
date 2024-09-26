<?php

namespace App\Laravel\Controllers\Portal;

use App\Laravel\Models\{Role,Permission};

use App\Laravel\Requests\PageRequest;
use App\Laravel\Requests\Portal\RoleRequest;

use Carbon,DB,Str;

class RolesController extends Controller{
    protected $data;

    public function __construct(){
        parent::__construct();
        array_merge($this->data?:[], parent::get_data());
        $this->data['page_title'] .= " - Roles";
        $this->data['statuses'] = ['' => "-- Select Status -- ",'active' => "Active",'inactive' => "Inactive"];
        $this->per_page = env("DEFAULT_PER_PAGE", 10);
    }

    public function index(PageRequest $request){
        $this->data['page_title'] .= " - List of Roles";

        $this->data['keyword'] = strtolower($request->get('keyword'));
        $this->data['selected_status'] = $request->input('status');

        $first_record = Role::orderBy('created_at', 'ASC')->first();
        $start_date = $request->get('start_date', now()->startOfMonth());
        if ($first_record) {
            $start_date = $request->get('start_date', $first_record->created_at->format("Y-m-d"));
        }

        $this->data['start_date'] = Carbon::parse($start_date)->format("Y-m-d");
        $this->data['end_date'] = Carbon::parse($request->get('end_date', now()))->format("Y-m-d");
        $this->data['statuses'] = ['' => "All",'active' => "Active",'inactive' => "Inactive"];

        $this->data['record'] = Role::where(function ($query) {
            if (strlen($this->data['keyword']) > 0) {
                return $query
                    ->whereRaw("LOWER(name) LIKE '%{$this->data['keyword']}%'");
            }
        })
        ->where(function ($query) {
            if (strlen($this->data['selected_status']) > 0) {
                return $query->where('status', $this->data['selected_status']);
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
        ->orderBy('created_at','DESC')
        ->paginate($this->per_page);

        return view('portal.cms.roles.index', $this->data);
    }

    public function create(PageRequest $request){
        $this->data['page_title'] .= " - Create Role";
        $this->data['permissions'] = Permission::all()->groupBy('module_name');

        return view('portal.cms.roles.create', $this->data);
    }

    public function store(RoleRequest $request){
        DB::beginTransaction();
        try {
            $role = new Role;
            $role->guard_name = 'web';
            $role->name = Str::lower($request->input('role'));
            $role->status = Str::lower($request->input('status'));
            $role->save();

            $role->syncPermissions($request->input('permissions'));

            DB::commit();

            session()->flash('notification-status', "success");
            session()->flash('notification-msg', "New role has been created.");
            return redirect()->route('portal.cms.roles.index');
        }catch(\Exception $e){
            DB::rollback();
            
            session()->flash('notification-status', "failed");
            session()->flash('notification-msg', "Server Error: Code #{$e->getLine()}");
            return redirect()->back();
        }

        session()->flash('notification-status', "warning");
        session()->flash('notification-msg', "Unable to create new role.");
        return redirect()->back();
    }

    public function edit(PageRequest $request,$id = null){
        $this->data['page_title'] .= " - Update Role";
        $this->data['role'] = Role::find($id);

        if(!$this->data['role']){
			session()->flash('notification-status',"failed");
			session()->flash('notification-msg',"Record not found.");
			return redirect()->route('portal.cms.roles.index');
		}

        $this->data['permissions'] = Permission::all()->groupBy('module_name');

        return view('portal.cms.roles.edit', $this->data);
    }

    public function update(RoleRequest $request,$id = null){
        $role = Role::find($id);

        if(!$role){
            session()->flash('notification-status', "failed");
            session()->flash('notification-msg', "Record not found.");
            return redirect()->route('portal.cms.roles.index');
        }

        DB::beginTransaction();
        try {
            $role->name = Str::lower($request->input('role'));
            $role->status = Str::lower($request->input('status'));
            $role->save();
            
            $role->syncPermissions($request->input('permissions'));

            DB::commit();

            session()->flash('notification-status', "success");
            session()->flash('notification-msg', "Role has been updated.");
            return redirect()->route('portal.cms.roles.index');
        }catch(\Exception $e){
            DB::rollback();
            
            session()->flash('notification-status', "failed");
            session()->flash('notification-msg', "Server Error: Code #{$e->getLine()}");
            return redirect()->back();
        }

        session()->flash('notification-status', "warning");
        session()->flash('notification-msg', "Unable to update the role.");
        return redirect()->back();
    }
}
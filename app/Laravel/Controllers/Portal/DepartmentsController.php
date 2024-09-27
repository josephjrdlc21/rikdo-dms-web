<?php

namespace App\Laravel\Controllers\Portal;

use App\Laravel\Models\Department;

use App\Laravel\Requests\PageRequest;
use App\Laravel\Requests\Portal\DepartmentRequest;

use Carbon,DB;

class DepartmentsController extends Controller{
    protected $data;

    public function __construct(){
        parent::__construct();
        array_merge($this->data?:[], parent::get_data());
        $this->data['page_title'] .= " - Departments";
        $this->per_page = env("DEFAULT_PER_PAGE", 10);
    }

    public function index(PageRequest $request){
        $this->data['page_title'] .= " - List of Department";

        $this->data['keyword'] = strtolower($request->get('keyword'));

        $first_record = Department::orderBy('created_at', 'ASC')->first();
        $start_date = $request->get('start_date', now()->startOfMonth());
        if ($first_record) {
            $start_date = $request->get('start_date', $first_record->created_at->format("Y-m-d"));
        }

        $this->data['start_date'] = Carbon::parse($start_date)->format("Y-m-d");
        $this->data['end_date'] = Carbon::parse($request->get('end_date', now()))->format("Y-m-d");

        $this->data['record'] = Department::where(function ($query) {
            if (strlen($this->data['keyword']) > 0) {
                return $query
                    ->whereRaw("LOWER(dept_code) LIKE '%{$this->data['keyword']}%'")
                    ->orWhereRaw("LOWER(dept_name) LIKE '%{$this->data['keyword']}%'");
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

        return view('portal.cms.departments.index', $this->data);
    }

    public function create(PageRequest $request){
        $this->data['page_title'] .= " - Create Department";

        return view('portal.cms.departments.create', $this->data);
    }

    public function store(DepartmentRequest $request){
        DB::beginTransaction();
        try {
            $department = new Department;
            $department->dept_code = $request->input('dept_code');
            $department->dept_name = $request->input('dept_name');
            $department->save();

            DB::commit();

            session()->flash('notification-status', "success");
            session()->flash('notification-msg', "New department has been created.");
            return redirect()->route('portal.cms.departments.index');
        }catch(\Exception $e){
            DB::rollback();
            
            session()->flash('notification-status', "failed");
            session()->flash('notification-msg', "Server Error: Code #{$e->getLine()}");
            return redirect()->back();
        }

        session()->flash('notification-status', "warning");
        session()->flash('notification-msg', "Unable to create new department.");
        return redirect()->back();
    }
    
    public function edit(PageRequest $request,$id = null){
        $this->data['page_title'] .= " - Update Department";
        $this->data['department'] = Department::find($id);

        if(!$this->data['department']){
			session()->flash('notification-status', "failed");
			session()->flash('notification-msg', "Record not found.");
			return redirect()->route('portal.cms.departments.index');
		}

        return view('portal.cms.departments.edit', $this->data);
    }

    public function update(DepartmentRequest $request,$id = null){
        $department = Department::find($id);

        if(!$department){
            session()->flash('notification-status', "failed");
            session()->flash('notification-msg', "Record not found.");
            return redirect()->route('portal.cms.departments.index');
        }

        DB::beginTransaction();
        try{
            $department->dept_code = $request->input('dept_code');
            $department->dept_name = $request->input('dept_name');
            $department->save();

            DB::commit();

            session()->flash('notification-status', "success");
            session()->flash('notification-msg', "Department has been updated.");
            return redirect()->route('portal.cms.departments.index');
        }catch(\Exception $e){
            DB::rollback();

            session()->flash('notification-status', "failed");
            session()->flash('notification-msg', "Server Error: Code #{$e->getLine()}");
            return redirect()->route('portal.cms.departments.index');
        }

        session()->flash('notification-status', "warning");
        session()->flash('notification-msg', "Unable to update department.");
        return redirect()->back();
    }

    public function destroy(PageRequest $request,$id = null){
        $department = Department::find($id);

        if(!$department){
            session()->flash('notification-status', "failed");
            session()->flash('notification-msg', "Record not found.");
            return redirect()->route('portal.cms.departments.index');
        }

        if($department->delete()){
            session()->flash('notification-status', 'success');
            session()->flash('notification-msg', "Department has been deleted.");
            return redirect()->back();
        }
    }
}
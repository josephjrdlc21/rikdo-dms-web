<?php

namespace App\Laravel\Controllers\Portal;

use App\Laravel\Models\{Course,AuditTrail};

use App\Laravel\Requests\PageRequest;
use App\Laravel\Requests\Portal\CourseRequest;

use Carbon,DB;

class CoursesController extends Controller{
    protected $data;

    public function __construct(){
        parent::__construct();
        array_merge($this->data?:[], parent::get_data());
        $this->data['page_title'] .= " - Courses";
        $this->per_page = env("DEFAULT_PER_PAGE", 10);
    }

    public function index(PageRequest $request){
        $this->data['page_title'] .= " - List of Course";

        $this->data['keyword'] = strtolower($request->get('keyword'));

        $first_record = Course::orderBy('created_at', 'ASC')->first();
        $start_date = $request->get('start_date', now()->startOfMonth());
        if ($first_record) {
            $start_date = $request->get('start_date', $first_record->created_at->format("Y-m-d"));
        }

        $this->data['start_date'] = Carbon::parse($start_date)->format("Y-m-d");
        $this->data['end_date'] = Carbon::parse($request->get('end_date', now()))->format("Y-m-d");

        $this->data['record'] = Course::where(function ($query) {
            if (strlen($this->data['keyword']) > 0) {
                $query->whereRaw("LOWER(course_code) LIKE '%{$this->data['keyword']}%'")
                    ->orWhereRaw("LOWER(course_name) LIKE '%{$this->data['keyword']}%'");
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

        return view('portal.cms.courses.index', $this->data);
    }

    public function create(PageRequest $request){
        $this->data['page_title'] .= " - Create Course";

        return view('portal.cms.courses.create', $this->data);
    }

    public function store(CourseRequest $request){
        DB::beginTransaction();
        try {
            $course = new Course;
            $course->course_code = $request->input('course_code');
            $course->course_name = $request->input('course_name');
            $course->save();

            $audit_trail = new AuditTrail;
            $audit_trail->user_id = $this->data['auth']->id;
            $audit_trail->process = "CREATE_COURSE";
            $audit_trail->ip = $this->data['ip'];
            $audit_trail->remarks = "{$this->data['auth']->name} has created a new course.";
            $audit_trail->type = "USER_ACTION";
            $audit_trail->save();

            DB::commit();

            session()->flash('notification-status', "success");
            session()->flash('notification-msg', "New course has been created.");
            return redirect()->route('portal.cms.courses.index');
        }catch(\Exception $e){
            DB::rollback();
            
            session()->flash('notification-status', "failed");
            session()->flash('notification-msg', "Server Error: Code #{$e->getLine()}");
            return redirect()->back();
        }

        session()->flash('notification-status', "warning");
        session()->flash('notification-msg', "Unable to create new course.");
        return redirect()->back();
    }

    public function edit(PageRequest $request,$id = null){
        $this->data['page_title'] .= " - Update Course";
        $this->data['course'] = Course::find($id);

        if(!$this->data['course']){
			session()->flash('notification-status', "failed");
			session()->flash('notification-msg', "Record not found.");
			return redirect()->route('portal.cms.courses.index');
		}

        return view('portal.cms.courses.edit', $this->data);
    }

    public function update(CourseRequest $request,$id = null){
        $course = Course::find($id);

        if(!$course){
            session()->flash('notification-status', "failed");
            session()->flash('notification-msg', "Record not found.");
            return redirect()->route('portal.cms.courses.index');
        }

        DB::beginTransaction();
        try{
            $course->course_code = $request->input('course_code');
            $course->course_name = $request->input('course_name');
            $course->save();

            $audit_trail = new AuditTrail;
            $audit_trail->user_id = $this->data['auth']->id;
            $audit_trail->process = "UPDATE_COURSE";
            $audit_trail->ip = $this->data['ip'];
            $audit_trail->remarks = "{$this->data['auth']->name} has updated a course.";
            $audit_trail->type = "USER_ACTION";
            $audit_trail->save();

            DB::commit();

            session()->flash('notification-status', "success");
            session()->flash('notification-msg', "Course has been updated.");
            return redirect()->route('portal.cms.courses.index');
        }catch(\Exception $e){
            DB::rollback();

            session()->flash('notification-status', "failed");
            session()->flash('notification-msg', "Server Error: Code #{$e->getLine()}");
            return redirect()->back();
        }

        session()->flash('notification-status', "warning");
        session()->flash('notification-msg', "Unable to update course.");
        return redirect()->back();
    }

    public function destroy(PageRequest $request,$id = null){
        $course = Course::find($id);

        if(!$course){
            session()->flash('notification-status', "failed");
            session()->flash('notification-msg', "Record not found.");
            return redirect()->route('portal.cms.courses.index');
        }

        if($course->delete()){
            $audit_trail = new AuditTrail;
            $audit_trail->user_id = $this->data['auth']->id;
            $audit_trail->process = "DELETE_COURSE";
            $audit_trail->ip = $this->data['ip'];
            $audit_trail->remarks = "{$this->data['auth']->name} has deleted a course.";
            $audit_trail->type = "USER_ACTION";
            $audit_trail->save();

            session()->flash('notification-status', 'success');
            session()->flash('notification-msg', "Course has been deleted.");
            return redirect()->back();
        }
    }
}
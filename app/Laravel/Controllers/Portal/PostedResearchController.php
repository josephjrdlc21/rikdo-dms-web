<?php

namespace App\Laravel\Controllers\Portal;

use App\Laravel\Models\{PostedResearch,CompletedResearch,Department,Course,Yearlevel,ResearchType,User,AuditTrail};

use App\Laravel\Requests\PageRequest;
use App\Laravel\Requests\Portal\PostedResearchRequest;

use Carbon,DB,FileDownloader;

class PostedResearchController extends Controller{
    protected $data;

    public function __construct(){
        parent::__construct();
        array_merge($this->data?:[], parent::get_data());
        $this->data['page_title'] .= " - Posted Research";
        $this->per_page = env("DEFAULT_PER_PAGE", 10);
    }

    public function index(PageRequest $request){
        $this->data['page_title'] .= " - List of Posted Research";

        $this->data['keyword'] = strtolower($request->get('keyword'));
        $this->data['selected_department'] = strtolower($request->input('department'));
        $this->data['selected_course'] = strtolower($request->input('course'));
        $this->data['selected_yearlevel'] = strtolower($request->input('yearlevel'));
        $this->data['selected_type'] = strtolower($request->input('type'));

        $first_record = PostedResearch::orderBy('created_at', 'ASC')->first();
        $start_date = $request->get('start_date', now()->startOfMonth());
        if ($first_record) {
            $start_date = $request->get('start_date', $first_record->created_at->format("Y-m-d"));
        }

        $this->data['start_date'] = Carbon::parse($start_date)->format("Y-m-d");
        $this->data['end_date'] = Carbon::parse($request->get('end_date', now()))->format("Y-m-d");
        $this->data['departments'] = ['' => "All"] + Department::pluck('dept_code', 'id')->toArray();
        $this->data['courses'] = ['' => "All"] + Course::pluck('course_code', 'id')->toArray();
        $this->data['yearlevels'] = ['' => "All"] + Yearlevel::pluck('yearlevel_name', 'id')->toArray();
        $this->data['types'] = ['' => "All"] + ResearchType::pluck('type', 'id')->toArray();

        $this->data['record'] = PostedResearch::with(['department', 'course', 'yearlevel', 'research_type', 'processor'])->where(function ($query) {
            if (strlen($this->data['keyword']) > 0) {
                $query->whereRaw("LOWER(title) LIKE '%{$this->data['keyword']}%'");
            }
        })
        ->where(function ($query) {
            if (strlen($this->data['selected_type']) > 0) {
                $query->where('research_type_id', $this->data['selected_type']);
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
        ->orderBy('created_at','DESC')
        ->paginate($this->per_page);

        return view('portal.posted-research.index', $this->data);
    }

    public function create(PageRequest $request,$id = null){
        $this->data['page_title'] .= " - Post Research";
        $this->data['for_posting'] = ['' => "Select Research"] + CompletedResearch::where('status', 'for_posting')->pluck('title', 'id')->toArray();

        if(!is_null($id)){
            $this->data['default_research'] = CompletedResearch::where('status', 'for_posting')->where('id', $id)->pluck('id')->toArray();
        }

        return view('portal.posted-research.create', $this->data);
    }

    public function store(PostedResearchRequest $request,$id = null){
        DB::beginTransaction();
        try{
            $completed_research = CompletedResearch::where('id', $request->input('research'))->where('status', 'for_posting')->first();
            $completed_research->status = "posted";
            $completed_research->save();
            
            $posted_research = new PostedResearch;
            $posted_research->title = $completed_research->title;
            $posted_research->research_type_id = $completed_research->research_type_id;
            $posted_research->department_id = $completed_research->department_id;
            $posted_research->course_id = $completed_research->course_id;
            $posted_research->yearlevel_id = $completed_research->yearlevel_id;
            $posted_research->abstract = $completed_research->abstract;
            $posted_research->authors = $completed_research->authors;
            $posted_research->processor_id = $this->data['auth']->id;
            $posted_research->path = $completed_research->path;
            $posted_research->directory = $completed_research->directory;
            $posted_research->filename = $completed_research->filename;
            $posted_research->source = $completed_research->source;
            $posted_research->save();

            $audit_trail = new AuditTrail;
            $audit_trail->user_id = $this->data['auth']->id;
            $audit_trail->process = "POST_RESEARCH";
            $audit_trail->ip = $this->data['ip'];
            $audit_trail->remarks = "{$this->data['auth']->name} has posted a new research.";
            $audit_trail->type = "USER_ACTION";
            $audit_trail->save();
            
            DB::commit();

            session()->flash('notification-status', "success");
            session()->flash('notification-msg', "Research has been posted.");
            return redirect()->route('portal.posted_research.index');
        }catch(\Exception $e){
            DB::rollback();

            session()->flash('notification-status', "failed");
            session()->flash('notification-msg', "Server Error: Code #{$e->getLine()}");
            return redirect()->back();
        }

        session()->flash('notification-status', "warning");
        session()->flash('notification-msg', "Unable to post research.");
        return redirect()->back();  
    }

    public function show(PageRequest $request,$id = null){
        $this->data['page_title'] .= " - Information";
        $this->data['posted_research'] = PostedResearch::with(['department', 'course', 'yearlevel', 'processor', 'research_type'])->find($id);

        if(!$this->data['posted_research']){
            session()->flash('notification-status', "failed");
            session()->flash('notification-msg', "Record not found.");
            return redirect()->route('portal.posted_research.index');
        }

        $this->data['authors'] = User::whereIn('id', explode(',', $this->data['posted_research']->authors))->get();

        return view('portal.posted-research.show', $this->data);
    }

    public function download(PageRequest $request,$id = null){
        $posted_research = PostedResearch::find($id);

        if(!$posted_research){
            session()->flash('notification-status', "failed");
            session()->flash('notification-msg', "Record not found.");
            return redirect()->route('portal.posted_research.index');
        }

        try{
            $path = $posted_research->path ? "{$posted_research->path}/{$posted_research->filename}" : "{$posted_research->directory}/{$posted_research->filename}";

            $download = FileDownloader::download($path);

            $audit_trail = new AuditTrail;
            $audit_trail->user_id = $this->data['auth']->id;
            $audit_trail->process = "DOWNLOAD_POSTED_RESEARCH";
            $audit_trail->ip = $this->data['ip'];
            $audit_trail->remarks = "{$this->data['auth']->name} has downloaded a posted research file.";
            $audit_trail->type = "USER_ACTION";
            $audit_trail->save();

            if($download){
                return $download;
            }
        }catch(\Exception $e){
            session()->flash('notification-status', "failed");
            session()->flash('notification-msg', "Server Error: Code #{$e->getLine()}");
        }
        
        return redirect()->route('portal.posted_research.show', [$posted_research->id]);
    }
}
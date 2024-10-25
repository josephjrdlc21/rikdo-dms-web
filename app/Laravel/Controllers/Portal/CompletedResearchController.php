<?php

namespace App\Laravel\Controllers\Portal;

use App\Laravel\Models\{CompletedResearch,Department,Course,Yearlevel,Research,SharedResearch,User,ResearchType};

use App\Laravel\Requests\PageRequest;
use App\Laravel\Requests\Portal\CompletedResearchRequest;

use Carbon,DB,FileUploader,FileDownloader;

class CompletedResearchController extends Controller{
    protected $data;

    public function __construct(){
        parent::__construct();
        array_merge($this->data?:[], parent::get_data());
        $this->data['page_title'] .= " - Completed Research";
        $this->per_page = env("DEFAULT_PER_PAGE", 10);
    }

    public function index(PageRequest $request){
        $this->data['page_title'] .= " - List of Completed Research";

        $this->data['keyword'] = strtolower($request->get('keyword'));
        $this->data['selected_department'] = strtolower($request->input('department'));
        $this->data['selected_course'] = strtolower($request->input('course'));
        $this->data['selected_yearlevel'] = strtolower($request->input('yearlevel'));
        $this->data['selected_status'] = strtolower($request->input('status'));
        $this->data['selected_type'] = strtolower($request->input('type'));

        $first_record = CompletedResearch::orderBy('created_at', 'ASC')->first();
        $start_date = $request->get('start_date', now()->startOfMonth());
        if ($first_record) {
            $start_date = $request->get('start_date', $first_record->created_at->format("Y-m-d"));
        }

        $this->data['start_date'] = Carbon::parse($start_date)->format("Y-m-d");
        $this->data['end_date'] = Carbon::parse($request->get('end_date', now()))->format("Y-m-d");
        $this->data['statuses'] = ['' => "All", 'pending' => "Pending", 're_submission' => "Re Submission", 'for_posting' => "For Posting", 'rejected' => "Rejected"];
        $this->data['departments'] = ['' => "All"] + Department::pluck('dept_code', 'id')->toArray();
        $this->data['courses'] = ['' => "All"] + Course::pluck('course_code', 'id')->toArray();
        $this->data['yearlevels'] = ['' => "All"] + Yearlevel::pluck('yearlevel_name', 'id')->toArray();
        $this->data['types'] = ['' => "All"] + ResearchType::pluck('type', 'id')->toArray();

        $this->data['record'] = CompletedResearch::with(['department', 'course', 'yearlevel', 'research_type', 'processor'])->where(function ($query) {
            if (strlen($this->data['keyword']) > 0) {
                $query->whereRaw("LOWER(title) LIKE '%{$this->data['keyword']}%'");
            }
        })
        ->where(function ($query) {
            if (strlen($this->data['selected_status']) > 0) {
                $query->where('status', $this->data['selected_status']);
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

        return view('portal.completed-research.index', $this->data);
    }

    public function create(PageRequest $request){
        $this->data['page_title'] .= " - Create For Posted Research";

        $shared = SharedResearch::where('user_id', $this->data['auth']->id)->pluck('research_id')->toArray();

        $this->data['research'] = Research::where(function ($query) use ($shared) {
            $query->where('submitted_by_id', $this->data['auth']->id)
                ->orWhereIn('id', $shared);
        })
        ->where('status', 'approved')
        ->distinct()
        ->pluck('title', 'title')->toArray();

        return view('portal.completed-research.create', $this->data);
    }

    public function store(CompletedResearchRequest $request){
        DB::beginTransaction();
        try{
            $research = Research::where('title', $request->input('title'))->first();
            $authors = array_merge([$research->submitted_by_id], SharedResearch::where('research_id', $research->id)->pluck('user_id')->toArray());

            $completed_research = new CompletedResearch;
            $completed_research->title = $request->input('title');
            $completed_research->research_type_id = $research->research_type_id;
            $completed_research->department_id = $research->department_id;
            $completed_research->course_id = $research->course_id;
            $completed_research->yearlevel_id = $research->yearlevel_id;
            $completed_research->abstract = $request->input('abstract');
            $completed_research->authors = implode(',', $authors);
            $completed_research->save();

            if($request->hasFile('research_file')){
                $research_file = FileUploader::upload($request->file('research_file'), "uploads/completed/{$completed_research->id}");

                $completed_research->path = $research_file['path'];
                $completed_research->directory = $research_file['directory'];
                $completed_research->filename = $research_file['filename'];
                $completed_research->source = $research_file['source'];
                $completed_research->save();
            }
           
            DB::commit();

            session()->flash('notification-status', "success");
            session()->flash('notification-msg', "Research has been posted.");
            return redirect()->route('portal.completed_research.index');
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
        $this->data['completed_research'] = CompletedResearch::with(['department', 'course', 'yearlevel', 'processor', 'research_type'])->find($id);

        if(!$this->data['completed_research']){
            session()->flash('notification-status', "failed");
            session()->flash('notification-msg', "Record not found.");
            return redirect()->route('portal.completed_research.index');
        }

        $this->data['authors'] = User::whereIn('id', explode(',', $this->data['completed_research']->authors))->get();

        return view('portal.completed-research.show', $this->data);
    }

    public function download(PageRequest $request,$id = null){
        $completed_research = CompletedResearch::find($id);

        if(!$completed_research){
            session()->flash('notification-status', "failed");
            session()->flash('notification-msg', "Record not found.");
            return redirect()->route('portal.completed_research.index');
        }

        $path = $completed_research->path ? "{$completed_research->path}/{$completed_research->filename}" : "{$completed_research->directory}/{$completed_research->filename}";

        $download = FileDownloader::download($path);

        if($download){
            return $download;
        }
        
        session()->flash('notification-status', "error");
        session()->flash('notification-msg', "Failed to download research file.");
        return redirect()->route('portal.completed_research.show', [$completed_research->id]);
    }
}
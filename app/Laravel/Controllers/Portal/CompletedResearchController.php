<?php

namespace App\Laravel\Controllers\Portal;

use App\Laravel\Models\{CompletedResearch,Department,Course,Yearlevel,Research,SharedResearch,User,ResearchType,AuditTrail};

use App\Laravel\Requests\PageRequest;
use App\Laravel\Requests\Portal\CompletedResearchRequest;

use App\Laravel\Notifications\{CompletedResearchEvaluated};

use Carbon,DB,FileUploader,FileDownloader,FileRemover,Mail;

class CompletedResearchController extends Controller{
    protected $data;

    public function __construct(){
        parent::__construct();
        array_merge($this->data?:[], parent::get_data());
        $shared = SharedResearch::where('user_id', $this->data['auth']->id)->pluck('research_id')->toArray();
        $this->data['research'] = Research::where(function ($query) use ($shared) {
            $query->where('submitted_by_id', $this->data['auth']->id)
                ->orWhereIn('id', $shared);
        })
        ->where('status', 'approved')
        ->distinct()
        ->pluck('title', 'title')->toArray();
        $this->data['researchers'] = User::pluck('name', 'id')->toArray();
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
        $this->data['statuses'] = ['' => "All", 'pending' => "Pending", 're_submission' => "Re Submission", 'for_posting' => "For Posting", 'rejected' => "Rejected", 'posted' => "Posted"];
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

        return view('portal.completed-research.create', $this->data);
    }

    public function store(CompletedResearchRequest $request){
        DB::beginTransaction();
        try{
            $research = Research::where('title', $request->input('title'))->first();

            $completed_research = new CompletedResearch;
            $completed_research->title = $request->input('title');
            $completed_research->research_type_id = $research->research_type_id;
            $completed_research->department_id = $research->department_id;
            $completed_research->course_id = $research->course_id;
            $completed_research->yearlevel_id = $research->yearlevel_id;
            $completed_research->abstract = $request->input('abstract');
            $completed_research->authors = implode(',', $request->input('authors', []));
            $completed_research->save();

            if($request->hasFile('research_file')){
                $research_file = FileUploader::upload($request->file('research_file'), "uploads/completed/{$completed_research->id}");

                $completed_research->path = $research_file['path'];
                $completed_research->directory = $research_file['directory'];
                $completed_research->filename = $research_file['filename'];
                $completed_research->source = $research_file['source'];
                $completed_research->save();
            }

            $audit_trail = new AuditTrail;
            $audit_trail->user_id = $this->data['auth']->id;
            $audit_trail->process = "CREATE_FOR_POSTED_RESEARCH";
            $audit_trail->ip = $this->data['ip'];
            $audit_trail->remarks = "{$this->data['auth']->name} has submitted a completed research.";
            $audit_trail->type = "USER_ACTION";
            $audit_trail->save();
           
            DB::commit();

            session()->flash('notification-status', "success");
            session()->flash('notification-msg', "Research has been completed.");
            return redirect()->route('portal.completed_research.index');
        }catch(\Exception $e){
            DB::rollback();

            session()->flash('notification-status', "failed");
            session()->flash('notification-msg', "Server Error: Code #{$e->getLine()}");
            return redirect()->back();
        }

        session()->flash('notification-status', "warning");
        session()->flash('notification-msg', "Unable to completed research.");
        return redirect()->back();
    }

    public function edit(PageRequest $request,$id = null){
        $this->data['completed_research'] = CompletedResearch::find($id);

        if(!$this->data['completed_research']){
            session()->flash('notification-status', "failed");
            session()->flash('notification-msg', "Record not found.");
            return redirect()->route('portal.completed_research.index');
        }

        if(!$this->data['research']){
            session()->flash('notification-status', "failed");
            session()->flash('notification-msg', "Only authors of this research can re submit this file.");
            return redirect()->route('portal.completed_research.index');
        }

        $this->data['authors'] = User::whereIn('id', explode(',', $this->data['completed_research']->authors))->pluck('id')->toArray();
        
        return view('portal.completed-research.edit', $this->data);
    }

    public function update(CompletedResearchRequest $request,$id = null){
        $completed_research = CompletedResearch::find($id);

        if(!$completed_research){
            session()->flash('notification-status', "failed");
			session()->flash('notification-msg', "Record not found.");
			return redirect()->route('portal.completed_research.index');
        }

        DB::beginTransaction();
        try{
            $completed_research->abstract = $request->input('abstract');
            $completed_research->status = "pending";
            $completed_research->remarks = null;
            $completed_research->authors = implode(',', $request->input('authors', []));
            $completed_research->save();

            if($request->hasFile('research_file')){
                FileRemover::remove($completed_research->path);

                $research_file = FileUploader::upload($request->file('research_file'), "uploads/completed/{$completed_research->id}");

                $completed_research->path = $research_file['path'];
                $completed_research->directory = $research_file['directory'];
                $completed_research->filename = $research_file['filename'];
                $completed_research->source = $research_file['source'];
                $completed_research->save();
            }

            $audit_trail = new AuditTrail;
            $audit_trail->user_id = $this->data['auth']->id;
            $audit_trail->process = "UPDATE_FOR_POSTED_RESEARCH";
            $audit_trail->ip = $this->data['ip'];
            $audit_trail->remarks = "{$this->data['auth']->name} has updated submitted completed research.";
            $audit_trail->type = "USER_ACTION";
            $audit_trail->save();

            DB::commit();

            session()->flash('notification-status', "success");
            session()->flash('notification-msg', "Research has been resubmitted.");
        }catch(\Exception $e){
            DB::rollback();

            session()->flash('notification-status', "failed");
            session()->flash('notification-msg', "Server Error: Code #{$e->getLine()}");
            return redirect()->back();
        }

        return redirect()->route('portal.completed_research.index');
    }

    public function edit_status(PageRequest $request,$id = null,$status = "pending"){
        $completed_research = CompletedResearch::find($id);

        if(!$completed_research){
            session()->flash('notification-status', "failed");
            session()->flash('notification-msg', "Record not found.");
            return redirect()->route('portal.completed_research.index');
        }

        if($completed_research->status !== "pending"){
            session()->flash('notification-status', "warning");
            session()->flash('notification-msg', "Research has already been processed. It cannot be process again.");
            return redirect()->route('portal.completed_research.index');
        }

        if(in_array($this->data['auth']->id, explode(',', $completed_research->authors))){
            session()->flash('notification-status', "danger");
            session()->flash('notification-msg', "Authors cannot remark a research.");
            return redirect()->route('portal.completed_research.index');
        }

        switch($status){
            case "for_posting":
                try{
                    $completed_research->status = $status;
                    $completed_research->processor_id = $this->data['auth']->id;
                    $completed_research->save();

                    $audit_trail = new AuditTrail;
                    $audit_trail->user_id = $this->data['auth']->id;
                    $audit_trail->process = "EVALUATE_COMPLETED_RESEARCH";
                    $audit_trail->ip = $this->data['ip'];
                    $audit_trail->remarks = "{$this->data['auth']->name} has been evaluate completed research and set status to {$completed_research->status}.";
                    $audit_trail->type = "USER_ACTION";
                    $audit_trail->save();

                    if(env('MAIL_SERVICE', false)){
                        $completed_research_authors = User::whereIn('id', explode(',', $completed_research->authors))->get();

                        $data = [
                            'title' => $completed_research->title,
                            'submitted_by' => $completed_research->submitted_by->name,
                            'process_by' => $completed_research->processor->name,
                            'status' => $completed_research->status,
                            'date_time' => $completed_research->updated_at->format('m/d/Y h:i A'),
                        ];

                        foreach($completed_research_authors as $send){
                            Mail::to($send->email)->send(new CompletedResearchEvaluated($data));
                        }
                        //Mail::to($research->modified_by->email)->send(new StudentResearchSharedSuccess($data));
                    }
                
                    DB::commit();

                    session()->flash('notification-status', "success");
                    session()->flash('notification-msg', "Research has been ready for posting.");
                }catch(\Exception $e){
                    DB::rollback();

                    session()->flash('notification-status', "failed");
                    session()->flash('notification-msg', "Server Error: Code #{$e->getLine()}");
                    return redirect()->back();
                }

                return redirect()->route('portal.completed_research.index');
                break;
            case "re_submission":
                try{
                    $completed_research->status = $status;
                    $completed_research->processor_id = $this->data['auth']->id;
                    $completed_research->remarks = $request->get('remarks');
                    $completed_research->save();

                    $audit_trail = new AuditTrail;
                    $audit_trail->user_id = $this->data['auth']->id;
                    $audit_trail->process = "EVALUATE_COMPLETED_RESEARCH";
                    $audit_trail->ip = $this->data['ip'];
                    $audit_trail->remarks = "{$this->data['auth']->name} has been evaluate completed research and set status to {$completed_research->status}.";
                    $audit_trail->type = "USER_ACTION";
                    $audit_trail->save();
                   
                    DB::commit();

                    session()->flash('notification-status', "success");
                    session()->flash('notification-msg', "Research has been remarked for re submission.");
                }catch(\Exception $e){
                    DB::rollback();

                    session()->flash('notification-status', "failed");
                    session()->flash('notification-msg', "Server Error: Code #{$e->getLine()}");
                    return redirect()->back();
                }

                return redirect()->route('portal.completed_research.index');
                break;
            case "rejected":
                try{
                    $completed_research->status = $status;
                    $completed_research->processor_id = $this->data['auth']->id;
                    $completed_research->remarks = $request->get('remarks');
                    $completed_research->save();

                    $audit_trail = new AuditTrail;
                    $audit_trail->user_id = $this->data['auth']->id;
                    $audit_trail->process = "EVALUATE_COMPLETED_RESEARCH";
                    $audit_trail->ip = $this->data['ip'];
                    $audit_trail->remarks = "{$this->data['auth']->name} has been evaluate completed research and set status to {$completed_research->status}.";
                    $audit_trail->type = "USER_ACTION";
                    $audit_trail->save();
                   
                    DB::commit();

                    session()->flash('notification-status', "success");
                    session()->flash('notification-msg', "Research has been rejected.");
                }catch(\Exception $e){
                    DB::rollback();

                    session()->flash('notification-status', "failed");
                    session()->flash('notification-msg', "Server Error: Code #{$e->getLine()}");
                    return redirect()->back();
                }

                return redirect()->route('portal.completed_research.index');
                break;
            default:
                return redirect()->route('portal.completed_research.index');

                break;
        }
    }

    public function show(PageRequest $request,$id = null){
        $this->data['page_title'] .= " - Information";
        $this->data['completed_research'] = CompletedResearch::with(['department', 'course', 'yearlevel', 'processor', 'research_type'])->find($id);

        if(!$this->data['completed_research']){
            session()->flash('notification-status', "failed");
            session()->flash('notification-msg', "Record not found.");
            return redirect()->route('portal.completed_research.index');
        }

        $check_authors = explode(',', $this->data['completed_research']->authors);

        $this->data['authors'] = User::whereIn('id', $check_authors)->get();
        $this->data['check_authenticated'] = in_array($this->data['auth']->id, $check_authors) ? true : false;

        return view('portal.completed-research.show', $this->data);
    }

    public function destroy(PageRequest $request,$id = null){
        $completed_research = CompletedResearch::find($id);

        if(!$completed_research){
            session()->flash('notification-status', "failed");
            session()->flash('notification-msg', "Record not found.");
            return redirect()->route('portal.completed_research.index');
        }

        if($completed_research->status === "posted") {
            session()->flash('notification-status', "danger");
            session()->flash('notification-msg', "Research has already been posted. It cannot be deleted.");
            return redirect()->route('portal.completed_research.index');
        }

        if($completed_research->delete()){
            $audit_trail = new AuditTrail;
            $audit_trail->user_id = $this->data['auth']->id;
            $audit_trail->process = "DELETE_COMPLETED_RESEARCH";
            $audit_trail->ip = $this->data['ip'];
            $audit_trail->remarks = "{$this->data['auth']->name} has deleted a completed reseach.";
            $audit_trail->type = "USER_ACTION";
            $audit_trail->save();

            session()->flash('notification-status', 'success');
            session()->flash('notification-msg', "Research has been deleted.");
            return redirect()->back();
        }
    }

    public function download(PageRequest $request,$id = null){
        $completed_research = CompletedResearch::find($id);

        if(!$completed_research){
            session()->flash('notification-status', "failed");
            session()->flash('notification-msg', "Record not found.");
            return redirect()->route('portal.completed_research.index');
        }

        try{
            $path = $completed_research->path ? "{$completed_research->path}/{$completed_research->filename}" : "{$completed_research->directory}/{$completed_research->filename}";

            $download = FileDownloader::download($path);

            $audit_trail = new AuditTrail;
            $audit_trail->user_id = $this->data['auth']->id;
            $audit_trail->process = "DOWNLOAD_COMPLETED_RESEARCH";
            $audit_trail->ip = $this->data['ip'];
            $audit_trail->remarks = "{$this->data['auth']->name} has downloaded a completed research.";
            $audit_trail->type = "USER_ACTION";
            $audit_trail->save();

            if($download){
                return $download;
            }
        }catch(\Exception $e){
            session()->flash('notification-status', "failed");
            session()->flash('notification-msg', "Server Error: Code #{$e->getLine()}");
        }

        return redirect()->route('portal.completed_research.show', [$completed_research->id]);
    }
}
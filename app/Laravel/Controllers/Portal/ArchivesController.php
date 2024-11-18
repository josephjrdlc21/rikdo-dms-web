<?php

namespace App\Laravel\Controllers\Portal;

use App\Laravel\Models\{Research,Department,Course,Yearlevel,ResearchType,CompletedResearch,User,AuditTrail};

use App\Laravel\Requests\PageRequest;

use App\Laravel\Notifications\{ArchivesResearchRestored,ArchivesResearchRestoredSuccess,ArchivesCompletedResearchRestored,
ArchivesCompletedResearchRestoredSuccess,ArchivesResearchDeleted,ArchivesResearchDeletedSuccess,ArchivesCompletedResearchDeleted,
ArchivesCompletedResearchDeletedSuccess};

use Carbon,DB,FileRemover,Mail;

class ArchivesController extends Controller{
    protected $data;

    public function __construct(){
        parent::__construct();
        array_merge($this->data?:[], parent::get_data());
        $this->data['page_title'] .= " - Researches";
        $this->per_page = env("DEFAULT_PER_PAGE", 10);
    }

    public function index(PageRequest $request){
        $this->data['page_title'] .= " - List of Research";

        $this->data['keyword'] = strtolower($request->get('keyword'));
        $this->data['selected_department'] = strtolower($request->input('department'));
        $this->data['selected_course'] = strtolower($request->input('course'));
        $this->data['selected_yearlevel'] = strtolower($request->input('yearlevel'));
        $this->data['selected_status'] = strtolower($request->input('status'));
        $this->data['selected_type'] = strtolower($request->input('type'));

        $first_record = Research::onlyTrashed()->orderBy('created_at', 'ASC')->first();
        $start_date = $request->get('start_date', now()->startOfMonth());
        if ($first_record) {
            $start_date = $request->get('start_date', $first_record->created_at->format("Y-m-d"));
        }

        $this->data['start_date'] = Carbon::parse($start_date)->format("Y-m-d");
        $this->data['end_date'] = Carbon::parse($request->get('end_date', now()))->format("Y-m-d");
        $this->data['statuses'] = ['' => "All", 'pending' => "Pending", 'for_revision' => "For Revision", 'approved' => "Approved", 'rejected' => "Rejected"];
        $this->data['departments'] = ['' => "All"] + Department::pluck('dept_code', 'id')->toArray();
        $this->data['courses'] = ['' => "All"] + Course::pluck('course_code', 'id')->toArray();
        $this->data['yearlevels'] = ['' => "All"] + Yearlevel::pluck('yearlevel_name', 'id')->toArray();
        $this->data['types'] = ['' => "All"] + ResearchType::pluck('type', 'id')->toArray();

        $this->data['record'] = Research::onlyTrashed()->with(['submitted_by', 'submitted_to', 'research_type'])->where(function ($query) {
            if (strlen($this->data['keyword']) > 0) {
                $query->whereRaw("LOWER(title) LIKE '%{$this->data['keyword']}%'")
                    ->orWhereHas('submitted_to', function ($q) {
                        $q->whereRaw("LOWER(name) LIKE '%{$this->data['keyword']}%'");
                    })
                    ->orWhereHas('submitted_by', function ($q) {
                        $q->whereRaw("LOWER(name) LIKE '%{$this->data['keyword']}%'");
                    });
            }
        })
        ->whereHas('research_type', function ($query) {
            if (strlen($this->data['selected_type']) > 0) {
                $query->where('id', $this->data['selected_type']);
            }
        })
        ->where(function ($query) {
            if(strlen($this->data['selected_status']) > 0){
                $query->where('status', $this->data['selected_status']);
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

        return view('portal.archives.index', $this->data);
    }

    public function completed(PageRequest $request){
        $this->data['page_title'] .= " - List of Completed Research";

        $this->data['keyword'] = strtolower($request->get('keyword'));
        $this->data['selected_department'] = strtolower($request->input('department'));
        $this->data['selected_course'] = strtolower($request->input('course'));
        $this->data['selected_yearlevel'] = strtolower($request->input('yearlevel'));
        $this->data['selected_status'] = strtolower($request->input('status'));
        $this->data['selected_type'] = strtolower($request->input('type'));

        $first_record = CompletedResearch::onlyTrashed()->orderBy('created_at', 'ASC')->first();
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

        $this->data['record'] = CompletedResearch::onlyTrashed()->with(['department', 'course', 'yearlevel', 'research_type', 'processor'])->where(function ($query) {
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
        ->where('status', '!=', 'posted')
        ->orderBy('created_at','DESC')
        ->paginate($this->per_page);

        return view('portal.archives.completed', $this->data);
    }

    public function show(PageRequest $request,$id = null){
        $this->data['page_title'] .= " - Information";

        switch ($request->get('type')) {
            case 'completed':
                $this->data['completed_research'] = CompletedResearch::withTrashed()->with(['department', 'course', 'yearlevel', 'processor', 'research_type'])->find($id);
        
                if(!$this->data['completed_research']){
                    session()->flash('notification-status', "failed");
                    session()->flash('notification-msg', "Record not found.");
                    return redirect()->route('portal.archives.index');
                }
               
                $this->data['authors'] = User::whereIn('id', explode(',', $this->data['completed_research']->authors))->get();
        
                return view('portal.archives.show-completed', $this->data);
                break;
            case 'researches':
                $this->data['research'] = Research::withTrashed()->with(['submitted_by', 'submitted_to', 'research_type', 'department', 'course', 'yearlevel', 'modified_by', 'logs', 'shared'])->find($id);

                if(!$this->data['research']){
                    session()->flash('notification-status', "failed");
                    session()->flash('notification-msg', "Record not found.");
                    return redirect()->route('portal.archives.index');
                }
        
                return view('portal.archives.show', $this->data);
                break;
            default:  
                return redirect()->back();

                break;
        }

        return redirect()->back();
    }

    public function destroy(PageRequest $request,$id = null){
        if($request->get('type')){
            $audit_trail = new AuditTrail;
            $audit_trail->user_id = $this->data['auth']->id;
            $audit_trail->process = "DELETE_RESEARCH_PERMANENTLY";
            $audit_trail->ip = $this->data['ip'];
            $audit_trail->remarks = "{$this->data['auth']->name} has deleted a research permanently.";
            $audit_trail->type = "USER_ACTION";
            $audit_trail->save();
        }

        switch ($request->get('type')) {
            case 'completed':
                $research = CompletedResearch::withTrashed()->find($id);

                if(!$research){
                    session()->flash('notification-status', "failed");
                    session()->flash('notification-msg', "Record not found.");
                    return redirect()->route('portal.archives.index');
                }

                FileRemover::remove($research->path);

                if($research->forceDelete()){
                    if(env('MAIL_SERVICE', false)){
                        $research_authors = User::whereIn('id', explode(',', $research->authors))->get();

                        $data = [
                            'title' => $research->title,
                            'authors' => $research_authors,
                            'deleted_by' => $this->data['auth']->name,
                            'status' => $research->status,
                            'date_time' => Carbon::now()->format('m/d/Y h:i A'),
                        ];

                        foreach($research_authors as $send){
                            Mail::to($send->email)->send(new ArchivesCompletedResearchDeleted($data));
                        }
                        Mail::to($this->data['auth']->email)->send(new ArchivesCompletedResearchDeletedSuccess($data));
                    }

                    session()->flash('notification-status', 'success');
                    session()->flash('notification-msg', "Completed research has been deleted.");
                    return redirect()->back();
                }

                break;
            case 'researches':
                $research = Research::withTrashed()->find($id);

                if(!$research){
                    session()->flash('notification-status', "failed");
                    session()->flash('notification-msg', "Record not found.");
                    return redirect()->route('portal.archives.index');
                }

                FileRemover::remove($research->path);

                if($research->forceDelete()){
                    $research->shared_with_trashed()->forceDelete();
                    $research->logs_with_trashed()->forceDelete();

                    if(env('MAIL_SERVICE', false)){
                        $data = [
                            'title' => $research->title,
                            'chapter' => $research->chapter,
                            'version' => $research->version,
                            'submitted_to' => $research->submitted_to->name,
                            'deleted_by' => $this->data['auth']->name,
                            'status' => $research->status,
                            'date_time' => Carbon::now()->format('m/d/Y h:i A'),
                        ];
                        Mail::to($research->submitted_to->email)->send(new ArchivesResearchDeleted($data));
                        Mail::to($this->data['auth']->email)->send(new ArchivesResearchDeletedSuccess($data));
                    }

                    session()->flash('notification-status', 'success');
                    session()->flash('notification-msg', "Research has been deleted.");
                    return redirect()->back();
                }

                break;
            default:
                return redirect()->back();

                break;
        }

        return redirect()->back();
    }

    public function restore(PageRequest $request,$id = null){
        if($request->get('type')){
            $audit_trail = new AuditTrail;
            $audit_trail->user_id = $this->data['auth']->id;
            $audit_trail->process = "RESTORE_RESEARCH";
            $audit_trail->ip = $this->data['ip'];
            $audit_trail->remarks = "{$this->data['auth']->name} has retored a research.";
            $audit_trail->type = "USER_ACTION";
            $audit_trail->save();
        }

        switch ($request->get('type')) {
            case 'completed':
                $research = CompletedResearch::withTrashed()->find($id);

                if(!$research){
                    session()->flash('notification-status', "failed");
                    session()->flash('notification-msg', "Record not found.");
                    return redirect()->route('portal.archives.index');
                }

                if($research->restore()){
                    if(env('MAIL_SERVICE', false)){
                        $research_authors = User::whereIn('id', explode(',', $research->authors))->get();

                        $data = [
                            'title' => $research->title,
                            'authors' => $research_authors,
                            'restored_by' => $this->data['auth']->name,
                            'status' => $research->status,
                            'date_time' => Carbon::now()->format('m/d/Y h:i A'),
                        ];

                        foreach($research_authors as $send){
                            Mail::to($send->email)->send(new ArchivesCompletedResearchRestored($data));
                        }
                        Mail::to($this->data['auth']->email)->send(new ArchivesCompletedResearchRestoredSuccess($data));
                    }

                    session()->flash('notification-status', 'success');
                    session()->flash('notification-msg', "Completed research has been restored.");
                    return redirect()->back();
                }

                break;
            case 'researches':
                $research = Research::withTrashed()->find($id);

                if(!$research){
                    session()->flash('notification-status', "failed");
                    session()->flash('notification-msg', "Record not found.");
                    return redirect()->route('portal.archives.index');
                }

                if($research->restore()){
                    $research->logs_with_trashed()->restore();
                    $research->shared_with_trashed()->restore();

                    if(env('MAIL_SERVICE', false)){
                        $data = [
                            'title' => $research->title,
                            'chapter' => $research->chapter,
                            'version' => $research->version,
                            'submitted_to' => $research->submitted_to->name,
                            'restored_by' => $this->data['auth']->name,
                            'status' => $research->status,
                            'date_time' => Carbon::now()->format('m/d/Y h:i A'),
                        ];
                        Mail::to($research->submitted_to->email)->send(new ArchivesResearchRestored($data));
                        Mail::to($this->data['auth']->email)->send(new ArchivesResearchRestoredSuccess($data));
                    }

                    session()->flash('notification-status', 'success');
                    session()->flash('notification-msg', "Research has been restored.");
                    return redirect()->back();
                }

                break;
            default:
                return redirect()->back();

                break;
        }

        return redirect()->back();
    }
}
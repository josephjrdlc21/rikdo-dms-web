<?php

namespace App\Laravel\Controllers\Portal;

use App\Laravel\Models\{Research,ResearchType,User,SharedResearch,ResearchLog,AuditTrail};

use App\Laravel\Requests\PageRequest;
use App\Laravel\Requests\Portal\StudentResearchRequest;

use App\Laravel\Traits\{VerifyResearch,SharesResearch};

use App\Laravel\Notifications\{StudentResearchShared,StudentResearchSharedSuccess,StudentResearchEvaluated,
StudentResearchEvaluatedSuccess,StudentResearchDeleted,StudentResearchDeletedSuccess};

use Carbon,DB,Helper,FileUploader,FileDownloader,FileRemover,Mail;

class StudentResearchController extends Controller{
    use VerifyResearch, SharesResearch;

    protected $data;

    public function __construct(){
        parent::__construct();
        array_merge($this->data?:[], parent::get_data());
        $this->data['research_types'] = ['' => "Select Research Types"] + ResearchType::pluck('type', 'id')->toArray();
        $this->data['page_title'] .= " - Student Research";
        $this->per_page = env("DEFAULT_PER_PAGE", 10);
    }

    public function index(PageRequest $request){
        $this->data['page_title'] .= " - List of Research";

        $this->data['keyword'] = strtolower($request->get('keyword'));
        $this->data['selected_type'] = strtolower($request->input('research_type'));
        $this->data['chapter'] = strtolower($request->input('chapter'));
        $this->data['version'] = strtolower($request->input('version'));

        $first_record = Research::orderBy('created_at', 'ASC')->first();
        $start_date = $request->get('start_date', now()->startOfMonth());
        if ($first_record) {
            $start_date = $request->get('start_date', $first_record->created_at->format("Y-m-d"));
        }

        $this->data['start_date'] = Carbon::parse($start_date)->format("Y-m-d");
        $this->data['end_date'] = Carbon::parse($request->get('end_date', now()))->format("Y-m-d");
        $this->data['research_types'] = ['' => "All"] + ResearchType::pluck('type', 'id')->toArray();

        $this->data['record'] = Research::with(['submitted_by', 'submitted_to', 'research_type'])->where(function ($query) {
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
            if(strlen($this->data['chapter']) > 0){
                $query->where('chapter', $this->data['chapter']);
            }
        })
        ->where(function ($query) {
            if(strlen($this->data['version']) > 0){
                $query->where('version', $this->data['version']);
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
        ->where('submitted_to_id', $this->data['auth']->id)
        ->where('status', 'pending')
        ->orderBy('created_at','DESC')
        ->paginate($this->per_page);

        return view('portal.student-research.index', $this->data);
    }

    public function approved(PageRequest $request){
        $this->data['page_title'] .= " - List of Research";

        $this->data['keyword'] = strtolower($request->get('keyword'));
        $this->data['selected_type'] = strtolower($request->input('research_type'));
        $this->data['chapter'] = strtolower($request->input('chapter'));
        $this->data['version'] = strtolower($request->input('version'));

        $first_record = Research::orderBy('created_at', 'ASC')->first();
        $start_date = $request->get('start_date', now()->startOfMonth());
        if ($first_record) {
            $start_date = $request->get('start_date', $first_record->created_at->format("Y-m-d"));
        }

        $this->data['start_date'] = Carbon::parse($start_date)->format("Y-m-d");
        $this->data['end_date'] = Carbon::parse($request->get('end_date', now()))->format("Y-m-d");
        $this->data['research_types'] = ['' => "All"] + ResearchType::pluck('type', 'id')->toArray();

        $this->data['record'] = Research::with(['submitted_by', 'submitted_to', 'research_type'])->where(function ($query) {
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
            if(strlen($this->data['chapter']) > 0){
                $query->where('chapter', $this->data['chapter']);
            }
        })
        ->where(function ($query) {
            if(strlen($this->data['version']) > 0){
                $query->where('version', $this->data['version']);
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
        ->where('submitted_to_id', $this->data['auth']->id)
        ->where('status', 'approved')
        ->orderBy('created_at','DESC')
        ->paginate($this->per_page);

        return view('portal.student-research.approved', $this->data);
    }

    public function for_revision(PageRequest $request){
        $this->data['page_title'] .= " - List of Research";

        $this->data['keyword'] = strtolower($request->get('keyword'));
        $this->data['selected_type'] = strtolower($request->input('research_type'));
        $this->data['chapter'] = strtolower($request->input('chapter'));
        $this->data['version'] = strtolower($request->input('version'));

        $first_record = Research::orderBy('created_at', 'ASC')->first();
        $start_date = $request->get('start_date', now()->startOfMonth());
        if ($first_record) {
            $start_date = $request->get('start_date', $first_record->created_at->format("Y-m-d"));
        }

        $this->data['start_date'] = Carbon::parse($start_date)->format("Y-m-d");
        $this->data['end_date'] = Carbon::parse($request->get('end_date', now()))->format("Y-m-d");
        $this->data['research_types'] = ['' => "All"] + ResearchType::pluck('type', 'id')->toArray();

        $this->data['record'] = Research::with(['submitted_by', 'submitted_to', 'research_type'])->where(function ($query) {
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
            if(strlen($this->data['chapter']) > 0){
                $query->where('chapter', $this->data['chapter']);
            }
        })
        ->where(function ($query) {
            if(strlen($this->data['version']) > 0){
                $query->where('version', $this->data['version']);
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
        ->where('submitted_to_id', $this->data['auth']->id)
        ->where('status', 'for_revision')
        ->orderBy('created_at','DESC')
        ->paginate($this->per_page);

        return view('portal.student-research.for-revision', $this->data);
    }

    public function rejected(PageRequest $request){
        $this->data['page_title'] .= " - List of Research";

        $this->data['keyword'] = strtolower($request->get('keyword'));
        $this->data['selected_type'] = strtolower($request->input('research_type'));
        $this->data['chapter'] = strtolower($request->input('chapter'));
        $this->data['version'] = strtolower($request->input('version'));

        $first_record = Research::orderBy('created_at', 'ASC')->first();
        $start_date = $request->get('start_date', now()->startOfMonth());
        if ($first_record) {
            $start_date = $request->get('start_date', $first_record->created_at->format("Y-m-d"));
        }

        $this->data['start_date'] = Carbon::parse($start_date)->format("Y-m-d");
        $this->data['end_date'] = Carbon::parse($request->get('end_date', now()))->format("Y-m-d");
        $this->data['research_types'] = ['' => "All"] + ResearchType::pluck('type', 'id')->toArray();

        $this->data['record'] = Research::with(['submitted_by', 'submitted_to', 'research_type'])->where(function ($query) {
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
            if(strlen($this->data['chapter']) > 0){
                $query->where('chapter', $this->data['chapter']);
            }
        })
        ->where(function ($query) {
            if(strlen($this->data['version']) > 0){
                $query->where('version', $this->data['version']);
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
        ->where('submitted_to_id', $this->data['auth']->id)
        ->where('status', 'rejected')
        ->orderBy('created_at','DESC')
        ->paginate($this->per_page);

        return view('portal.student-research.rejected', $this->data);
    }

    public function edit_share(PageRequest $request,$id = null){
        $this->data['page_title'] .= " - Share Research";
        $this->data['research'] = Research::with('research_type')->find($id);

        if(!$this->data['research']){
			session()->flash('notification-status', "failed");
			session()->flash('notification-msg', "Record not found.");
			return redirect()->route('portal.student_research.index');
		}

        $this->data['authors'] = User::where('id', '!=', $this->data['research']->submitted_by_id)->pluck('name', 'id')->toArray();
        $this->data['shared'] = SharedResearch::where('research_id', $this->data['research']->id)->pluck('user_id')->toArray();

        return view('portal.student-research.edit-share', $this->data);
    }

    public function update_share(PageRequest $request,$id = null){
        $research = Research::find($id);

        if(!$research){
            session()->flash('notification-status', "failed");
			session()->flash('notification-msg', "Record not found.");
			return redirect()->route('portal.student_research.index');
        }

        DB::beginTransaction();
        try{
            if($request->input('share_file')){
                $existing_authors = $research->shared()->pluck('user_id')->toArray();

                $remove_authors = array_diff($existing_authors, $request->input('share_file'));
                $new_authors = array_diff($request->input('share_file'), $existing_authors);

                if(!empty($remove_authors)){
                    SharedResearch::where('research_id', $research->id)->whereIn('user_id', $remove_authors)->forceDelete();
                }

                $check_submitted = SharedResearch::where('research_id', $research->id)->where('user_id', $research->submitted_to_id)->first();

                if($check_submitted){
                    $check_submitted->forceDelete();
                }
                
                $this->share($research, $new_authors);
            }

            $research->modified_by_id = $this->data['auth']->id;
            $research->updated_at = Carbon::now();
            $research->save();

            $research_log = new ResearchLog;
            $research_log->research_id = $research->id;
            $research_log->user_id = $this->data['auth']->id;
            $research_log->remarks = "Research has been shared to other users";
            $research_log->save();

            $audit_trail = new AuditTrail;
            $audit_trail->user_id = $this->data['auth']->id;
            $audit_trail->process = "SHARE_RESEARCH";
            $audit_trail->ip = $this->data['ip'];
            $audit_trail->remarks = "{$this->data['auth']->name} has share research to other users.";
            $audit_trail->type = "USER_ACTION";
            $audit_trail->save();

            if(env('MAIL_SERVICE', false)){
                $data = [
                    'title' => $research->title,
                    'chapter' => $research->chapter,
                    'version' => $research->version,
                    'submitted_by' => $research->submitted_by->name,
                    'modified_by' => $research->modified_by->name,
                    'status' => $research->status,
                    'date_time' => $research->updated_at->format('m/d/Y h:i A'),
                ];
                Mail::to($research->submitted_by->email)->send(new StudentResearchShared($data));
                Mail::to($research->modified_by->email)->send(new StudentResearchSharedSuccess($data));
            }

            DB::commit();

            session()->flash('notification-status', "success");
            session()->flash('notification-msg', "Research has been shared with other authors or advisers.");
            return redirect()->route('portal.student_research.show', [$research->id]);
        }catch(\Exception $e){
            DB::rollback();

            session()->flash('notification-status', "failed");
            session()->flash('notification-msg', "Server Error: Code #{$e->getLine()}");
        }

        return redirect()->back();
    }

    public function edit_status(PageRequest $request,$id = null,$status = "pending"){
        $research = Research::find($id);

        if(!$research){
            session()->flash('notification-status', "failed");
			session()->flash('notification-msg', "Record not found.");
			return redirect()->route('portal.student_research.index');
        }

        if($research->status !== "pending"){
            session()->flash('notification-status', "warning");
            session()->flash('notification-msg', "Research has already been processed. It cannot be process again.");
            return redirect()->route('portal.student_research.index');
        }

        switch($status){
            case "approved":
                try{
                    $research->status = $status;
                    $research->process_by_id = $this->data['auth']->id;
                    $research->save();

                    $research_log = new ResearchLog;
                    $research_log->research_id = $research->id;
                    $research_log->user_id = $this->data['auth']->id;
                    $research_log->remarks = "Research has been {$research->status}";
                    $research_log->save();

                    $audit_trail = new AuditTrail;
                    $audit_trail->user_id = $this->data['auth']->id;
                    $audit_trail->process = "EVALUATE_RESEARCH";
                    $audit_trail->ip = $this->data['ip'];
                    $audit_trail->remarks = "{$this->data['auth']->name} has been evaluated the research and set status to {$research->status}.";
                    $audit_trail->type = "USER_ACTION";
                    $audit_trail->save();

                    if(env('MAIL_SERVICE', false)){
                        $data = [
                            'title' => $research->title,
                            'chapter' => $research->chapter,
                            'version' => $research->version,
                            'submitted_by' => $research->submitted_by->name,
                            'processed_by' => $research->processed_by->name,
                            'status' => $research->status,
                            'date_time' => $research->updated_at->format('m/d/Y h:i A'),
                        ];
                        Mail::to($research->submitted_by->email)->send(new StudentResearchEvaluated($data));
                        Mail::to($research->processed_by->email)->send(new StudentResearchEvaluatedSuccess($data));
                    }
                
                    DB::commit();

                    session()->flash('notification-status', "success");
                    session()->flash('notification-msg', "Research has been {$research->status}.");
                }catch(\Exception $e){
                    DB::rollback();

                    session()->flash('notification-status', "failed");
                    session()->flash('notification-msg', "Server Error: Code #{$e->getLine()}");
                    return redirect()->back();
                }

                return redirect()->route('portal.student_research.show', [$research->id]);
                break;
            case "for_revision":
                $this->data['page_title'] .= " - For Revision";
                $this->data['research'] = Research::with(['submitted_by', 'submitted_to', 'research_type'])->find($research->id);
                $this->data['authors'] = User::where('id', '!=', $this->data['research']->submitted_by_id)->pluck('name', 'id')->toArray();
                $this->data['shared'] = SharedResearch::where('research_id', $this->data['research']->id)->pluck('user_id')->toArray();

                return view('portal.student-research.revision', $this->data);
                break;
            case "rejected":
                try{
                    $research->status = $status;
                    $research->process_by_id = $this->data['auth']->id;
                    $research->save();

                    $research_log = new ResearchLog;
                    $research_log->research_id = $research->id;
                    $research_log->user_id = $this->data['auth']->id;
                    $research_log->remarks = "Research has been {$research->status}";
                    $research_log->save();

                    $audit_trail = new AuditTrail;
                    $audit_trail->user_id = $this->data['auth']->id;
                    $audit_trail->process = "EVALUATE_RESEARCH";
                    $audit_trail->ip = $this->data['ip'];
                    $audit_trail->remarks = "{$this->data['auth']->name} has been evaluated the research and set status to {$research->status}.";
                    $audit_trail->type = "USER_ACTION";
                    $audit_trail->save();

                    if(env('MAIL_SERVICE', false)){
                        $data = [
                            'title' => $research->title,
                            'chapter' => $research->chapter,
                            'version' => $research->version,
                            'submitted_by' => $research->submitted_by->name,
                            'processed_by' => $research->processed_by->name,
                            'status' => $research->status,
                            'date_time' => $research->updated_at->format('m/d/Y h:i A'),
                        ];
                        Mail::to($research->submitted_by->email)->send(new StudentResearchEvaluated($data));
                        Mail::to($research->processed_by->email)->send(new StudentResearchEvaluatedSuccess($data));
                    }
                
                    DB::commit();

                    session()->flash('notification-status', "success");
                    session()->flash('notification-msg', "Research has been {$research->status}.");
                }catch(\Exception $e){
                    DB::rollback();

                    session()->flash('notification-status', "failed");
                    session()->flash('notification-msg', "Server Error: Code #{$e->getLine()}");
                    return redirect()->back();
                }

                return redirect()->route('portal.student_research.show', [$research->id]);
                break;
            default:
                return redirect()->route('portal.student_research.show', [$research->id]);

                break;
        }
    }

    public function update_status(StudentResearchRequest $request,$id = null,$status = "pending"){
        $research = Research::find($id);

        if(!$research){
            session()->flash('notification-status', "failed");
			session()->flash('notification-msg', "Record not found.");
			return redirect()->route('portal.student_research.index');
        }

        if($research->status !== "pending"){
            session()->flash('notification-status', "warning");
            session()->flash('notification-msg', "Research has already been processed. It cannot be process again.");
            return redirect()->route('portal.student_research.index');
        }

        if($this->check_research_title($request, $research->id)) {return redirect()->back();}
        if($this->check_chapter_limit($request)) {return redirect()->back();}
        if($this->check_chapter_version($request)) {return redirect()->back();}

        DB::beginTransaction();
        try{
            $research->title = strtoupper($request->input('title'));
            $research->research_type_id = $request->input('research_type');
            $research->process_by_id = $this->data['auth']->id;
            $research->chapter = $request->input('chapter', 0);
            $research->version = $request->input('version');
            $research->save();

            if($request->hasFile('research_file')){                
                FileRemover::remove($research->path);

                $research_file = FileUploader::upload($request->file('research_file'), "uploads/research/{$research->id}");

                $research->path = $research_file['path'];
                $research->directory = $research_file['directory'];
                $research->filename = $research_file['filename'];
                $research->source = $research_file['source'];
                $research->save();
            }

            if($request->input('share_file')){
                $existing_authors = $research->shared()->pluck('user_id')->toArray();

                $remove_authors = array_diff($existing_authors, $request->input('share_file'));
                $new_authors = array_diff($request->input('share_file'), $existing_authors);

                if(!empty($remove_authors)){
                    SharedResearch::where('research_id', $research->id)->whereIn('user_id', $remove_authors)->forceDelete();
                }

                $check_submitted = SharedResearch::where('research_id', $research->id)->where('user_id', $research->submitted_to_id)->first();

                if($check_submitted){
                    $check_submitted->forceDelete();
                }
                
                $this->share($research, $new_authors);
            }

            $research->status = $status;
            $research->updated_at = Carbon::now();
            $research->save();

            $research_log = new ResearchLog;
            $research_log->research_id = $research->id;
            $research_log->user_id = $this->data['auth']->id;
            $research_log->remarks = "Research has been {$research->status}";
            $research_log->save();

            $audit_trail = new AuditTrail;
            $audit_trail->user_id = $this->data['auth']->id;
            $audit_trail->process = "EVALUATE_RESEARCH";
            $audit_trail->ip = $this->data['ip'];
            $audit_trail->remarks = "{$this->data['auth']->name} has been evaluated the research and set status to {$research->status}.";
            $audit_trail->type = "USER_ACTION";
            $audit_trail->save();

            if(env('MAIL_SERVICE', false)){
                $data = [
                    'title' => $research->title,
                    'chapter' => $research->chapter,
                    'version' => $research->version,
                    'submitted_by' => $research->submitted_by->name,
                    'processed_by' => $research->processed_by->name,
                    'status' => $research->status,
                    'date_time' => $research->updated_at->format('m/d/Y h:i A'),
                ];
                Mail::to($research->submitted_by->email)->send(new StudentResearchEvaluated($data));
                Mail::to($research->processed_by->email)->send(new StudentResearchEvaluatedSuccess($data));
            }

            DB::commit();

            session()->flash('notification-status', "success");
            session()->flash('notification-msg', "Research has been evaluate {$research->status}.");
            return redirect()->route('portal.student_research.show', [$research->id]);
        }catch(\Exception $e){
            DB::rollback();

            session()->flash('notification-status', "failed");
            session()->flash('notification-msg', "Server Error: Code #{$e->getLine()}");
            return redirect()->back();
        }

        session()->flash('notification-status', "warning");
        session()->flash('notification-msg', "Unable to revise research.");
        return redirect()->back();
    }

    public function destroy(PageRequest $request,$id = null){
        $research = Research::find($id);

        if(!$research){
            session()->flash('notification-status', "failed");
            session()->flash('notification-msg', "Record not found.");
            return redirect()->route('portal.research.index');
        }

        if($research->delete()){
            $audit_trail = new AuditTrail;
            $audit_trail->user_id = $this->data['auth']->id;
            $audit_trail->process = "DELETE_RESEARCH";
            $audit_trail->ip = $this->data['ip'];
            $audit_trail->remarks = "{$this->data['auth']->name} has been deleted a research.";
            $audit_trail->type = "USER_ACTION";
            $audit_trail->save();

            if(env('MAIL_SERVICE', false)){
                $data = [
                    'title' => $research->title,
                    'chapter' => $research->chapter,
                    'version' => $research->version,
                    'submitted_to' => $research->submitted_to->name,
                    'deleted_by' => $this->data['auth']->name,
                    'status' => $research->status,
                    'date_time' => $research->deleted_at->format('m/d/Y h:i A'),
                ];
                Mail::to($research->submitted_to->email)->send(new StudentResearchDeleted($data));
                Mail::to($this->data['auth']->email)->send(new StudentResearchDeletedSuccess($data));
            }

            session()->flash('notification-status', 'success');
            session()->flash('notification-msg', "Research has been deleted.");
            return redirect()->back();
        }
    }

    public function show(PageRequest $request,$id = null){
        $this->data['page_title'] .= " - Information";
        $this->data['research'] = Research::with(['submitted_by', 'submitted_to', 'research_type', 'department', 'course', 'yearlevel', 'modified_by', 'processed_by', 'logs', 'shared'])->find($id);

        if(!$this->data['research']){
            session()->flash('notification-status', "failed");
            session()->flash('notification-msg', "Record not found.");
            return redirect()->route('portal.student_research.index');
        }

        return view('portal.student-research.show', $this->data);
    }

    public function download(PageRequest $request,$id = null){
        $research = Research::find($id);

        if(!$research){
            session()->flash('notification-status', "failed");
            session()->flash('notification-msg', "Record not found.");
            return redirect()->route('portal.student_research.index');
        }

        try{
            $path = $research->path ? "{$research->path}/{$research->filename}" : "{$research->directory}/{$research->filename}";

            $download = FileDownloader::download($path);

            $audit_trail = new AuditTrail;
            $audit_trail->user_id = $this->data['auth']->id;
            $audit_trail->process = "DOWNLOAD_RESEARCH";
            $audit_trail->ip = $this->data['ip'];
            $audit_trail->remarks = "{$this->data['auth']->name} has downloaded a research.";
            $audit_trail->type = "USER_ACTION";
            $audit_trail->save();

            if($download){
                return $download;
            }
        }catch(\Exception $e){
            session()->flash('notification-status', "failed");
            session()->flash('notification-msg', "Server Error: Code #{$e->getLine()}");
        }

        return redirect()->route('portal.student_research.show', [$research->id]);
    }
}
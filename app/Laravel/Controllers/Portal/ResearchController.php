<?php

namespace App\Laravel\Controllers\Portal;

use App\Laravel\Models\{Research,ResearchType,User,SharedResearch,ResearchLog,AuditTrail};

use App\Laravel\Requests\PageRequest;
use App\Laravel\Requests\Portal\ResearchRequest;

use App\Laravel\Traits\{VerifyResearch,SharesResearch};

use App\Laravel\Notifications\{ResearchSubmitted,ResearchSubmittedSuccess,ResearchSubmittedModified,
ResearchSubmittedModifiedSuccess,ResearchShared,ResearchSharedSuccess,ResearchDeleted,ResearchDeletedSuccess};

use Carbon,DB,Helper,FileUploader,FileDownloader,FileRemover,Mail;

class ResearchController extends Controller{
    use VerifyResearch, SharesResearch;

    protected $data;

    public function __construct(){
        parent::__construct();
        array_merge($this->data?:[], parent::get_data());
        $this->data['research_types'] = ['' => "Select Research Types"] + ResearchType::pluck('type', 'id')->toArray();
        $this->data['researchers'] = User::where('id', '!=', $this->data['auth']->id)->pluck('name', 'id')->toArray();
        $this->data['page_title'] .= " - My Research";
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

        $shared = SharedResearch::where('user_id', $this->data['auth']->id)->pluck('research_id')->toArray();

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
        ->where(function ($query) use ($shared) {
            $query->where('submitted_by_id', $this->data['auth']->id)
                  ->orWhereIn('id', $shared);
        })
        ->where('status', 'pending')
        ->orderBy('created_at','DESC')
        ->paginate($this->per_page);

        return view('portal.research.index', $this->data);
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

        $shared = SharedResearch::where('user_id', $this->data['auth']->id)->pluck('research_id')->toArray();

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
        ->where(function ($query) use ($shared) {
            $query->where('submitted_by_id', $this->data['auth']->id)
                  ->orWhereIn('id', $shared);
        })
        ->where('status', 'approved')
        ->orderBy('created_at','DESC')
        ->paginate($this->per_page);

        return view('portal.research.approved', $this->data);
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

        $shared = SharedResearch::where('user_id', $this->data['auth']->id)->pluck('research_id')->toArray();

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
        ->where(function ($query) use ($shared) {
            $query->where('submitted_by_id', $this->data['auth']->id)
                  ->orWhereIn('id', $shared);
        })
        ->where('status', 'for_revision')
        ->orderBy('created_at','DESC')
        ->paginate($this->per_page);

        return view('portal.research.for-revision', $this->data);
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

        $shared = SharedResearch::where('user_id', $this->data['auth']->id)->pluck('research_id')->toArray();

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
        ->where(function ($query) use ($shared) {
            $query->where('submitted_by_id', $this->data['auth']->id)
                  ->orWhereIn('id', $shared);
        })        
        ->where('status', 'rejected')
        ->orderBy('created_at','DESC')
        ->paginate($this->per_page);

        return view('portal.research.rejected', $this->data);
    }

    public function create(PageRequest $request){
        $this->data['page_title'] .= " - Create Research";

        return view('portal.research.create', $this->data);
    }

    public function store(ResearchRequest $request){
        if($this->check_research_title($request)) {return redirect()->back();}
        if($this->check_chapter_limit($request)) {return redirect()->back();}
        if($this->check_chapter_version($request)){ return redirect()->back();}

        DB::beginTransaction();
        try {
            $research = new Research;
            $research->title = strtoupper($request->input('title'));
            $research->research_type_id = $request->input('research_type');
            $research->department_id = $this->data['auth']->user_info->department_id;
            $research->course_id = $this->data['auth']->user_info->course_id;
            $research->yearlevel_id = $this->data['auth']->user_info->yearlevel_id;
            $research->submitted_to_id = $request->input('submit_to');
            $research->submitted_by_id = $this->data['auth']->id;
            $research->chapter = $request->input('chapter', 0);
            $research->version = $request->input('version');
            $research->save();

            if($request->hasFile('research_file')){
                $research_file = FileUploader::upload($request->file('research_file'), "uploads/research/{$research->id}");

                $research->path = $research_file['path'];
                $research->directory = $research_file['directory'];
                $research->filename = $research_file['filename'];
                $research->source = $research_file['source'];
                $research->save();
            }

            if($request->input('share_file')){
                $this->share($research, $request->input('share_file'));
            }

            $research_log = new ResearchLog;
            $research_log->research_id = $research->id;
            $research_log->user_id = $this->data['auth']->id;
            $research_log->remarks = "New research has been created";
            $research_log->save();

            $audit_trail = new AuditTrail;
            $audit_trail->user_id = $this->data['auth']->id;
            $audit_trail->process = "CREATE_RESEARCH";
            $audit_trail->ip = $this->data['ip'];
            $audit_trail->remarks = "{$this->data['auth']->name} has created a new research.";
            $audit_trail->type = "USER_ACTION";
            $audit_trail->save();

            if(env('MAIL_SERVICE', false)){
                $data = [
                    'title' => $research->title,
                    'chapter' => $research->chapter,
                    'version' => $research->version,
                    'submitted_to' => $research->submitted_to->name,
                    'submitted_by' => $research->submitted_by->name,
                    'status' => $research->status ?? 'pending',
                    'date_time' => $research->created_at->format('m/d/Y h:i A'),
                ];
                Mail::to($research->submitted_to->email)->send(new ResearchSubmitted($data));
                Mail::to($research->submitted_by->email)->send(new ResearchSubmittedSuccess($data));
            }

            DB::commit();

            session()->flash('notification-status', "success");
            session()->flash('notification-msg', "New research has been created.");
            return redirect()->route('portal.research.index');
        }catch(\Exception $e){
            DB::rollback();
            
            session()->flash('notification-status', "failed");
            session()->flash('notification-msg', "Server Error: Code #{$e->getLine()}");
            return redirect()->back();
        }

        session()->flash('notification-status', "warning");
        session()->flash('notification-msg', "Unable to create new research.");
        return redirect()->back();
    }

    public function edit(PageRequest $request,$id = null){
        $this->data['page_title'] .= " - Edit Research";
        $this->data['research'] = Research::with(['submitted_by', 'submitted_to', 'research_type'])->find($id);

        if(!$this->data['research']){
			session()->flash('notification-status', "failed");
			session()->flash('notification-msg', "Record not found.");
			return redirect()->route('portal.research.index');
		}

        if($this->data['research']->status !== "pending"){
			session()->flash('notification-status', "warning");
			session()->flash('notification-msg', "Research has already been processed. It cannot be edited.");
			return redirect()->route('portal.research.index');
		}
        
        $this->data['authors'] = User::where('id', '!=', $this->data['research']->submitted_by_id)->pluck('name', 'id')->toArray();
        $this->data['shared'] = SharedResearch::where('research_id', $this->data['research']->id)->pluck('user_id')->toArray();

        return view('portal.research.edit', $this->data);
    }

    public function update(ResearchRequest $request,$id = null){
        $research = Research::find($id);

        if(!$research){
            session()->flash('notification-status', "failed");
			session()->flash('notification-msg', "Record not found.");
			return redirect()->route('portal.research.index');
        }

        if($research->status !== "pending"){
            session()->flash('notification-status', "warning");
            session()->flash('notification-msg', "Research has already been processed. It cannot be edited.");
            return redirect()->route('portal.research.index');
        }

        if($this->check_research_title($request,$research->id)) {return redirect()->back();}
        if($this->check_chapter_limit($request)) {return redirect()->back();}
        if($this->check_chapter_version($request)) {return redirect()->back();}

        DB::beginTransaction();
        try{
            $research->title = strtoupper($request->input('title'));
            $research->research_type_id = $request->input('research_type');
            $research->submitted_to_id = $request->input('submit_to');
            $research->modified_by_id = $this->data['auth']->id;
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

            $research->updated_at = Carbon::now();
            $research->save();

            $research_log = new ResearchLog;
            $research_log->research_id = $research->id;
            $research_log->user_id = $this->data['auth']->id;
            $research_log->remarks = "Research has been updated";
            $research_log->save();

            $audit_trail = new AuditTrail;
            $audit_trail->user_id = $this->data['auth']->id;
            $audit_trail->process = "UPDATE_RESEARCH";
            $audit_trail->ip = $this->data['ip'];
            $audit_trail->remarks = "{$this->data['auth']->name} has updated a research.";
            $audit_trail->type = "USER_ACTION";
            $audit_trail->save();

            if(env('MAIL_SERVICE', false)){
                $data = [
                    'title' => $research->title,
                    'chapter' => $research->chapter,
                    'version' => $research->version,
                    'submitted_to' => $research->submitted_to->name,
                    'modified_by' => $research->modified_by->name,
                    'status' => $research->status,
                    'date_time' => $research->updated_at->format('m/d/Y h:i A'),
                ];
                Mail::to($research->submitted_to->email)->send(new ResearchSubmittedModified($data));
                Mail::to($research->modified_by->email)->send(new ResearchSubmittedModifiedSuccess($data));
            }

            DB::commit();

            session()->flash('notification-status', "success");
            session()->flash('notification-msg', "Research has been updated.");
            return redirect()->route('portal.research.index');
        }catch(\Exception $e){
            DB::rollback();

            session()->flash('notification-status', "failed");
            session()->flash('notification-msg', "Server Error: Code #{$e->getLine()}");
            return redirect()->back();
        }

        session()->flash('notification-status', "warning");
        session()->flash('notification-msg', "Unable to update research.");
        return redirect()->back();
    }

    public function edit_share(PageRequest $request,$id = null){
        $this->data['page_title'] .= " - Share Research";
        $this->data['research'] = Research::with('research_type')->find($id);

        if(!$this->data['research']){
			session()->flash('notification-status', "failed");
			session()->flash('notification-msg', "Record not found.");
			return redirect()->route('portal.research.index');
		}

        $this->data['authors'] = User::where('id', '!=', $this->data['research']->submitted_by_id)->pluck('name', 'id')->toArray();
        $this->data['shared'] = SharedResearch::where('research_id', $this->data['research']->id)->pluck('user_id')->toArray();

        return view('portal.research.edit-share', $this->data);
    }

    public function update_share(PageRequest $request,$id = null){
        $research = Research::find($id);

        if(!$research){
            session()->flash('notification-status', "failed");
			session()->flash('notification-msg', "Record not found.");
			return redirect()->route('portal.research.index');
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
                    'submitted_to' => $research->submitted_to->name,
                    'modified_by' => $research->modified_by->name,
                    'status' => $research->status,
                    'date_time' => $research->updated_at->format('m/d/Y h:i A'),
                ];
                Mail::to($research->submitted_to->email)->send(new ResearchShared($data));
                Mail::to($research->modified_by->email)->send(new ResearchSharedSuccess($data));
            }

            DB::commit();

            session()->flash('notification-status', "success");
            session()->flash('notification-msg', "Research has been shared with other authors or advisers.");
            return redirect()->route('portal.research.show', [$research->id]);
        }catch(\Exception $e){
            DB::rollback();

            session()->flash('notification-status', "failed");
            session()->flash('notification-msg', "Server Error: Code #{$e->getLine()}");
        }

        return redirect()->back();
    }

    public function destroy(PageRequest $request,$id = null){
        $research = Research::find($id);

        if(!$research){
            session()->flash('notification-status', "failed");
            session()->flash('notification-msg', "Record not found.");
            return redirect()->route('portal.research.index');
        }

        if($research->status !== "pending") {
            session()->flash('notification-status', "warning");
            session()->flash('notification-msg', "Research has already been processed. It cannot be deleted.");
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
                Mail::to($research->submitted_to->email)->send(new ResearchDeleted($data));
                Mail::to($this->data['auth']->email)->send(new ResearchDeletedSuccess($data));
            }

            session()->flash('notification-status', 'success');
            session()->flash('notification-msg', "Research has been deleted.");
            return redirect()->back();
        }
    }

    public function show(PageRequest $request,$id = null){
        $this->data['page_title'] .= " - Information";
        $this->data['research'] = Research::with(['submitted_by', 'submitted_to', 'research_type', 'department', 'course', 'yearlevel', 'modified_by', 'logs', 'shared'])->find($id);

        if(!$this->data['research']){
            session()->flash('notification-status', "failed");
            session()->flash('notification-msg', "Record not found.");
            return redirect()->route('portal.research.index');
        }

        return view('portal.research.show', $this->data);
    }

    public function download(PageRequest $request,$id = null){
        $research = Research::find($id);

        if(!$research){
            session()->flash('notification-status', "failed");
            session()->flash('notification-msg', "Record not found.");
            return redirect()->route('portal.research.index');
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
        
        return redirect()->route('portal.research.show', [$research->id]);
    }
}
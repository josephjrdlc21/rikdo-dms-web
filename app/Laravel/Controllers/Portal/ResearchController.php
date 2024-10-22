<?php

namespace App\Laravel\Controllers\Portal;

use App\Laravel\Models\{Research,ResearchType,User,SharedResearch,ResearchLog};

use App\Laravel\Requests\PageRequest;
use App\Laravel\Requests\Portal\ResearchRequest;

use App\Laravel\Traits\VerifyResearch;

use Carbon,DB,Helper,FileUploader,FileDownloader,FileRemover;

class ResearchController extends Controller{
    use VerifyResearch;

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
        if($this->check_research_title($request)){
            return redirect()->back();
        }

        if($this->check_chapter_limit($request)){
            return redirect()->back();
        }

        if($this->check_chapter_version($request)){
            return redirect()->back();
        }

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
                foreach($request->input('share_file') as $author){
                    if($author != $research->submitted_to_id){
                        $check_research = SharedResearch::where('research_id', $research->id)->where('user_id', $author)->first();
                
                        if(!$check_research){
                            $share_file = new SharedResearch;
                            $share_file->research_id = $research->id;
                            $share_file->user_id = $author;
                            $share_file->save();
                        }
                    }
                }
            }

            $research_log = new ResearchLog;
            $research_log->research_id = $research->id;
            $research_log->user_id = $this->data['auth']->id;
            $research_log->remarks = "New research has been created";
            $research_log->save();

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

        if($this->check_research_title($request,$research->id)){
            return redirect()->back();
        }

        if($this->check_chapter_limit($request)){
            return redirect()->back();
        }

        if($this->check_chapter_version($request)){
            return redirect()->back();
        }

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
                $remove_authors = array_diff($research->shared()->pluck('user_id')->toArray(), $request->input('share_file'));
                $new_authors = array_diff($request->input('share_file'), $research->shared()->pluck('user_id')->toArray());

                if(!empty($remove_authors)){
                    SharedResearch::where('research_id', $research->id)->whereIn('user_id', $remove_authors)->forceDelete();
                    
                    $research->updated_at = Carbon::now();
                    $research->save();
                }

                $check_submitted = SharedResearch::where('research_id', $research->id)->where('user_id', $research->submitted_to_id)->first();

                if($check_submitted){
                    $check_submitted->forceDelete();

                    $research->updated_at = Carbon::now();
                    $research->save();
                }
                
                foreach($new_authors as $author){
                    if($author != $research->submitted_to_id){
                        $check_research = SharedResearch::where('research_id', $research->id)->where('user_id', $author)->first();

                        if(!$check_research){
                            $share = new SharedResearch();
                            $share->research_id = $research->id;
                            $share->user_id = $author;
                            $share->save();

                            $research->updated_at = Carbon::now();
                            $research->save();
                        }
                    }
                }
            }

            $research_log = new ResearchLog;
            $research_log->research_id = $research->id;
            $research_log->user_id = $this->data['auth']->id;
            $research_log->remarks = "Research has been updated";
            $research_log->save();

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
                $remove_authors = array_diff($research->shared()->pluck('user_id')->toArray(), $request->input('share_file'));
                $new_authors = array_diff($request->input('share_file'), $research->shared()->pluck('user_id')->toArray());

                if(!empty($remove_authors)){
                    SharedResearch::where('research_id', $research->id)->whereIn('user_id', $remove_authors)->forceDelete();
                    
                    $research->updated_at = Carbon::now();
                    $research->save();
                }

                $check_submitted = SharedResearch::where('research_id', $research->id)->where('user_id', $research->submitted_to_id)->first();

                if($check_submitted){
                    $check_submitted->forceDelete();

                    $research->updated_at = Carbon::now();
                    $research->save();
                }
                
                foreach($new_authors as $author){
                    if($author != $research->submitted_to_id){
                        $check_research = SharedResearch::where('research_id', $research->id)->where('user_id', $author)->first();

                        if(!$check_research){
                            $share = new SharedResearch();
                            $share->research_id = $research->id;
                            $share->user_id = $author;
                            $share->save();

                            $research->updated_at = Carbon::now();
                            $research->save();
                        }
                    }
                }
            }

            $research_log = new ResearchLog;
            $research_log->research_id = $research->id;
            $research_log->user_id = $this->data['auth']->id;
            $research_log->remarks = "Research has been shared to other users";
            $research_log->save();

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
            session()->flash('notification-status', 'success');
            session()->flash('notification-msg', "Research has been deleted.");
            return redirect()->back();
        }
    }

    public function show(PageRequest $request,$id = null){
        $this->data['page_title'] .= " - Information";
        $this->data['research'] = Research::with(['submitted_by', 'submitted_to', 'research_type', 'department', 'course', 'yearlevel', 'modified_by', 'processed_by'])->find($id);

        if(!$this->data['research']){
            session()->flash('notification-status', "failed");
            session()->flash('notification-msg', "Record not found.");
            return redirect()->route('portal.research.index');
        }

        $this->data['research_history'] = ResearchLog::with('user')->where('research_id', $this->data['research']->id)->get();
        $this->data['shared'] = SharedResearch::with('user')->where('research_id', $this->data['research']->id)->get();

        return view('portal.research.show', $this->data);
    }

    public function download(PageRequest $request,$id = null){
        $research = Research::find($id);

        if(!$research){
            session()->flash('notification-status', "failed");
            session()->flash('notification-msg', "Record not found.");
            return redirect()->route('portal.research.index');
        }

        $path = $research->path ? "{$research->path}/{$research->filename}" : "{$research->directory}/{$research->filename}";

        $download = FileDownloader::download($path);

        if($download){
            return $download;
        }
        
        session()->flash('notification-status', "error");
        session()->flash('notification-msg', "Failed to download research file.");
        return redirect()->route('portal.research.show', [$research->id]);
    }
}
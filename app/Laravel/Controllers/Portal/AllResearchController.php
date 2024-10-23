<?php

namespace App\Laravel\Controllers\Portal;

use App\Laravel\Models\{Research,ResearchType};

use App\Laravel\Requests\PageRequest;

use Carbon,DB,Helper,FileUploader,FileDownloader,FileRemover;

class AllResearchController extends Controller{
    protected $data;

    public function __construct(){
        parent::__construct();
        array_merge($this->data?:[], parent::get_data());
        $this->data['page_title'] .= " - All Research";
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
        ->where('status', 'pending')
        ->orderBy('created_at','DESC')
        ->paginate($this->per_page);
        
        return view('portal.all-research.index', $this->data);
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
        ->where('status', 'approved')
        ->orderBy('created_at','DESC')
        ->paginate($this->per_page);
        
        return view('portal.all-research.approved', $this->data);
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
        ->where('status', 'for_revision')
        ->orderBy('created_at','DESC')
        ->paginate($this->per_page);
        
        return view('portal.all-research.for-revision', $this->data);
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
        ->where('status', 'rejected')
        ->orderBy('created_at','DESC')
        ->paginate($this->per_page);
        
        return view('portal.all-research.rejected', $this->data);
    }

    public function destroy(PageRequest $request,$id = null){
        $research = Research::find($id);

        if(!$research){
            session()->flash('notification-status', "failed");
            session()->flash('notification-msg', "Record not found.");
            return redirect()->route('portal.all_research.index');
        }

        if($research->delete()){
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
            return redirect()->route('portal.all_research.index');
        }

        return view('portal.all-research.show', $this->data);
    }

    public function download(PageRequest $request,$id = null){
        $research = Research::find($id);

        if(!$research){
            session()->flash('notification-status', "failed");
            session()->flash('notification-msg', "Record not found.");
            return redirect()->route('portal.all_research.index');
        }

        $path = $research->path ? "{$research->path}/{$research->filename}" : "{$research->directory}/{$research->filename}";

        $download = FileDownloader::download($path);

        if($download){
            return $download;
        }
        
        session()->flash('notification-status', "error");
        session()->flash('notification-msg', "Failed to download research file.");
        return redirect()->route('portal.all_research.show', [$research->id]);
    }
}
<?php

namespace App\Laravel\Controllers\Portal;

use App\Laravel\Models\{Research,CompletedResearch,PostedResearch,Department,Course,Yearlevel,ResearchType};

use App\Laravel\Requests\PageRequest;

use Rap2hpoutre\FastExcel\FastExcel;
use Carbon,DB,Str,SnappyPDF,Helper;

class ResearchReportsController extends Controller{

    public function __construct(){
        parent::__construct();
        array_merge($this->data?:[], parent::get_data());
        $this->data['page_title'] .= " - Reports";
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

        $first_record = Research::orderBy('created_at', 'ASC')->first();
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

        return view('portal.research-reports.index', $this->data);
    }

    public function completed(PageRequest $request){
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
        ->where('status', '!=', 'posted')
        ->orderBy('created_at','DESC')
        ->paginate($this->per_page);

        return view('portal.research-reports.completed', $this->data);
    }

    public function posted(PageRequest $request){
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

        return view('portal.research-reports.posted', $this->data);
    }

    public function summary(PageRequest $request){
        $this->data['page_title'] .= " - Summary Report";

        return view('portal.research-reports.summary', $this->data);
    }

    public function export(PageRequest $request){
        $this->data['keyword'] = strtolower($request->get('keyword'));
        $this->data['selected_department'] = strtolower($request->get('department'));
        $this->data['selected_course'] = strtolower($request->get('course'));
        $this->data['selected_yearlevel'] = strtolower($request->get('yearlevel'));
        $this->data['selected_status'] = strtolower($request->get('status'));
        $this->data['selected_type'] = strtolower($request->get('type'));
        $this->data['start_date'] = Carbon::parse($request->get('start_date'))->format("Y-m-d");
        $this->data['end_date'] = Carbon::parse($request->get('end_date', now()))->format("Y-m-d");

        switch($request->get('report_type')){
            case "researches":
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
                ->get();
                
                switch(Str::lower($request->get('file_type'))){
                    case "pdf":
                        $pdf = SnappyPDF::loadView('pdf.researches', $this->data)
                            ->setPaper('a4')
                            ->setOrientation('landscape')
                            ->setOption('enable-local-file-access', true);
                    
                            return $pdf->download("researches.pdf");
        
                        break;
        
                    case "excel":
                        return (new FastExcel($this->data['record']))->download('researches.csv', function ($researches) {
                            return [
                               'Title' => $researches->title ?? '',
                               'Type' => $researches->research_type->type ?? '',
                               'Status' => Helper::capitalize_text(str_replace('_', ' ', $researches->status)) ?? '',
                               'Department' => $researches->department->dept_code ?? '',
                               'Course' => $researches->course->course_code ?? '',
                               'Yearlevel' => $researches->yearlevel->yearlevel_name ?? '',
                               'Student' => $researches->submitted_by->name ?? '',
                               'Adviser' => $researches->submitted_to->name ?? '',
                               'Date Created' => Carbon::parse($researches->created_at)->format('m/d/Y h:i A') ?? ''
                            ];
                        });
                
                        break;
                }

                break;
            case "completed":
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
                ->where('status', '!=', 'posted')
                ->orderBy('created_at','DESC')
                ->get();

                switch(Str::lower($request->get('file_type'))){
                    case "pdf":
                        $pdf = SnappyPDF::loadView('pdf.completed-research', $this->data)
                            ->setPaper('a4')
                            ->setOrientation('landscape')
                            ->setOption('enable-local-file-access', true);
                    
                            return $pdf->download("completed-research.pdf");
        
                        break;
        
                    case "excel":
                        return (new FastExcel($this->data['record']))->download('completed-research.csv', function ($completed) {
                            return [
                               'Title' => $completed->title ?? '',
                               'Type' => $completed->research_type->type ?? '',
                               'Status' => Helper::capitalize_text(str_replace('_', ' ', $completed->status)) ?? '',
                               'Department' => $completed->department->dept_code ?? '',
                               'Course' => $completed->course->course_code ?? '',
                               'Yearlevel' => $completed->yearlevel->yearlevel_name ?? '',
                               'Process by' => $completed->processor->name ?? '',
                               'Date Created' => $completed->created_at->format("m/d/Y h:i A") ?? ''
                            ];
                        });
                
                        break;
                }

                break;
            case "posted":
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
                ->get();

                switch(Str::lower($request->get('file_type'))){
                    case "pdf":
                        $pdf = SnappyPDF::loadView('pdf.posted-research', $this->data)
                            ->setPaper('a4')
                            ->setOrientation('landscape')
                            ->setOption('enable-local-file-access', true);
                    
                            return $pdf->download("posted-research.pdf");
        
                        break;
        
                    case "excel":
                        return (new FastExcel($this->data['record']))->download('posted-research.csv', function ($completed) {
                            return [
                               'Title' => $completed->title ?? '',
                               'Type' => $completed->research_type->type ?? '',
                               'Department' => $completed->department->dept_code ?? '',
                               'Course' => $completed->course->course_code ?? '',
                               'Yearlevel' => $completed->yearlevel->yearlevel_name ?? '',
                               'Process by' => $completed->processor->name ?? '',
                               'Date Created' => $completed->created_at->format("m/d/Y h:i A") ?? ''
                            ];
                        });
                
                        break;
                }

                break;
            default:
                return redirect()->back();  

                break;
        }
    }
}
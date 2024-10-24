<?php

namespace App\Laravel\Controllers\Portal;

use App\Laravel\Models\{CompletedResearch,Department,Course,Yearlevel};

use App\Laravel\Requests\PageRequest;

use Carbon,DB;

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

        $this->data['record'] = CompletedResearch::with(['department', 'course', 'yearlevel', 'research_type', 'processor'])->where(function ($query) {
            if (strlen($this->data['keyword']) > 0) {
                $query->whereRaw("LOWER(title) LIKE '%{$this->data['keyword']}%'")
                    ->orWhereRaw("LOWER(authors) LIKE '%{$this->data['keyword']}%'")
                    ->orWhereHas('processor', function ($q) {
                        $q->whereRaw("LOWER(name) LIKE '%{$this->data['keyword']}%'");
                    });
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
}
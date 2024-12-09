<?php 

namespace App\Laravel\Controllers\Portal;

use App\Laravel\Models\{Research,CompletedResearch,PostedResearch,User,UserKYC,Department,Course,Yearlevel,ResearchType};

use App\Laravel\Requests\PageRequest;

use Str,DB,Carbon;

class MainController extends Controller{
    protected $data;
    
    public function __construct(){
        parent::__construct();
		array_merge($this->data?:[], parent::get_data());
        $this->data['page_title'] .= " - Dashboard";
        $this->data['posted_research'] = ['' => "Search Research"] + PostedResearch::pluck('title', 'id')->toArray();
        $this->per_page = env("DEFAULT_PER_PAGE", 10);
    }

    public function index(PageRequest $request){
        $this->data['page_title'] .= " - Data";

        $this->data['total_researches'] = Research::all()->count();
        $this->data['total_completed_research'] = CompletedResearch::all()->count();
        $this->data['total_posted_research'] = PostedResearch::all()->count();
        $this->data['total_researchers'] = User::all()->count();

        $this->data['total_personal_research'] = Research::where('submitted_by_id', $this->data['auth']->id)->count();
        $this->data['total_student_research'] = Research::where('submitted_to_id', $this->data['auth']->id)->count();
        $this->data['total_archives'] = Research::onlyTrashed()->count();
        $this->data['total_applications'] = UserKYC::all()->count();

        $this->data['posted_statistics_data'] = ['labels' => range(date('Y'), date('Y') - 5), 'data' => []];
        
        foreach (range(date('Y'), date('Y') - 5) as $posted_year) {
            $this->data['posted_statistics_data']['data'][] = PostedResearch::whereYear('created_at', $posted_year)->count();
        }

        $this->data['research_statistics_data'] = ['labels' => range(date('Y'), date('Y') - 5), 'data' => []];
        
        foreach (range(date('Y'), date('Y') - 5) as $research_year) {
            $this->data['research_statistics_data']['data'][] = Research::whereYear('created_at', $research_year)->count();
        }

        $this->data['research_status_data'] = ['labels' => ["pending", "approved", "for_revision", "rejected"], 'data' => []];

        foreach ($this->data['research_status_data']['labels'] as $status) {
            $this->data['research_status_data']['data'][] = Research::where('status', $status)->count();
        }

        return view('portal.index', $this->data);
    }

    public function home(PageRequest $request){
        $this->data['page_title'] .= " - Home";
        $this->data['selected_posted_research'] = $request->get('posted_research');

        $this->data['record'] = PostedResearch::where(function ($query) {
            if (strlen($this->data['selected_posted_research']) > 0) {
                $query->where('id', $this->data['selected_posted_research']);
            }
        })
        ->latest('created_at')
        ->first();

        return view('portal.home', $this->data);
    }

    public function about(PageRequest $request){
        $this->data['page_title'] .= " - About";

        return view('portal.about', $this->data);
    }

    public function contact(PageRequest $request){
        $this->data['page_title'] .= " - Contact";

        return view('portal.contact', $this->data);
    }

    public function researches(PageRequest $request){
        $this->data['page_title'] .= " - Researches";

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

        return view('portal.researches', $this->data);
    }

    public function research(PageRequest $request,$id = null){
        $this->data['page_title'] .= " - Research";
        $this->data['research'] = PostedResearch::find($id);

        if(!$this->data['research']){
            session()->flash('notification-status', "failed");
            session()->flash('notification-msg', "Record not found.");
            return redirect()->route('portal.researches');
        }

        return view('portal.research', $this->data);
    }

    public function statistics(PageRequest $request){
        $this->data['page_title'] .= " - Statistics";
        $this->data['total_posted_research'] = PostedResearch::all()->count();
        $this->data['posted_statistics_data'] = ['labels' => range(date('Y'), date('Y') - 5), 'data' => []];
        
        foreach (range(date('Y'), date('Y') - 5) as $posted_year) {
            $this->data['posted_statistics_data']['data'][] = PostedResearch::whereYear('created_at', $posted_year)->count();
        }

        $this->data['research_status_data'] = ['labels' => ["pending", "re_submission", "for_posting", "rejected"], 'data' => []];

        foreach ($this->data['research_status_data']['labels'] as $status) {
            $this->data['research_status_data']['data'][] = CompletedResearch::where('status', $status)->count();
        }

        $this->data['researchers_statistics_data'] = ['labels' => range(date('Y'), date('Y') - 5), 'data' => []];
        
        foreach (range(date('Y'), date('Y') - 5) as $researchers_year) {
            $this->data['researchers_statistics_data']['data'][] = User::whereYear('created_at', $researchers_year)->count();
        }

        return view('portal.statistics', $this->data);
    }
}
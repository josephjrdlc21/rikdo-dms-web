<?php 

namespace App\Laravel\Controllers\Portal;

use App\Laravel\Models\{Research,CompletedResearch,PostedResearch,User,UserKYC};

use App\Laravel\Requests\PageRequest;

use Str,DB,Carbon;

class MainController extends Controller{
    protected $data;
    
    public function __construct(){
        parent::__construct();
		array_merge($this->data?:[], parent::get_data());
        $this->data['page_title'] .= " - Dashboard";
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

        return view('portal.home', $this->data);
    }
}
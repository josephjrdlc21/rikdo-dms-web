<?php 

namespace App\Laravel\Controllers\Portal;

use App\Laravel\Models\{Research,CompletedResearch,PostedResearch,User};

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

        return view('portal.index', $this->data);
    }
}
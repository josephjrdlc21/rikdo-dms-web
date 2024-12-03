<?php 

namespace App\Laravel\Controllers\Web;

//use App\Laravel\Models\{Research,CompletedResearch,PostedResearch,User,UserKYC};

use App\Laravel\Requests\PageRequest;

use Str,DB,Carbon;

class MainController extends Controller{
    protected $data;
    
    public function __construct(){
        parent::__construct();
		array_merge($this->data?:[], parent::get_data());
        $this->data['page_title'] .= " - Library";
    }

    public function index(PageRequest $request){
        $this->data['page_title'] .= " - Home";

        return view('web.index', $this->data);
    }
}
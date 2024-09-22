<?php 

namespace App\Laravel\Controllers\Portal;

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

        return view('portal.index', $this->data);
    }
}
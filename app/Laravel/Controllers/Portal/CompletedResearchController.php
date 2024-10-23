<?php

namespace App\Laravel\Controllers\Portal;

use App\Laravel\Models\CompletedResearch;

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

        return view('portal.completed-research.index', $this->data);
    }
}
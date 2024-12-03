<?php

namespace App\Laravel\Controllers\Web;

use App\Laravel\Controllers\Controller as BaseController;

use Illuminate\Support\Facades\Request;

use Route;

class Controller extends BaseController{
	protected $data;

	public function __construct(){
		self::set_current_route();
	}

	public function get_data(){
        $this->data['page_title'] = env("APP_NAME");
        
		return $this->data;
	}

	public function set_current_route(){
		$this->data['current_route'] = Route::currentRouteName();
	}
}
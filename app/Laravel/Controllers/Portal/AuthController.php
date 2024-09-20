<?php 

namespace App\Laravel\Controllers\Portal;

use App\Laravel\Models\User;

use App\Laravel\Requests\PageRequest;

use Str,DB,Helper,Mail,Carbon;

class AuthController extends Controller{
    protected $data;
    protected $guard;
    
    public function __construct(){
        parent::__construct();
		array_merge($this->data?:[], parent::get_data());
        $this->data['page_title'] .= " - Portal";
        $this->guard = "portal";
    }

    public function login(PageRequest $request){
		$this->data['page_title'] .= " - Login";

		return view('portal.auth.login', $this->data);
	}
}
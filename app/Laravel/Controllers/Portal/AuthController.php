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
        $this->guard = "web";
    }

    public function login(PageRequest $request){
		$this->data['page_title'] .= " - Login";

		return view('portal.auth.login', $this->data);
	}

    public function authenticate(PageRequest $request){
        $email = filter_var($request->input('email'), FILTER_VALIDATE_EMAIL);
        $password = $request->input('password');
        $remember_me = $request->input('remember', 0);

        if(auth($this->guard)->attempt(['email' => $email, 'password' => $password], $remember_me)){
            $account = auth($this->guard)->user();
            
            session()->flash('notification-status', "success");
			session()->flash('notification-msg', "Welcome {$account->name}!");
			return redirect()->route('portal.index');
        }

        session()->flash('notification-status', "failed");
		session()->flash('notification-msg', "Invalid account credentials.");
		return redirect()->back();
    }

    public function logout(PageRequest $request){
		auth($this->guard)->logout();

		session()->flash('notification-status', "success");
		session()->flash('notification-msg', "Logged out successfully.");
		return redirect()->route('portal.auth.login');
	}
}
<?php 

namespace App\Laravel\Controllers\Portal;

use App\Laravel\Models\{User,UserKYC,Role,Department,Course,Yearlevel,PasswordReset};

use App\Laravel\Requests\PageRequest;
use App\Laravel\Requests\Portal\{RegisterRequest,ForgotPasswordRequest,ResetPasswordRequest};

use App\Laravel\Notifications\{ResetPassword,ResetPasswordSuccess};

use Str,DB,Helper,Mail,Carbon;

class AuthController extends Controller{
    protected $data;
    protected $guard;
    
    public function __construct(){
        parent::__construct();
		array_merge($this->data?:[], parent::get_data());
        $this->data['roles'] = ['' => "Select Role"] + Role::where('status', 'active')->pluck('name', 'name')->toArray();
        $this->data['departments'] = ['' => "Select Department"] + Department::pluck('dept_code', 'id')->toArray();
        $this->data['courses'] = ['' => "Select Courses"] + Course::pluck('course_code', 'id')->toArray();
        $this->data['yearlevels'] = ['' => "Select Yearlevel"] + Yearlevel::pluck('yearlevel_name', 'id')->toArray();
        $this->data['page_title'] .= " - Portal";
        $this->guard = "web";
    }

    public function login(PageRequest $request){
		$this->data['page_title'] .= " - Login";

		return view('portal.auth.login', $this->data);
	}

    public function authenticate(PageRequest $request){
		$email = Str::lower($request->input('email'));
        $password = $request->input('password');
        $remember_me = $request->input('remember', 0);

        $field = filter_var($email, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

        if(auth($this->guard)->attempt([$field => $email, 'password' => $password], $remember_me)){
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

    public function register(PageRequest $request){
        $this->data['page_title'] .= " - Register";

        if(!$request->session()->has('current_progress')){
            $request->session()->put('current_progress', '1');
        }

        if(!$request->session()->has('max_progress')){
            $request->session()->put('max_progress', '1');
        }

        $current_progress = $request->session()->get('current_progress');

        switch ($current_progress){
            case '1':
                return view('portal.auth.register-personal-info', $this->data);
                break;
            case '2':
                return view('portal.auth.register-credential', $this->data);
                break;
            case '3':
                return view('portal.auth.register-success', $this->data);
                break;
            default:
                return redirect()->route('portal.auth.register');
                break;
        }
    }

    public function store(RegisterRequest $request){
        $current_progress = $request->session()->get('current_progress', '1');
        $max_progress = $request->session()->get('max_progress', '1');
        
        switch ($current_progress){
            case '1':
                session()->put('personal_info.firstname', strtoupper($request->input('firstname')));
                session()->put('personal_info.middlename', strtoupper($request->input('middlename')));
                session()->put('personal_info.lastname', strtoupper($request->input('lastname')));
                session()->put('personal_info.suffix', strtoupper($request->input('suffix')));
                session()->put('personal_info.birthdate', $request->input('birthdate'));
                session()->put('personal_info.contact', Helper::format_phone($request->input('contact')));
                session()->put('personal_info.email', $request->input('email'));
                session()->put('personal_info.address', $request->input('address'));

                $request->session()->put('current_progress', '2');
                $request->session()->put('max_progress', max($max_progress, '2'));

                return redirect()->route('portal.auth.register');
                break;
            case '2':
                session()->put('credential.id_number', $request->input('id_number'));
                session()->put('credential.role', $request->input('role'));
                session()->put('credential.department', $request->input('department'));
                session()->put('credential.course', $request->input('course'));
                session()->put('credential.yearlevel', $request->input('yearlevel'));

                DB::beginTransaction();
                try{
                    $user_kyc = new UserKYC;
                    $user_kyc->firstname = session()->get('personal_info.firstname');
                    $user_kyc->middlename = session()->get('personal_info.middlename');
                    $user_kyc->lastname = session()->get('personal_info.lastname');
                    $user_kyc->suffix = session()->get('personal_info.suffix');
                    $user_kyc->birthdate = session()->get('personal_info.birthdate');
                    $user_kyc->contact_number = session()->get('personal_info.contact');
                    $user_kyc->email = session()->get('personal_info.email');
                    $user_kyc->address = session()->get('personal_info.address');
                    $user_kyc->id_number = session()->get('credential.id_number');
                    $user_kyc->role = session()->get('credential.role');
                    $user_kyc->department_id = session()->get('credential.department');
                    $user_kyc->course_id = session()->get('credential.course');
                    $user_kyc->yearlevel_id = session()->get('credential.yearlevel');
                    $user_kyc->save();

                    DB::commit();

                    session()->forget('personal_info');
                    session()->forget('credential');

                    $request->session()->put('current_progress', '3');
                    $request->session()->put('max_progress', max($max_progress, '3'));
                }catch(\Exception $e){
                    DB::rollback();

                    session()->flash('notification-status', "failed");
                    session()->flash('notification-msg', "Server Error: Code #{$e->getLine()}");
                    return redirect()->back();
                }

                session()->forget('personal_info');
                session()->forget('credential');

                $request->session()->put('current_progress', '3');
                $request->session()->put('max_progress', max($max_progress, '3'));
                
                return redirect()->route('portal.auth.register');
                break;      
            default:
                return redirect()->route('portal.auth.register');
                break;
        }
    }

    public function forgot_password(PageRequest $request){
        $this->data['page_title'] .= " - Forgot Password";

        return view('portal.auth.forgot-password', $this->data);
    }

    public function forgot_password_email(ForgotPasswordRequest $request){
        $email = strtolower($request->input('email'));
        $token = Str::random(60);

        $user = User::where('email', $email)->first();

        if(!$user){
            session()->flash('notification-status', "failed");
            session()->flash('notification-msg', "Record not found.");
            return redirect()->back();
        }

        if($user->status == "inactive"){
            session()->flash('notification-status', "warning");
            session()->flash('notification-msg', "Unable to process this request because account is locked.");
            return redirect()->back();
        }

        DB::beginTransaction();
        try{
            PasswordReset::where('email', $email)->delete();

            $password_reset = new PasswordReset;
            $password_reset->email = $email;
            $password_reset->token = $token;
            $password_reset->created_at = Carbon::now();
            $password_reset->save();

            if(env('MAIL_SERVICE', false)){
                $data = [
                    'email' => $password_reset->email, 
                    'token' => $password_reset->token,
                    'date_time' => $password_reset->created_at->format('m/d/Y h:i A')
                ];
                Mail::to($password_reset->email)->send(new ResetPassword($data));
            }

            DB::commit();
        }catch(\Exception $e){
            DB::rollback();

            session()->flash('notification-status', "failed");
            session()->flash('notification-msg', "Server Error: Code #{$e->getLine()}");
            return redirect()->back();
        }

        session()->flash('notification-status', "success");
        session()->flash('notification-msg', "Password reset was sent successfully to your email.");
        return redirect()->back();
    }

    public function reset_password(PageRequest $request,$refid = null){
        $this->data['page_title'] .= " - Reset Password";

        $password_reset = PasswordReset::where('token', $refid)->first();

        if(!$password_reset){
            session()->flash('notification-status', "warning");
            session()->flash('notification-msg', "Token is not valid. Please try again!");
            return redirect()->route('portal.auth.forgot_password');
        }

        return view('portal.auth.reset-password', $this->data);
    }

    public function store_password(ResetPasswordRequest $request, $refid = null){
        $password_reset = PasswordReset::where('token', $refid)->first();
        $current_date_time = Carbon::now();

        if (!$password_reset) {
            session()->flash('notification-status', "warning");
            session()->flash('notification-msg', "Token is not valid. Please try again!");
            return redirect()->route('portal.auth.forgot_password');
        }

        $user = User::where('email', strtolower($password_reset->email))->first();

        if(!$user){
            session()->flash('notification-status', "warning");
            session()->flash('notification-msg', "Record not found.");
            return redirect()->route('portal.auth.forgot_password');
        }

        DB::beginTransaction();
        try{
            $user->password = bcrypt($request->input('password'));
            $user->save();

            if (env('MAIL_SERVICE', false)) {
                $data = [
                    'name' => $user->name,
                    'email' => $user->email, 
                    'date_time' => $user->updated_at->format('m/d/Y h:i A'),
                ];
                Mail::to($user->email)->send(new ResetPasswordSuccess($data));
            }

            PasswordReset::where('email', $user->email)->delete();

            DB::commit();

            session()->flash('notification-status', "success");
            session()->flash('notification-msg', "New password successfully stored. Login to the platform using your updated credentials.");

            auth($this->guard)->login($user);

            return redirect()->route('portal.index');
        }catch(\Exception $e){
            DB::rollback();

            session()->flash('notification-status', "failed");
            session()->flash('notification-msg', "Server Error: Code #{$e->getLine()}");
        }

        return redirect()->back();
    }

    public function cancel(PageRequest $request){
        session()->forget('personal_info');
        session()->forget('credential');
        session()->forget('current_progress');
        session()->forget('max_progress');

        return redirect()->route('portal.auth.login');
    }

    public function step_back(PageRequest $request,$step = null){
        $max_progress = $request->session()->get('max_progress', '1');

        if(in_array($step, ['1', '2', '3']) && $step <= $max_progress){
            session()->put('current_progress', $step);
        }

        return redirect()->route('portal.auth.register');
    }
}
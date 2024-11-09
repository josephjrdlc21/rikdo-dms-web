<?php

namespace App\Laravel\Controllers\Portal;

use App\Laravel\Models\User;

use App\Laravel\Requests\PageRequest;
use App\Laravel\Requests\Portal\ChangePasswordRequest;

use Carbon,DB,Helper;

class ProfileController extends Controller{
    protected $data;

    public function __construct(){
        parent::__construct();
        array_merge($this->data?:[], parent::get_data());
        $this->data['page_title'] .= " - Profile";
        $this->per_page = env("DEFAULT_PER_PAGE", 10);
    }

    public function edit_password(PageRequest $request){
        $this->data['page_title'] .= " - Change Password";

        return view('portal.profile.edit-password', $this->data);
    }

    public function update_password(ChangePasswordRequest $request){
        $account = $this->data['auth'];

        DB::beginTransaction();
		try{
            $account->password = bcrypt($request->input('password'));
            $account->save();

			DB::commit();

			session()->flash('notification-status',"success");
			session()->flash('notification-msg',"Your password has been changed.");
			return redirect()->route('portal.index');
		}catch(\Exception $e){
			DB::rollBack();

			session()->flash('notification-status',"failed");
            session()->flash('notification-msg', "Server Error: Code #{$e->getLine()}");			
            return redirect()->back();
		}

        session()->flash('notification-status', "warning");
        session()->flash('notification-msg', "Unable to change password.");
        return redirect()->back();
    }
}
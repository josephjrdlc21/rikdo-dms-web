<?php

namespace App\Laravel\Controllers\Portal;

use App\Laravel\Models\{User,UserInfo};

use App\Laravel\Requests\PageRequest;
use App\Laravel\Requests\Portal\{ChangePasswordRequest,ChangePictureRequest};

use Carbon,DB,Helper,FileUploader,FileRemover;

class ProfileController extends Controller{
    protected $data;

    public function __construct(){
        parent::__construct();
        array_merge($this->data?:[], parent::get_data());
        $this->data['page_title'] .= " - Profile";
        $this->per_page = env("DEFAULT_PER_PAGE", 10);
    }

    public function index(PageRequest $request){
        $this->data['page_title'] .= " - Details";

        return view('portal.profile.index', $this->data);
    }

    public function update_profile(ChangePictureRequest $request){
        $account = $this->data['auth'];

        if(!$account){
			session()->flash('notification-status', "failed");
			session()->flash('notification-msg', "Record not found.");
			return redirect()->route('portal.index');
		}
        
        DB::beginTransaction();
        try{
            if($request->hasFile('profile_picture')){                
                FileRemover::remove($account->user_info->path);

                $profile_picture = FileUploader::upload($request->file('profile_picture'), "uploads/profile/{$account->user_info->id}");

                $account->user_info->path = $profile_picture['path'];
                $account->user_info->directory = $profile_picture['directory'];
                $account->user_info->filename = $profile_picture['filename'];
                $account->user_info->source = $profile_picture['source'];
                $account->user_info->save();
            }

            DB::commit();

            session()->flash('notification-status', "success");
            session()->flash('notification-msg', "Profile picture was updated.");
        }catch(\Exception $e){
            DB::rollback();

            session()->flash('notification-status', "failed");
            session()->flash('notification-msg', "Server Error: Code #{$e->getLine()}");
        }

        return redirect()->back();
    }

    public function edit_password(PageRequest $request){
        $this->data['page_title'] .= " - Change Password";

        return view('portal.profile.edit-password', $this->data);
    }

    public function update_password(ChangePasswordRequest $request){
        $account = $this->data['auth'];

        if(!$account){
			session()->flash('notification-status', "failed");
			session()->flash('notification-msg', "Record not found.");
			return redirect()->route('portal.index');
		}

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
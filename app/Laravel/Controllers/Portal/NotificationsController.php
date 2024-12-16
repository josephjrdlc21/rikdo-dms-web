<?php

namespace App\Laravel\Controllers\Portal;

use App\Laravel\Models\Notification;

use App\Laravel\Requests\PageRequest;
use App\Laravel\Requests\Portal\NotificationRequest;

use DB;

class NotificationsController extends Controller{
    protected $data;

    public function __construct(){
        parent::__construct();
        array_merge($this->data?:[], parent::get_data());
        $this->data['page_title'] .= " - Notifications";
        $this->per_page = env("DEFAULT_PER_PAGE", 10);
    }

    public function index(PageRequest $request){
        $this->data['page_title'] .= " - List of Notification";
        $this->data['notification'] = Notification::where('user_id', $this->data['auth']->id)->first();

        return view('portal.notifications.index', $this->data);
    }

    public function store(NotificationRequest $request){
        DB::beginTransaction();
        try{
            $notification = Notification::where('user_id', $this->data['auth']->id)->first() ?? new Notification;
            $notification->user_id = $this->data['auth']->id;
            $notification->has_research = $request->input('has_research', 0);
            $notification->has_student_research = $request->input('has_student_research', 0);
            $notification->has_all_research = $request->input('has_all_research', 0);
            $notification->has_completed_research = $request->input('has_completed_research', 0);
            $notification->has_posted_research = $request->input('has_posted_research', 0);
            $notification->has_archives = $request->input('has_archives', 0);
            $notification->save();

            DB::commit();

            session()->flash('notification-status', "success");
            session()->flash('notification-msg', "Notification has been set.");
        }catch(\Exception $e){
            DB::rollback();
            
            session()->flash('notification-status', "failed");
            session()->flash('notification-msg', "Server Error: Code #{$e->getLine()}");
            return redirect()->back();
        }
        
        return redirect()->route('portal.notifications.index');
    }
}
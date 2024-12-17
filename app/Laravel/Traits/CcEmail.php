<?php

namespace App\Laravel\Traits;

use App\Laravel\Models\Notification;

use DB;

trait CcEmail{
    public function cc_email_has_research(){
        $users = Notification::with('user')->where('has_research', true)->get()->pluck('user.email')->filter()->toArray();

        return $users;
    }

    public function cc_email_has_student_research(){
        $users = Notification::with('user')->where('has_student_research', true)->get()->pluck('user.email')->filter()->toArray();

        return $users;
    }

    public function cc_email_has_all_research(){
        $users = Notification::with('user')->where('has_all_research', true)->get()->pluck('user.email')->filter()->toArray();

        return $users;
    }

    public function cc_email_has_completed_research(){
        $users = Notification::with('user')->where('has_completed_research', true)->get()->pluck('user.email')->filter()->toArray();

        return $users;
    }

    public function cc_email_has_posted_research(){
        $users = Notification::with('user')->where('has_posted_research', true)->get()->pluck('user.email')->filter()->toArray();

        return $users;
    }

    public function cc_email_has_archives(){
        $users = Notification::with('user')->where('has_archives', true)->get()->pluck('user.email')->filter()->toArray();

        return $users;
    }
}
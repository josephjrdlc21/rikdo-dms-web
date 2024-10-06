<?php

namespace App\Laravel\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class UserAccountApproved extends Mailable
{
    use Queueable;
    use SerializesModels;
    
    /**
    * Create a new message instance.
    *
    * @return void
    */
    public function __construct($data)
    {
        $this->data = $data;
        $this->subject("RIKDO User Account Approved");
    }
    
    /**
    * Build the message.
    *
    * @return $this
    */
    public function build()
    {
        return $this->view('emails.user-account-approved')
            ->with([
                'username' => $this->data['username'],
                'name' => $this->data['name'],
                'email' => $this->data['email'],
                'date_time' => $this->data['date_time'],
                'password' => $this->data['password']
            ]);
    }
}

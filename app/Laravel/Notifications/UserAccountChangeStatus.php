<?php

namespace App\Laravel\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class UserAccountChangeStatus extends Mailable
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
        $this->subject("RIKDO User Account Change Status");
    }
    
    /**
    * Build the message.
    *
    * @return $this
    */
    public function build()
    {
        return $this->view('emails.user-account-change-status')
            ->with([
                'name' => $this->data['name'],
                'status' => $this->data['status'],
                'email' => $this->data['email'],
                'date_time' => $this->data['date_time']
            ]);
    }
}

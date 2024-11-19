<?php

namespace App\Laravel\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CompletedResearchUpdatedSuccess extends Mailable
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
        $this->subject("RIKDO Completed Research Updated Success");
    }
    
    /**
    * Build the message.
    *
    * @return $this
    */
    public function build()
    {
        return $this->view('emails.completed-research-updated-success')
            ->with([
                'title' => $this->data['title'],
                'authors' => $this->data['authors'],
                'status' => $this->data['status'],
                'date_time' => $this->data['date_time']
            ]);
    }
}

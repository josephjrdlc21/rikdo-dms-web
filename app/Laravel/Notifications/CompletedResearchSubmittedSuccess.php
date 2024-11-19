<?php

namespace App\Laravel\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CompletedResearchSubmittedSuccess extends Mailable
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
        $this->subject("RIKDO Completed Research Submitted Success");
    }
    
    /**
    * Build the message.
    *
    * @return $this
    */
    public function build()
    {
        return $this->view('emails.completed-research-submitted-success')
            ->with([
                'title' => $this->data['title'],
                'authors' => $this->data['authors'],
                'status' => $this->data['status'],
                'date_time' => $this->data['date_time']
            ]);
    }
}

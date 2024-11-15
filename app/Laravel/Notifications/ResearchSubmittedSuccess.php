<?php

namespace App\Laravel\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ResearchSubmittedSuccess extends Mailable
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
        $this->subject("RIKDO Research Submitted Success");
    }
    
    /**
    * Build the message.
    *
    * @return $this
    */
    public function build()
    {
        return $this->view('emails.research-submitted-success')
            ->with([
                'title' => $this->data['title'],
                'chapter' => $this->data['chapter'],
                'version' => $this->data['version'],
                'submitted_to' => $this->data['submitted_to'],
                'submitted_by' => $this->data['submitted_by'],
                'status' => $this->data['status'],
                'date_time' => $this->data['date_time']
            ]);
    }
}

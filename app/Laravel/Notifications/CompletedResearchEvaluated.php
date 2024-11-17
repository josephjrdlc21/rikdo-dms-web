<?php

namespace App\Laravel\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CompletedResearchEvaluated extends Mailable
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
        $this->subject("RIKDO Completed Research Evaluated");
    }
    
    /**
    * Build the message.
    *
    * @return $this
    */
    public function build()
    {
        return $this->view('emails.completed-research-evaluated')
            ->with([
                'title' => $this->data['title'],
                'chapter' => $this->data['chapter'],
                'version' => $this->data['version'],
                'submitted_by' => $this->data['submitted_by'],
                'processed_by' => $this->data['processed_by'],
                'status' => $this->data['status'],
                'date_time' => $this->data['date_time']
            ]);
    }
}

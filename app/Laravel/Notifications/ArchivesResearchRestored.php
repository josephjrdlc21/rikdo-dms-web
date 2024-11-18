<?php

namespace App\Laravel\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ArchivesResearchRestored extends Mailable
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
        $this->subject("RIKDO Archives Research Restored");
    }
    
    /**
    * Build the message.
    *
    * @return $this
    */
    public function build()
    {
        return $this->view('emails.archives-research-restored')
            ->with([
                'title' => $this->data['title'],
                'chapter' => $this->data['chapter'],
                'version' => $this->data['version'],
                'submitted_by' => $this->data['submitted_by'],
                'restored_by' => $this->data['restored_by'],
                'status' => $this->data['status'],
                'date_time' => $this->data['date_time']
            ]);
    }
}

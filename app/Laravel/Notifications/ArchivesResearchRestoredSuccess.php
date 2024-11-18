<?php

namespace App\Laravel\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ArchivesResearchRestoredSuccess extends Mailable
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
        $this->subject("RIKDO Archives Research Restored Success");
    }
    
    /**
    * Build the message.
    *
    * @return $this
    */
    public function build()
    {
        return $this->view('emails.archives-research-restored-success')
            ->with([
                'title' => $this->data['title'],
                'chapter' => $this->data['chapter'],
                'version' => $this->data['version'],
                'restored_by' => $this->data['restored_by'],
                'status' => $this->data['status'],
                'date_time' => $this->data['date_time']
            ]);
    }
}

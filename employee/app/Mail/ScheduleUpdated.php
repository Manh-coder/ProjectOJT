<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ScheduleUpdated extends Mailable
{
    use Queueable, SerializesModels;

    public $scheduleData;

    /**
     * Create a new message instance.
     *
     * @param array $scheduleData
     */
    public function __construct($scheduleData)
    {
        $this->scheduleData = $scheduleData;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Schedule Update Notification')
                    ->view('emails.schedule_updated')
                    ->with('scheduleData', $this->scheduleData);
    }
}

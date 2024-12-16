<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Models\LeaveRequest;

class LeaveRequestStatusChanged extends Mailable
{
    use Queueable, SerializesModels;

    public $leaveRequest;
    public $message;

    /**
     * Create a new message instance.
     */
    public function __construct(LeaveRequest $leaveRequest, $message)
    {
        $this->leaveRequest = $leaveRequest;
        $this->message = $message;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->subject('Leave Request Status Update')
                    ->view('emails.leave_request_status')
                    ->with([
                        'leaveRequest' => $this->leaveRequest,
                        'message' => $this->message,
                    ]);
    }
}

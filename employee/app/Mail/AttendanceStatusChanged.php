<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;

class AttendanceStatusChanged extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $status;
    public $explanation;

    /**
     * Create a new message instance.
     *
     * @param User $user
     * @param string $status
     * @param string|null $explanation
     * @return void
     */
    public function __construct(User $user, string $status, ?string $explanation)
    {
        $this->user = $user;
        $this->status = $status;
        $this->explanation = $explanation;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.attendance_status_changed')
            ->subject('Your Attendance Status Has Been Updated')
            ->with([
                'userName' => $this->user->name,
                'status' => $this->status,
                'explanation' => $this->explanation,
            ]);
    }
}

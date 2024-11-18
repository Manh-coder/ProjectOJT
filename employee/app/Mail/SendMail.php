<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SendMail extends Mailable
{
    use Queueable, SerializesModels;

    public $subject;
    public $messageContent;
    public $employeeName;

    /**
     * Tạo một instance mới của email.
     */
    public function __construct($subject, $messageContent, $employeeName)
    {
        $this->subject = $subject;
        $this->messageContent = $messageContent;
        $this->employeeName = $employeeName;
    }

    /**
     * Định nghĩa envelope cho email.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: $this->subject,
        );
    }

    /**
     * Định nghĩa nội dung email với Markdown.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'emails.employee_notification',
            with: [
                'name' => $this->employeeName,
                'content' => $this->messageContent,
            ]
        );
    }
}

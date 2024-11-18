<?php

namespace App\Console\Commands;

use App\Mail\SendMail;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class SendMailCheckOut extends Command
{
    protected $signature = 'app:send-mail-check-out';
    protected $description = 'Send reminder emails for employees to check out';

    public function handle()
    {
        $employees = User::typeEmployee()->get();

        foreach ($employees as $employee) {
            $subject = 'Check-out Reminder';
            $content = 'Please check out at 5:00 PM to end your work day!';
            $name = $employee->name;

            Mail::to($employee->email)->send(new SendMail($subject, $content, $name));
            sleep(1); // Pause for 1 second between emails
        }

        $this->info('Check-out reminder emails have been successfully sent!');
    }
}

<?php

namespace App\Console\Commands;

use App\Mail\SendMail;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class SendMailCheckIn extends Command
{
    protected $signature = 'app:send-mail-check-in';
    protected $description = 'Send reminder emails for employees to check-in';

    public function handle()
    {
        $employees = User::typeEmployee()->get();

        foreach ($employees as $employee) {
            $subject = 'Check-in Reminder';
            $content = 'Please check in to start your work day!';
            $name = $employee->name;

            Mail::to($employee->email)->send(new SendMail($subject, $content, $name));
            sleep(1); // Pause for 1 second between emails
        }

        $this->info('Check-in reminder emails have been successfully sent!');
    }
}

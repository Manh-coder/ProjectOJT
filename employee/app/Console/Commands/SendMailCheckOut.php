<?php

namespace App\Console\Commands;

use App\Mail\SendMail;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class SendMailCheckOut extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:send-mail-check-out';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send email reminders to employees to checkout at the end of the day';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $entries = User::typeEmployee()->get();

        foreach ($entries as $entry) {
            // Gửi email nhắc nhở checkout
            Mail::to($entry->email)->send(new SendMail('Please Checkout'));
            sleep(1); // Tạm dừng 1 giây giữa các email
        }
    }
}

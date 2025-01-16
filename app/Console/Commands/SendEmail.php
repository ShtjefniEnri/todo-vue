<?php

namespace App\Console\Commands;

use App\Exports\TodosExport;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use Maatwebsite\Excel\Facades\Excel;

class SendEmail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:send-email';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command is used to send email of the todo report to the user.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        if ($this->confirm('Do you want to send the email?', true)) {
            $password = $this->secret('Enter your password');

            if ($password === env('SEND_EMAIL_PASSWORD')) {
                $file = storage_path('app/private/report.xlsx');
                $email = 'enri.shtjefni@ash.al';
                $subject = 'Todos Report';
                $data = [
                    'name' => 'Enri Shtjefni',
                ];

                Excel::store(new TodosExport, 'report.xlsx');

                Mail::send('report', $data, function ($message) use ($email, $subject, $file) {
                    $message->to($email)->subject($subject)->attach($file);
                    $message->from(env('MAIL_FROM_ADDRESS'), env('MAIL_FROM_NAME'));
                });
                $this->info('Email sent successfully!');
            }
            else {
                $this->error('Password is incorrect!');
            }
        } else {
            $this->error('Email not sent!');
        }
    }
}

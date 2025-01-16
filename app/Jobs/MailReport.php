<?php

namespace App\Jobs;

use App\Exports\TodosExport;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use Maatwebsite\Excel\Facades\Excel;

class MailReport implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
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
    }
}

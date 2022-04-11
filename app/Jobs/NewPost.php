<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class NewPost implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $emails;
    protected $inputs;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($emails, $input)
    {
        $this->emails = $emails;
        $this->inputs = $input;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $inputs = $this->inputs;
        $data = [
            'title' => $inputs['title'],
            'description' => $inputs['description'],
        ];
        $recipients = $this->emails;

        Mail::send('emails.newpost', $data, function ($message) use ($data, $recipients) {
            $message->to($recipients);
            $message->subject("New Post Alert");
        });
    }
}

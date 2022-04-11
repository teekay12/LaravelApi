<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class NewPost implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $emails;
    protected $request;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($emails, $request)
    {
        $this->emails = $emails;
        $this->request = $request;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $request = $this->request;
        $data = [
            'title' => $this->request->title,
            'description' => $this->request->description,
        ];
        $recipients = $this->emails;

        Mail::send('emails.newpost', $input, function ($message) use ($data, $recipients) {
            $message->to($recipients);
            $message->subject("New Post Alert");
        });
    }
}

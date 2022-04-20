<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use App\Models\Subscription;

class NewPost implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $inputs;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($input)
    {
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
        $arr = [];
        $email_obj = Subscription::join('users', 'subscriptions.user_id', '=', 'users.id')
                        ->where('website_id', $input['website_id'])
                        ->orderBy('subscription.user_id', 'desc')
                        ->select('users.email')->get();

        foreach($email_obj as $item){
            array_push($arr, $item->email);
        }

        if(count($arr) > 0){
            $data = [
                'title' => $inputs['title'],
                'description' => $inputs['description'],
            ];
            $recipients = $arr;

            Mail::send('emails.newpost', $data, function ($message) use ($data, $recipients) {
                $message->from("hello@example.com");
                $message->to($recipients);
                $message->subject("New Post Alert");
            });
        }
    }
}

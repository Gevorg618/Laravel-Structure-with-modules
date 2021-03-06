<?php

namespace App\Jobs;

use App\Mail\UserSendMail;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class SendUserEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $subject;

    public $content;

    public $email;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($subject, $content, $email)
    {
        $this->subject = $subject;
        $this->content = $content;
        $this->email = $email;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        \Mail::to($this->email)->send(new UserSendMail($this->content, $this->subject));
    }
}

<?php

namespace App\Mail\Test;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class TestMail extends Mailable
{
    public $content;
    public $subject;
    public $order;
    public $invite;

    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @param $content
     * @param $subject
     */
    public function __construct($subject, $content, $order, $invite)
    {
        $this->content = $content;
        $this->subject = $subject;
        $this->order = $order;
        $this->invite = (bool) $invite;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $a = $this->invite ? 'invite' : 'test';
        return $this->view('emails.test.' . $a)->subject($this->subject);
    }
}

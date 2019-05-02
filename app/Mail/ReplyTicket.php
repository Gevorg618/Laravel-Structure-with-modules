<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class ReplyTicket extends Mailable
{
    public $content;

    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @param $content
     * @param $subject
     */
    public function __construct($content, $subject)
    {
        $this->content = $content;
        $this->subject = $subject;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.tickets.reply')->subject($this->subject);
    }
}

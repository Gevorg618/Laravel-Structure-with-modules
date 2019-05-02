<?php

namespace Dashboard\Mail\Auth;

use App\Models\Users\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Password;
use Illuminate\Contracts\Queue\ShouldQueue;

class PasswordReset extends Mailable implements ShouldQueue
{
    public $user;
    public $token;
    public $subject;

    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @param $content
     * @param $subject
     */
    public function __construct(User $user)
    {
        $this->user = $user;
        $this->token = Password::getRepository()->create($user);
        $this->subject = 'Password Reset Request';
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('dashboard::emails.auth.reset', ['title' => $this->subject])->subject(mailSubject($this->subject));
    }
}

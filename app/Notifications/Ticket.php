<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class Ticket extends Notification
{
    use Queueable;

    private $ticket;
    private $comment;
    private $subject = 'Ticket Notification';

    /**
     * Ticket constructor.
     * @param $ticket
     * @param $type
     * @param $comment
     */
    public function __construct($ticket, $type, $comment)
    {
        $this->ticket = $ticket;
        $this->comment = $comment;

        switch ($type) {
            case 'comment':
                $this->subject = sprintf('New Ticket Comment Posted [#%s]', $ticket->id);
                break;

            case 'assign':
                $this->subject = sprintf('New Support Ticket Assigned [#%s]', $ticket->id);
                break;
        }
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        if (admin()->id != $notifiable->id) {
            \App\Models\Mail\Notification::insert([
                'from_userid' => admin()->id,
                'to_userid' => $notifiable->id,
                'created_date' => time(),
                'message' => $this->subject,
                'link' => route('admin.ticket.manager.view', ['id' => $this->ticket->id])
            ]);
        }

        return (new MailMessage)->subject($this->subject)
            ->view('admin::ticket.manager.templates._ticket_notification', [
                'ticket' => $this->ticket, 'comment' => $this->comment
            ]);
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}

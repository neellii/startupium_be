<?php
namespace App\Notifications\Message\Mail;

use App\Entity\Chat\Message;
use App\Entity\User\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PostedMessage extends Notification implements ShouldQueue
{
    use Queueable;

    private $message;
    private $userFrom;

    public function __construct(Message $message, User $userFrom)
    {
        $this->message = $message;
        $this->userFrom = $userFrom;
    }

    public function via($notifiable)
    {
        if ($notifiable->sendByEmailSettings()->get()->first()->newMessage
            && $notifiable->last_online_at->diffInDays(now()) !== 0) {
            return ['mail'];
        }
        return [];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Вам новое сообщение')
            ->view('emails.notification.new-message', ['user' => $this->userFrom, 'text' => $this->message->text]);
    }
}

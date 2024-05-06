<?php
namespace App\Notifications\Comment\Mail;

use App\Entity\Comment\Comment;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AnsweredComment extends Notification implements ShouldQueue
{
    use Queueable;
    private $comment;

    public function __construct(Comment $comment)
    {
        $this->comment = $comment;
    }

    public function via($notifiable)
    {
        if ($notifiable->sendByEmailSettings()->get()->first()?->commentAnswer
            && $notifiable->last_online_at->diffInDays(now()) !== 0) {
            return ['mail'];
        }
        return [];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Новый комментарий')
            ->view('emails.notification.answered-comment', [
                'user' => $this->comment->user,
                'text' => $this->comment->comment,
                'project' => $this->comment->project
            ]);
    }
}

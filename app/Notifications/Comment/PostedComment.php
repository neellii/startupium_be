<?php
namespace App\Notifications\Comment;

use App\Entity\Comment\Comment;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class PostedComment extends Notification implements ShouldQueue
{
    use Queueable;

    private $comment;

    public function __construct(Comment $comment)
    {
        $this->comment = $comment;
    }

    public function via($notifiable)
    {
        if ($notifiable->notificationSettings()->get()->first()?->showComments
            && authUser()->id !== $notifiable->id) {
            return ['database'];
        }
        return [];
    }

    public function toArray($notifiable)
    {
        return [
            'type' => Comment::POST_COMMENT,
            'title' => $this->comment->project->title,
            'dataId' => $this->comment->project->id,
            'dataSlug' => $this->comment->project->slug,
            'author' => [
                'id' => $this->comment->user->id,
                'firstname' => $this->comment->user->firstname,
                'lastname' => lastnameFormat($this->comment->user->lastname),
                'isOnline' => $this->comment->user->isOnline(),
                'lastOnlineAt' => $this->comment->user->last_online_at,
                'avatarUrl' => $this->comment->user->avatar ? $this->comment->user->avatar->url : null,
            ],
            'createdAt' => now()
        ];
    }
}

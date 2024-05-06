<?php

namespace App\Http\Resources\Notification;

use Illuminate\Http\Request;
use App\Entity\Comment\Comment;
use Illuminate\Http\Resources\Json\JsonResource;

class CommentNotificationResource extends JsonResource
{
    private $comment;
    private $type;
    public function __construct(Comment $comment, string $type)
    {
        $this->comment = $comment;
        $this->type = $type;
    }

    public function toArray(Request $request): array
    {
        return [
            'data' => [
                'type' => $this->type,
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
            ],
            'readAt' => null,
            'createdAt' => now()
        ];
    }
}

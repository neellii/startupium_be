<?php

namespace App\Http\Resources\Comment;

use Illuminate\Http\Request;
use App\Entity\Comment\BlogComment;
use Illuminate\Http\Resources\Json\JsonResource;

class BlogCommentDetailResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        /** @var BlogComment $this */
        $parent = $this->getParentOrNull();
        $reply = $this->getReplyOrNull();
        return [
            'id' => $this->id,
            'message' => $this->getCustomMessage(),
            'createdAt' => $this->created_at,
            'isDeleted' => $this->isDeleted(),
            'author' => $this->getAuthor(),
            'blog' => [
                'id' => $this->blog?->id,
                'title' => $this->blog?->title
            ],
            'replyComment' => $reply ? [
                'id' => $reply->id,
                'author' => $this->getAuthor(),
            ] : null,
            'totalReplies' => $parent?->id ?
                $parent->children()->count() :
                 $this->children()->count(),
            'parent' => $parent ? [
                'id' => $parent->id,
                'author' => $this->getAuthor(),
            ] : null,
            'total' => $this->blog?->getCommentsCount(),
            //'hasInComplaints' => $this->hasInComplaints($this->id),
        ];
    }
}

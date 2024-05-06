<?php

namespace App\Entity\Comment;

use Carbon\Carbon;
use App\Entity\Blog\Blog;
use App\Entity\User\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * @property string $id
 * @property string $user_id
 * @property string $blog_id
 * @property string $comment
 * @property Carbon $deleted_at
 * @property Blog $blog
 * @property User $user
*/
class BlogComment extends Model
{
    use HasFactory;

    protected $table = 'blog_comments';
    protected $fillable = ['comment', 'blog_id', 'user_id'];

    // Пользователь, который оставил комментарий.
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    // Модель, для которой, был оставлен, комментарий.
    public function blog()
    {
        return $this->belongsTo(Blog::class, 'blog_id', 'id');
    }

    // Возвращает все комментарии, для которых этот комментарий является родителем.
    public function children()
    {
        return $this->hasMany(BlogComment::class, 'child_id');
    }

    // Возвращает комментарий, к которому принадлежит этот комментарий.
    public function parent()
    {
        return $this->belongsTo(BlogComment::class, 'child_id');
    }

    // Возвращает комментарий, на который ответили.
    public function reply()
    {
        return $this->belongsTo(BlogComment::class, 'reply_id');
    }

    public function isDeleted(): bool
    {
        return $this->deleted_at ? true : false;
    }

    public function getCustomMessage(): string
    {
        return $this->isDeleted() ? config('constants.comment_remove') : $this->comment;
    }

    public function getAuthor() {
        /** @var User $author */
        $author = $this->user()->withTrashed()->first();
        return [
            'id' => $author?->id,
            'status' => $author?->status?->status,
            'firstname' => $author?->isDeleted() ? 'DELETED' : $author?->firstname,
            'lastname' => $author?->isDeleted() ? '' : $author?->lastname,
            'isOnline' => $author?->isDeleted() ? '' : $author?->isOnline(),
            'lastOnlineAt' => $author?->isDeleted() ? '' : $author?->last_online_at,
            'avatarUrl' => $author?->isDeleted() ? null : $author?->getAvatarUrl()
        ];
    }

    public function getParentOrNull()
    {
        $parent = $this->parent()->first();
        return $parent;
    }

    public function getReplyOrNull()
    {
        $reply = $this->reply()->first();
        return $reply;
    }
}

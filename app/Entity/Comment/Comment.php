<?php
namespace App\Entity\Comment;

use App\Entity\Project\Project;
use App\Entity\User\User;
use Database\Factories\CommentFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property int id
 * @property int user_id
 * @property User user
 * @property int project_id
 * @property Project project
 * @property string comment
 * @property string status
 *
*/
class Comment extends Model
{
    use SoftDeletes, HasFactory;

    public const POST_COMMENT = 'postComment';
    public const REPLY_TO_COMMENT = 'replyToComment';
    public const DELETE_COMMENT = 'deleteComment';

    protected $table = 'project_comments';
    protected $with = ['user'];

    protected $fillable = ['comment', 'created_at', 'project_id', 'user_id'];
    protected $hidden = ['updated_at'];

    protected $casts = [
        'approved' => 'boolean'
    ];

    // Пользователь, который оставил комментарий.
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
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

    // Модель, которая была прокомментирована.
    public function project()
    {
        return $this->belongsTo(Project::class, 'project_id', 'id');
    }

    // Возвращает все комментарии, для которых этот комментарий является родителем.
    public function children()
    {
        return $this->hasMany(Comment::class, 'child_id');
    }

    // Возвращает комментарий, к которому принадлежит этот комментарий.
    public function parent()
    {
        return $this->belongsTo(Comment::class, 'child_id');
    }

    // Возвращает комментарий, на который ответили.
    public function reply()
    {
        return $this->belongsTo(Comment::class, 'reply_id');
    }

    public function commentsReports()
    {
        return $this->belongsToMany(User::class, 'comment_report', 'comment_id', 'user_id');
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

    public function isDeleted(): bool
    {
        return $this->deleted_at ? true : false;
    }

    public function getCustomMessage(): string
    {
        return $this->isDeleted() ? config('constants.comment_remove') : $this->comment;
    }

    public function hasInComplaints($commentId): bool
    {
        $user = findAuthUser();
        if (!$user) return false;
        return $user->hasInCommentComplaints($commentId);
    }

    protected static function newFactory()
    {
        return CommentFactory::new();
    }
}

<?php

namespace App\Entity\Blog;

use App\Entity\Comment\BlogComment;
use App\Entity\User\User;
use Carbon\Carbon;
use App\Entity\Project\Project;
use Database\Factories\BlogFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * @property int $project_id
 * @property int $user_id
 * @property Project $project
 * @property string $slug
 * @property string $title
 * @property string $description
 * @property User $user
 * @property string $status
 * @property string $id
 * @property Carbon $created_at
 *
*/
class Blog extends Model
{
    use HasFactory, HasUuids;

    public const STATUS_DRAFT = 'Draft';
    public const STATUS_MODERATION = 'Moderation';
    public const STATUS_REJECTED = 'Rejected';
    public const STATUS_ACTIVE = 'Active';
    public const STATUS_CLOSED = 'Closed';
    public const STATUS_DELETED = 'Deleted';

    protected $table = 'blogs';
    protected $fillable = ['title', 'description', 'user_id', 'project_id', 'status', 'slug', 'id', 'created_at'];
    protected $hidden = [];

    public function project()
    {
        return $this->belongsTo(Project::class, 'project_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function subjects()
    {
        return $this->belongsToMany(Subject::class, 'blog_to_subjects_ref', 'blog_id', 'blog_subject_id');
    }

    public function hasInSubjects($subject_id): bool
    {
        return $this->subjects()->where('id', 'like', $subject_id)->exists();
    }

    public function favoritesUsers()
    {
        return $this->belongsToMany(User::class, 'blog_favorites_ref', 'blog_id', 'user_id');
    }

    // комментарии
    public function comments()
    {
        return $this->hasMany(BlogComment::class, 'blog_id', 'id');
    }

    public function getCommentsCount()
    {
        return $this->comments()->count();
    }

    public function getFavoritesCount()
    {
        return $this->favoritesUsers()->count();
    }

    public function hasInFavorites($blogId)
    {
        $user = findAuthUser();
        return $this->favoritesUsers()
            ->wherePivot('user_id', 'like', $user?->id ?? "")
            ->wherePivot('blog_id', 'like', $blogId ?? "")
            ->exists();
    }

    protected static function newFactory()
    {
        return BlogFactory::new();
    }
}

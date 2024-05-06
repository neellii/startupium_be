<?php

namespace App\Entity\Blog;

use Database\Factories\BlogSubjectFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property string $title
 * @property string $status
 * @property string $id
 *
*/
class Subject extends Model
{
    use HasFactory;

    public const STATUS_DRAFT = 'Draft';
    public const STATUS_MODERATION = 'Moderation';
    public const STATUS_REJECTED = 'Rejected';
    public const STATUS_ACTIVE = 'Active';
    public const STATUS_CLOSED = 'Closed';
    public const STATUS_DELETED = 'Deleted';

    protected $table = 'blog_subjects';
    protected $fillable = ['title', 'status'];
    protected $hidden = [];

    public function blogs()
    {
        return $this->belongsToMany(Blog::class, 'blog_to_subjects_ref', 'blog_id', 'blog_subject_id');
    }

    protected static function newFactory()
    {
        return BlogSubjectFactory::new();
    }
}

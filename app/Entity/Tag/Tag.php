<?php
namespace App\Entity\Tag;

use App\Entity\Project\Project;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $tag
 * @property Carbon $created_at
 * @property Project[] projects
 */
class Tag extends Model
{
    protected $guarded = [];
    protected $hidden = [
        'created_at', 'updated_at'
    ];

    public function tags()
    {
        return $this->belongsToMany(Project::class, 'tags_ref', 'tag_id', 'project_id');
    }
}

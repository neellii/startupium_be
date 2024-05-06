<?php

namespace App\Entity\Wiki;

use App\Entity\Project\Project;
use Database\Factories\WikiSectionFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property char id
 * @property int project_id
 * @property Project project
 * @property string title
 *
*/
class WikiSection extends Model
{
    use HasFactory, HasUuids;
    protected $table = 'wiki_sections';
    protected $fillable = ['title', 'parent_id', 'nesting', 'type'];
    protected $hidden = ['updated_at', 'project_id', 'nesting'];
    protected $casts = [
        'nesting' => 'integer'
    ];

    public function project()
    {
        return $this->belongsTo(Project::class, 'project_id', 'id');
    }

    public function wikiArticles()
    {
        return $this->hasMany(WikiArticle::class, 'section_id', 'id');
    }

    protected static function newFactory()
    {
        return WikiSectionFactory::new();
    }
}

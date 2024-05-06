<?php

namespace App\Entity\Wiki;

use App\Entity\Project\Project;
use Database\Factories\WikiArticleFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

/**
 * @property char $id
 * @property int $project_id
 * @property Project $project
 * @property string $title
 * @property string $text
 * @property WikiSection $wikiSection
 *
*/
class WikiArticle extends Model
{
    use HasFactory, HasUuids;
    protected $table = 'wiki_articles';
    protected $fillable = ['title', 'text', 'section_id', 'number_of_copies'];
    protected $hidden = ['updated_at', 'project_id', 'number_of_copies'];

    protected $casts = [
        'number_of_copies' => 'integer',
    ];

    public function project()
    {
        return $this->belongsTo(Project::class, 'project_id', 'id');
    }

    public function wikiSection()
    {
        return $this->belongsTo(WikiSection::class, 'section_id', 'id');
    }

    public function defaultArticle()
    {
        return $this->belongsToMany(Project::class, 'default_article_ref', 'project_id', 'article_id')
            ->withPivot('article_id');
    }

    public function hasDefaultArticle() {
        return Project::query()->whereHas('defaultArticle', function (Builder $query) {
            $query->where('article_id', $this->id);
        })->exists();
    }

    protected static function newFactory()
    {
        return WikiArticleFactory::new();
    }
}

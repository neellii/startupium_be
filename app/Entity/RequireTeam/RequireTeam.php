<?php

namespace App\Entity\RequireTeam;

use App\Casts\TagTitleCast;
use Carbon\Carbon;
use App\Entity\Project\Project;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * @property int $id
 * @property string $title
 * @property Carbon $created_at
 * @property Project[] $projects
 * @property string $title
 */
class RequireTeam extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $hidden = [
        'created_at', 'updated_at'
    ];
    protected $fillable = ['title', 'status'];
    protected $casts = [
        'title' => TagTitleCast::class
    ];

    public function requireTeams()
    {
        return $this->belongsToMany(Project::class, 'require_teams_ref', 'require_team_id', 'project_id')
            ->withPivot('is_hidden');
    }
}

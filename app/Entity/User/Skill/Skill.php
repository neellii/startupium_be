<?php
namespace App\Entity\User\Skill;

use App\Entity\User\User;
use Database\Factories\UserSkillsFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

// навыки пользователя
/**
 * @property int id
 * @property string title
 * @property string status
 * @property User[] users
 */
class Skill extends Model
{
    use HasFactory;
    public const STATUS_ACTIVE = 'Active';
    public const STATUS_MODERATE = 'Moderate';

    protected $guarded = [];

    public function skills()
    {
        return $this->belongsToMany(User::class, 'user_skills_ref', 'skill_id', 'user_id');
    }

    protected static function newFactory()
    {
        return UserSkillsFactory::new();
    }
}

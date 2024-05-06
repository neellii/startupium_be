<?php
namespace App\Entity\Traits\User;

use App\Entity\User\Skill\Skill;

trait SkillTrait
{
    public function skills()
    {
        return $this->belongsToMany(Skill::class, 'user_skills_ref');
    }

    public function hasInSkills($skill_id): bool
    {
        return $this->skills()->where('id', $skill_id)->exists();
    }

    public function addToSkills($skill_id): void
    {
        if ($this->hasInSkills($skill_id)) {
            return;
        }
        $this->skills()->attach($skill_id);
    }

    public function removeFromSkills($skill_id): void
    {
        if (!$this->hasInSkills($skill_id)) {
            return;
        }
        $this->skills()->detach($skill_id);
    }
}

<?php
namespace App\Entity\Traits\User;

use App\Entity\User\Technology\Technology;

trait TechnologyTrait
{
    public function technologies()
    {
        return $this->belongsToMany(Technology::class, 'user_technology_ref');
    }

    public function hasInTechnology($technology_id): bool
    {
        return $this->technologies()->where('id', $technology_id)->exists();
    }

    public function addToTechnologies($technology_id): void
    {
        if ($this->hasInTechnology($technology_id)) {
            return;
        }
        $this->technologies()->attach($technology_id);
    }

    public function removeFromTechnologies($technology_id): void
    {
        if (!$this->hasInTechnology($technology_id)) {
            return;
        }
        $this->technologies()->detach($technology_id);
    }
}

<?php
namespace App\Entity\Traits\User;

use App\Entity\User\Quality\Quality;

trait QualityTrait
{
    public function qualities()
    {
        return $this->belongsToMany(Quality::class, 'qualities_ref');
    }

    public function hasInQualities($quality_id): bool
    {
        return $this->qualities()->where('id', $quality_id)->exists();
    }

    public function addToQualities($quality_id): void
    {
        if ($this->hasInQualities($quality_id)) {
            return;
        }
        $this->qualities()->attach($quality_id);
    }

    public function removeFromSQualities($quality_id): void
    {
        if (!$this->hasInQualities($quality_id)) {
            return;
        }
        $this->qualities()->detach($quality_id);
    }
}

<?php
namespace App\Entity\Traits\Project;

use App\Entity\Project\Project;

trait SubscriberTrait
{
    public function projectSubscribers()
    {
        return $this->belongsToMany(Project::class, 'project_subscribers_refs', 'subscriber_id', 'project_id')
            ->withPivot('require_team_id')
            ->withPivot('subscribed_at')
            ->withPivot('role_id');
    }
}

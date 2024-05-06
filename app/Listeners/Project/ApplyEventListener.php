<?php

namespace App\Listeners\Project;

use App\Events\User\ApplyEvent;
use App\Notifications\Project\ApplyNotification;

class ApplyEventListener
{
    public function handle(ApplyEvent $event): void
    {
        $event->project->user->notify(new ApplyNotification($event->project, $event->subscriber));
    }
}

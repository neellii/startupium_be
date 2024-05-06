<?php

namespace App\Events\User;

use App\Entity\Project\Project;
use App\Entity\User\User;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ApplyEvent
{
    use Dispatchable, SerializesModels;

    public $project;
    public $subscriber;
    public function __construct(Project $project, User $subscriber)
    {
        $this->project = $project;
        $this->subscriber = $subscriber;
    }
}

<?php
namespace App\Events\Project;

use App\Entity\Project\Project;
use App\Entity\User\User;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ProjectEvent
{
    use Dispatchable, SerializesModels;

    public $project;  // проект к которому применяется событие (like, unlike и т.д.)
    public $authorEvent; // автор события
    public $type; // тип события

    public function __construct(Project $project, User $authorEvent, string $type)
    {
        $this->project = $project;
        $this->authorEvent = $authorEvent;
        $this->type = $type;
    }
}

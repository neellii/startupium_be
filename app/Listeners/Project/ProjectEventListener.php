<?php
namespace App\Listeners\Project;

use App\Entity\Project\Project;
use App\Events\Project\ProjectEvent;
use App\Notifications\Project\AddToBookmark;
use App\Notifications\Project\AddToFavorite;
use App\Notifications\Project\AddToReport;
use App\Notifications\Project\RemoveFromBookmark;
use App\Notifications\Project\RemoveFromFavorite;
use App\Notifications\Project\RemoveFromReport;

class ProjectEventListener
{
    public function handle(ProjectEvent $event)
    {
        switch ($event->type) {
            case Project::PROJECT_TO_FAVORITES:
                $event->project->user->notify(new AddToFavorite($event->project, $event->authorEvent));
                break;
            case Project::PROJECT_FROM_FAVORITES:
                $event->project->user->notify(new RemoveFromFavorite($event->project, $event->authorEvent));
                break;
            case Project::PROJECT_TO_BOOKMARKS:
                $event->project->user->notify(new AddToBookmark($event->project, $event->authorEvent));
                break;
            case Project::PROJECT_FROM_BOOKMARKS:
                $event->project->user->notify(new RemoveFromBookmark($event->project, $event->authorEvent));
                break;
            case Project::ADD_TO_REPORTS:
                $event->project->user->notify(new AddToReport($event->project, $event->authorEvent));
                break;
            case Project::REMOVE_FROM_REPORTS:
                $event->project->user->notify(new RemoveFromReport($event->project, $event->authorEvent));
                break;
            default:
        }
    }
}

<?php
namespace App\Console\Commands\Project;

use App\Entity\Project\Project;
use App\Events\Project\ProjectEvent;
use Illuminate\Console\Command;

class RejectProject extends Command
{
    protected $signature = 'project:reject
                                        {id : the ID of the project}
                                        {reason* : the reason for rejection}';

    protected $description = 'Reject project';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $id = $this->argument('id');
        $reason = $this->argument('reason');

        $project = Project::query()->where('id', $id)->first();
        if (!$project) {
            $this->info('Проект с id=' . $id . ' не найден.');
            return;
        }
        if (($project->status === Project::STATUS_ACTIVE || $project->status === Project::STATUS_MODERATION)) {
            $project->reject(implode(' ', $reason));

            /** @var Project $project */
            event(new ProjectEvent($project, $project->user, Project::STATUS_REJECTED));

            $this->info('Проект с id=' . $id . ' отклонен.');
        } else {
            $this->info('Проект с id=' . $id . ' находится в статусе ' . $project->status);
        }
    }
}

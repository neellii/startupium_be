<?php
namespace App\Console\Commands\Project;

use App\Entity\Project\Project;
use App\Entity\User\Status\Status;
use App\Entity\User\User;
use App\Notifications\Project\PublicToAll;
use App\Notifications\Project\PublicToMe;
use Illuminate\Console\Command;

class ProjectActive extends Command
{
    protected $signature = 'project:active {id* : projects id}';

    protected $description = 'Make project or projects active';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $users = $this->getUsers();
        $ids = $this->argument('id');
        foreach ($ids as $id) {
            $project = Project::query()->where('id', $id)->first();
            if (!$project) {
                $this->info('Проект с id=' . $id . ' не найден.');
                return;
            }
            if ($project->status === Project::STATUS_MODERATION) {
                $project->moderate();
                $this->info('Проект с id=' . $id . ' активен.');
                $this->sendProjectPublishedNotification($users, $project);
            } else {
                $this->info('Проект с id=' . $id . ' не находится в статусе ' . Project::STATUS_MODERATION);
            }
        }
    }

    private function getUsers()
    {
        return User::query()
            ->join('user_status', function ($join) {
                $join->on('users.id', '=', 'user_status.user_id')
                    ->where('user_status.status', '=', Status::STATUS_ACTIVE);
            })
            ->join('notification_settings', 'users.id', '=', 'notification_settings.user_id')
            ->select('notification_settings.showPublicProjects', 'users.id')
            ->get();
    }

    private function sendProjectPublishedNotification($users, $project)
    {
        foreach ($users as $user) {
            if ($user->showPublicProjects) {
                if ($user->id !== $project->user->id) {
                    $user->notify(new PublicToAll($project));
                } // отправка всем активным пользователям
                else {
                    $user->notify(new PublicToMe($project));
                }  // отправка автору проекта
            }
        }
    }
}

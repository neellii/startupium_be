<?php
namespace App\Console\Commands\Project;

use App\Entity\Project\Project;
use App\Entity\User\Status\Status;
use App\Entity\User\User;
use Illuminate\Console\Command;

class PopularProjects extends Command
{
    protected $signature = 'projects:popular';

    protected $description = 'Send notification to email about popular projects';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $users = $this->getUsers();
        $projects = $this->getPopularProjects();
        foreach ($users as $user) {
            if ($user->popularProjects) {
                $user->notify(new \App\Notifications\Projects\PopularProjects($projects->toArray()['data']));
            }
        }
    }

    private function getPopularProjects()
    {
        $projects = Project::query()
            ->where('status', 'like', 'Active')
            ->orWhere('status', 'like', 'Moderation')
            ->leftJoin('project_comments', 'projects.id', '=', 'project_comments.project_id')
            ->groupBy('projects.id')
            ->orderBy('comments_count', 'desc')
            ->selectRaw('projects.*, count(project_comments.id) as comments_count')
            ->paginate(5);
        //dd($projects);
        return $projects;
    }

    private function getUsers()
    {
        return User::query()
            ->join('user_status', function ($join) {
                $join->on('users.id', '=', 'user_status.user_id')
                    ->where('user_status.status', '=', Status::STATUS_ACTIVE);
            })
            ->join('send_by_email_settings', 'users.id', '=', 'send_by_email_settings.user_id')
            ->select('users.id', 'send_by_email_settings.popularProjects')
            ->get();
    }
}

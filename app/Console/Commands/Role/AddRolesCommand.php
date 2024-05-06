<?php

namespace App\Console\Commands\Role;

use App\Entity\Project\Project;
use App\Entity\User\Role\Role;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;

class AddRolesCommand extends Command
{
    protected $signature = 'roles:add';

    protected $description = 'Add roles to database';

    public function handle()
    {
        $roles = Role::rolesList();
        foreach($roles as $role) {
            Role::query()->where('title', 'like', $role['title'])->firstOrCreate($role);
        }

        $projects = Project::query()->get();
        foreach($projects as $project) {
            if (!$project->hasInSubscribers($project?->user?->id)) {
                $project->projectSubscribers()->attach($project->user, [
                    'role_id' => findOrCreateRole(Role::PROJECT_FOUNDER)->id,
                    'subscribed_at' => Carbon::now()
                ]);
            }
        }
    }
}

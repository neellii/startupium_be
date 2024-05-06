<?php

namespace App\Console\Commands\Permission;

use App\Entity\User\Permission\Permission;
use App\Entity\User\Role\Role;
use Illuminate\Console\Command;

class AddPermissionsCommand extends Command
{
    protected $signature = 'permissions:add';

    protected $description = 'Add permissions to database';

    public function handle()
    {
        $permissions = Permission::managementPermissionList();
        foreach($permissions as $permission) {
            Permission::query()->where('title', 'like', $permission['title'])->firstOrCreate($permission);
        }

        // permissions for project_founder
        $role = Role::query()->where('title', Role::PROJECT_FOUNDER)->first();
        $permissions = Permission::query()->get();
        foreach ($permissions as $permission) {
            if (!$role->hasInPermissions($permission->id)) {
                $role->permissions()->attach($permission);
            }
        }

        // permissions for project_admin
        $role = Role::query()->where('title', Role::PROJECT_ADMIN)->first();
        $list = [];
        foreach (Permission::managementPermissionListProjectAdmin() as $permission) {
            $list[] = $permission['title'];
        }
        $permissions = Permission::query()->whereIn('title', $list)->get();
        foreach ($permissions as $permission) {
            if (!$role->hasInPermissions($permission->id)) {
                $role->permissions()->attach($permission);
            }
        }

        // permissions for project_participant
        $role = Role::query()->where('title', Role::PROJECT_PARTICIPANT)->first();
        $list = [];
        foreach (Permission::managementPermissionListProjectParticipant() as $permission) {
            $list[] = $permission['title'];
        }
        $permissions = Permission::query()->whereIn('title', $list)->get();
        foreach ($permissions as $permission) {
            if (!$role->hasInPermissions($permission->id)) {
                $role->permissions()->attach($permission);
            }
        }

        // permissions for project_guest
        $role = Role::query()->where('title', Role::PROJECT_GUEST)->first();
        $list = [];
        foreach (Permission::managementPermissionListProjectGuest() as $permission) {
            $list[] = $permission['title'];
        }
        $permissions = Permission::query()->whereIn('title', $list)->get();
        foreach ($permissions as $permission) {
            if (!$role->hasInPermissions($permission->id)) {
                $role->permissions()->attach($permission);
            }
        }
    }
}

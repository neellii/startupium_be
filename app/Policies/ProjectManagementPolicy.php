<?php

namespace App\Policies;

use App\Entity\User\Permission\Permission;
use App\Entity\User\Role\Role;
use App\Entity\User\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Gate;

class ProjectManagementPolicy
{
    // register in AuthServiceProvider
    public static function registerPermissions() {
        Gate::define('create-article', [ProjectManagementPolicy::class, 'createArticle']);
        Gate::define('delete-article', [ProjectManagementPolicy::class, 'deleteArticle']);
        Gate::define('edit-article', [ProjectManagementPolicy::class, 'editArticle']);

        Gate::define('create-section', [ProjectManagementPolicy::class, 'createSection']);
        Gate::define('delete-section', [ProjectManagementPolicy::class, 'deleteSection']);
        Gate::define('edit-section', [ProjectManagementPolicy::class, 'editSection']);

        Gate::define('leave-project', [ProjectManagementPolicy::class, 'leaveProject']);

        Gate::define('create_message', [ProjectManagementPolicy::class, 'createMessage']);
    }

    // wiki
    public function createArticle(User $user, $role_id) {
        return $this->checkPermission(Permission::PROJECT_MANAGEMENT_CREATE_ARTICLE, $role_id);
    }
    public function deleteArticle(User $user, $role_id) {
        return $this->checkPermission(Permission::PROJECT_MANAGEMENT_DELETE_ARTICLE, $role_id);
    }
    public function editArticle(User $user, $role_id) {
        return $this->checkPermission(Permission::PROJECT_MANAGEMENT_EDIT_ARTICLE, $role_id);
    }
    public function createSection(User $user, $role_id) {
        return $this->checkPermission(Permission::PROJECT_MANAGEMENT_CREATE_SECTION, $role_id);
    }
    public function deleteSection(User $user, $role_id) {
        return $this->checkPermission(Permission::PROJECT_MANAGEMENT_DELETE_SECTION, $role_id);
    }
    public function editSection(User $user, $role_id) {
        return $this->checkPermission(Permission::PROJECT_MANAGEMENT_EDIT_SECTION, $role_id);
    }

    // team
    public function leaveProject(User $user, $role_id) {
        return $this->checkPermission(Permission::PROJECT_MANAGEMENT_LEAVE_PROJECT, $role_id);
    }

    // communication
    public function createMessage(User $user, $role_id) {
        return $this->checkPermission(Permission::PROJECT_MANAGEMENT_CREATE_MESSAGE, $role_id);
    }

    private function checkPermission($permission, $role_id): bool {
        $permission = Permission::query()->where('title', 'like', $permission)->first();
        return Role::query()->whereHas('permissions', function (Builder $query) use ($permission, $role_id) {
            $query->where('role_id', 'like', $role_id);
            $query->where('permission_id', 'like', $permission?->id);
        })->exists();
    }
}

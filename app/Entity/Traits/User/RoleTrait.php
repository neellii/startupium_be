<?php
namespace App\Entity\Traits\User;

use App\Entity\User\Role\Role;

trait RoleTrait {

    public function roles()
    {
        return $this->belongsToMany(Role::class, 'user_to_role_ref');
    }

    public function hasInRoles($role_id): bool
    {
        return $this->roles()->where('id', $role_id)->exists();
    }
}

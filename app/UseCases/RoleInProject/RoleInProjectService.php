<?php
namespace App\UseCases\RoleInProject;

use Illuminate\Http\Request;

class RoleInProjectService
{
    public function createOrUpdate(Request $request) {
        $roles = $request['rolesInProject'];
        $user = authUser();
        $role = $user->rolesInProject()->first();
        if ($role) {
            $user->rolesInProject()->update([
                'mentor' => $roles['mentor'],
                'seeker' => $roles['seeker'],
                'founder' => $roles['founder'],
                'investor' => $roles['investor'],
                'trainee' => $roles['trainee'],
            ]);
        } else {
            $user->rolesInProject()->create([
                'mentor' => $roles['mentor'] ?? false,
                'seeker' => $roles['seeker'] ?? false,
                'founder' => $roles['founder'] ?? false,
                'investor' => $roles['investor'] ?? false,
                'trainee' => $roles['trainee'] ?? false,
            ]);
        }
    }
}

<?php

namespace App\Entity\User\Role;

use App\Entity\User\User;
use Illuminate\Database\Eloquent\Model;
use App\Entity\User\Permission\Permission;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Role extends Model
{
    use HasFactory;
    protected $table = 'roles';
    protected $fillable = ['title', 'description'];
    protected $hidden = [
        'created_at', 'updated_at'
    ];

    // main roles
    public const USER = [
        'title' => 'User',
        'description' => 'Пользователь'
    ];
    public const MODERATOR = [
        'title' => 'Moderator',
        'description' => 'Модератор'
    ];
    public const ADMIN = [
        'title' => 'Admin',
        'description' => 'Администратор',
    ];

    // project roles
    public const PROJECT_FOUNDER = [
        'title' => 'Project Founder',
        'description' => 'Основатель'
    ];
    public const PROJECT_ADMIN = [
        'title' => 'Project Admin',
        'description' => 'Администратор'
    ];
    public const PROJECT_GUEST = [
        'title' => 'Project Guest',
        'description' => 'Гость'
    ];
    public const PROJECT_PARTICIPANT = [
        'title' => 'Project Participant',
        'description' => 'Участник'
    ];

    public function users()
    {
        return $this->belongsToMany(User::class, 'user_to_role_ref', 'role_id', 'user_id');
    }

    public function permissions()
    {
        return $this->belongsToMany(Permission::class, 'role_to_permission_ref');
    }
    public function hasInPermissions($permission_id): bool
    {
        return $this->permissions()->where('id', $permission_id)->exists();
    }

    public static function rolesList(): array
    {
        return [
            self::USER,
            self::ADMIN,
            self::MODERATOR,
            self::PROJECT_GUEST,
            self::PROJECT_ADMIN,
            self::PROJECT_FOUNDER,
            self::PROJECT_PARTICIPANT,
        ];
    }

    public static function rolesInTeamList(): array
    {
        return [
            self::PROJECT_GUEST,
            self::PROJECT_ADMIN,
            self::PROJECT_PARTICIPANT,
        ];
    }


}

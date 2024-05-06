<?php

namespace App\Entity\User\Permission;

use App\Entity\User\Role\Role;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    use HasFactory;
    protected $table = 'permissions';
    protected $hidden = [
        'created_at', 'updated_at'
    ];
    protected $fillable = ['title', 'description'];

    // Management permissions

    // Team
    public const PROJECT_MANAGEMENT_EDIT_MEMBER = [
        'title' => 'Project Management Edit Member',
        'description' => 'Редактирование участника.'
    ];
    public const PROJECT_MANAGEMENT_DELETE_MEMBER = [
        'title' => 'Project Management Delete Member',
        'description' => 'Удаление участника.'
    ];
    public const PROJECT_MANAGEMENT_LEAVE_PROJECT = [
        'title' => 'Project Management Leave Project',
        'description' => 'Покинуть проект.'
    ];
    public const PROJECT_MANAGEMENT_CREATE_POSITION = [
        'title' => 'Project Management Create Position',
        'description' => 'Создать специальность.'
    ];
    public const PROJECT_MANAGEMENT_EDIT_POSITION = [
        'title' => 'Project Management Edit Position',
        'description' => 'Редактировать специальность.'
    ];
    public const PROJECT_MANAGEMENT_DELETE_POSITION = [
        'title' => 'Project Management Delete Position',
        'description' => 'Удалить специальность.'
    ];
    public const PROJECT_MANAGEMENT_APPLY_APPLICATION = [
        'title' => 'Project Management Apply Application',
        'description' => 'Принять заявку.'
    ];
    public const PROJECT_MANAGEMENT_DELETE_APPLICATION = [
        'title' => 'Project Management Delete Application',
        'description' => 'Отклонить заявку.'
    ];

    // Wiki
    public const PROJECT_MANAGEMENT_CREATE_ARTICLE = [
        'title' => 'Project Management Create Article',
        'description' => 'Создать статью.'
    ];
    public const PROJECT_MANAGEMENT_DELETE_ARTICLE = [
        'title' => 'Project Management Delete Article',
        'description' => 'Удалить статью.'
    ];
    public const PROJECT_MANAGEMENT_EDIT_ARTICLE = [
        'title' => 'Project Management Edit Article',
        'description' => 'Редактировать статью.'
    ];
    public const PROJECT_MANAGEMENT_COPY_ARTICLE = [
        'title' => 'Project Management Copy Article',
        'description' => 'Копировать статью.'
    ];
    public const PROJECT_MANAGEMENT_MOVE_ARTICLE = [
        'title' => 'Project Management Move Article',
        'description' => 'Переместить статью.'
    ];
    public const PROJECT_MANAGEMENT_CREATE_SECTION = [
        'title' => 'Project Management Create Section',
        'description' => 'Создать раздел.'
    ];
    public const PROJECT_MANAGEMENT_EDIT_SECTION = [
        'title' => 'Project Management Edit Section',
        'description' => 'Редактировать раздел.'
    ];
    public const PROJECT_MANAGEMENT_DELETE_SECTION = [
        'title' => 'Project Management Delete Section',
        'description' => 'Удалить раздел.'
    ];

    // Communication
    public const PROJECT_MANAGEMENT_CREATE_MESSAGE = [
        'title' => 'Project Management Create Message',
        'description' => 'Создать сообщение.'
    ];
    public const PROJECT_MANAGEMENT_EDIT_MESSAGE = [
        'title' => 'Project Management Edit Message',
        'description' => 'Редактировать любое сообщение.'
    ];
    public const PROJECT_MANAGEMENT_DELETE_MESSAGE = [
        'title' => 'Project Management Delete Message',
        'description' => 'Удалить любое сообщение.'
    ];
    public const PROJECT_MANAGEMENT_EDIT_OWN_MESSAGE = [
        'title' => 'Project Management Edit Own Message',
        'description' => 'Редактировать собственное сообщение.'
    ];
    public const PROJECT_MANAGEMENT_DELETE_OWN_MESSAGE = [
        'title' => 'Project Management Delete Own Message',
        'description' => 'Удалить собственное сообщение.'
    ];

    public function roles()
    {
        return $this->belongsToMany(Role::class, 'role_to_permission_ref');
    }

    public static function managementPermissionList(): array
    {
        return [
            self::PROJECT_MANAGEMENT_APPLY_APPLICATION,
            self::PROJECT_MANAGEMENT_CREATE_POSITION,
            self::PROJECT_MANAGEMENT_DELETE_APPLICATION,
            self::PROJECT_MANAGEMENT_DELETE_MEMBER,
            self::PROJECT_MANAGEMENT_EDIT_MEMBER,
            self::PROJECT_MANAGEMENT_LEAVE_PROJECT,
            self::PROJECT_MANAGEMENT_DELETE_POSITION,
            self::PROJECT_MANAGEMENT_EDIT_POSITION,
            self::PROJECT_MANAGEMENT_CREATE_ARTICLE,
            self::PROJECT_MANAGEMENT_DELETE_ARTICLE,
            self::PROJECT_MANAGEMENT_EDIT_ARTICLE,
            self::PROJECT_MANAGEMENT_COPY_ARTICLE,
            self::PROJECT_MANAGEMENT_CREATE_SECTION,
            self::PROJECT_MANAGEMENT_EDIT_SECTION,
            self::PROJECT_MANAGEMENT_DELETE_SECTION,
            self::PROJECT_MANAGEMENT_MOVE_ARTICLE,
            self::PROJECT_MANAGEMENT_DELETE_OWN_MESSAGE,
            self::PROJECT_MANAGEMENT_EDIT_OWN_MESSAGE,
            self::PROJECT_MANAGEMENT_EDIT_MESSAGE,
            self::PROJECT_MANAGEMENT_DELETE_MESSAGE,
            self::PROJECT_MANAGEMENT_CREATE_MESSAGE,
        ];
    }

    public static function managementPermissionListProjectAdmin(): array
    {
        return [
            self::PROJECT_MANAGEMENT_CREATE_ARTICLE,
            self::PROJECT_MANAGEMENT_DELETE_ARTICLE,
            self::PROJECT_MANAGEMENT_EDIT_ARTICLE,
            self::PROJECT_MANAGEMENT_COPY_ARTICLE,
            self::PROJECT_MANAGEMENT_CREATE_SECTION,
            self::PROJECT_MANAGEMENT_EDIT_SECTION,
            self::PROJECT_MANAGEMENT_DELETE_SECTION,
            self::PROJECT_MANAGEMENT_MOVE_ARTICLE,
            self::PROJECT_MANAGEMENT_CREATE_MESSAGE,
            self::PROJECT_MANAGEMENT_DELETE_OWN_MESSAGE,
            self::PROJECT_MANAGEMENT_EDIT_OWN_MESSAGE,
            self::PROJECT_MANAGEMENT_EDIT_MESSAGE,
            self::PROJECT_MANAGEMENT_DELETE_MESSAGE
        ];
    }

    public static function managementPermissionListProjectParticipant(): array
    {
        return [
            self::PROJECT_MANAGEMENT_CREATE_ARTICLE,
            self::PROJECT_MANAGEMENT_EDIT_ARTICLE,
            self::PROJECT_MANAGEMENT_COPY_ARTICLE,
            self::PROJECT_MANAGEMENT_CREATE_SECTION,
            self::PROJECT_MANAGEMENT_EDIT_SECTION,
            self::PROJECT_MANAGEMENT_CREATE_MESSAGE,
            self::PROJECT_MANAGEMENT_DELETE_OWN_MESSAGE,
            self::PROJECT_MANAGEMENT_EDIT_OWN_MESSAGE
        ];
    }

    public static function managementPermissionListProjectGuest(): array
    {
        return [
            self::PROJECT_MANAGEMENT_CREATE_MESSAGE,
            self::PROJECT_MANAGEMENT_DELETE_OWN_MESSAGE,
            self::PROJECT_MANAGEMENT_EDIT_OWN_MESSAGE
        ];
    }
}

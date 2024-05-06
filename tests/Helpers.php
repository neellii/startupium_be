<?php
namespace Tests;

use App\Entity\Project\Project;
use App\Entity\Settings\NotificationSettings;
use App\Entity\Settings\PrivateSettings;
use App\Entity\Settings\SendByEmailSettings;
use App\Entity\User\Avatar\Avatar;
use App\Entity\User\Status\Status;
use App\Entity\User\User;

trait UseHelpers {

    // Создание авторизованного пользователя для логина
    function findOrCreateUser($data): User {
        $user = User::query()->where('id', 'like', $data['id'])->first();
        if (!$user) {
            $user = $this->createUser($data);
        }
        return $user;
    }

    function login(User $user): string {
        $user->tokens()->where("user_id", $user->id)->delete();
        $accessToken = createAccessToken($user);
        $refreshToken = createRefreshToken($user);
        return $accessToken['token'];
    }

    function createProject(User $user): Project {
        $project = $user->projects()
            ->where('user_id', $user->id)
            ->whereIn('status', [Project::STATUS_MODERATION, Project::STATUS_ACTIVE])
            ->first();
        if (!$project) {
            $project = Project::query()->create([
                'title' => "Hello Project" . $user->id,
                'description' => 'Very cool project' . $user->id,
                'text' => 'bla bla' . $user->id,
                'user_id' => $user->id,
                'slug' => 'hello-project-' . $user->id,
                'status' => Project::STATUS_MODERATION
            ]);
        }
        return $project;
    }

    function createDraftProject(User $user): Project {
        $project = $user->projects()
            ->where('user_id', $user->id)
            ->whereIn('status', [Project::STATUS_DRAFT])
            ->first();
        if (!$project) {
            $project = Project::query()->create([
                'title' => "Hello Project" . $user->id,
                'description' => 'Very cool project' . $user->id,
                'text' => 'bla bla' . $user->id,
                'user_id' => $user->id,
                'slug' => 'draft-hello-project-' . $user->id,
                'status' => Project::STATUS_DRAFT
            ]);
        }
        return $project;
    }

    private function createUser($data) {
        $user = User::query()->create($data);
        // создаем аватар
        $avatar = Avatar::query()->make([
            'url' => config('constants.free_icon')
        ]);
        $avatar->user()->associate($user);
        $avatar->save();

        // присваиваем статус в ожидании
        $status = Status::query()->make([
            'status' => Status::STATUS_ACTIVE
        ]);
        $status->user()->associate($user);
        $status->save();

        // инициализируем настройки
        $notSettings = new NotificationSettings();
        $notSettings->user()->associate($user);
        $notSettings->save();
        $user->notificationSettings()->attach($notSettings->id);

        $privateSettings = new PrivateSettings();
        $privateSettings->user()->associate($user);
        $privateSettings->save();
        $user->privateSettings()->attach($privateSettings->id);

        $sendByEmailSettings = new SendByEmailSettings();
        $sendByEmailSettings->user()->associate($user);
        $sendByEmailSettings->save();
        $user->sendByEmailSettings()->attach($sendByEmailSettings->id);

        $user->rolesInProject()->create();

        return $user;
    }
}

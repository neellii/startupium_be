<?php

namespace App\Http\Controllers\Api\Auth;

use Carbon\Carbon;
use App\Entity\User\User;
use Illuminate\Support\Facades\DB;
use App\Entity\User\Avatar\Avatar;
use App\Entity\User\Status\Status;
use App\Http\Controllers\Controller;
use App\Entity\Settings\PrivateSettings;
use App\Entity\Settings\NotificationSettings;
use App\Entity\Settings\SendByEmailSettings;
use App\Helpers\Response\Response;
use App\Http\Requests\Auth\YandexSignInRequest;
use App\Http\Resources\Login\LoginSuccessResource;
use Illuminate\Http\Request;

class YandexLoginController extends Controller
{
    // переходим после успешной авторизации через yandex
    public function handleProviderCallback(YandexSignInRequest $request)
    {
        $email = $request['email'];
        $psuid = $request['psuID'];
        $user = null;
        $token = null;
        $refreshToken = null;
        //если такого пользователя у нас нет, то создаем
        DB::transaction(function () use ($email, &$user, &$token, &$refreshToken, $request, $psuid) {
            if (!($user = findUserByEmail($email ?? ""))) {
                $user = $this->createUser($request);
            }
            if (!$user->hasVerifiedEmail()) {
                $user->setEmailVerified();
            }
            $user->tokens()->where("user_id", $user->id)->delete();
            $token = createAccessToken($user);
            $refreshToken = createRefreshToken($user);
        });
        return Response::HTTP_OK(new LoginSuccessResource($user, $token, $refreshToken));
    }

    private function createUser(Request $request): User
    {
        $email = $request['email'];
        $psuid = $request['psuID'];
        $firstname = $request['firstname'];
        $lastname = $request['lastname'];
        $avatarUrl = $request['avatarUrl'];

        $user = User::create([
            'firstname' => $firstname ?? 'Пользователь',
            'lastname' => $lastname ?? "",
            'email' => $email,
            'psuid' => $psuid,
            'email_verified_at' => Carbon::now()
        ]);

        // создаем аватар
        $avatar = Avatar::query()->make([
            'url' => $avatarUrl ?? config('constants.free_icon')
        ]);
        $avatar->user()->associate($user);
        $avatar->save();

        // назначаем статус - активен
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

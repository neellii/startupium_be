<?php
namespace App\UseCases\Password;

use App\Entity\User\User;
use App\Mail\ForgotPasswordMail;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class PasswordService
{
    // забыл пароль
    public function forgotPassword(Request $request): void
    {
        $email = $request['email'];
        $user = findUserByEmail($email);
        // если пользователь не найден с такем email
        if (!$user) {
            throw new \DomainException(config('constants.user_with_email_no_exists'));
        }

        // отправлять запрос на обновление токена и отправку письма на почту не чаще 5 минут
        // время жизни токена
        if ($user->last_email_at->diffInMinutes(now()) >= config('constants.last_email_minutes')) {
            $token = sha1(time());
            $user->update([
                'last_email_at' => now(),
                'remember_token' => $token
            ]);

            // формируем ссылку
            $data = ['link' => config('app.origin') . '/password-reset/' . $token];
            Mail::to($email)->send(new ForgotPasswordMail($data));
        } else {
            throw new \DomainException(config('constants.last_email_at'));
        }
    }

    // проверка токена
    public function checkToken(string $token): void
    {
        $this->findUserByRememberToken($token);
    }

    // сброс пароля
    public function resetPassword(Request $request, string $token): void
    {
        $user = $this->findUserByRememberToken($token);
        $user->update([
            'password' => Hash::make($request['password']),
            'password_changed_at' => Carbon::now(),
            'remember_token' => null
        ]);
    }

    // ищем пользователя по токену
    private function findUserByRememberToken(string $token): User
    {
        /** @var User $user */
        $user = User::query()->where('remember_token', $token)->first();
        // если не найден
        if (!$user) {
            throw new \DomainException(config('constants.something_went_wrong'));
        }
        // если не подтверждена почта
        if (!$user->hasVerifiedEmail()) {
            throw new \DomainException(config('constants.something_went_wrong'));
        }
        // если токен устарел
        if ($user->last_email_at->diffInMinutes(now()) >= config('constants.last_email_minutes')) {
            throw new \DomainException(config('constants.email_link_time_is_up'));
        }
        return $user;
    }
}

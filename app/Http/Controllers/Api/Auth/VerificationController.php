<?php
namespace App\Http\Controllers\Api\Auth;

use DomainException;
use App\Entity\User\User;
use Illuminate\Http\JsonResponse;
use App\Helpers\Response\Response;
use App\Http\Controllers\Controller;
use App\Http\Requests\Email\ResendEmailRequest;
use App\Http\Resources\User\EmailVerificationResource;
use Illuminate\Auth\Access\AuthorizationException;

class VerificationController extends Controller
{
    // переход по ссылке из письма (подтверждение email)
    public function verification(string $code): JsonResponse
    {
        /** @var User $user */
        // ищем пользователя по коду
        $user = User::query()->where('verification_code', 'like', '%' . $code . '%')->first();
        if (!$user) {
            throw new AuthorizationException;
        }
        // подтвереждаем почту
        $user->setEmailVerified();
        //$accessToken = $user->createToken('authToken')->accessToken;
        return Response::HTTP_OK(new EmailVerificationResource(""));
    }

    // смена email
    public function resend(ResendEmailRequest $request): JsonResponse
    {
        $email = $request['email'];
        $user = User::query()->where('email', 'like', '%' . $email . '%')->first();
        if (!$user) {
            throw new DomainException(config("constants.user_not_found"));
        }
        // раз в 5 минут можно отправлять письмо на почту и обновлять код подтверждения
        if (!$user?->hasVerifiedEmail() &&
            $user?->last_email_at->diffInMinutes(now()) >= config('constants.last_email_minutes')
        ) {
            $verification_code = sha1(time());
            $user?->update([
                'verification_code' => $verification_code,
                'last_email_at' => now()
            ]);
            sendEmail($user);
            return Response::HTTP_OK(['emailSuccessfullyResend' => true]);
        } else {
            throw new \DomainException("Письмо с ссылкой для подтверждения почтового адреса можно отправлять раз в 5 минут.");
        }
    }
}

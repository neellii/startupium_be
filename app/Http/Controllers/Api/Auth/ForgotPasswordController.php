<?php
namespace App\Http\Controllers\Api\Auth;

use App\Helpers\Response\Response;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\NewPasswordRequest;
use App\Http\Requests\Email\ResendEmailRequest;
use App\UseCases\Password\PasswordService;
use Illuminate\Http\JsonResponse;

class ForgotPasswordController extends Controller
{
    private $service;

    public function __construct(PasswordService $service)
    {
        $this->service = $service;
    }

    // забыл пароль
    public function forgotPassword(ResendEmailRequest $request): JsonResponse
    {
        $this->service->forgotPassword($request);
        return Response::HTTP_CREATED([
            'message' => config('constants.password_reset_successfully_sent'),
            'isEmailExisits' => true
        ]);
    }

    // сброс пароля
    public function resetPassword(NewPasswordRequest $request, string $token): JsonResponse
    {
        $this->service->resetPassword($request, $token);
        return Response::HTTP_CREATED([
            'message' => config('constants.password_successfully_changed'),
            'passwordSuccessfullyReset' => true
        ]);
    }

    // проверка токена
    public function checkToken(string $token): JsonResponse
    {
        $this->service->checkToken($token);
        return Response::HTTP_OK([
            'hasTokenVerified' => true
        ]);
    }
}

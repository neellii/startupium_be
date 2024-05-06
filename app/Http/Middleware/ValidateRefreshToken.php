<?php

namespace App\Http\Middleware;

use Closure;
use App\Entity\User\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Auth\AuthenticationException;

class ValidateRefreshToken
{
    public function handle(Request $request, Closure $next)
    {
        /** @var User $user */
        $user = Auth::guard('api')->user();
        if (!$user) {
            throw new AuthenticationException();
        }

        $token = $user->token();
        if ($token->name === "refreshToken") {
            throw new AuthenticationException();
        }

        // если время жизни $accessToken истекло
        if ($token?->expires_at?->diffInMinutes(now()) > config('constants.access_token_expires_in')) {
            throw new AuthenticationException(config('constants.access_token_expires_message'));
        }
        return $next($request);
    }
}

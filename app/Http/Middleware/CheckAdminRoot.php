<?php

namespace App\Http\Middleware;

use Closure;
use DomainException;
use App\Entity\User\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckAdminRoot
{
    public function handle(Request $request, Closure $next): Response
    {
        /** @var User $user */
        $user = Auth::guard('api')->user();
        if (!$user->isAdmin()) {
            throw new DomainException(config('constants.something_went_wrong'));
        }
        return $next($request);
    }
}

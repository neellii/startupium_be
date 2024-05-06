<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Auth\AuthenticationException;

class AuthPagination
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = findAuthUser();
        $page = $request->input("page");
        if ($page && $page > 1 && !$user) {
            throw new AuthenticationException();
        }
        return $next($request);
    }
}

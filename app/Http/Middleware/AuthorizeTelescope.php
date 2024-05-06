<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Auth\Middleware\Authenticate;

class AuthorizeTelescope extends Authenticate
{
    public function handle($request, Closure $next, ...$guards): mixed
  {
        if (app()->environment('local')) {
            return $next($request);
        }

        if (app()->environment('production') && !$request->expectsJson()) {
           parent::handle($request, $next, ...$guards);
        }

        return abort(403);
  }
}

<?php
namespace App\Http\Middleware;

use Carbon\Carbon;
use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class IsUserOnline
{
    public function handle($request, Closure $next)
    {
        if (Auth::guard('api')->check()) {
            $user = findAuthUser();

            $expiresAt = Carbon::now()->addMinutes(15);

            Cache::put('user-is-online-' . $user->id, true, $expiresAt);
        }
        return $next($request);
    }
}

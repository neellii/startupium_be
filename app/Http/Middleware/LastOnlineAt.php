<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class LastOnlineAt
{
    public function handle($request, Closure $next)
    {
        if (!Auth::guard('api')->check()) {
            return $next($request);
        }

        $user = authUser();
        if ($user->last_online_at->diffInMinutes(now()) >= 15) {
            DB::table('users')
                ->where('id', $user->id)
                ->update(['last_online_at' => now()]);
        }
        return $next($request);
    }
}

<?php

namespace App\Http\Middleware;

use Closure;
use Exception;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class DecryptMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $cdata = $request['cdata'];
        $data = "";
        try {
            if ($cdata) {
                $data = handleDecrypt(base64_decode($cdata));
                $request->merge($data ?? []);
            }
        }
        catch (Exception $ex) {
            $data = [];
            $request->merge($data);
        }
        return $next($request);
    }
}

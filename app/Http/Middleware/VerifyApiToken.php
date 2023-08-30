<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class VerifyApiToken
{
    public function handle(Request $request, Closure $next, ...$guards)
    {
        if (! config('app.api_token')) {
            throw new \RuntimeException('Empty api token , please  set one');
        }

        $token = $request->header('Token');
        if (! $token || ! hash_equals(config('app.api_token'), $token)) {
            throw new AccessDeniedHttpException('Access Denied');
        }

        return $next($request);
    }
}

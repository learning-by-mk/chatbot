<?php

namespace App\Http\Middleware;

use Closure;

class NoopCsrfToken
{
    public function handle($request, Closure $next)
    {
        return $next($request);
    }
}

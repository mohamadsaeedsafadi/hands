<?php

namespace App\Http\Middleware;

use App\Services\SystemLogger;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth ;
use Laravel\Reverb\Loggers\Log;
use Symfony\Component\HttpFoundation\Response;

class LogRequests
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
   public function handle($request, Closure $next)
{
    SystemLogger::log(
        'request_hit',
        null,
        null,
        null,
        'request'
    );

    return $next($request);
}
}

<?php

namespace App\Http\Middleware;

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
    Log::info('Request', [
        'user_id' => Auth::user()->id,
        'url' => $request->fullUrl(),
        'method' => $request->method(),
        'ip' => $request->ip()
    ]);

    return $next($request);
}
}

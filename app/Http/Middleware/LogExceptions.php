<?php

namespace App\Http\Middleware;

use Closure;
use Throwable;
use App\Services\SystemLogger;

class LogExceptions
{
    public function handle($request, Closure $next)
    {
        try {

            return $next($request);

        } catch (Throwable $e) {

            SystemLogger::log(
                'exception',
                null,
                null,
                [
                    'message' => $e->getMessage(),
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                ],
                'system',
                'critical',
                false,
                'Unhandled exception'
            );

            throw $e;
        }
    }
}
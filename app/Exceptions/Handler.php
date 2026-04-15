<?php
namespace App\Exceptions;
use App\Services\SystemLogger;
use Throwable;

class Handler {
public function report(Throwable $e)
{
    SystemLogger::log(
        'system_error',
        null,
        null,
        [
            'message' => $e->getMessage()
        ],
        'error',
        'critical'
    );

 
}
}
<?php
namespace App\Services;

use App\Models\AuditLog;
use Illuminate\Support\Facades\Auth;

class SystemLogger
{
    public static function log(
        $action,
        $model = null,
        $old = null,
        $new = null,
        $eventType = 'general',
        $severity = 'info'
    ) {
        AuditLog::create([
            'user_id' => Auth::check() ? Auth::id() : null,
            'action' => $action,
            'event_type' => $eventType ?: ($model ? class_basename($model) : 'general'),
            'severity' => $severity,
            'model_type' => $model ? get_class($model) : null,
            'model_id' => $model?->id,
            'old_values' => $old,
            'new_values' => $new,
            'ip' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'url' => request()->fullUrl(),
            'method' => request()->method()
        ]);
    }
}
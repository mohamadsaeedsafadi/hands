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
        $severity = 'info',
        $success = true,
        $description = null
    ) {

        $user = Auth::user();

        $guard = null;

        if (Auth::guard('admin_api')->check()) {
            $guard = 'admin_api';
        } elseif (Auth::guard('cashier_api')->check()) {
            $guard = 'cashier_api';
        } elseif (Auth::guard('user_api')->check()) {
            $guard = 'user_api';
        }

        AuditLog::create([

            /* 'user_id' => $user?->id, */

            'user_email' => $user?->email,

            'user_type' => $user
                ? class_basename($user)
                : null,

            'guard' => $guard,

            'success' => $success,

            'description' => $description,

            'executed_at' => now(),

            'action' => $action,

            'event_type' => $eventType
                ?: ($model ? class_basename($model) : 'general'),

            'severity' => $severity,

            'model_type' => $model
                ? get_class($model)
                : null,

            'model_id' => $model?->id,

            'old_values' => $old,

            'new_values' => $new,

            'ip' => request()->ip(),

            'user_agent' => request()->userAgent(),

            'url' => request()->fullUrl(),

            'method' => request()->method(),
        ]);
    }
}
<?php
namespace App\Traits;

use App\Models\AuditLog;
use Illuminate\Support\Facades\Auth;

trait AuditTrait
{
    public static function bootAuditTrait()
    {
        static::created(function ($model) {
            self::logAction('created', $model, null, $model->toArray());
        });

        static::updated(function ($model) {
            self::logAction(
                'updated',
                $model,
                $model->getOriginal(),
                $model->getChanges()
            );
        });

        static::deleted(function ($model) {
            self::logAction('deleted', $model, $model->toArray(), null);
        });
    }

    protected static function logAction($action, $model, $old = null, $new = null)
    {
        AuditLog::create([
            'user_id' => Auth::id(),
            'action' => $action,
            'model_type' => get_class($model),
            'model_id' => $model->id,
            'old_values' => $old,
            'new_values' => $new,
            'ip' => request()->ip(),
            'user_agent' => request()->userAgent()
        ]);
    }
}
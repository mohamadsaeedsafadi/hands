<?php

namespace App\Observers;

use App\Services\SystemLogger;

class GlobalObserver
{
   public function creating($model)
{
    SystemLogger::log(
        'creating',
        $model,
        null,
        $model->getAttributes(),
        'model'
    );
}

public function updating($model)
{
    SystemLogger::log(
        'updating',
        $model,
        $model->getOriginal(),
        $model->getDirty(),
        'model'
    );
}

public function deleted($model)
{
    SystemLogger::log(
        'deleted',
        $model,
        null,
        null,
        'model'
    );
}
public function updated($model)
{
    SystemLogger::log('update_after', $model, null, $model->getChanges());
}
}
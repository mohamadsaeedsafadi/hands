<?php

namespace App\Observers;

use App\Models\ServiceRequest;
use App\Services\AuditService;

class ServiceRequestObserver
{

    protected $audit;

    public function __construct(AuditService $audit)
    {
        $this->audit = $audit;
    }

    public function updating($model)
    {
        $this->audit->log(
            "update_before",
            "ServiceRequest",
            $model->id,
            $model->getOriginal(),
            null
        );
    }

    public function updated($model)
    {
        $this->audit->log(
            "update_after",
            "ServiceRequest",
            $model->id,
            null,
            $model->getAttributes()
        );
    }

}
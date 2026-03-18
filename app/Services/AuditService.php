<?php 
namespace App\Services;

use App\Models\AuditLog;
use Illuminate\Support\Facades\Auth;

class AuditService
{

    public function log($action,$model,$modelId=null,$before=null,$after=null)
    {

        AuditLog::create([

            'user_id'=>Auth::id(),

            'action'=>$action,

            'model'=>$model,

            'model_id'=>$modelId,

            'before'=>$before,

            'after'=>$after,

            'ip_address'=>request()->ip(),

            'user_agent'=>request()->userAgent()

        ]);

    }

}
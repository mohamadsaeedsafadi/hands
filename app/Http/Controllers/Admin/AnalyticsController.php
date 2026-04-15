<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Services\AnalyticsService;
use Illuminate\Http\Request;

class AnalyticsController extends Controller
{
    public function __construct(protected AnalyticsService $service) {}

    public function dashboard(Request $request)
    {
        return response()->json([
            'users' => $this->service->usersCount($request->from, $request->to),
            'requests' => $this->service->requestsCount($request->from, $request->to),
            'completed' => $this->service->completedRequests($request->from, $request->to),
            'top_providers' => $this->service->topProviders(),
            'requests_per_category' => $this->service->requestsPerCategory()
        ]);
    }
    public function export()
{
    $logs = AuditLog::latest()->get();

    $csv = "user_id,action,model_type\n";

    foreach ($logs as $log) {
        $csv .= "{$log->user_id},{$log->action},{$log->model_type}\n";
    }

    return response($csv)
        ->header('Content-Type', 'text/csv')
        ->header('Content-Disposition', 'attachment; filename=logs.csv');
}
}
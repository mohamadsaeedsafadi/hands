<?php

namespace App\Http\Controllers\Admin;


use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use Illuminate\Http\Request;

class AuditController extends Controller
{
   public function index(Request $request)
{
    $query = AuditLog::query();

   
    if ($request->user_id) {
        $query->where('user_id', $request->user_id);
    }

    if ($request->model_type) {
        $query->where('model_type', $request->model_type);
    }

    if ($request->action) {
        $query->where('action', $request->action);
    }

    if ($request->from && $request->to) {
        $query->whereBetween('created_at', [$request->from, $request->to]);
    }

    return response()->json(
        $query->latest()->paginate(20)
    );
}

    public function show($id)
    {
        return AuditLog::findOrFail($id);
    }

    public function stats()
    {
        return [
            'total_logs' => AuditLog::count(),
            'errors' => AuditLog::where('severity', 'critical')->count(),
            'payments' => AuditLog::where('event_type', 'payment')->count(),
            'offers' => AuditLog::where('event_type', 'offer')->count(),
        ];
    }
}

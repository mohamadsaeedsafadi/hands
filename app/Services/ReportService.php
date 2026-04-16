<?php

namespace App\Services;

use App\Models\Report;
use Illuminate\Support\Facades\DB;

class ReportService
{
    public function createReport($reporter, $data)
    {
        DB::beginTransaction();

        $report = Report::create([
            'reporter_id' => $reporter->id,
            'reporter_type' => get_class($reporter),
            'reported_user_id' => $data['reported_user_id'],
            'type' => $data['type'],
            'description' => $data['description'] ?? null,
        ]);

       
        if (!empty($data['attachments'])) {
            foreach ($data['attachments'] as $file) {
                $report->attachments()->create([
                    'file_path' => $file->store('reports', 'public')
                ]);
            }
        }

        DB::commit();
app(\App\Services\BanService::class)
    ->autoBanIfNeeded($report->reported_user_id);
        return $report->load('attachments', 'reportedUser');
    }

    public function filterReports($filters)
    {
        $query = Report::with(['reporter', 'reportedUser','attachments']);

        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (!empty($filters['type'])) {
            $query->where('type', $filters['type']);
        }

        return $query->latest()->paginate(10);
    }

    public function updateStatus($id, $status)
    {
        $report = Report::findOrFail($id);

        $report->update([
            'status' => $status
        ]);
app(\App\Services\BanService::class)
    ->autoBanIfNeeded($report->reported_user_id);
        return $report;
    }
}
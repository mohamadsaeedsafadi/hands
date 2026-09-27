<?php

namespace App\Services\Admin\Charts;

use App\Models\WithdrawalRequest;

class WithdrawalChartService
{
    protected function filter($query, $filters)
    {
        if (!empty($filters['from'])) {
            $query->whereDate('created_at', '>=', $filters['from']);
        }

        if (!empty($filters['to'])) {
            $query->whereDate('created_at', '<=', $filters['to']);
        }

        return $query;
    }

    public function growth($filters = [])
    {
        return $this->filter(
            WithdrawalRequest::where('status', 'approved'),
            $filters
        )
        ->selectRaw('DATE(created_at) as date')
        ->selectRaw('SUM(amount) as total')
        ->groupBy('date')
        ->orderBy('date')
        ->get();
    }
}
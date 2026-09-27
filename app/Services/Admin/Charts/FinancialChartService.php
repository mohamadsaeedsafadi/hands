<?php

namespace App\Services\Admin\Charts;

use App\Models\Payment;

class FinancialChartService
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

    public function revenueGrowth($filters = [])
    {
        return $this->filter(
            Payment::where('status', 'paid'),
            $filters
        )
        ->selectRaw('DATE(created_at) as date')
        ->selectRaw('SUM(amount_syp) as total')
        ->groupBy('date')
        ->orderBy('date')
        ->get();
    }

    public function paymentsByStatus($filters = [])
    {
        return $this->filter(
            Payment::query(),
            $filters
        )
        ->select('status')
        ->selectRaw('COUNT(*) as total')
        ->groupBy('status')
        ->get();
    }
}
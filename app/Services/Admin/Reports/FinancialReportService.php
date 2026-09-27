<?php

namespace App\Services\Admin\Reports;

use App\Models\Payment;

class FinancialReportService
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

    public function report($filters = [])
    {
        $payments = $this->filter(Payment::query(), $filters);

        return [

            'total_paid' => (clone $payments)
                ->where('status', 'paid')
                ->sum('amount_syp'),

            'total_pending' => (clone $payments)
                ->where('status', 'pending')
                ->sum('amount_syp'),

            'total_failed' => (clone $payments)
                ->where('status', 'failed')
                ->sum('amount_syp'),

            'payments_count' => (clone $payments)->count(),

            'daily_revenue' => (clone $payments)
                ->selectRaw('DATE(created_at) as date')
                ->selectRaw('SUM(amount_syp) as total')
                ->groupBy('date')
                ->orderBy('date')
                ->get(),
                'top_payments' =>
    (clone $payments)
        ->where('status', 'paid')
        ->orderByDesc('amount_syp')
        ->take(10)
        ->get(),

'average_payment' =>
    (clone $payments)
        ->where('status', 'paid')
        ->avg('amount_syp'),

'payments_growth' =>
    (clone $payments)
        ->selectRaw('DATE(created_at) as date')
        ->selectRaw('COUNT(*) as total')
        ->groupBy('date')
        ->orderBy('date')
        ->get(),

'success_rate' =>
    (
        (clone $payments)
            ->where('status', 'paid')
            ->count()
        /
        max((clone $payments)->count(),1)
    ) * 100,
        ];
    }
}
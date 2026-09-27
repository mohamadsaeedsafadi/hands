<?php

namespace App\Services\Admin\Reports;

use App\Models\WithdrawalRequest;
use Illuminate\Support\Facades\DB;

class WithdrawalReportService
{
    protected function filter($query, $filters)
    {
        if (!empty($filters['from'])) {

            $query->whereDate(
                'created_at',
                '>=',
                $filters['from']
            );
        }

        if (!empty($filters['to'])) {

            $query->whereDate(
                'created_at',
                '<=',
                $filters['to']
            );
        }

        return $query;
    }

    public function report($filters = [])
    {
        $withdrawals = $this->filter(
            WithdrawalRequest::with([
                'provider',
                'cashier'
            ]),
            $filters
        );

        return [

            /*
            |--------------------------------------------------------------------------
            | General Statistics
            |--------------------------------------------------------------------------
            */

            'total_requests' =>
                (clone $withdrawals)->count(),

            'approved_requests' =>
                (clone $withdrawals)
                    ->where('status', 'approved')
                    ->count(),

            'pending_requests' =>
                (clone $withdrawals)
                    ->where('status', 'pending')
                    ->count(),

            'rejected_requests' =>
                (clone $withdrawals)
                    ->where('status', 'rejected')
                    ->count(),

            /*
            |--------------------------------------------------------------------------
            | Financial Statistics
            |--------------------------------------------------------------------------
            */

            'total_withdrawals' =>
                (clone $withdrawals)
                    ->where('status', 'approved')
                    ->sum('amount'),

            'total_commissions' =>
                (clone $withdrawals)
                    ->where('status', 'approved')
                    ->sum('commission'),

            'total_final_amounts' =>
                (clone $withdrawals)
                    ->where('status', 'approved')
                    ->sum('final_amount'),

            'average_withdrawal' =>
                (clone $withdrawals)
                    ->where('status', 'approved')
                    ->avg('amount'),

            'largest_withdrawal' =>
                (clone $withdrawals)
                    ->where('status', 'approved')
                    ->max('amount'),

            'smallest_withdrawal' =>
                (clone $withdrawals)
                    ->where('status', 'approved')
                    ->min('amount'),

            /*
            |--------------------------------------------------------------------------
            | Withdrawals By Status
            |--------------------------------------------------------------------------
            */

            'withdrawals_by_status' =>
                (clone $withdrawals)
                    ->select(
                        'status',
                        DB::raw('COUNT(*) as total')
                    )
                    ->groupBy('status')
                    ->get(),

            /*
            |--------------------------------------------------------------------------
            | Daily Withdrawals
            |--------------------------------------------------------------------------
            */

            'daily_withdrawals' =>
                (clone $withdrawals)
                    ->where('status', 'approved')
                    ->selectRaw('DATE(created_at) as date')
                    ->selectRaw('SUM(amount) as total')
                    ->groupBy('date')
                    ->orderBy('date')
                    ->get(),

            /*
            |--------------------------------------------------------------------------
            | Top Withdrawals
            |--------------------------------------------------------------------------
            */

            'top_withdrawals' =>
                (clone $withdrawals)
                    ->where('status', 'approved')
                    ->orderByDesc('amount')
                    ->take(10)
                    ->get(),

            /*
            |--------------------------------------------------------------------------
            | Latest Requests
            |--------------------------------------------------------------------------
            */

            'latest_requests' =>
                (clone $withdrawals)
                    ->latest()
                    ->take(20)
                    ->get(),

            /*
            |--------------------------------------------------------------------------
            | Most Active Providers In Withdrawals
            |--------------------------------------------------------------------------
            */

            'top_providers_withdrawals' =>
                (clone $withdrawals)
                    ->select(
                        'provider_id',
                        DB::raw('COUNT(*) as total_requests'),
                        DB::raw('SUM(amount) as total_amount')
                    )
                    ->with('provider:id,name,email')
                    ->groupBy('provider_id')
                    ->orderByDesc('total_amount')
                    ->take(10)
                    ->get(),

            /*
            |--------------------------------------------------------------------------
            | Cashiers Performance
            |--------------------------------------------------------------------------
            */

            'cashiers_activity' =>
                (clone $withdrawals)
                    ->whereNotNull('cashier_id')
                    ->select(
                        'cashier_id',
                        DB::raw('COUNT(*) as processed_requests'),
                        DB::raw('SUM(amount) as processed_amount')
                    )
                    ->with('cashier:id,name,email')
                    ->groupBy('cashier_id')
                    ->orderByDesc('processed_requests')
                    ->take(10)
                    ->get(),
        ];
    }
}
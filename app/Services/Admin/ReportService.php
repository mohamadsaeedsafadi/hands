<?php

namespace App\Services\Admin;

use App\Models\AuditLog;
use App\Models\Payment;
use App\Models\ServiceCategory;
use App\Models\ServiceRequest;
use App\Models\User;
use App\Models\WithdrawalRequest;
use Illuminate\Support\Facades\DB;

class ReportService
{
    protected function applyDateFilter($query, $filters)
    {
        if (!empty($filters['from'])) {
            $query->whereDate('created_at', '>=', $filters['from']);
        }

        if (!empty($filters['to'])) {
            $query->whereDate('created_at', '<=', $filters['to']);
        }

        return $query;
    }

    /*
    |--------------------------------------------------------------------------
    | Dashboard Overview
    |--------------------------------------------------------------------------
    */

    public function dashboard($filters = [])
    {
        $users = $this->applyDateFilter(User::query(), $filters);

        $payments = $this->applyDateFilter(Payment::query(), $filters);

        $withdrawals = $this->applyDateFilter(
            WithdrawalRequest::query(),
            $filters
        );

        $requests = $this->applyDateFilter(
            ServiceRequest::query(),
            $filters
        );

        return [

            'users_count' => (clone $users)->count(),

            'providers_count' => (clone $users)
                ->where('role', 'provider')
                ->count(),

            'verified_providers' => (clone $users)
                ->whereNotNull('provider_verified_at')
                ->count(),

            'payments_total' => (clone $payments)
                ->where('status', 'paid')
                ->sum('amount_syp'),

            'payments_count' => (clone $payments)->count(),

            'withdrawals_total' => (clone $withdrawals)
                ->where('status', 'approved')
                ->sum('amount'),

            'commission_total' => (clone $withdrawals)
                ->where('status', 'approved')
                ->sum('commission'),

            'requests_count' => (clone $requests)->count(),

            'completed_requests' => (clone $requests)
                ->where('status', 'completed')
                ->count(),

            'cancelled_requests' => (clone $requests)
                ->where('status', 'cancelled')
                ->count(),

            'pending_requests' => (clone $requests)
                ->where('status', 'pending')
                ->count(),
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | User Growth
    |--------------------------------------------------------------------------
    */

    public function usersGrowth($filters = [])
    {
        return $this->applyDateFilter(User::query(), $filters)
            ->selectRaw('DATE(created_at) as date')
            ->selectRaw('COUNT(*) as total')
            ->groupBy('date')
            ->orderBy('date')
            ->get();
    }

    /*
    |--------------------------------------------------------------------------
    | Most Active Users
    |--------------------------------------------------------------------------
    */

    public function mostActiveUsers($filters = [])
    {
        return $this->applyDateFilter(AuditLog::query(), $filters)
            ->select('user_email')
            ->selectRaw('COUNT(*) as total')
            ->groupBy('user_email')
            ->orderByDesc('total')
            ->take(10)
            ->get();
    }

    /*
    |--------------------------------------------------------------------------
    | Most Requested Categories
    |--------------------------------------------------------------------------
    */

    public function topCategories()
    {
        return ServiceCategory::withCount('requests')
            ->orderByDesc('requests_count')
            ->take(10)
            ->get();
    }

    /*
    |--------------------------------------------------------------------------
    | Financial Stats
    |--------------------------------------------------------------------------
    */

    public function financialStats($filters = [])
    {
        $payments = $this->applyDateFilter(
            Payment::query(),
            $filters
        );

        return [

            'paid_total' => (clone $payments)
                ->where('status', 'paid')
                ->sum('amount_syp'),

            'pending_total' => (clone $payments)
                ->where('status', 'pending')
                ->sum('amount_syp'),

            'failed_total' => (clone $payments)
                ->where('status', 'failed')
                ->sum('amount_syp'),

            'payments_by_day' => (clone $payments)
                ->selectRaw('DATE(created_at) as date')
                ->selectRaw('SUM(amount_syp) as total')
                ->groupBy('date')
                ->orderBy('date')
                ->get(),
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Withdrawals Report
    |--------------------------------------------------------------------------
    */

    public function withdrawals($filters = [])
    {
        return $this->applyDateFilter(
            WithdrawalRequest::with(['provider', 'cashier']),
            $filters
        )->latest()->paginate(20);
    }

    /*
    |--------------------------------------------------------------------------
    | Audit Logs
    |--------------------------------------------------------------------------
    */

    public function logs($filters = [])
    {
        return $this->applyDateFilter(
            AuditLog::query(),
            $filters
        )
        ->latest()
        ->paginate(30);
    }
}
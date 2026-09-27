<?php

namespace App\Services\Admin\Reports;

use App\Models\User;
use App\Models\Payment;
use App\Models\ServiceRequest;
use App\Models\ServiceCategory;
use App\Models\AuditLog;
use App\Models\Rating;
use App\Models\ServiceOffer;
use Illuminate\Support\Facades\DB;

class AnalyticsReportService
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

    public function analytics($filters = [])
    {
        /*
        |--------------------------------------------------------------------------
        | Base Queries
        |--------------------------------------------------------------------------
        */

        $users = $this->filter(User::query(), $filters);

        $payments = $this->filter(Payment::query(), $filters);

        $requests = $this->filter(ServiceRequest::query(), $filters);

        $logs = $this->filter(AuditLog::query(), $filters);

        /*
        |--------------------------------------------------------------------------
        | Analytics
        |--------------------------------------------------------------------------
        */

        return [

            /*
            |--------------------------------------------------------------------------
            | General Statistics
            |--------------------------------------------------------------------------
            */

            'total_users' =>
                (clone $users)->count(),

            'total_providers' =>
                (clone $users)
                    ->where('role', 'provider')
                    ->count(),

            'verified_providers' =>
                (clone $users)
                    ->whereNotNull('provider_verified_at')
                    ->count(),

            'new_users_today' =>
                (clone $users)
                    ->whereDate('created_at', today())
                    ->count(),

            /*
            |--------------------------------------------------------------------------
            | Users Growth
            |--------------------------------------------------------------------------
            */

            'users_growth_monthly' =>

                (clone $users)
                    ->selectRaw('MONTH(created_at) as month')
                    ->selectRaw('COUNT(*) as total')
                    ->groupBy('month')
                    ->orderBy('month')
                    ->get(),

            'users_growth_daily' =>

                (clone $users)
                    ->selectRaw('DATE(created_at) as date')
                    ->selectRaw('COUNT(*) as total')
                    ->groupBy('date')
                    ->orderBy('date')
                    ->get(),

            /*
            |--------------------------------------------------------------------------
            | Providers Growth
            |--------------------------------------------------------------------------
            */

            'providers_growth' =>

                (clone $users)
                    ->where('role', 'provider')
                    ->selectRaw('MONTH(created_at) as month')
                    ->selectRaw('COUNT(*) as total')
                    ->groupBy('month')
                    ->get(),

            /*
            |--------------------------------------------------------------------------
            | Financial Growth
            |--------------------------------------------------------------------------
            */

            'payments_growth' =>

                (clone $payments)
                    ->where('status', 'paid')
                    ->selectRaw('MONTH(created_at) as month')
                    ->selectRaw('SUM(amount_syp) as total')
                    ->groupBy('month')
                    ->orderBy('month')
                    ->get(),

            'total_revenue' =>

                (clone $payments)
                    ->where('status', 'paid')
                    ->sum('amount_syp'),

            'average_payment' =>

                (clone $payments)
                    ->where('status', 'paid')
                    ->avg('amount_syp'),

            /*
            |--------------------------------------------------------------------------
            | Requests Statistics
            |--------------------------------------------------------------------------
            */

            'total_requests' =>

                (clone $requests)->count(),

            'completed_requests' =>

                (clone $requests)
                    ->where('status', 'completed')
                    ->count(),

            'pending_requests' =>

                (clone $requests)
                    ->where('status', 'pending')
                    ->count(),

            'cancelled_requests' =>

                (clone $requests)
                    ->where('status', 'cancelled')
                    ->count(),

            'completion_rate' =>

                (clone $requests)->count() > 0

                    ? round(
                        (
                            (clone $requests)
                                ->where('status', 'completed')
                                ->count()

                            /

                            (clone $requests)->count()
                        ) * 100,
                        2
                    )

                    : 0,

            /*
            |--------------------------------------------------------------------------
            | Requests By Status
            |--------------------------------------------------------------------------
            */

            'requests_by_status' =>

                (clone $requests)
                    ->select('status')
                    ->selectRaw('COUNT(*) as total')
                    ->groupBy('status')
                    ->get(),

            /*
            |--------------------------------------------------------------------------
            | Most Requested Categories
            |--------------------------------------------------------------------------
            */

            'top_categories' =>

                ServiceCategory::withCount('requests')
                    ->orderByDesc('requests_count')
                    ->take(10)
                    ->get(),

            /*
            |--------------------------------------------------------------------------
            | Most Requested Services
            |--------------------------------------------------------------------------
            */

            'top_services' =>

                ServiceRequest::select(
                        'category_id',
                        DB::raw('COUNT(*) as total')
                    )
                    ->with('category')
                    ->groupBy('category_id')
                    ->orderByDesc('total')
                    ->take(10)
                    ->get(),

            /*
            |--------------------------------------------------------------------------
            | Most Active Users
            |--------------------------------------------------------------------------
            */

            'most_active_users' =>

                (clone $logs)
                    ->select('user_email')
                    ->selectRaw('COUNT(*) as total')
                    ->groupBy('user_email')
                    ->orderByDesc('total')
                    ->take(10)
                    ->get(),

            /*
            |--------------------------------------------------------------------------
            | Most Active Providers
            |--------------------------------------------------------------------------
            */

            'most_active_providers' =>

                ServiceOffer::select(
                        'provider_id',
                        DB::raw('COUNT(*) as total')
                    )
                    ->with('provider')
                    ->groupBy('provider_id')
                    ->orderByDesc('total')
                    ->take(10)
                    ->get(),

            /*
            |--------------------------------------------------------------------------
            | Top Rated Providers
            |--------------------------------------------------------------------------
            */

            'top_rated_providers' =>

                User::where('role', 'provider')
                    ->orderByDesc('rating_avg')
                    ->take(10)
                    ->get(),

            /*
            |--------------------------------------------------------------------------
            | Ratings Statistics
            |--------------------------------------------------------------------------
            */

            'ratings_average' =>

                Rating::avg('rating'),

            'ratings_count' =>

                Rating::count(),

            /*
            |--------------------------------------------------------------------------
            | Daily Requests Average
            |--------------------------------------------------------------------------
            */

            'daily_requests_average' =>

                round(
                    ServiceRequest::count() /
                    max(
                        now()->diffInDays(
                            ServiceRequest::min('created_at')
                        ),
                        1
                    ),
                    2
                ),
        ];
    }
}
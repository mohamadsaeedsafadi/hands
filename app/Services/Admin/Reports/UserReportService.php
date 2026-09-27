<?php

namespace App\Services\Admin\Reports;

use App\Models\User;
use App\Models\AuditLog;
use App\Models\ServiceOffer;
use App\Models\ServiceRequest;
use Illuminate\Support\Facades\DB;

class UserReportService
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
        $users = $this->filter(
            User::query(),
            $filters
        );

        return [

            /*
            |--------------------------------------------------------------------------
            | General Statistics
            |--------------------------------------------------------------------------
            */

            'users_count' =>
                (clone $users)->count(),

            'providers_count' =>
                (clone $users)
                    ->where('role', 'provider')
                    ->count(),

            'normal_users_count' =>
                (clone $users)
                    ->where('role', 'user')
                    ->count(),

            'verified_providers' =>
                (clone $users)
                    ->whereNotNull('provider_verified_at')
                    ->count(),

            'unverified_providers' =>
                (clone $users)
                    ->where('role', 'provider')
                    ->whereNull('provider_verified_at')
                    ->count(),

            /*
            |--------------------------------------------------------------------------
            | Users Growth
            |--------------------------------------------------------------------------
            */

            'growth' =>
                (clone $users)
                    ->selectRaw('DATE(created_at) as date')
                    ->selectRaw('COUNT(*) as total')
                    ->groupBy('date')
                    ->orderBy('date')
                    ->get(),

            /*
            |--------------------------------------------------------------------------
            | Users Registered Per Month
            |--------------------------------------------------------------------------
            */

            'users_per_month' =>
                (clone $users)
                    ->selectRaw('MONTH(created_at) as month')
                    ->selectRaw('COUNT(*) as total')
                    ->groupBy('month')
                    ->orderBy('month')
                    ->get(),

            /*
            |--------------------------------------------------------------------------
            | Most Active Users
            |--------------------------------------------------------------------------
            */

            'most_active_users' =>
                AuditLog::select(
                        'user_email',
                        DB::raw('COUNT(*) as total')
                    )
                    ->whereNotNull('user_email')
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
                    ->with('provider:id,name,email,rating_avg')
                    ->whereNotNull('provider_id')
                    ->groupBy('provider_id')
                    ->orderByDesc('total')
                    ->take(10)
                    ->get(),

            /*
            |--------------------------------------------------------------------------
            | Best Providers By Rating
            |--------------------------------------------------------------------------
            */

            'top_providers' =>
                User::where('role', 'provider')
                    ->orderByDesc('rating_avg')
                    ->take(10)
                    ->get([
                        'id',
                        'name',
                        'email',
                        'rating_avg',
                        'ratings_count'
                    ]),

            /*
            |--------------------------------------------------------------------------
            | Providers Most Completed Requests
            |--------------------------------------------------------------------------
            */

            'most_requested_providers' =>
                ServiceOffer::select(
                        'provider_id',
                        DB::raw('COUNT(*) as total')
                    )
                    ->with('provider:id,name,email')
                    ->where('status', 'completed')
                    ->groupBy('provider_id')
                    ->orderByDesc('total')
                    ->take(10)
                    ->get(),

            /*
            |--------------------------------------------------------------------------
            | Recently Registered Users
            |--------------------------------------------------------------------------
            */

            'latest_users' =>
                (clone $users)
                    ->latest()
                    ->take(15)
                    ->get([
                        'id',
                        'name',
                        'email',
                        'role',
                        'created_at'
                    ]),

            /*
            |--------------------------------------------------------------------------
            | Locked Users
            |--------------------------------------------------------------------------
            */

            'locked_users' =>
                (clone $users)
                    ->whereNotNull('locked_until')
                    ->count(),

            /*
            |--------------------------------------------------------------------------
            | Email Verified Users
            |--------------------------------------------------------------------------
            */

            'verified_users' =>
                (clone $users)
                    ->whereNotNull('email_verified_at')
                    ->count(),

            'unverified_users' =>
                (clone $users)
                    ->whereNull('email_verified_at')
                    ->count(),

            /*
            |--------------------------------------------------------------------------
            | Top Rated Providers
            |--------------------------------------------------------------------------
            */

            'top_rated_providers' =>
                User::where('role', 'provider')
                    ->where('ratings_count', '>', 0)
                    ->orderByDesc('rating_avg')
                    ->take(10)
                    ->get([
                        'name',
                        'email',
                        'rating_avg',
                        'ratings_count'
                    ]),

            /*
            |--------------------------------------------------------------------------
            | Providers With Highest Ratings Count
            |--------------------------------------------------------------------------
            */

            'most_reviewed_providers' =>
                User::where('role', 'provider')
                    ->orderByDesc('ratings_count')
                    ->take(10)
                    ->get([
                        'name',
                        'email',
                        'ratings_count',
                        'rating_avg'
                    ]),
        ];
    }
}
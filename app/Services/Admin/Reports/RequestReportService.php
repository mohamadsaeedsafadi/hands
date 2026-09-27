<?php

namespace App\Services\Admin\Reports;

use App\Models\ServiceRequest;
use App\Models\ServiceCategory;
use Illuminate\Support\Facades\DB;

class RequestReportService
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
        $requests = $this->filter(
            ServiceRequest::query(),
            $filters
        );

        return [

            /*
            |--------------------------------------------------------------------------
            | Counts
            |--------------------------------------------------------------------------
            */

            'requests_count' =>
                (clone $requests)->count(),

            'completed' =>
                (clone $requests)
                    ->where('status', 'completed')
                    ->count(),

            'pending' =>
                (clone $requests)
                    ->where('status', 'pending')
                    ->count(),

            'cancelled' =>
                (clone $requests)
                    ->where('status', 'cancelled')
                    ->count(),

            /*
            |--------------------------------------------------------------------------
            | Top Categories
            |--------------------------------------------------------------------------
            */

            'top_categories' =>
                ServiceCategory::withCount('requests')
                    ->orderByDesc('requests_count')
                    ->take(10)
                    ->get(),

            /*
            |--------------------------------------------------------------------------
            | Requests Growth
            |--------------------------------------------------------------------------
            */

            'requests_growth' =>
                (clone $requests)
                    ->selectRaw('DATE(created_at) as date')
                    ->selectRaw('COUNT(*) as total')
                    ->groupBy('date')
                    ->orderBy('date')
                    ->get(),

            /*
            |--------------------------------------------------------------------------
            | Average Daily Requests
            |--------------------------------------------------------------------------
            */

            'daily_average' =>
                round(
                    (clone $requests)->count() /
                    max(
                        now()->diffInDays(
                            now()->subMonth()
                        ),
                        1
                    ),
                    2
                ),
        ];
    }
}
<?php

namespace App\Services\Admin\Charts;

use App\Models\ServiceRequest;
use App\Models\ServiceCategory;

class RequestChartService
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

    public function requestsByStatus($filters = [])
    {
        return $this->filter(
            ServiceRequest::query(),
            $filters
        )
        ->select('status')
        ->selectRaw('COUNT(*) as total')
        ->groupBy('status')
        ->get();
    }

    public function topCategories($filters = [])
    {
        return ServiceCategory::withCount('requests')
            ->orderByDesc('requests_count')
            ->take(10)
            ->get();
    }

    public function growth($filters = [])
    {
        return $this->filter(
            ServiceRequest::query(),
            $filters
        )
        ->selectRaw('DATE(created_at) as date')
        ->selectRaw('COUNT(*) as total')
        ->groupBy('date')
        ->orderBy('date')
        ->get();
    }
}
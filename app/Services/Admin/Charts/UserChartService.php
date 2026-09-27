<?php

namespace App\Services\Admin\Charts;

use App\Models\User;
use App\Models\AuditLog;
use App\Models\ServiceOffer;
use App\Models\ServiceRequest;
use Illuminate\Support\Facades\DB;

class UserChartService
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
        return $this->filter(User::query(), $filters)
            ->selectRaw('DATE(created_at) as date')
            ->selectRaw('COUNT(*) as total')
            ->groupBy('date')
            ->orderBy('date')
            ->get();
    }

    public function mostActiveUsers($filters = [])
    {
        return $this->filter(
            AuditLog::query(),
            $filters
        )
        ->select('user_email')
        ->selectRaw('COUNT(*) as total')
        ->groupBy('user_email')
        ->orderByDesc('total')
        ->take(10)
        ->get();
    }

    public function topProviders($filters = [])
    {
        return $this->filter(
            ServiceOffer::query(),
            $filters
        )
        ->select('provider_id')
        ->selectRaw('COUNT(*) as total')
        ->with('provider:id,name,email')
        ->groupBy('provider_id')
        ->orderByDesc('total')
        ->take(10)
        ->get();
    }
}
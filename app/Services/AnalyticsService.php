<?php 
namespace App\Services;

use App\Models\ServiceOffer;
use App\Models\ServiceRequest;
use App\Models\User;

class AnalyticsService
{
    public function usersCount($from = null, $to = null)
    {
        $query = User::query();

        if ($from && $to) {
            $query->whereBetween('created_at', [$from, $to]);
        }

        return $query->count();
    }

    public function requestsCount($from = null, $to = null)
    {
        $query = ServiceRequest::query();

        if ($from && $to) {
            $query->whereBetween('created_at', [$from, $to]);
        }

        return $query->count();
    }

    public function completedRequests($from = null, $to = null)
    {
        $query = ServiceOffer::where('status', 'completed');

        if ($from && $to) {
            $query->whereBetween('updated_at', [$from, $to]);
        }

        return $query->count();
    }

    public function topProviders($from = null, $to = null)
{
    $query = User::where('role','provider');
    if($from && $to) $query->whereBetween('created_at', [$from, $to]);
    return $query->orderByDesc('rating')->limit(5)->get();
}

    public function requestsPerCategory()
    {
        return ServiceRequest::selectRaw('category_id, COUNT(*) as total')
            ->groupBy('category_id')
            ->get();
    }
}
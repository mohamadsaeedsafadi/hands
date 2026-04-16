<?php
namespace App\Repositories;

use App\Models\ServiceRequest;

class ServiceRequestRepository
{
    public function create(array $data)
    {
        return ServiceRequest::create($data);
    }
    public function getRequestsForProvider($provider)
{
    $categoryIds = $provider->categories->pluck('id');

    return (ServiceRequest::whereIn('category_id', $categoryIds)
        ->where('status', 'pending')
        ->paginate(10));
}
}
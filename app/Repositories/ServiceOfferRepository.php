<?php
namespace App\Repositories;

use App\Models\ServiceOffer;

class ServiceOfferRepository
{
    public function getUserOffers($userId, $filters = [])
    {
        $query = ServiceOffer::whereHas('request', fn($q) => $q->where('user_id', $userId));

        if(isset($filters['min_price'])) {
            $query->where('min_price', '>=', $filters['min_price']);
        }
        if(isset($filters['max_price'])) {
            $query->where('max_price', '<=', $filters['max_price']);
        }
        if(isset($filters['is_verified'])) {
            $query->whereHas('provider', fn($q) => $q->where('is_verified', $filters['is_verified']));
        }
        if(isset($filters['rating'])) {
            $query->whereHas('provider', fn($q) => $q->where('rating', '>=', $filters['rating']));
        }

        return $query->get();
    }

    public function create($data)
    {
        return ServiceOffer::create($data);
    }

    public function update(ServiceOffer $offer, $data)
    {
        $offer->update($data);
        return $offer;
    }
}
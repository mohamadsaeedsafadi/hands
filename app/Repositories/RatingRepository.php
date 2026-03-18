<?php

namespace App\Repositories;

use App\Models\Rating;

class RatingRepository
{
    public function create(array $data)
    {
        return Rating::create($data);
    }

    public function findByOffer($offerId)
    {
        return Rating::where('service_offer_id',$offerId)->first();
    }

    public function getProviderRatings($providerId)
    {
        return Rating::where('provider_id',$providerId)->get();
    }
}
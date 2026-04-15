<?php

namespace App\Services;

use App\Repositories\RatingRepository;
use App\Models\ServiceOffer;
use Exception;

class RatingService
{
    protected $ratingRepo;

    public function __construct(RatingRepository $ratingRepo)
    {
        $this->ratingRepo = $ratingRepo;
    }

    public function rateOffer($offerId, $userId, $data)
{
    $offer = ServiceOffer::findOrFail($offerId);

    if ($offer->status !== 'waiting_for_rating') {
        throw new Exception("Service not paid yet");
    }

    if ($offer->serviceRequest->user_id != $userId) {
        throw new Exception("Unauthorized");
    }

    $existing = $this->ratingRepo->findByOffer($offerId);

    if ($existing) {
        throw new Exception("Already rated");
    }

  
    $rating = $this->ratingRepo->create([
        'service_offer_id' => $offerId,
        'user_id' => $userId,
        'provider_id' => $offer->provider_id,
        'rating' => $data['rating'],
        'review' => $data['review'] ?? null
    ]);

    
    $this->updateProviderRating($offer->provider_id);

    
    $offer->update([
        'status' => 'closed'
    ]);

    return $rating;
}
protected function updateProviderRating($providerId)
{
    $ratings = \App\Models\Rating::where('provider_id', $providerId);

    $avg = $ratings->avg('rating');
    $count = $ratings->count();

    \App\Models\User::where('id', $providerId)
        ->update([
            'rating_avg' => round($avg, 2),
            'ratings_count' => $count
        ]);
}
}
<?php
namespace App\Repositories;

use App\Models\ServiceOffer;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ServiceOfferRepository
{
public function getUserOffers($userId, $filters)
{
    $user = Auth::user();

    $lat = $user->lat;
    $lng = $user->lng;

    $query = DB::table('service_offers')
        ->join('users', 'service_offers.provider_id', '=', 'users.id')
        ->join('service_requests', 'service_offers.service_request_id', '=', 'service_requests.id')
        ->where('service_requests.user_id', $userId)
        ->where('service_offers.status', 'pending');

    
    if ($lat && $lng) {
     $query->selectRaw("
    service_offers.id,

    ( 6371 * acos(
        cos(radians(?)) *
        cos(radians(users.lat)) *
        cos(radians(users.lng) - radians(?)) +
        sin(radians(?)) *
        sin(radians(users.lat))
    )) AS distance,

    (
        ( 6371 * acos(
            cos(radians(?)) *
            cos(radians(users.lat)) *
            cos(radians(users.lng) - radians(?)) +
            sin(radians(?)) *
            sin(radians(users.lat))
        )) / 40
    ) * 60 AS eta_minutes
", [$lat, $lng, $lat, $lat, $lng, $lat]);
    } else {
        $query->select('service_offers.id');
    }

   

    if (!empty($filters['min_price'])) {
        $query->where('service_offers.min_price', '>=', $filters['min_price']);
    }

    if (!empty($filters['max_price'])) {
        $query->where('service_offers.max_price', '<=', $filters['max_price']);
    }

    if (!empty($filters['is_verified'])) {
        $query->whereNotNull('users.provider_verified_at');
    }

    if (!empty($filters['rating'])) {
        $query->where('users.rating_avg', '>=', $filters['rating']);
    }



    if (!empty($filters['sort_by']) && is_array($filters['sort_by'])) {

        foreach ($filters['sort_by'] as $sort) {

            switch ($sort) {

                case 'distance':
                    if ($lat && $lng) {
                        $query->orderBy('distance', 'asc');
                    }
                    break;

                case 'price_asc':
                    $query->orderBy('service_offers.min_price', 'asc');
                    break;

                case 'price_desc':
                    $query->orderBy('service_offers.max_price', 'desc');
                    break;

                case 'rating':
                    $query->orderBy('users.rating_avg', 'desc');
                    break;
            }
        }

    } else {
        
        if ($lat && $lng) {
            $query->orderBy('distance', 'asc');
        }
    }


    $results = $query->paginate(10);

    $offers = ServiceOffer::with([
        'provider.profile',
        'provider.portfolios',
        'provider.ratings',
        'serviceRequest'
    ])
    ->whereIn('id', $results->pluck('id'))
    ->get()
    ->keyBy('id');

    $final = $results->map(function ($item) use ($offers) {
        $offer = $offers[$item->id] ?? null;

        if (!$offer) return null;

        $offer->distance = isset($item->distance)
    ? round($item->distance, 2)
    : null;

$offer->eta_minutes = isset($item->eta_minutes)
    ? round($item->eta_minutes)
    : null;

        return $offer;
    })->filter()->values();

    return $final;
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
    public function getNearbyOffers($lat, $lng, $filters = [])
{
    $query = DB::table('service_offers')
        ->join('users', 'service_offers.provider_id', '=', 'users.id')
       ->selectRaw("
    service_offers.*,
    users.name as provider_name,
    users.rating_avg,
    users.lat,
    users.lng,

    ( 6371 * acos(
        cos(radians(?)) *
        cos(radians(users.lat)) *
        cos(radians(users.lng) - radians(?)) +
        sin(radians(?)) *
        sin(radians(users.lat))
    )) AS distance,

    (
        ( 6371 * acos(
            cos(radians(?)) *
            cos(radians(users.lat)) *
            cos(radians(users.lng) - radians(?)) +
            sin(radians(?)) *
            sin(radians(users.lat))
        )) / 40
    ) * 60 AS eta_minutes

", [$lat, $lng, $lat, $lat, $lng, $lat])
        ->where('users.role', 'provider')
        ->whereNotNull('users.lat')
        ->whereNotNull('users.lng')
        ->where('service_offers.status', 'pending'); 
        return $query->paginate(10);
}
public function smartProviders($lat, $lng, $categoryId)
{
    return DB::table('users')
        ->join('provider_categories', 'users.id', '=', 'provider_categories.provider_id')
        ->leftJoin('service_offers', 'users.id', '=', 'service_offers.provider_id')

        ->selectRaw("
            users.id,
            users.name,
            users.lat,
            users.lng,
            users.rating_avg,

            COUNT(CASE WHEN service_offers.status = 'paid' THEN 1 END) as jobs_count,

            ( 6371 * acos(
                cos(radians(?)) *
                cos(radians(users.lat)) *
                cos(radians(users.lng) - radians(?)) +
                sin(radians(?)) *
                sin(radians(users.lat))
            )) AS distance,

            (
                ( 6371 * acos(
                    cos(radians(?)) *
                    cos(radians(users.lat)) *
                    cos(radians(users.lng) - radians(?)) +
                    sin(radians(?)) *
                    sin(radians(users.lat))
                )) / 40
            ) * 60 AS eta_minutes,

            (
                (users.rating_avg * 0.5) +
                (COUNT(CASE WHEN service_offers.status = 'paid' THEN 1 END) * 0.2) -
                ((6371 * acos(
                    cos(radians(?)) *
                    cos(radians(users.lat)) *
                    cos(radians(users.lng) - radians(?)) +
                    sin(radians(?)) *
                    sin(radians(users.lat))
                )) * 0.3)
            ) as score

        ", [
            $lat, $lng, $lat,      // distance
            $lat, $lng, $lat,      // eta
            $lat, $lng, $lat       // score
        ])

        ->where('provider_categories.category_id', $categoryId)
        ->where('users.role', 'provider')

        ->groupBy(
            'users.id',
            'users.name',
            'users.lat',
            'users.lng',
            'users.rating_avg'
        )

        ->orderByDesc('score')
        ->limit(10)
        ->paginate(10);
}
}
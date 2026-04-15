<?php
namespace App\Services;

use App\Models\AuditLog;
use App\Models\ServiceCategory;
use App\Models\ServiceOffer;
use App\Repositories\ServiceOfferRepository;
use App\Services\chat\ChatService;
use Exception;
use Illuminate\Support\Facades\Auth;

class ServiceOfferService
{
    protected $offerRepo;
    public function __construct(
    protected ServiceOfferRepository $repo,
    protected ChatService $chatService,
    protected PaymentService $paymentService
) {}

    public function createOffer($provider, array $data)
    {
        if ($provider->role !== 'provider') {
            throw new Exception('فقط مقدمي الخدمة يمكنهم إرسال عروض.');
        }
        $exists = ServiceOffer::where('service_request_id', $data['service_request_id'])
    ->where('provider_id', $provider->id)
    ->exists();

if ($exists) {
    throw new Exception('لقد قمت بإرسال عرض مسبقاً لهذا الطلب.');
}

        return $this->repo->create([
            'service_request_id' => $data['service_request_id'],
            'provider_id' => $provider->id,
            'min_price' => $data['min_price'],
            'max_price' => $data['max_price'],
            'message' => $data['message'] ?? null
        ]);
    }
   
  public function assignCategories($provider, array $categoryIds)
{
    foreach ($categoryIds as $id) {

        $category = ServiceCategory::findOrFail($id);

        if ($category->parent_id === null) {
            throw new Exception("يجب اختيار فئة فرعية فقط.");
        }
    }

    $provider->categories()->sync($categoryIds);
    $provider->update([
        'provider_verified_at' => null
    ]);
}
public function acceptOffer(ServiceOffer $offer)
{
    $offer->load(['serviceRequest', 'provider']);

    $user = Auth::user();

    if ($offer->serviceRequest->user_id !== $user->id) {
        throw new \Exception("Unauthorized");
    }

    if ($offer->serviceRequest->status !== 'pending') {
        throw new \Exception("Request already accepted");
    }

    $offer->update(['status' => 'accepted']);
    $offer->serviceRequest->update(['status' => 'accepted']);
 ServiceOffer::where('service_request_id', $offer->service_request_id)
        ->where('id', '!=', $offer->id)
        ->update(['status' => 'rejected']);
  $this->chatService->createConversation(
    $user,
    $offer->provider,
    $offer->serviceRequest
);

    return $offer;
}
    public function completeService($provider, $offerId, $finalPrice)
    {
        $offer = ServiceOffer::findOrFail($offerId);
if ($offer->status !== 'accepted' && $offer->status !== 'in_progress') {
    throw new \Exception("Invalid state transition");
}
        if($offer->provider_id !== $provider->id ) {
            throw new \Exception("Unauthorized");
        }

        if($finalPrice < $offer->min_price || $finalPrice > $offer->max_price) {
          
            $this->repo->update($offer, [
                'final_price' => $finalPrice,
                'status' => 'awaiting_user_approval'
            ]);
        } else {
           
            $this->repo->update($offer, [
                'final_price' => $finalPrice,
                'status' => 'awaiting_payment'
            ]);
        }

        return $offer;
    }
    public function userApprovePrice($user, $offerId)
    {
        $offer = ServiceOffer::findOrFail($offerId);

        if($offer->serviceRequest->user_id !== $user->id || $offer->status !== 'awaiting_user_approval') {
            throw new \Exception("Unauthorized or invalid state");
        }

        $this->repo->update($offer, ['status' => 'awaiting_payment']);
        return $offer;
    }

    public function closeOffer($offer)
    {
        $this->repo->update($offer, ['status' => 'closed']);
        return $offer;
    }
    public function userRejectPrice($user, $offerId)
{
    $offer = ServiceOffer::findOrFail($offerId);

    if (
        $offer->serviceRequest->user_id !== $user->id ||
        $offer->status !== 'awaiting_user_approval'
    ) {
        throw new \Exception("Unauthorized or invalid state");
    }

    $this->repo->update($offer, [
        'status' => 'price_rejected'
    ]);

    return $offer;
}
public function updateFinalPrice($provider, $offerId, $newPrice)
{
    $offer = ServiceOffer::findOrFail($offerId);

    if ($offer->provider_id !== $provider->id) {
        throw new \Exception("Unauthorized");
    }

    if ($offer->status !== 'price_rejected') {
        throw new \Exception("Cannot update price now");
    }


   if ($offer->final_price !== null && $newPrice >= $offer->final_price) {
    throw new \Exception("New price must be lower than previous price");
}

   
    if ($newPrice < $offer->min_price || $newPrice > $offer->max_price) {

        $this->repo->update($offer, [
            'final_price' => $newPrice,
            'status' => 'awaiting_user_approval'
        ]);

    } else {

        $this->repo->update($offer, [
            'final_price' => $newPrice,
            'status' => 'awaiting_payment'
        ]);
    }

    return $offer;
}
public function startService($provider, $offerId)
{
    $offer = ServiceOffer::findOrFail($offerId);

    if ($offer->provider_id !== $provider->id) {
        throw new \Exception("Unauthorized");
    }

    if ($offer->status !== 'accepted') {
        throw new \Exception("Service cannot be started");
    }

    $this->repo->update($offer, [
        'status' => 'in_progress'
    ]);

    return $offer;
}
public function myoffer($request){
    $userid = Auth::user()->id;
 return $this->repo->getUserOffers($userid, $request->all());
}
public function nearbyOffers($user, $filters)
{
    if (!$user->lat || !$user->lng) {
        throw new \Exception("User location not set");
    }

    return $this->repo->getNearbyOffers(
        $user->lat,
        $user->lng,
        $filters
    );
}
public function recommendProviders($user, $categoryId)
{
    if (!$user->lat || !$user->lng) {
        throw new \Exception("Location required");
    }

    return $this->repo->smartProviders(
        $user->lat,
        $user->lng,
        $categoryId
    );
}
}
<?php

namespace App\Services;

use App\Models\ServiceOffer;
use App\Models\Payment;
use Stripe\StripeClient;
use Exception;
use App\Services\ShamCashService;
class PaymentService
{
   

public function pay(ServiceOffer $offer, $price)
{
    if ($offer->status !== 'awaiting_payment') {
        throw new Exception("Offer not ready for payment");
    }

    if ($price != $offer->final_price) {
        throw new Exception("ادخل المبلغ الصحيح");
    }

   
    $payment = Payment::create([
        'service_offer_id' => $offer->id,
        'service_request_id' => $offer->service_request_id,
        'user_id' => $offer->serviceRequest->user_id,
        'provider_id' => $offer->provider_id,
        'amount_syp' => $price,
        'reference' => 'PAY-' . $offer->id . '-' . rand(100,999),
        'status' => 'pending'
    ]);

    return $payment;
}
}

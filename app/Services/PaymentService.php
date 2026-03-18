<?php

namespace App\Services;

use App\Models\ServiceOffer;
use App\Models\Payment;
use Stripe\StripeClient;
use Exception;

class PaymentService
{
    public function pay(ServiceOffer $offer ,$price)
    {
       if ($offer->status !== 'awaiting_payment') {
            throw new Exception("Offer not ready for payment");
       }
      
      if($price !=$offer->final_price){
        throw new Exception("ادخل المبلغ المطلوب للدفع");
      }
        $payment = Payment::create([
            'service_offer_id' => $offer->id,
            'service_request_id' => $offer->service_request_id,
            'user_id' => $offer->serviceRequest->user_id,
            'provider_id' => $offer->provider_id,
            'stripe_session_id' => $offer->id,
            'amount_syp' => $offer->final_price,
            'amount_usd' => $offer->final_price/10000,
            'status' => 'paid'
        ]);

    
        $offer->update([
            'status' => 'waiting_for_rating'
        ]);

        return $payment;
    }
}

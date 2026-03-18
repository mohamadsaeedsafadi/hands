<?php
namespace App\Repositories;

use App\Models\Payment;

class PaymentRepository
{
    public function create(array $data)
    {
        return Payment::create($data);
    }

    public function findBySessionId($sessionId)
    {
        return Payment::where('stripe_session_id', $sessionId)->first();
    }

    public function markAsPaid($payment)
    {
        $payment->update(['status' => 'paid']);
        return $payment;
    }
}
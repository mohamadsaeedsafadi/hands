<?php
namespace App\Services;

use App\Models\WithdrawalRequest;

class WithdrawalService{
public function createRequest($provider, array $data)
{
    $amount = $data['amount'];

    if ($amount < 300) {
        throw new \Exception('الحد الأدنى للسحب 300 ليرة');
    }

    if ($amount > 10000) {
        throw new \Exception('الحد الأعلى للسحب 10000 ليرة');
    }

    if ($provider->wallet_balance < $amount) {
        throw new \Exception('الرصيد غير كاف');
    }
$exists = WithdrawalRequest::where('provider_id', $provider->id)
    ->where('status', 'pending')
    ->exists();

if ($exists) {
    throw new \Exception('لديك طلب سحب قيد المعالجة');
}
    $commission = $amount * 0.01;

    $finalAmount = $amount - $commission;

    return WithdrawalRequest::create([
        'provider_id' => $provider->id,
        'amount' => $amount,
        'commission' => $commission,
        'final_amount' => $finalAmount,
        'shamcash_account' => $data['shamcash_account'],
        'status' => 'pending'
    ]);
}


}
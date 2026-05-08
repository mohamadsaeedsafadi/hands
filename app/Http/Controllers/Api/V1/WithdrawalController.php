<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\WithdrawalRequest;
use App\Services\WithdrawalService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WithdrawalController extends Controller
{
    public function store(Request $request)
{
    $request->validate([
        'amount' => 'required|numeric|min:300|max:10000',
        'shamcash_account' => 'required|string'
    ]);

    $withdrawal = app(WithdrawalService::class)
        ->createRequest($request->user(), $request->all());

    return response()->json([
        'message' => 'تم إرسال طلب السحب',
        'data' => $withdrawal
    ]);
}
public function pending()
{
    return WithdrawalRequest::where('status', 'pending')
        ->latest()
        ->get();
}

public function approve($id, Request $request)
{
    $withdrawal = WithdrawalRequest::findOrFail($id);

    if ($withdrawal->status !== 'pending') {
        throw new \Exception('تمت معالجة الطلب مسبقاً');
    }

    $provider = $withdrawal->provider;

    if ($provider->wallet_balance < $withdrawal->amount) {
        throw new \Exception('الرصيد غير كاف');
    }

    $provider->decrement(
        'wallet_balance',
        $withdrawal->amount
    );

    $withdrawal->update([
        'status' => 'approved',
        'cashier_id' => $request->user()->id,
        'processed_at' => now()
    ]);

    return response()->json([
        'message' => 'تم تحويل المبلغ بنجاح'
    ]);
}

public function reject($id, Request $request)
{
    $withdrawal = WithdrawalRequest::findOrFail($id);

    $withdrawal->update([
        'status' => 'rejected',
        'cashier_id' => $request->user()->id,
        'processed_at' => now(),
        'note' => $request->note
    ]);

    return response()->json([
        'message' => 'تم رفض الطلب'
    ]);
}
public function my(){
    $x= Auth::user()->id;
   return  WithdrawalRequest::where('provider_id',$x)->paginate(10);

}
}

<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\ServiceOffer;
use App\Services\chat\ChatService;
use App\Services\ServiceOfferService;
use Illuminate\Http\Request;
use App\Services\PaymentService;
use Illuminate\Support\Facades\Auth;
use Stripe\PaymentIntent;
use Stripe\PaymentMethod;
use Stripe\Stripe;

class ServiceOfferController extends Controller
{
    protected $service;
    protected $chatService;
    protected $pay;
    public function __construct(ServiceOfferService $service, ChatService $chatService,PaymentService $pay)
    {
        $this->service = $service;
        $this->pay =$pay;
      $this->chatService = $chatService;
    }

    public function store(Request $request)
    {
        $request->validate([
            'service_request_id' => 'required|exists:service_requests,id',
            'min_price' => 'required|numeric',
            'max_price' => 'required|numeric|gte:min_price',
            'message' => 'nullable|string'
        ]);

        $offer = $this->service->createOffer(
            $request->user(),
            $request->all()
        );

        return response()->json($offer);
    }
    public function accept(ServiceOffer $offer)
    {
        $offer2 = $this->service->acceptOffer($offer);
        return response()->json($offer2);
    }
public function updateCategories(Request $request)
{
    $request->validate([
        'categories' => 'required|array',
        'categories.*' => 'exists:service_categories,id'
    ]);

    $this->service->assignCategories(
        $request->user(),
        $request->categories
    );

    return response()->json(['message' => 'تم تحديث المجالات']);
}
public function complete(Request $request, $id)
    {
        $request->validate(['final_price' => 'required|numeric']);
        $offer = $this->service->completeService($request->user(), $id, $request->final_price);
        return response()->json($offer);
    }

    // موافقة المستخدم على السعر الجديد
    public function approvePrice(Request $request, $id)
    {
        $offer = $this->service->userApprovePrice($request->user(), $id);
        return response()->json($offer);
    }



   public function pay($offer,Request $request)
{
$request->validate([
    'amount' => 'required|numeric|min:1,max:9000000',
    'cvc'=>'required|numeric|digits:3',
    'account_number'=>'required|numeric|digits:16'
]);
    $price = $request->amount;
    $offer2 = ServiceOffer::findOrFail($offer);
    $payment = app(\App\Services\PaymentService::class)
        ->pay($offer2,$price);

    return response()->json([
        'message' => 'Payment successful',
        'payment' => $payment
    ]);
}
   public function rejectPrice(Request $request, $id)
{
    $offer = $this->service->userRejectPrice($request->user(), $id);
    return response()->json($offer);
}
public function updatePrice(Request $request, $id)
{
    $request->validate([
        'price' => 'required|numeric'
    ]);

    $offer = $this->service->updateFinalPrice(
        $request->user(),
        $id,
        $request->price
    );

    return response()->json($offer);
}
}


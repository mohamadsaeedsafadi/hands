<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Responses\ApiResponse;
use App\Models\ServiceOffer;
use App\Services\chat\ChatService;
use App\Services\ServiceOfferService;
use Illuminate\Http\Request;
use App\Services\PaymentService;
use App\Services\UserService;
use Illuminate\Support\Facades\Auth;
use Stripe\PaymentIntent;
use Stripe\PaymentMethod;
use Stripe\Stripe;

class ServiceOfferController extends Controller
{
    protected $service;
    protected $chatService;
    protected $pay;
    protected $userser;
    public function __construct(ServiceOfferService $service, ChatService $chatService,PaymentService $pay ,UserService $userser)
    {
        $this->service = $service;
        $this->pay =$pay;
      $this->chatService = $chatService;
      $this->userser= $userser;
    }

    public function store(Request $request)
    {
        $request->validate([
            'service_request_id' => 'required|exists:service_requests,id',
            'min_price' => 'required|numeric|max:10000000|lte:max_price',
            'max_price' => 'required|numeric|gte:min_price|max:100000000',
            'message' => 'nullable|string'
        ]);

        $offer = $this->service->createOffer(
            $request->user(),
            $request->all()
        );

       
        return ApiResponse::success(
    $offer
);
    }
    public function accept(ServiceOffer $offer)
    {
        $offer2 = $this->service->acceptOffer($offer);
        
        return ApiResponse::success(
    $offer2
);
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

   return ApiResponse::success(null, 'تم تحديث المجالات');
}
public function complete(Request $request, $id)
    {
        $request->validate(['final_price' => 'required|numeric']);
        $offer = $this->service->completeService($request->user(), $id, $request->final_price);
        return ApiResponse::success($offer, 'Service completed');
    }

    
    public function approvePrice(Request $request, $id)
    {
        $offer = $this->service->userApprovePrice($request->user(), $id);
        return ApiResponse::success($offer);
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

    

    return ApiResponse::success($payment);
}
   public function rejectPrice(Request $request, $id)
{
    $offer = $this->service->userRejectPrice($request->user(), $id);
    
    return ApiResponse::success($offer);
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
return ApiResponse::success($offer);
}
public function myoffer(Request $request)
{
    $request->validate([
    'min_price' => 'nullable|numeric',
    'max_price' => 'nullable|numeric',
    'rating' => 'nullable|numeric|min:1|max:5',
    'lat' => 'nullable|numeric',
    'lng' => 'nullable|numeric',
    'radius' => 'nullable|numeric',
    'max_eta' => 'nullable|numeric'
]);
    $offers = $this->service->myoffer($request);

    
    return ApiResponse::success(
    $offers
);
}
public function nearby(Request $request)
{
    $radius = $request->radius ?? 10;

  
    return ApiResponse::success(
    $this->userser->nearbyProviders($request->user(), $radius)
);
}
public function nearbyOffers(Request $request)
{
    return ApiResponse::success(
        $this->service->nearbyOffers(
            $request->user(),
            $request->all()
        )
    );
}
public function recommend(Request $request)
{
    $request->validate([
        'category_id' => 'required|exists:service_categories,id'
    ]);

    
     return ApiResponse::success(
     $this->service->recommendProviders(
            $request->user(),
            $request->category_id
        )
);
}
}


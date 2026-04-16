<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Responses\ApiResponse;
use App\Services\ServiceRequestService;
use Illuminate\Http\Request;
class ServiceRequestController extends Controller
{
    protected $service;

    public function __construct(ServiceRequestService $service)
    {
        $this->service = $service;
    }

    public function store(Request $request)
    {
        $request->validate([
            'category_id' => 'required|exists:service_categories,id',
            'answers' => 'required|array',
'images' => 'nullable|array',
'images.*' => 'image|mimes:jpg,png,jpeg,gif,svg|max:2048'
        ]);

        $serviceRequest = $this->service->createRequest(
            $request->user(),
            $request->all()
        );

       
        return ApiResponse::success($serviceRequest);
    }
    public function availableRequests(Request $request)
{
    
       return ApiResponse::success(
     $this->service->getAvailableRequestsForProvider(
            $request->user()
        )
);
}

}
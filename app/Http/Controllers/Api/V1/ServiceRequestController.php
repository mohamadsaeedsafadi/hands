<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
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
            'answers' => 'required|array'
        ]);

        $serviceRequest = $this->service->createRequest(
            $request->user(),
            $request->all()
        );

        return response()->json($serviceRequest);
    }
    public function availableRequests(Request $request)
{
    return response()->json(
        $this->service->getAvailableRequestsForProvider(
            $request->user()
        )
    );
}

}
<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Responses\ApiResponse;
use App\Repositories\VerificationRepository;
use App\Services\VerificationService;
use Illuminate\Http\Request;
class AdminVerificationController extends Controller
{
    public function __construct(protected VerificationRepository $repo, protected VerificationService $service) {}

    public function pending()
    {
        
    return ApiResponse::success(
    $this->repo->getAllPending()
);
    }

    
    public function approve($id)
    {
       
         return ApiResponse::success(
    $this->service->approve($id)
);
    }

    
    public function reject(Request $request, $id)
    {
     
            return ApiResponse::success(
  $this->service->reject($id, $request->note)
);
    }
}
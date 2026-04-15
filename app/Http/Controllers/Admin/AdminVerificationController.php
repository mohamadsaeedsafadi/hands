<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Repositories\VerificationRepository;
use App\Services\VerificationService;
use Illuminate\Http\Request;
class AdminVerificationController extends Controller
{
    public function __construct(protected VerificationRepository $repo, protected VerificationService $service) {}

    public function pending()
    {
        return response()->json(
            $this->repo->getAllPending()
        );
    }

    
    public function approve($id)
    {
        return response()->json(
            $this->service->approve($id)
        );
    }

    
    public function reject(Request $request, $id)
    {
        return response()->json(
            $this->service->reject($id, $request->note)
        );
    }
}
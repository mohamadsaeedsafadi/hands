<?php

namespace App\Http\Controllers\Api\V1;
use App\Http\Controllers\Controller;
use App\Services\VerificationService;
use Illuminate\Http\Request;
class VerificationController extends Controller
{
    public function __construct(protected VerificationService $service) {}

    public function request(Request $request)
    {
        $data = $request->validate([
            'id_document' => 'required|file|image',
            'work_document' => 'required|file|image'
        ]);

        return response()->json(
            $this->service->requestVerification($request->user(), $data)
        );
    }

    public function pending()
    {
        return response()->json(
            $this->service->pendingRequests()
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
        $request->validate([
            'note' => 'required|string'
        ]);

        return response()->json(
            $this->service->reject($id, $request->note)
        );
    }
}
<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\ReportRequest;
use App\Http\Responses\ApiResponse;
use App\Services\ReportService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReportController extends Controller
{
    protected $service;

    public function __construct(ReportService $service)
    {
        $this->service = $service;
    }

    public function store(ReportRequest $request)
    {
        $report = $this->service->createReport(
            Auth::user(),
            $request->validated()
        );

        
        return ApiResponse::success(
    $report
);
    }

    public function index(Request $request)
    {
        $fil= $this->service->filterReports($request->all());
        return ApiResponse::success(
    $fil
);
    }

    public function updateStatus($id, Request $request)
    {
        $upd= $this->service->updateStatus($id, $request->status);
        return ApiResponse::success(
    $upd
);
    }
}


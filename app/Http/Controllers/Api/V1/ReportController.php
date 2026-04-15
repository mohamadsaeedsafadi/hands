<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\ReportRequest;
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

        return response()->json($report);
    }

    public function index(Request $request)
    {
        return $this->service->filterReports($request->all());
    }

    public function updateStatus($id, Request $request)
    {
        return $this->service->updateStatus($id, $request->status);
    }
}


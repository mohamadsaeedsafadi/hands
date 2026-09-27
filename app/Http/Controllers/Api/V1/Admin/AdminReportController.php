<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

use App\Services\Admin\Reports\FinancialReportService;
use App\Services\Admin\Reports\UserReportService;
use App\Services\Admin\Reports\RequestReportService;
use App\Services\Admin\Reports\WithdrawalReportService;
use App\Services\Admin\Reports\AuditReportService;
use App\Services\Admin\Reports\TicketReportService;
use App\Services\Admin\Reports\AnalyticsReportService;

class AdminReportController extends Controller
{
    public function __construct(
        private FinancialReportService $financial,
        private UserReportService $users,
        private RequestReportService $requests,
        private WithdrawalReportService $withdrawals,
        private AuditReportService $audit,
        private TicketReportService $tickets,
        private AnalyticsReportService $analytics,
    ) {}

    /*
    |--------------------------------------------------------------------------
    | Financial Report PDF
    |--------------------------------------------------------------------------
    */

    public function financialPdf(Request $request)
    {
        $data = $this->financial->report($request->all());

        $pdf = Pdf::loadView(
            'reports.financial',
            $data
        );
$pdf->setPaper('A4', 'portrait');
        return $pdf->download('financial-report.pdf');
    }

    /*
    |--------------------------------------------------------------------------
    | Users Report PDF
    |--------------------------------------------------------------------------
    */

    public function usersPdf(Request $request)
    {
        $data = $this->users->report($request->all());

        $pdf = Pdf::loadView(
            'reports.users',
            $data
        );
$pdf->setPaper('A4', 'portrait');
        return $pdf->download('users-report.pdf');
    }

    /*
    |--------------------------------------------------------------------------
    | Requests Report PDF
    |--------------------------------------------------------------------------
    */

    public function requestsPdf(Request $request)
    {
        $data = $this->requests->report($request->all());

        $pdf = Pdf::loadView(
            'reports.requests',
            $data
        );
$pdf->setPaper('A4', 'portrait');
        return $pdf->download('requests-report.pdf');
    }

    /*
    |--------------------------------------------------------------------------
    | Withdrawals Report PDF
    |--------------------------------------------------------------------------
    */

    public function withdrawalsPdf(Request $request)
    {
        $data = $this->withdrawals->report($request->all());

        $pdf = Pdf::loadView(
            'reports.withdrawals',
            $data
        );
$pdf->setPaper('A4', 'portrait');
        return $pdf->download('withdrawals-report.pdf');
    }

    /*
    |--------------------------------------------------------------------------
    | Audit Report PDF
    |--------------------------------------------------------------------------
    */

    public function auditPdf(Request $request)
    {
        $data = $this->audit->report($request->all());

        $pdf = Pdf::loadView(
            'reports.audit',
            $data
        );
$pdf->setPaper('A4', 'portrait');
        return $pdf->download('audit-report.pdf');
    }

    /*
    |--------------------------------------------------------------------------
    | Tickets Report PDF
    |--------------------------------------------------------------------------
    */

    public function ticketsPdf(Request $request)
    {
        $data = $this->tickets->report(
            $request->all()
        );

        $pdf = Pdf::loadView(
            'reports.tickets',
            $data
        );
$pdf->setPaper('A4', 'portrait');
        return $pdf->download('tickets-report.pdf');
    }

    /*
    |--------------------------------------------------------------------------
    | Analytics Report PDF
    |--------------------------------------------------------------------------
    */

  public function analyticsPdf(Request $request)
{
    $data = $this->analytics->analytics(
        $request->all()
    );

    $pdf = Pdf::loadView(
        'reports.analytics',
        $data
    );

    $pdf->setPaper('A4', 'portrait');

    return $pdf->download('analytics-report.pdf');
}
}
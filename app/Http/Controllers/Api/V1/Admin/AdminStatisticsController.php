<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Services\Admin\Charts\UserChartService;
use App\Services\Admin\Charts\FinancialChartService;
use App\Services\Admin\Charts\RequestChartService;
use App\Services\Admin\Charts\TicketChartService;
use App\Services\Admin\Charts\WithdrawalChartService;
use App\Services\Admin\Charts\DashboardChartService;

class AdminStatisticsController extends Controller
{
    public function __construct(
        private DashboardChartService $dashboard,
        private UserChartService $users,
        private FinancialChartService $financial,
        private RequestChartService $requests,
        private TicketChartService $tickets,
        private WithdrawalChartService $withdrawals,
    ) {}

    public function dashboard(Request $request)
    {
        $filters = $request->all();

        return response()->json([

            /*
            |--------------------------------------------------------------------------
            | KPI CARDS
            |--------------------------------------------------------------------------
            */

            'overview' =>
                $this->dashboard->overview($filters),

            /*
            |--------------------------------------------------------------------------
            | USERS
            |--------------------------------------------------------------------------
            */

            'users_growth' =>
                $this->users->growth($filters),

            'top_active_users' =>
                $this->users->mostActiveUsers($filters),

            'top_providers' =>
                $this->users->topProviders($filters),

            /*
            |--------------------------------------------------------------------------
            | FINANCIAL
            |--------------------------------------------------------------------------
            */

            'revenue_growth' =>
                $this->financial->revenueGrowth($filters),

            'payments_status' =>
                $this->financial->paymentsByStatus($filters),

            /*
            |--------------------------------------------------------------------------
            | REQUESTS
            |--------------------------------------------------------------------------
            */

            'requests_status' =>
                $this->requests->requestsByStatus($filters),

            'top_categories' =>
                $this->requests->topCategories($filters),

            'requests_growth' =>
                $this->requests->growth($filters),

            /*
            |--------------------------------------------------------------------------
            | TICKETS
            |--------------------------------------------------------------------------
            */

            'tickets_status' =>
                $this->tickets->ticketsByStatus($filters),

            'tickets_growth' =>
                $this->tickets->growth($filters),

            /*
            |--------------------------------------------------------------------------
            | WITHDRAWALS
            |--------------------------------------------------------------------------
            */

            'withdrawals_growth' =>
                $this->withdrawals->growth($filters),
        ]);
    }
}
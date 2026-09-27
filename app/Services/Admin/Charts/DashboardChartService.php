<?php

namespace App\Services\Admin\Charts;

use App\Models\User;
use App\Models\Payment;
use App\Models\ServiceRequest;
use App\Models\Ticket;
use App\Models\WithdrawalRequest;

class DashboardChartService
{
    protected function filter($query, $filters)
    {
        if (!empty($filters['from'])) {
            $query->whereDate('created_at', '>=', $filters['from']);
        }

        if (!empty($filters['to'])) {
            $query->whereDate('created_at', '<=', $filters['to']);
        }

        return $query;
    }

    public function overview($filters = [])
    {
        return [

            'users' =>
                $this->filter(User::query(), $filters)->count(),

            'providers' =>
                $this->filter(
                    User::where('role', 'provider'),
                    $filters
                )->count(),

            'payments' =>
                $this->filter(
                    Payment::where('status', 'paid'),
                    $filters
                )->sum('amount_syp'),

            'requests' =>
                $this->filter(
                    ServiceRequest::query(),
                    $filters
                )->count(),

            'tickets' =>
                $this->filter(
                    Ticket::query(),
                    $filters
                )->count(),

            'withdrawals' =>
                $this->filter(
                    WithdrawalRequest::where('status', 'approved'),
                    $filters
                )->sum('amount'),
        ];
    }
}
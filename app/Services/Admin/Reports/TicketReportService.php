<?php

namespace App\Services\Admin\Reports;

use App\Models\Ticket;
use Illuminate\Support\Facades\DB;

class TicketReportService
{
    protected function filter($query, $filters)
    {
        if (!empty($filters['from'])) {

            $query->whereDate(
                'created_at',
                '>=',
                $filters['from']
            );
        }

        if (!empty($filters['to'])) {

            $query->whereDate(
                'created_at',
                '<=',
                $filters['to']
            );
        }

        return $query;
    }

    public function report($filters = [])
    {
        $tickets = $this->filter(
            Ticket::query(),
            $filters
        );

        return [

            /*
            |--------------------------------------------------------------------------
            | General Statistics
            |--------------------------------------------------------------------------
            */

            'total_tickets' =>
                (clone $tickets)->count(),

            'open_tickets' =>
                (clone $tickets)
                    ->where('status', 'open')
                    ->count(),

            'pending_tickets' =>
                (clone $tickets)
                    ->where('status', 'pending')
                    ->count(),

            'closed_tickets' =>
                (clone $tickets)
                    ->where('status', 'closed')
                    ->count(),

            /*
            |--------------------------------------------------------------------------
            | Resolution Rate
            |--------------------------------------------------------------------------
            */

            'resolved_percentage' =>
                $this->calculateResolvedPercentage(
                    clone $tickets
                ),

            /*
            |--------------------------------------------------------------------------
            | Daily Average
            |--------------------------------------------------------------------------
            */

            'average_daily_tickets' =>
                round(
                    (clone $tickets)->count()
                    /
                    max(
                        1,
                        now()->diffInDays(
                            (clone $tickets)->min('created_at') ?? now()
                        )
                    ),
                    2
                ),

            /*
            |--------------------------------------------------------------------------
            | Tickets By Status
            |--------------------------------------------------------------------------
            */

            'tickets_by_status' =>
                (clone $tickets)
                    ->select('status')
                    ->selectRaw('COUNT(*) as total')
                    ->groupBy('status')
                    ->get(),

            /*
            |--------------------------------------------------------------------------
            | Daily Growth
            |--------------------------------------------------------------------------
            */

            'tickets_growth' =>
                (clone $tickets)
                    ->selectRaw('DATE(created_at) as date')
                    ->selectRaw('COUNT(*) as total')
                    ->groupBy('date')
                    ->orderBy('date')
                    ->get(),

            /*
            |--------------------------------------------------------------------------
            | Most Reporting Users
            |--------------------------------------------------------------------------
            */

            'most_reporting_users' =>
                (clone $tickets)
                    ->select('user_id')
                    ->selectRaw('COUNT(*) as total')
                    ->with('user:id,name,email')
                    ->groupBy('user_id')
                    ->orderByDesc('total')
                    ->take(10)
                    ->get(),

            /*
            |--------------------------------------------------------------------------
            | Users With Open Tickets
            |--------------------------------------------------------------------------
            */

            'users_with_open_tickets' =>
                (clone $tickets)
                    ->where('status', 'open')
                    ->select('user_id')
                    ->selectRaw('COUNT(*) as total')
                    ->with('user:id,name,email')
                    ->groupBy('user_id')
                    ->orderByDesc('total')
                    ->take(10)
                    ->get(),

            /*
            |--------------------------------------------------------------------------
            | Peak Ticket Day
            |--------------------------------------------------------------------------
            */

            'peak_ticket_day' =>
                (clone $tickets)
                    ->selectRaw('DATE(created_at) as date')
                    ->selectRaw('COUNT(*) as total')
                    ->groupBy('date')
                    ->orderByDesc('total')
                    ->first(),

            /*
            |--------------------------------------------------------------------------
            | Latest Tickets
            |--------------------------------------------------------------------------
            */

            'latest_tickets' =>
                (clone $tickets)
                    ->with([
                        'user:id,name,email'
                    ])
                    ->latest()
                    ->take(20)
                    ->get(),

            /*
            |--------------------------------------------------------------------------
            | Oldest Open Tickets
            |--------------------------------------------------------------------------
            */

            'oldest_open_tickets' =>
                (clone $tickets)
                    ->where('status', 'open')
                    ->with([
                        'user:id,name,email'
                    ])
                    ->oldest()
                    ->take(10)
                    ->get(),
        ];
    }

    protected function calculateResolvedPercentage($tickets)
    {
        $total = (clone $tickets)->count();

        if ($total == 0) {
            return 0;
        }

        $closed = (clone $tickets)
            ->where('status', 'closed')
            ->count();

        return round(($closed / $total) * 100, 2);
    }
}
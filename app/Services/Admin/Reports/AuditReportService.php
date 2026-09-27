<?php

namespace App\Services\Admin\Reports;

use App\Models\AuditLog;

class AuditReportService
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

    public function report($filters = [])
    {
        $logs = $this->filter(
            AuditLog::query(),
            $filters
        );

        return [

            /*
            |------------------------------------------------------------------
            | General Statistics
            |------------------------------------------------------------------
            */

            'logs_count' =>
                (clone $logs)->count(),

            'critical_logs' =>
                (clone $logs)
                    ->where('severity', 'critical')
                    ->count(),

            'warning_logs' =>
                (clone $logs)
                    ->where('severity', 'warning')
                    ->count(),

            'info_logs' =>
                (clone $logs)
                    ->where('severity', 'info')
                    ->count(),

            'failed_operations' =>
                (clone $logs)
                    ->where('success', false)
                    ->count(),

            'successful_operations' =>
                (clone $logs)
                    ->where('success', true)
                    ->count(),

            /*
            |------------------------------------------------------------------
            | Latest Logs
            |------------------------------------------------------------------
            */

            'latest_logs' =>
                (clone $logs)
                    ->latest()
                    ->take(50)
                    ->get(),

            /*
            |------------------------------------------------------------------
            | Most Common Actions
            |------------------------------------------------------------------
            */

            'most_common_actions' =>
                (clone $logs)
                    ->select('action')
                    ->selectRaw('COUNT(*) as total')
                    ->groupBy('action')
                    ->orderByDesc('total')
                    ->take(10)
                    ->get(),

            /*
            |------------------------------------------------------------------
            | Most Active Users
            |------------------------------------------------------------------
            */

            'most_active_users' =>
                (clone $logs)
                    ->select('user_email')
                    ->selectRaw('COUNT(*) as total')
                    ->groupBy('user_email')
                    ->orderByDesc('total')
                    ->take(10)
                    ->get(),

            /*
            |------------------------------------------------------------------
            | Most Dangerous IPs
            |------------------------------------------------------------------
            */

            'most_dangerous_ips' =>
                (clone $logs)
                    ->select('ip')
                    ->selectRaw('COUNT(*) as total')
                    ->groupBy('ip')
                    ->orderByDesc('total')
                    ->take(10)
                    ->get(),

            /*
            |------------------------------------------------------------------
            | Event Types
            |------------------------------------------------------------------
            */

            'events_by_type' =>
                (clone $logs)
                    ->select('event_type')
                    ->selectRaw('COUNT(*) as total')
                    ->groupBy('event_type')
                    ->orderByDesc('total')
                    ->get(),

            /*
            |------------------------------------------------------------------
            | Logs By Severity
            |------------------------------------------------------------------
            */

            'severity_distribution' =>
                (clone $logs)
                    ->select('severity')
                    ->selectRaw('COUNT(*) as total')
                    ->groupBy('severity')
                    ->get(),

            /*
            |------------------------------------------------------------------
            | Failed Login Attempts
            |------------------------------------------------------------------
            */

            'failed_logins' =>
                (clone $logs)
                    ->where('action', 'login')
                    ->where('success', false)
                    ->latest()
                    ->take(20)
                    ->get(),

            /*
            |------------------------------------------------------------------
            | Daily System Activity
            |------------------------------------------------------------------
            */

            'daily_activity' =>
                (clone $logs)
                    ->selectRaw('DATE(created_at) as date')
                    ->selectRaw('COUNT(*) as total')
                    ->groupBy('date')
                    ->orderBy('date')
                    ->get(),
        ];
    }
}
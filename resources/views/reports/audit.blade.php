@extends('reports.layout')

@section('content')

<h1>System Monitoring & Audit Report</h1>

<div class="card">
Total Logs:
{{ $logs_count }}
</div>

<div class="card">
Critical Logs:
{{ $critical_logs }}
</div>

<div class="card">
Warnings:
{{ $warning_logs }}
</div>

<div class="card">
Info Logs:
{{ $info_logs }}
</div>

<div class="card">
Failed Operations:
{{ $failed_operations }}
</div>

<div class="card">
Successful Operations:
{{ $successful_operations }}
</div>

<h2>Most Common Actions</h2>

<table>
<thead>
<tr>
<th>Action</th>
<th>Total</th>
</tr>
</thead>

<tbody>

@foreach($most_common_actions as $item)

<tr>
<td>{{ $item->action }}</td>
<td>{{ $item->total }}</td>
</tr>

@endforeach

</tbody>
</table>

<h2>Most Active Users</h2>

<table>
<thead>
<tr>
<th>Email</th>
<th>Operations</th>
</tr>
</thead>

<tbody>

@foreach($most_active_users as $item)

<tr>
<td>{{ $item->user_email }}</td>
<td>{{ $item->total }}</td>
</tr>

@endforeach

</tbody>
</table>

<h2>Most Active IPs</h2>

<table>
<thead>
<tr>
<th>IP</th>
<th>Requests</th>
</tr>
</thead>

<tbody>

@foreach($most_dangerous_ips as $item)

<tr>
<td>{{ $item->ip }}</td>
<td>{{ $item->total }}</td>
</tr>

@endforeach

</tbody>
</table>

<h2>Events Distribution</h2>

<table>
<thead>
<tr>
<th>Type</th>
<th>Total</th>
</tr>
</thead>

<tbody>

@foreach($events_by_type as $item)

<tr>
<td>{{ $item->event_type }}</td>
<td>{{ $item->total }}</td>
</tr>

@endforeach

</tbody>
</table>

<h2>Severity Distribution</h2>

<table>
<thead>
<tr>
<th>Severity</th>
<th>Total</th>
</tr>
</thead>

<tbody>

@foreach($severity_distribution as $item)

<tr>
<td>{{ $item->severity }}</td>
<td>{{ $item->total }}</td>
</tr>

@endforeach

</tbody>
</table>

<h2>Failed Login Attempts</h2>

<table>
<thead>
<tr>
<th>User</th>
<th>IP</th>
<th>Date</th>
</tr>
</thead>

<tbody>

@foreach($failed_logins as $log)

<tr>
<td>{{ $log->user_email }}</td>
<td>{{ $log->ip }}</td>
<td>{{ $log->created_at }}</td>
</tr>

@endforeach

</tbody>
</table>

<h2>Latest Logs</h2>

<table>

<thead>
<tr>
<th>User</th>
<th>Action</th>
<th>Type</th>
<th>Severity</th>
<th>Date</th>
</tr>
</thead>

<tbody>

@foreach($latest_logs as $log)

<tr>
<td>{{ $log->user_email }}</td>
<td>{{ $log->action }}</td>
<td>{{ $log->event_type }}</td>
<td>{{ $log->severity }}</td>
<td>{{ $log->created_at }}</td>
</tr>

@endforeach

</tbody>

</table>

@endsection
@extends('reports.layout')

@section('content')

<h1>
    Tickets Report
</h1>

<div class="section">

    <h2>
        General Statistics
    </h2>

    <table>

        <tr>
            <th>Total Tickets</th>
            <td>{{ $total_tickets }}</td>
        </tr>

        <tr>
            <th>Open Tickets</th>
            <td>{{ $open_tickets }}</td>
        </tr>

        <tr>
            <th>Pending Tickets</th>
            <td>{{ $pending_tickets }}</td>
        </tr>

        <tr>
            <th>Closed Tickets</th>
            <td>{{ $closed_tickets }}</td>
        </tr>

        <tr>
            <th>Resolved Percentage</th>
            <td>{{ $resolved_percentage }} %</td>
        </tr>

        <tr>
            <th>Average Daily Tickets</th>
            <td>{{ $average_daily_tickets }}</td>
        </tr>

    </table>

</div>

{{-- ------------------------------------------------------------------ --}}

<div class="section">

    <h2>
        Tickets By Status
    </h2>

    <table>

        <thead>

        <tr>
            <th>Status</th>
            <th>Total</th>
        </tr>

        </thead>

        <tbody>

        @foreach($tickets_by_status as $item)

        <tr>

            <td>{{ $item->status }}</td>

            <td>{{ $item->total }}</td>

        </tr>

        @endforeach

        </tbody>

    </table>

</div>

{{-- ------------------------------------------------------------------ --}}

<div class="section">

    <h2>
        Tickets Growth
    </h2>

    <table>

        <thead>

        <tr>
            <th>Date</th>
            <th>Total</th>
        </tr>

        </thead>

        <tbody>

        @foreach($tickets_growth as $item)

        <tr>

            <td>{{ $item->date }}</td>

            <td>{{ $item->total }}</td>

        </tr>

        @endforeach

        </tbody>

    </table>

</div>

{{-- ------------------------------------------------------------------ --}}

<div class="section">

    <h2>
        Most Reporting Users
    </h2>

    <table>

        <thead>

        <tr>

            <th>User</th>

            <th>Email</th>

            <th>Tickets Count</th>

        </tr>

        </thead>

        <tbody>

        @foreach($most_reporting_users as $item)

        <tr>

            <td>
                {{ $item->user?->name }}
            </td>

            <td>
                {{ $item->user?->email }}
            </td>

            <td>
                {{ $item->total }}
            </td>

        </tr>

        @endforeach

        </tbody>

    </table>

</div>

{{-- ------------------------------------------------------------------ --}}

<div class="section">

    <h2>
        Users With Open Tickets
    </h2>

    <table>

        <thead>

        <tr>

            <th>User</th>

            <th>Email</th>

            <th>Open Tickets</th>

        </tr>

        </thead>

        <tbody>

        @foreach($users_with_open_tickets as $item)

        <tr>

            <td>
                {{ $item->user?->name }}
            </td>

            <td>
                {{ $item->user?->email }}
            </td>

            <td>
                {{ $item->total }}
            </td>

        </tr>

        @endforeach

        </tbody>

    </table>

</div>

{{-- ------------------------------------------------------------------ --}}

<div class="section">

    <h2>
        Latest Tickets
    </h2>

    <table>

        <thead>

        <tr>

            <th>ID</th>

            <th>User</th>

            <th>Status</th>

            <th>Created At</th>

        </tr>

        </thead>

        <tbody>

        @foreach($latest_tickets as $ticket)

        <tr>

            <td>{{ $ticket->id }}</td>

            <td>
                {{ $ticket->user?->email }}
            </td>

            <td>{{ $ticket->status }}</td>

            <td>{{ $ticket->created_at }}</td>

        </tr>

        @endforeach

        </tbody>

    </table>

</div>

{{-- ------------------------------------------------------------------ --}}

<div class="section">

    <h2>
        Oldest Open Tickets
    </h2>

    <table>

        <thead>

        <tr>

            <th>ID</th>

            <th>User</th>

            <th>Email</th>

            <th>Status</th>

            <th>Created At</th>

        </tr>

        </thead>

        <tbody>

        @foreach($oldest_open_tickets as $ticket)

        <tr>

            <td>
                {{ $ticket->id }}
            </td>

            <td>
                {{ $ticket->user?->name }}
            </td>

            <td>
                {{ $ticket->user?->email }}
            </td>

            <td>
                {{ $ticket->status }}
            </td>

            <td>
                {{ $ticket->created_at }}
            </td>

        </tr>

        @endforeach

        </tbody>

    </table>

</div>

{{-- ------------------------------------------------------------------ --}}

@if($peak_ticket_day)

<div class="section">

    <h2>
        Peak Ticket Day
    </h2>

    <table>

        <tr>

            <th>Date</th>

            <td>
                {{ $peak_ticket_day->date }}
            </td>

        </tr>

        <tr>

            <th>Tickets Count</th>

            <td>
                {{ $peak_ticket_day->total }}
            </td>

        </tr>

    </table>

</div>

@endif

@endsection
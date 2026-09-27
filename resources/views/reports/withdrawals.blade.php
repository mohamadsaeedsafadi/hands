@extends('reports.layout')

@section('content')

<h1>
    Withdrawals Report
</h1>

{{-- ------------------------------------------------------------- --}}
{{-- General Statistics --}}
{{-- ------------------------------------------------------------- --}}

<h2>
    General Statistics
</h2>

<table>

    <tbody>

    <tr>
        <th>Total Requests</th>
        <td>{{ $total_requests }}</td>
    </tr>

    <tr>
        <th>Approved Requests</th>
        <td>{{ $approved_requests }}</td>
    </tr>

    <tr>
        <th>Pending Requests</th>
        <td>{{ $pending_requests }}</td>
    </tr>

    <tr>
        <th>Rejected Requests</th>
        <td>{{ $rejected_requests }}</td>
    </tr>

    </tbody>

</table>

{{-- ------------------------------------------------------------- --}}
{{-- Financial Statistics --}}
{{-- ------------------------------------------------------------- --}}

<h2>
    Financial Statistics
</h2>

<table>

    <tbody>

    <tr>
        <th>Total Withdrawals</th>
        <td>{{ $total_withdrawals }}</td>
    </tr>

    <tr>
        <th>Total Commissions</th>
        <td>{{ $total_commissions }}</td>
    </tr>

    <tr>
        <th>Total Final Amounts</th>
        <td>{{ $total_final_amounts }}</td>
    </tr>

    <tr>
        <th>Average Withdrawal</th>
        <td>{{ number_format($average_withdrawal,2) }}</td>
    </tr>

    <tr>
        <th>Largest Withdrawal</th>
        <td>{{ $largest_withdrawal }}</td>
    </tr>

    <tr>
        <th>Smallest Withdrawal</th>
        <td>{{ $smallest_withdrawal }}</td>
    </tr>

    </tbody>

</table>

{{-- ------------------------------------------------------------- --}}
{{-- Withdrawals By Status --}}
{{-- ------------------------------------------------------------- --}}

<h2>
    Withdrawals By Status
</h2>

<table>

    <thead>

    <tr>

        <th>Status</th>

        <th>Total</th>

    </tr>

    </thead>

    <tbody>

    @foreach($withdrawals_by_status as $item)

    <tr>

        <td>{{ $item->status }}</td>

        <td>{{ $item->total }}</td>

    </tr>

    @endforeach

    </tbody>

</table>

{{-- ------------------------------------------------------------- --}}
{{-- Daily Withdrawals --}}
{{-- ------------------------------------------------------------- --}}

<h2>
    Daily Withdrawals
</h2>

<table>

    <thead>

    <tr>

        <th>Date</th>

        <th>Total Amount</th>

    </tr>

    </thead>

    <tbody>

    @foreach($daily_withdrawals as $item)

    <tr>

        <td>{{ $item->date }}</td>

        <td>{{ $item->total }}</td>

    </tr>

    @endforeach

    </tbody>

</table>

{{-- ------------------------------------------------------------- --}}
{{-- Top Withdrawals --}}
{{-- ------------------------------------------------------------- --}}

<h2>
    Largest Withdrawals
</h2>

<table>

    <thead>

    <tr>

        <th>Provider</th>

        <th>Email</th>

        <th>Amount</th>

        <th>Commission</th>

        <th>Final Amount</th>

        <th>Status</th>

    </tr>

    </thead>

    <tbody>

    @foreach($top_withdrawals as $item)

    <tr>

        <td>{{ $item->provider?->name }}</td>

        <td>{{ $item->provider?->email }}</td>

        <td>{{ $item->amount }}</td>

        <td>{{ $item->commission }}</td>

        <td>{{ $item->final_amount }}</td>

        <td>{{ $item->status }}</td>

    </tr>

    @endforeach

    </tbody>

</table>

{{-- ------------------------------------------------------------- --}}
{{-- Most Active Providers --}}
{{-- ------------------------------------------------------------- --}}

<h2>
    Most Active Providers In Withdrawals
</h2>

<table>

    <thead>

    <tr>

        <th>Provider</th>

        <th>Email</th>

        <th>Total Requests</th>

        <th>Total Withdrawals</th>

    </tr>

    </thead>

    <tbody>

    @foreach($top_providers_withdrawals as $item)

    <tr>

        <td>{{ $item->provider?->name }}</td>

        <td>{{ $item->provider?->email }}</td>

        <td>{{ $item->total_requests }}</td>

        <td>{{ $item->total_amount }}</td>

    </tr>

    @endforeach

    </tbody>

</table>

{{-- ------------------------------------------------------------- --}}
{{-- Cashiers Performance --}}
{{-- ------------------------------------------------------------- --}}

<h2>
    Cashiers Performance
</h2>

<table>

    <thead>

    <tr>

        <th>Cashier</th>

        <th>Email</th>

        <th>Processed Requests</th>

        <th>Total Processed Amount</th>

    </tr>

    </thead>

    <tbody>

    @foreach($cashiers_activity as $item)

    <tr>

        <td>{{ $item->cashier?->name }}</td>

        <td>{{ $item->cashier?->email }}</td>

        <td>{{ $item->processed_requests }}</td>

        <td>{{ $item->processed_amount }}</td>

    </tr>

    @endforeach

    </tbody>

</table>

{{-- ------------------------------------------------------------- --}}
{{-- Latest Requests --}}
{{-- ------------------------------------------------------------- --}}

<h2>
    Latest Withdrawal Requests
</h2>

<table>

    <thead>

    <tr>

        <th>Provider</th>

        <th>Cashier</th>

        <th>Amount</th>

        <th>Commission</th>

        <th>Final Amount</th>

        <th>Status</th>

        <th>Date</th>

    </tr>

    </thead>

    <tbody>

    @foreach($latest_requests as $item)

    <tr>

        <td>{{ $item->provider?->name }}</td>

        <td>{{ $item->cashier?->name }}</td>

        <td>{{ $item->amount }}</td>

        <td>{{ $item->commission }}</td>

        <td>{{ $item->final_amount }}</td>

        <td>{{ $item->status }}</td>

        <td>{{ $item->created_at }}</td>

    </tr>

    @endforeach

    </tbody>

</table>

@endsection
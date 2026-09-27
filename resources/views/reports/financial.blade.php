@extends('reports.layout')

@section('content')

<h1>
    Financial Report
</h1>

{{-- ------------------------------------------------------------- --}}
{{-- General Financial Statistics --}}
{{-- ------------------------------------------------------------- --}}

<h2>
    General Statistics
</h2>

<table>

    <tbody>

    <tr>
        <th>Total Successful Payments</th>
        <td>{{ number_format($total_paid, 2) }}</td>
    </tr>

    <tr>
        <th>Total Pending Payments</th>
        <td>{{ number_format($total_pending, 2) }}</td>
    </tr>

    <tr>
        <th>Total Failed Payments</th>
        <td>{{ number_format($total_failed, 2) }}</td>
    </tr>

    <tr>
        <th>Total Payments Count</th>
        <td>{{ $payments_count }}</td>
    </tr>

    <tr>
        <th>Average Payment</th>
        <td>{{ number_format($average_payment, 2) }}</td>
    </tr>

    <tr>
        <th>Success Rate</th>
        <td>{{ number_format($success_rate, 2) }} %</td>
    </tr>

    </tbody>

</table>

{{-- ------------------------------------------------------------- --}}
{{-- Daily Revenue --}}
{{-- ------------------------------------------------------------- --}}

<h2>
    Daily Revenue
</h2>

<table>

    <thead>

        <tr>

            <th>Date</th>

            <th>Revenue</th>

        </tr>

    </thead>

    <tbody>

        @foreach($daily_revenue as $day)

        <tr>

            <td>{{ $day->date }}</td>

            <td>{{ number_format($day->total, 2) }}</td>

        </tr>

        @endforeach

    </tbody>

</table>

{{-- ------------------------------------------------------------- --}}
{{-- Payments Growth --}}
{{-- ------------------------------------------------------------- --}}

<h2>
    Payments Growth
</h2>

<table>

    <thead>

        <tr>

            <th>Date</th>

            <th>Total Amount</th>

        </tr>

    </thead>

    <tbody>

        @foreach($payments_growth as $growth)

        <tr>

            <td>{{ $growth->date }}</td>

            <td>{{ number_format($growth->total, 2) }}</td>

        </tr>

        @endforeach

    </tbody>

</table>

{{-- ------------------------------------------------------------- --}}
{{-- Top Payments --}}
{{-- ------------------------------------------------------------- --}}

<h2>
    Top Payments
</h2>

<table>

    <thead>

        <tr>

            <th>ID</th>

            <th>Amount</th>

            <th>Status</th>

            <th>Date</th>

        </tr>

    </thead>

    <tbody>

        @foreach($top_payments as $payment)

        <tr>

            <td>{{ $payment->id }}</td>

            <td>{{ number_format($payment->amount_syp, 2) }}</td>

            <td>{{ $payment->status }}</td>

            <td>{{ $payment->created_at }}</td>

        </tr>

        @endforeach

    </tbody>

</table>

{{-- ------------------------------------------------------------- --}}
{{-- Extra Insights --}}
{{-- ------------------------------------------------------------- --}}

<h2>
    Financial Insights
</h2>

<table>

    <tbody>

    <tr>
        <th>Highest Payment</th>

        <td>
            {{ number_format($top_payments->max('amount_syp') ?? 0, 2) }}
        </td>
    </tr>

    <tr>
        <th>Lowest Top Payment</th>

        <td>
            {{ number_format($top_payments->min('amount_syp') ?? 0, 2) }}
        </td>
    </tr>

    <tr>
        <th>Total Revenue From Top Payments</th>

        <td>
            {{ number_format($top_payments->sum('amount_syp'), 2) }}
        </td>
    </tr>

    </tbody>

</table>

@endsection
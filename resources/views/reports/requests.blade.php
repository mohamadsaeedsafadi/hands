@extends('reports.layout')

@section('content')

<h1 class="title">
    Requests Report
</h1>

<div class="section">

    <div class="card">
        <strong>Total Requests:</strong>
        {{ $requests_count }}
    </div>

    <div class="card success">
        <strong>Completed Requests:</strong>
        {{ $completed }}
    </div>

    <div class="card warning">
        <strong>Pending Requests:</strong>
        {{ $pending }}
    </div>

    <div class="card danger">
        <strong>Cancelled Requests:</strong>
        {{ $cancelled }}
    </div>

    <div class="card info">
        <strong>Average Daily Requests:</strong>
        {{ $daily_average }}
    </div>

</div>

{{-- ====================================================================== --}}
{{-- Requests Status Percentage --}}
{{-- ====================================================================== --}}

<h2>Requests Status Percentage</h2>

<table>

    <thead>
        <tr>
            <th>Status</th>
            <th>Count</th>
            <th>Percentage</th>
        </tr>
    </thead>

    <tbody>

        <tr>
            <td>Completed</td>

            <td>
                {{ $completed }}
            </td>

            <td>
                {{
                    $requests_count > 0
                    ? round(($completed / $requests_count) * 100, 2)
                    : 0
                }}%
            </td>
        </tr>

        <tr>
            <td>Pending</td>

            <td>
                {{ $pending }}
            </td>

            <td>
                {{
                    $requests_count > 0
                    ? round(($pending / $requests_count) * 100, 2)
                    : 0
                }}%
            </td>
        </tr>

        <tr>
            <td>Cancelled</td>

            <td>
                {{ $cancelled }}
            </td>

            <td>
                {{
                    $requests_count > 0
                    ? round(($cancelled / $requests_count) * 100, 2)
                    : 0
                }}%
            </td>
        </tr>

    </tbody>

</table>

{{-- ====================================================================== --}}
{{-- Top Categories --}}
{{-- ====================================================================== --}}

<h2>Most Requested Categories</h2>

<table>

    <thead>
        <tr>
            <th>#</th>
            <th>Category</th>
            <th>Requests Count</th>
        </tr>
    </thead>

    <tbody>

        @foreach($top_categories as $index => $category)

        <tr>

            <td>
                {{ $index + 1 }}
            </td>

            <td>
                {{ $category->name }}
            </td>

            <td>
                {{ $category->requests_count }}
            </td>

        </tr>

        @endforeach

    </tbody>

</table>

{{-- ====================================================================== --}}
{{-- Requests Growth --}}
{{-- ====================================================================== --}}

<h2>Requests Growth</h2>

<table>

    <thead>

        <tr>
            <th>Date</th>
            <th>Requests Count</th>
        </tr>

    </thead>

    <tbody>

        @foreach($requests_growth as $growth)

        <tr>

            <td>
                {{ $growth->date }}
            </td>

            <td>
                {{ $growth->total }}
            </td>

        </tr>

        @endforeach

    </tbody>

</table>

{{-- ====================================================================== --}}
{{-- Insights --}}
{{-- ====================================================================== --}}

<h2>System Insights</h2>

<table>

    <tbody>

        <tr>
            <th>Most Common Status</th>

            <td>

                @php

                    $statuses = [
                        'Completed' => $completed,
                        'Pending' => $pending,
                        'Cancelled' => $cancelled,
                    ];

                    arsort($statuses);

                @endphp

                {{ array_key_first($statuses) }}

            </td>
        </tr>

        <tr>
            <th>Request Completion Rate</th>

            <td>

                {{
                    $requests_count > 0
                    ? round(($completed / $requests_count) * 100, 2)
                    : 0
                }}%

            </td>
        </tr>

        <tr>
            <th>Cancellation Rate</th>

            <td>

                {{
                    $requests_count > 0
                    ? round(($cancelled / $requests_count) * 100, 2)
                    : 0
                }}%

            </td>
        </tr>

    </tbody>

</table>

@endsection
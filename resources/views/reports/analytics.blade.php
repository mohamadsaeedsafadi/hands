@extends('reports.layout')

@section('content')

<h1>System Analytics Report</h1>

<h2>General Statistics</h2>

<table>
    <tr>
        <th>Total Users</th>
        <td>{{ $total_users }}</td>
    </tr>

    <tr>
        <th>Total Providers</th>
        <td>{{ $total_providers }}</td>
    </tr>

    <tr>
        <th>Verified Providers</th>
        <td>{{ $verified_providers }}</td>
    </tr>

    <tr>
        <th>New Users Today</th>
        <td>{{ $new_users_today }}</td>
    </tr>

    <tr>
        <th>Total Revenue</th>
        <td>{{ $total_revenue }}</td>
    </tr>

    <tr>
        <th>Average Payment</th>
        <td>{{ $average_payment }}</td>
    </tr>

    <tr>
        <th>Total Requests</th>
        <td>{{ $total_requests }}</td>
    </tr>

    <tr>
        <th>Completion Rate</th>
        <td>{{ $completion_rate }} %</td>
    </tr>
</table>

<h2>Top Categories</h2>

<table>

    <thead>
        <tr>
            <th>Category</th>
            <th>Requests</th>
        </tr>
    </thead>

    <tbody>

    @foreach($top_categories as $category)

        <tr>
            <td>{{ $category->name }}</td>
            <td>{{ $category->requests_count }}</td>
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

    @foreach($most_active_users as $user)

        <tr>
            <td>{{ $user->user_email }}</td>
            <td>{{ $user->total }}</td>
        </tr>

    @endforeach

    </tbody>

</table>

<h2>Top Rated Providers</h2>

<table>

    <thead>
        <tr>
            <th>Name</th>
            <th>Rating</th>
        </tr>
    </thead>

    <tbody>

    @foreach($top_rated_providers as $provider)

        <tr>
            <td>{{ $provider->name }}</td>
            <td>{{ $provider->rating_avg }}</td>
        </tr>

    @endforeach

    </tbody>

</table>

@endsection
@extends('reports.layout')

@section('content')

<h1>
    Users Report
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
        <th>Total Users</th>
        <td>{{ $users_count }}</td>
    </tr>

    <tr>
        <th>Providers</th>
        <td>{{ $providers_count }}</td>
    </tr>

    <tr>
        <th>Normal Users</th>
        <td>{{ $normal_users_count }}</td>
    </tr>

    <tr>
        <th>Verified Providers</th>
        <td>{{ $verified_providers }}</td>
    </tr>

    <tr>
        <th>Unverified Providers</th>
        <td>{{ $unverified_providers }}</td>
    </tr>

    <tr>
        <th>Verified Emails</th>
        <td>{{ $verified_users }}</td>
    </tr>

    <tr>
        <th>Unverified Emails</th>
        <td>{{ $unverified_users }}</td>
    </tr>

    <tr>
        <th>Locked Users</th>
        <td>{{ $locked_users }}</td>
    </tr>

    </tbody>

</table>

{{-- ------------------------------------------------------------- --}}
{{-- User Growth --}}
{{-- ------------------------------------------------------------- --}}

<h2>
    Users Growth
</h2>

<table>

    <thead>

    <tr>

        <th>Date</th>

        <th>Users</th>

    </tr>

    </thead>

    <tbody>

    @foreach($growth as $item)

    <tr>

        <td>{{ $item->date }}</td>

        <td>{{ $item->total }}</td>

    </tr>

    @endforeach

    </tbody>

</table>

{{-- ------------------------------------------------------------- --}}
{{-- Users Per Month --}}
{{-- ------------------------------------------------------------- --}}

<h2>
    Monthly Registrations
</h2>

<table>

    <thead>

    <tr>

        <th>Month</th>

        <th>Total</th>

    </tr>

    </thead>

    <tbody>

    @foreach($users_per_month as $item)

    <tr>

        <td>{{ $item->month }}</td>

        <td>{{ $item->total }}</td>

    </tr>

    @endforeach

    </tbody>

</table>

{{-- ------------------------------------------------------------- --}}
{{-- Most Active Users --}}
{{-- ------------------------------------------------------------- --}}

<h2>
    Most Active Users
</h2>

<table>

    <thead>

    <tr>

        <th>Email</th>

        <th>Activities Count</th>

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

{{-- ------------------------------------------------------------- --}}
{{-- Most Active Providers --}}
{{-- ------------------------------------------------------------- --}}

<h2>
    Most Active Providers
</h2>

<table>

    <thead>

    <tr>

        <th>Name</th>

        <th>Email</th>

        <th>Completed Requests</th>

        <th>Rating</th>

    </tr>

    </thead>

    <tbody>

    @foreach($most_active_providers as $provider)

    <tr>

        <td>
            {{ $provider->provider?->name }}
        </td>

        <td>
            {{ $provider->provider?->email }}
        </td>

        <td>
            {{ $provider->total }}
        </td>

        <td>
            {{ $provider->provider?->rating_avg }}
        </td>

    </tr>

    @endforeach

    </tbody>

</table>

{{-- ------------------------------------------------------------- --}}
{{-- Top Rated Providers --}}
{{-- ------------------------------------------------------------- --}}

<h2>
    Top Rated Providers
</h2>

<table>

    <thead>

    <tr>

        <th>Name</th>

        <th>Email</th>

        <th>Rating</th>

        <th>Ratings Count</th>

    </tr>

    </thead>

    <tbody>

    @foreach($top_rated_providers as $provider)

    <tr>

        <td>{{ $provider->name }}</td>

        <td>{{ $provider->email }}</td>

        <td>{{ $provider->rating_avg }}</td>

        <td>{{ $provider->ratings_count }}</td>

    </tr>

    @endforeach

    </tbody>

</table>

{{-- ------------------------------------------------------------- --}}
{{-- Most Reviewed Providers --}}
{{-- ------------------------------------------------------------- --}}

<h2>
    Most Reviewed Providers
</h2>

<table>

    <thead>

    <tr>

        <th>Name</th>

        <th>Email</th>

        <th>Reviews</th>

        <th>Rating</th>

    </tr>

    </thead>

    <tbody>

    @foreach($most_reviewed_providers as $provider)

    <tr>

        <td>{{ $provider->name }}</td>

        <td>{{ $provider->email }}</td>

        <td>{{ $provider->ratings_count }}</td>

        <td>{{ $provider->rating_avg }}</td>

    </tr>

    @endforeach

    </tbody>

</table>

{{-- ------------------------------------------------------------- --}}
{{-- Latest Users --}}
{{-- ------------------------------------------------------------- --}}

<h2>
    Latest Registered Users
</h2>

<table>

    <thead>

    <tr>

        <th>Name</th>

        <th>Email</th>

        <th>Role</th>

        <th>Created At</th>

    </tr>

    </thead>

    <tbody>

    @foreach($latest_users as $user)

    <tr>

        <td>{{ $user->name }}</td>

        <td>{{ $user->email }}</td>

        <td>{{ $user->role }}</td>

        <td>{{ $user->created_at }}</td>

    </tr>

    @endforeach

    </tbody>

</table>

@endsection
@extends('layouts.superadmin')


@section('content')
<div class="container">
    <h1>Registered Users & Plans</h1>
    <table class="table">
        <thead>
            <tr>
                <th>Name</th>
                <th>Active Plan</th>
                <th>Subscription Status</th>
                <th>Last Payment</th>
            </tr>
        </thead>
        <tbody>
        @foreach($users as $user)
            @php
                $activeSubscription = $user->subscriptions->first();
                $lastPayment = optional($activeSubscription->payments->last());
            @endphp
            <tr>
                <td>{{ $user->name }}</td>
                <td>
                    @foreach($user->plans as $plan)
                        {{ $plan->name }}<br>
                    @endforeach
                </td>
                <td>{{ $activeSubscription?->status ?? 'N/A' }}</td>
                <td>{{ $lastPayment?->created_at ?? 'No Payments' }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
    {{ $users->links() }}
</div>
@endsection
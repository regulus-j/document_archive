@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h4>Subscription Plans</h4>
                </div>
                <div class="card-body">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Plan Name</th>
                                <th>Description</th>
                                <th>Price</th>
                                <th>Billing Cycle</th>
                                <th>Features</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($plans as $key => $plan)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $plan->plan_name }}</td>
                                <td>{{ $plan->description }}</td>
                                <td>${{ number_format($plan->price, 2) }}</td>
                                <td>{{ $plan->billing_cycle }}</td>
                                <td>
                                    @if($plan->feature_1)
                                        <span class="badge bg-success">Feature 1</span>
                                    @endif
                                    @if($plan->feature_2)
                                        <span class="badge bg-success">Feature 2</span>
                                    @endif
                                    @if($plan->feature_3)
                                        <span class="badge bg-success">Feature 3</span>
                                    @endif
                                </td>
                                <td>
                                    @if($plan->is_active)
                                        <span class="badge bg-success">Active</span>
                                    @else
                                        <span class="badge bg-danger">Inactive</span>
                                    @endif
                                </td>
                                <td>
                                    <a class="btn btn-info btn-sm" href="{{ route('plans.show', $plan->id) }}">Show</a>
                                    <a class="btn btn-primary btn-sm" href="{{ route('plans.edit', $plan->id) }}">Edit</a>
                                    <form action="{{ route('plans.destroy', $plan->id) }}" method="POST" style="display:inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@extends('layouts.app')

@section('content')

<div class="row">
    <div class="col-lg-12 margin-tb">
        <div class="pull-left">
            <h2>Reports</h2>
        </div>
        <div class="pull-right
        ">
            @can('report-create')
            <a class="btn btn-success btn-sm mb-2" href="{{ route('reports.create') }}"><i class="fa fa-plus"></i> Create New Report</a>
            @endcan
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-12 margin-tb">
        <div class="pull-left">
            <h2> Search </h2>
        </div>
        <form action="POST">
            <div class="form-group">
                <input type="text" name="text" class="form-control" placeholder="Search by text">
                <div class="form-group">
                    <label for="image">Search by Image</label>
                    <input type="file" name="image" class="form-control">
                </div>

                <input type="submit" value="Search">
            </div>
        </form>
    </div>
</div>

@session('success')
    <div class="alert alert-success" role="alert"> 
        {{ $value }}
    </div>
@endsession

@endsection
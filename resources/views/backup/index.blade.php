@extends('layouts.app')

@section('content')

<div class="container">
    <h1>Backups</h1>
    <div class="mb-3">
        <button class="btn btn-primary">Create Backup</button>
    </div>
    <div class="mb-3">
        <input type="text" class="form-control" placeholder="Search backups">
    </div>
    <table class="table table-striped">
        <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">Backup Name</th>
                <th scope="col">Date</th>
                <th scope="col">Actions</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <th scope="row">1</th>
                <td>Backup 1</td>
                <td>2023-01-01</td>
                <td>
                    <button class="btn btn-sm btn-info">Restore</button>
                    <button class="btn btn-sm btn-danger">Delete</button>
                </td>
            </tr>
            <tr>
                <th scope="row">2</th>
                <td>Backup 2</td>
                <td>2023-02-01</td>
                <td>
                    <button class="btn btn-sm btn-info">Restore</button>
                    <button class="btn btn-sm btn-danger">Delete</button>
                </td>
            </tr>
        </tbody>
    </table>
</div>

@endsection
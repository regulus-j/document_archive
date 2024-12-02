@extends('layouts.app')
@section('content')

<div class="container mt-4">
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h4>Pending Documents</h4>
                </div>
                <div class="card-body">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Title</th>
                                <th>From Office</th>
                                <th>To Office</th>
                                <th>Status</th>
                                <th>Date Created</th>
                                <th width="280px">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($documents as $key => $document)
                            <tr>
                                <td>{{ ++$key }}</td>
                                <td>{{ $document->title }}</td>
                                <td>{{ $document->transaction->fromOffice->name }}</td>
                                <td>{{ $document->transaction->toOffice->name }}</td>
                                <td>{{ $document->status->status }}</td>
                                <td>{{ $document->created_at->format('M d, Y') }}</td>
                                <td>
                                    @switch($document->status->status)
                                        @case('pending')
                                            <a class="btn btn-info btn-sm" href="{{ route('documents.receive', $document->id) }}">
                                                Receive
                                            </a>
                                            @break
                                        @case('received')
                                            <a class="btn btn-success btn-sm" href="{{ route('documents.confirmrelease', $document->id) }}">
                                                Release
                                            </a>
                                            @break
                                        @case('released')
                                            <a class="btn btn-success btn-sm" href="{{ route('documents.show', $document->id) }}">
                                                View
                                            </a>
                                            <a class="btn btn-success btn-sm" href="{{ route('documents.receive', $document->id) }}">
                                                Retract
                                            </a>
                                            @break
                                    @endswitch
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    {!! $documents->links() !!}
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
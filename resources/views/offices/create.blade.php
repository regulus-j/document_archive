@extends('layouts.app')

@section('content')

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Create New Office</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('office.store') }}">
                        @csrf

                        <div class="form-group mb-3">
                            <label for="name">Office Name</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" required>
                            @error('name')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label for="parent_office_id">Parent Office</label>
                            <select class="form-control @error('parent_office_id') is-invalid @enderror" id="parent_office_id" name="parent_office_id">
                                <option value="">None</option>
                                @foreach($offices as $id => $name)
                                    <option value="{{ $id }}">{{ $name }}</option>
                                @endforeach
                            </select>
                            @error('parent_office_id')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-primary">Create Office</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection


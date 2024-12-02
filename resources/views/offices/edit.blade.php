@extends('layouts.app')
@section('content')

<div class="container">
    <h2>Edit Office</h2>
    <form action="{{ route('office.update', $office->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label for="name">Office Name</label>
            <input type="text" class="form-control" id="name" name="name" value="{{ $office->name }}" required>
        </div>

        <div class="form-group">
            <label for="parent_office_id">Parent Office</label>
            <select class="form-control" id="parent_office_id" name="parent_office_id">
                <option value="">None</option>
                @foreach ($offices as $parentOffice)
                    @if($parentOffice->id != $office->id)
                        <option value="{{ $parentOffice->id }}" {{ $office->parent_office_id == $parentOffice->id ? 'selected' : '' }}>
                            {{ $parentOffice->name }}
                        </option>
                    @endif
                @endforeach
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Update Office</button>
        <a href="{{ route('office.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
</div>

@endsection
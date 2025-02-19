@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Edit Company</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('companies.update', $company->id) }}">
                        @csrf
                        @method('PUT')

                        <div class="form-group row mb-3">
                            <label for="company_name" class="col-md-4 col-form-label text-md-right">Company Name</label>
                            <div class="col-md-6">
                                <input id="company_name" type="text" class="form-control @error('company_name') is-invalid @enderror" 
                                    name="company_name" value="{{ old('company_name', $company->company_name) }}" required>
                                @error('company_name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row mb-3">
                            <label for="registered_name" class="col-md-4 col-form-label text-md-right">Registered Name</label>
                            <div class="col-md-6">
                                <input id="registered_name" type="text" class="form-control @error('registered_name') is-invalid @enderror" 
                                    name="registered_name" value="{{ old('registered_name', $company->registered_name) }}" required>
                                @error('registered_name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row mb-3">
                            <label for="company_email" class="col-md-4 col-form-label text-md-right">Company Email</label>
                            <div class="col-md-6">
                                <input id="company_email" type="email" class="form-control @error('company_email') is-invalid @enderror" 
                                    name="company_email" value="{{ old('company_email', $company->company_email) }}" required>
                                @error('company_email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row mb-3">
                            <label for="company_phone" class="col-md-4 col-form-label text-md-right">Company Phone</label>
                            <div class="col-md-6">
                                <input id="company_phone" type="text" class="form-control @error('company_phone') is-invalid @enderror" 
                                    name="company_phone" value="{{ old('company_phone', $company->company_phone) }}" required>
                                @error('company_phone')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row mb-3">
                            <label class="col-md-4 col-form-label text-md-right">Select User</label>
                            <div class="col-md-6">
                                @foreach($users as $user)
                                    <div class="form-check" id="owner-list">
                                        <input class="form-check-input" type="radio" 
                                            name="user_id" 
                                            id="user_{{ $user->id }}" 
                                            value="{{ $user->id }}"
                                            {{ old('user_id', $company->user_id) == $user->id ? 'checked' : '' }}>
                                        <label class="form-check-label" for="user_{{ $user->id }}">
                                            {{ $user->first_name . ' ' . $user->last_name }}
                                            <br>
                                            {{ $user->email }}
                                        </label>
                                    </div>
                                @endforeach
                                <div class="mb-2">
                                    <input type="text" id="ownerSearch" class="form-control" placeholder="Search users...">
                                </div>

                                @push('scripts')
                                <script>
                                    document.addEventListener('DOMContentLoaded', function() {
                                        document.getElementById('ownerSearch').addEventListener('keyup', function() {
                                            const searchText = this.value.toLowerCase();
                                            const radioButtons = document.querySelectorAll('#owner-list .form-check');

                                            radioButtons.forEach(function(item) {
                                                const label = item.querySelector('.form-check-label');
                                                const text = label.textContent.toLowerCase();
                                                
                                                item.style.display = text.includes(searchText) ? '' : 'none';
                                            });
                                        });
                                    });
                                </script>
                                @endpush
                                <div class="mt-2">
                                    {{ $users->links() }}
                                </div>

                                @error('user_id')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    Update Company
                                </button>
                                <a href="{{ route('companies.index') }}" class="btn btn-secondary">
                                    Cancel
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
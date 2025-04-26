@extends('layouts.app')

@section('title', 'Confirm Password')

@section('content')

<!-- Logo/Branding -->
<div class="text-center mb-5">
    <h2 class="fw-bold main-color mb-3">{{ __('Confirm Password') }}</h2>
    <p class="text-muted">{{ __('Please confirm your password before continuing.') }}</p>
</div>

<!-- Session Messages -->
@if(session('status'))
<div class="alert alert-success alert-dismissible fade show mb-4">
    {{ session('status') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif

@if(session('success'))
<div class="alert alert-success alert-dismissible fade show mb-4">
    {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif

@if(session('error'))
<div class="alert alert-danger alert-dismissible fade show mb-4">
    {{ session('error') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif

<!-- Confirm Password Form -->
<form method="POST" action="{{ route('password.confirm') }}">
    @csrf

    <!-- Password Field -->
    <div class="mb-4">
        <label for="password" class="form-label">{{ __('Password') }}</label>
        <div class="input-group">
            <span class="input-group-text bg-transparent">
                <i class="fas fa-lock text-muted"></i>
            </span>
            <input id="password" type="password"
                class="form-control @error('password') is-invalid @enderror"
                name="password" required autocomplete="current-password"
                placeholder="Enter your password">
            <button class="btn btn-outline-secondary toggle-password" type="button">
                <i class="fas fa-eye"></i>
            </button>
            @error('password')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <!-- Submit Button & Forgot Password -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <button type="submit" class="btn btn-custom btn-lg py-2 fw-bold">
            {{ __('Confirm Password') }}
        </button>

        @if (Route::has('password.request'))
        <a href="{{ route('password.request') }}" class="text-decoration-none main-color fw-medium">
            {{ __('Forgot Password?') }}
        </a>
        @endif
    </div>
</form>

@endsection
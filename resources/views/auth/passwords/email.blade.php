@extends('layouts.app')

@section('title', 'Reset Password')

@section('content')

<!-- Logo/Branding -->
<div class="text-center mb-5">
    <h2 class="fw-bold main-color mb-3">{{ __('Reset Password') }}</h2>
    <p class="text-muted">Enter your email to receive a password reset link</p>
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

<!-- Reset Password Form -->
<form method="POST" action="{{ route('password.email') }}">
    @csrf

    <!-- Email Field -->
    <div class="mb-4">
        <label for="email" class="form-label">{{ __('Email Address') }}</label>
        <div class="input-group">
            <span class="input-group-text bg-transparent">
                <i class="fas fa-envelope text-muted"></i>
            </span>
            <input id="email" type="email" class="form-control @error('email') is-invalid @enderror"
                name="email" value="{{ old('email') }}" required autocomplete="email"
                placeholder="e.g. john@example.com" autofocus>
            @error('email')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <!-- Submit Button -->
    <div class="d-grid mb-4">
        <button type="submit" class="btn btn-custom btn-lg py-2 fw-bold">
            {{ __('Send Password Reset Link') }}
        </button>
    </div>

    <!-- Back to Login Link -->
    <div class="text-center">
        <p class="mb-0">Remember your password?
            <a href="{{ route('login') }}" class="main-color fw-bold text-decoration-none">
                {{ __('Sign in here') }}
            </a>
        </p>
    </div>
</form>

@endsection
@extends('layouts.app')

@section('title', 'Reset Password')

@section('content')

<!-- Logo/Branding -->
<div class="text-center mb-5">
    <h2 class="fw-bold main-color mb-3">{{ __('Reset Password') }}</h2>
    <p class="text-muted">Create a new password for your account</p>
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
<form method="POST" action="{{ route('password.update') }}">
    @csrf
    <input type="hidden" name="token" value="{{ $token }}">

    <!-- Email Field -->
    <div class="mb-4">
        <label for="email" class="form-label">{{ __('Email Address') }}</label>
        <div class="input-group">
            <span class="input-group-text bg-transparent">
                <i class="fas fa-envelope text-muted"></i>
            </span>
            <input id="email" type="email" class="form-control @error('email') is-invalid @enderror"
                name="email" value="{{ $email ?? old('email') }}" required autocomplete="email"
                placeholder="e.g. john@example.com" autofocus readonly>
            @error('email')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <!-- Password Field -->
    <div class="mb-4">
        <label for="password" class="form-label">{{ __('New Password') }}</label>
        <div class="input-group">
            <span class="input-group-text bg-transparent">
                <i class="fas fa-lock text-muted"></i>
            </span>
            <input id="password" type="password" class="form-control @error('password') is-invalid @enderror"
                name="password" required autocomplete="new-password"
                placeholder="Enter new password">
            <button class="btn btn-outline-secondary toggle-password" type="button">
                <i class="fas fa-eye"></i>
            </button>
            @error('password')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <!-- Confirm Password Field -->
    <div class="mb-4">
        <label for="password-confirm" class="form-label">{{ __('Confirm Password') }}</label>
        <div class="input-group">
            <span class="input-group-text bg-transparent">
                <i class="fas fa-lock text-muted"></i>
            </span>
            <input id="password-confirm" type="password" class="form-control"
                name="password_confirmation" required autocomplete="new-password"
                placeholder="Confirm new password">
            <button class="btn btn-outline-secondary toggle-password" type="button">
                <i class="fas fa-eye"></i>
            </button>
        </div>
    </div>

    <!-- Submit Button -->
    <div class="d-grid mb-4">
        <button type="submit" class="btn btn-custom btn-lg py-2 fw-bold">
            {{ __('Reset Password') }}
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
@extends('layouts.app')

@section('title', 'Login')

@section('content')

<!-- Logo/Branding -->
<div class="text-center mb-5">
    <!-- <img src="{{ asset('assets/images/logo.png') }}" alt="Logo" class="mb-3" height="50"> -->
    <h2 class="fw-bold main-color mb-3">Welcome Back</h2>
    <p class="text-muted">Sign in to continue to your account</p>
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

<!-- Login Form -->
<form method="POST" action="{{ route('login') }}">
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
                placeholder="e.g. john@example.com">
            @error('email')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <!-- Password Field -->
    <div class="mb-4">
        <label for="password" class="form-label">{{ __('Password') }}</label>
        <div class="input-group">
            <span class="input-group-text bg-transparent">
                <i class="fas fa-lock text-muted"></i>
            </span>
            <input id="password" type="password" class="form-control @error('password') is-invalid @enderror"
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

    <!-- Remember Me & Forgot Password -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div class="form-check">
            <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
            <label class="form-check-label" for="remember">
                {{ __('Remember Me') }}
            </label>
        </div>
        @if (Route::has('password.request'))
        <a href="{{ route('password.request') }}" class="text-decoration-none main-color fw-medium">
            {{ __('Forgot Password?') }}
        </a>
        @endif
    </div>

    <!-- Submit Button -->
    <div class="d-grid mb-4">
        <button type="submit" class="btn btn-custom btn-lg py-2 fw-bold">
            {{ __('Sign In') }}
        </button>
    </div>

    <!-- Divider -->
    <div class="position-relative my-4">
        <hr class="border-1">
        <div class="position-absolute top-50 start-50 translate-middle bg-white px-2 text-muted small">
            Or continue with
        </div>
    </div>

    <!-- Social Login -->
    <div class="d-flex justify-content-center gap-3 mb-4">
        <a href="#" class="btn btn-outline-secondary rounded-circle px-3 py-2">
            <i class="fab fa-google"></i>
        </a>
        <a href="#" class="btn btn-outline-secondary rounded-circle px-3 py-2">
            <i class="fab fa-facebook-f"></i>
        </a>
        <a href="#" class="btn btn-outline-secondary rounded-circle px-3 py-2">
            <i class="fab fa-twitter"></i>
        </a>
    </div>

    <!-- Registration Link -->
    <div class="text-center">
        <p class="mb-0">Don't have an account?
            <a href="{{ route('register') }}" class="main-color fw-bold text-decoration-none">
                {{ __('Create account') }}
            </a>
        </p>
    </div>
</form>

@endsection
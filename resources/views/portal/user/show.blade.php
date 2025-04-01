@extends('layouts.app')

@section('title', $user->name.' Profile')

@section('content')
<div class="container py-5">
    <!-- Profile Header -->
    <div class="row mb-5">
        <div class="col-md-4 mb-4 mb-md-0">
            <!-- Profile Image -->
            <div class="profile-image-container">
                @if($user->primaryImage())
                <img src="{{ $user->primaryImage()->image_url }}" alt="{{ $user->name }}" class="img-fluid rounded shadow">
                @else
                <div class="no-image-placeholder bg-light d-flex align-items-center justify-content-center rounded">
                    <i class="fas fa-user fa-5x text-muted"></i>
                </div>
                @endif
            </div>

            <!-- Verification Badge -->
            @if($user->is_verified)
            <div class="verified-badge mt-3">
                <span class="badge bg-success">
                    <i class="fas fa-check-circle me-1"></i> Verified
                </span>
            </div>
            @endif

            <!-- Online Status -->
            <div class="mt-2">
                @if($user->last_online_at && $user->last_online_at->diffInMinutes(now()) < 15)
                    <span class="badge bg-success">
                    <i class="fas fa-circle me-1"></i> Online Now
                    </span>
                    @else
                    <span class="text-muted">
                        Last online: {{ $user->last_online_at ? $user->last_online_at->diffForHumans() : 'Unknown' }}
                    </span>
                    @endif
            </div>
        </div>

        <div class="col-md-8">
            <div class="d-flex justify-content-between align-items-start mb-3">
                <div>
                    <h1 class="main-color text-capitalize mb-1">{{ $user->name }}</h1>
                    <div class="d-flex flex-wrap gap-2 mb-2">
                        <span class="badge bg-primary">{{ $user->city ?? 'City' }}, {{ $user->country ?? 'Country' }}</span>
                        <span class="badge bg-secondary">{{ $user->age ?? 0 }} years</span>
                        @if($user->is_featured)
                        <span class="badge bg-warning text-dark">
                            <i class="fas fa-star me-1"></i> Featured
                        </span>
                        @endif
                    </div>
                </div>

                <div class="d-flex gap-2">
                    @auth
                    @if(auth()->id() === $user->id)
                    <a href="{{ route('profile.edit',$user->id) }}" class="btn btn-outline-custom btn-sm p-1">
                        <i class="fas fa-edit me-1"></i> Edit Profile
                    </a>
                    @else
                    <button class="btn btn-outline-custom btn-sm p-1">
                        <i class="fas fa-calendar-alt me-1"></i> Book Now
                    </button>
                    <button class="btn btn-outline-secondary">
                        <i class="fas fa-heart me-1"></i> Favorite
                    </button>
                    @endif
                    @else
                    <a href="{{ route('login') }}" class="btn btn-outline-custom btn-sm p-1">
                        <i class="fas fa-sign-in-alt me-1"></i> Login to Book
                    </a>
                    @endauth
                </div>
            </div>

            <!-- Basic Info -->
            <div class="row mb-4">
                <div class="col-md-6">
                    <ul class="list-unstyled">
                        <li class="mb-2">
                            <strong><i class="fas fa-venus-mars me-2"></i> Gender:</strong>
                            {{ ucfirst($user->gender ?? 'Not Set') }}
                        </li>
                        <li class="mb-2">
                            <strong><i class="fas fa-globe me-2"></i> Nationality:</strong>
                            {{ $user->nationality ?? 'Not Set' }}
                        </li>
                        <li class="mb-2">
                            <strong><i class="fas fa-language me-2"></i> Languages:</strong>
                            @if($user->languages)
                            {{ implode(', ', $user->languages) }}
                            @else
                            Not specified
                            @endif
                        </li>
                    </ul>
                </div>
                <div class="col-md-6">
                    <ul class="list-unstyled">
                        <li class="mb-2">
                            <strong><i class="fas fa-ruler-combined me-2"></i> Measurements:</strong>
                            @if($user->measurements)
                            {{ implode('-', $user->measurements) }}
                            @else
                            Not specified
                            @endif
                        </li>
                        <li class="mb-2">
                            <strong><i class="fas fa-eye me-2"></i> Eye Color:</strong>
                            {{ $user->eye_color ?? 'Not specified' }}
                        </li>
                        <li class="mb-2">
                            <strong><i class="fas fa-cut me-2"></i> Hair Color:</strong>
                            {{ $user->hair_color ?? 'Not specified' }}
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Hourly Rate -->
            <div class="bg-light p-3 rounded mb-4">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="mb-0">
                            <i class="fas fa-money-bill-wave me-2"></i>
                            Hourly Rate: <span class="text-success">KES {{ number_format($user->hourly_rate, 2) }}</span>
                        </h5>
                    </div>
                    @if($user->availability === 'available')
                    <span class="badge bg-success">
                        <i class="fas fa-check-circle me-1"></i> Available Now
                    </span>
                    @else
                    <span class="badge bg-secondary">
                        <i class="fas fa-clock me-1"></i> Check Availability
                    </span>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- About Section -->
    <div class="card mb-4 border-0 shadow-sm">
        <div class="card-body">
            <h4 class="main-color border-bottom-custom pb-2 mb-3">
                <i class="fas fa-user me-2"></i> About Me
            </h4>
            <p class="mb-0">{{ $user->about ?? 'No description provided.' }}</p>
        </div>
    </div>

    <!-- Services Section -->
    @if($user->services->count() > 0)
    <div class="card mb-4 border-0 shadow-sm">
        <div class="card-body">
            <h4 class="main-color border-bottom-custom pb-2 mb-3">
                <i class="fas fa-concierge-bell me-2"></i> Services
            </h4>
            <div class="row">
                @foreach($user->services as $service)
                <div class="col-md-6 mb-3">
                    <div class="d-flex">
                        <div class="flex-shrink-0 me-3">
                            <i class="fas fa-check-circle text-success fa-lg mt-1"></i>
                        </div>
                        <div>
                            <h5 class="mb-1">{{ $service->name }}</h5>
                            <p class="mb-0 text-muted">{{ $service->description }}</p>
                            @if($service->price)
                            <span class="badge bg-primary mt-1">${{ number_format($service->price, 2) }}</span>
                            @endif
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    @endif

    <!-- Gallery Section -->
    @if($user->gallery->count() > 0)
    <div class="card mb-4 border-0 shadow-sm">
        <div class="card-body">
            <h4 class="main-color border-bottom-custom pb-2 mb-3">
                <i class="fas fa-images me-2"></i> Gallery
            </h4>
            <div class="row g-3">
                @foreach($user->gallery as $image)
                <div class="col-6 col-md-4 col-lg-3">
                    <a href="{{ $image->image_url }}" data-lightbox="gallery" data-title="{{ $user->name }}">
                        <img src="{{ $image->thumbnail_url ?? $image->image_url }}" alt="{{ $user->name }}" class="img-fluid rounded shadow-sm">
                    </a>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    @endif

    <!-- Contact Section -->
    @auth
    @if(auth()->id() !== $user->id)
    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <h4 class="main-color border-bottom-custom pb-2 mb-3">
                <i class="fas fa-envelope me-2"></i> Contact {{ $user->name }}
            </h4>
            <form action="{{ route('messages.send', $user->id) }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label for="message" class="form-label">Your Message</label>
                    <textarea class="form-control" id="message" name="message" rows="4" required></textarea>
                </div>
                <button type="submit" class="btn btn-outline-custom btn-sm p-1">
                    <i class="fas fa-paper-plane me-1"></i> Send Message
                </button>
            </form>
        </div>
    </div>
    @endif
    @else
    <div class="alert alert-info">
        <i class="fas fa-info-circle me-2"></i>
        Please <a href="{{ route('login') }}" class="alert-link">login</a> to contact {{ $user->name }} or view full contact details.
    </div>
    @endauth
</div>
@endsection
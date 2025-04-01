@extends('layouts.app')

@section('title', 'Home')

@section('content')

<!-- Hero Section with Carousel -->
<div class="home-header position-relative">
    <!-- Carousel -->
    <div id="heroCarousel" class="carousel slide carousel-fade" data-bs-ride="carousel">
        <div class="carousel-inner">
            <div class="carousel-item active">
                <img src="{{ asset('assets/images/slides/2.jpg') }}" class="d-block w-100" alt="Happy couple laughing">
            </div>
            <div class="carousel-item">
                <img src="{{ asset('assets/images/slides/3.jpg') }}" class="d-block w-100" alt="Romantic dinner date">
            </div>
        </div>
    </div>

    <!-- Content Overlay -->
    <div class="overlay-container">
        <div class="container h-100">
            <div class="row h-100 align-items-center">
                <div class="col-12">
                    <div class="dating-overlay p-4 p-lg-5 rounded-4">
                        <div class="row w-100">
                            <!-- Text Content -->
                            <div class="col-lg-7 d-flex flex-column justify-content-center pe-lg-5">
                                <h1 class="fw-bold mb-3">Meet <span class="main-color">Singles</span> near you</h1>
                                <h3 class="mb-4">Start finding your match for <span class="main-color">free</span> today!</h3>
                                <p class="lead mb-4">
                                    Connect with like-minded individuals for dating, relationships, or more.
                                    Discover the possibilities around you!
                                </p>
                                <div class="d-flex flex-wrap gap-3 mt-2">
                                    <button class="btn btn-custom btn-lg px-4">How It Works</button>
                                    <button class="btn btn-outline-light btn-lg px-4">Success Stories</button>
                                </div>
                            </div>
                            <!-- Form Content -->
                            <div class="col-lg-5 d-none d-lg-flex flex-column justify-content-center">
                                <div class="form-overlay p-4 rounded-3 shadow">
                                    <h3 class="mb-4 text-center">Find Your Perfect Match</h3>
                                    <form>
                                        <!-- Specify Gender -->
                                        <div class="mb-3">
                                            <label for="gender" class="form-label fw-semibold">I am</label>
                                            <select class="form-select form-select-lg" id="gender" name="gender">
                                                <option value="male">A Male</option>
                                                <option value="female">A Female</option>
                                                <option value="other">Other</option>
                                            </select>
                                        </div>
                                        <!-- Looking For -->
                                        <div class="mb-3">
                                            <label for="lookingFor" class="form-label fw-semibold">Looking for</label>
                                            <select class="form-select form-select-lg" id="lookingFor" name="lookingFor">
                                                <option value="male">A man</option>
                                                <option value="female">A woman</option>
                                                <option value="other">Other</option>
                                            </select>
                                        </div>
                                        <!-- Reason -->
                                        <div class="mb-3">
                                            <label for="reason" class="form-label fw-semibold">Reason</label>
                                            <select class="form-select form-select-lg" id="reason" name="reason">
                                                <option value="dating">Dating</option>
                                                <option value="marriage">Marriage</option>
                                                <option value="partners">Partners</option>
                                                <option value="fwb">FWBs</option>
                                            </select>
                                        </div>
                                        <button type="submit" class="btn btn-outline-custom btn-lg w-100 mt-2">Find Matches Now</button>
                                    </form>
                                    <p class="text-center small mt-3 text-dark">By continuing, you agree to our
                                        <a href="#" class="main-color">Terms</a> and
                                        <a href="#" class="main-color">Privacy Policy</a>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Second Hero Section -->
<section class="hero-section position-relative" style="padding: 120px 0 180px;">
    <!-- Background elements -->
    <div class="position-absolute w-100 h-100 overflow-hidden" style="z-index: 1;">
        <div class="position-absolute top-0 start-0 w-100 h-100 bg-pattern"></div>
        <div class="position-absolute floating-heart" style="top: 20%; left: 15%;">❤️</div>
        <div class="position-absolute floating-heart" style="top: 65%; left: 80%;">❤️</div>
        <div class="position-absolute floating-heart" style="top: 35%; left: 70%;">❤️</div>
    </div>

    <!-- Main content -->
    <div class="container position-relative" style="z-index: 2;">
        <div class="row justify-content-center py-5">
            <div class="col-12 col-md-10 col-lg-8 text-center text-white px-4">
                <h1 class="display-4 fw-bold mb-4 gradient-text">Find Your Perfect Match on Raha Tele</h1>
                <p class="lead mb-5">The most trusted platform for connecting singles in Kenya. Discover meaningful relationships today!</p>

                <div class="d-flex flex-wrap justify-content-center gap-3">
                    <a href="{{ route('login') }}" class="btn btn-light btn-lg rounded-pill px-4 py-3 fw-bold shadow-sm" style="color: var(--primary-color);">
                        Join Now For Free
                    </a>
                    <a href="#" class="btn btn-outline-light btn-lg rounded-pill px-4 py-3 fw-bold shadow-sm">
                        How It Works
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Wave divider -->
    <div class="wave-divider position-absolute bottom-0 start-0 w-100" style="transform: scaleY(-1);">
        <svg viewBox="0 0 1200 120" preserveAspectRatio="none" style="display: block; width: 100%; height: 100%;">
            <path d="M0,0V46.29c47.79,22.2,103.59,32.17,158,28,70.36-5.37,136.33-33.31,206.8-37.5C438.64,32.43,512.34,53.67,583,72.05c69.27,18,138.3,24.88,209.4,13.08,36.15-6,69.85-17.84,104.45-29.34C989.49,25,1113-14.29,1200,52.47V0Z" opacity=".25" fill="white"></path>
            <path d="M0,0V15.81C13,36.92,27.64,56.86,47.69,72.05,99.41,111.27,165,111,224.58,91.58c31.15-10.15,60.09-26.47,89.67-39.8,40.92-19,84.73-46,130.83-49.67,36.26-2.85,70.9,9.42,98.6,31.56,31.77,25.39,62.32,62,103.63,73,40.44,10.79,81.35-6.69,119.13-24.28s75.16-39,116.92-43.05c59.73-5.85,113.28,22.88,168.9,38.84,30.2,8.66,59,6.17,87.09-7.5,22.43-10.89,48-26.93,60.65-49.24V0Z" opacity=".5" fill="white"></path>
            <path d="M0,0V5.63C149.93,59,314.09,71.32,475.83,42.57c43-7.64,84.23-20.12,127.61-26.46,59-8.63,112.48,12.24,165.56,35.4C827.93,77.22,886,95.24,951.2,90c86.53-7,172.46-45.71,248.8-84.81V0Z" fill="white"></path>
        </svg>
    </div>
</section>

<!-- Key Features Section -->
<section class="features-section py-5 bg-white">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="display-5 fw-bold mb-3">Why Choose Raha Tele?</h2>
            <p class="lead text-muted mx-auto" style="max-width: 700px;">Discover what makes us the preferred dating platform in Kenya</p>
        </div>
        <div class="row g-4 justify-content-center">
            <div class="col-lg-4 col-md-6">
                <div class="feature-card p-4 h-100 text-center shadow-sm rounded-3 border-0 transition-all">
                    <div class="icon-wrapper mb-4 p-3 rounded-circle d-inline-flex" style="background: rgba(255, 107, 107, 0.1);">
                        <i class="fas fa-heart fa-2x main-color"></i>
                    </div>
                    <h3 class="h4 fw-bold mb-3">Trusted & Reliable</h3>
                    <p class="text-muted mb-0">Voted #1 dating app in Kenya in 2023.</p>
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="feature-card p-4 h-100 text-center shadow-sm rounded-3 border-0 transition-all">
                    <div class="icon-wrapper mb-4 p-3 rounded-circle d-inline-flex" style="background: rgba(255, 107, 107, 0.1);">
                        <i class="fas fa-clock fa-2x main-color"></i>
                    </div>
                    <h3 class="h4 fw-bold mb-3">Find Love Quickly</h3>
                    <p class="text-muted mb-0">Every 14 minutes, someone finds love on Raha Tele.</p>
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="feature-card p-4 h-100 text-center shadow-sm rounded-3 border-0 transition-all">
                    <div class="icon-wrapper mb-4 p-3 rounded-circle d-inline-flex" style="background: rgba(255, 107, 107, 0.1);">
                        <i class="fas fa-gem fa-2x main-color"></i>
                    </div>
                    <h3 class="h4 fw-bold mb-3">Quality Matches</h3>
                    <p class="text-muted mb-0">Meet singles who are serious about dating and relationships.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Success Stories Section -->
<section class="success-stories py-5 py-5" style="background-color: #f0f0f0;">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="display-5 fw-bold mb-3">Real Success Stories</h2>
            <p class="lead text-muted mx-auto" style="max-width: 700px;">Join millions who have found love on Raha Tele</p>
        </div>
        <div class="row g-4 justify-content-center">
            <div class="col-lg-4 col-md-6">
                <div class="card story-card h-100 border-0 shadow-sm overflow-hidden rounded-3 transition-all">
                    <div class="story-image-container ratio ratio-16x9">
                        <img src="{{ asset('assets/images/others/10.jpg') }}" class="img-fluid object-fit-cover" alt="Love Story 1">
                    </div>
                    <div class="card-body text-center p-4">
                        <h5 class="fw-bold mb-3">Over 2 million found love</h5>
                        <p class="text-muted mb-0">... could you be next?</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="card story-card h-100 border-0 shadow-sm overflow-hidden rounded-3 transition-all">
                    <div class="story-image-container ratio ratio-16x9">
                        <img src="{{ asset('assets/images/others/1.jpg') }}" class="img-fluid object-fit-cover" alt="Love Story 2">
                    </div>
                    <div class="card-body text-center p-4">
                        <h5 class="fw-bold mb-3">Meet your perfect match</h5>
                        <p class="text-muted mb-0">... find yours today!</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="card story-card h-100 border-0 shadow-sm overflow-hidden rounded-3 transition-all">
                    <div class="story-image-container ratio ratio-16x9">
                        <img src="{{ asset('assets/images/others/6.jpg') }}" class="img-fluid object-fit-cover" alt="Love Story 3">
                    </div>
                    <div class="card-body text-center p-4">
                        <h5 class="fw-bold mb-3">Find real love</h5>
                        <p class="text-muted mb-0">... your journey starts here!</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="featured-escorts py-5" style="background-color: #f0f0f0;">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="display-5 fw-bold mb-3">Featured Members</h2>
            <p class="lead text-muted mx-auto" style="max-width: 700px;">Meet our most popular members</p>
        </div>

        <div class="d-md-flex justify-content-between align-items-center">
            <p class="mb-3 mb-md-0">Showing {{ $escorts->firstItem() }} - {{ $escorts->lastItem() }} of {{ $escorts->total() }} escorts</p>
            <div class="d-flex">
                <select class="form-select form-select-sm me-2" style="width: 150px;">
                    <option>Sort By</option>
                    <option>Newest</option>
                    <option>Price: Low to High</option>
                    <option>Price: High to Low</option>
                </select>
                <div class="btn-group" role="group">
                    <button type="button" class="btn btn-sm btn-outline-secondary"><i class="fas fa-th"></i></button>
                    <button type="button" class="btn btn-sm btn-outline-secondary"><i class="fas fa-list"></i></button>
                </div>
            </div>
        </div>

        <div class="row g-4 justify-content-center">
            @foreach($escorts as $escort)
            <div class="col-lg-3 col-md-6">
                <div class="member-card card border-0 shadow-sm h-100 overflow-hidden rounded-3 transition-all">
                    @if($escort->is_featured)
                    <div class="member-badge position-absolute bg-danger text-white px-3 py-1 rounded-end">
                        Featured
                    </div>
                    @elseif($escort->hasActiveSubscription())
                    <div class="member-badge position-absolute bg-success text-white px-3 py-1 rounded-end">
                        Premium
                    </div>
                    @else
                    <div class="member-badge position-absolute bg-primary text-white px-3 py-1 rounded-end">
                        Regular
                    </div>
                    @endif

                    <div class="member-image ratio ratio-1x1">
                        @if($escort->primaryImage())
                        <img src="{{ $escort->primaryImage()->image_url }}"
                            class="img-fluid object-fit-cover"
                            alt="Member profile">
                        @else
                        <img src="https://images.unsplash.com/photo-1438761681033-6461ffad8d80?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=80"
                            class="img-fluid object-fit-cover"
                            alt="Member profile">
                        @endif
                    </div>
                    <div class="card-body text-center text-capitalize p-4">
                        <h5 class="fw-bold mb-1">{{ explode(' ',$escort->name)[0] }}, {{ round($escort->age) ?? 0 }}</h5>
                        <p class="text-muted small mb-3">{{ $escort->city }}, {{ $escort->country }}</p>
                        <div class="d-flex justify-content-center gap-2 mb-3">
                            @if($escort->tags && count($escort->tags) > 0)
                            @foreach(array_slice($escort->tags, 0, 3) as $tag)
                            <span class="badge bg-light text-dark">{{ $tag }}</span>
                            @endforeach
                            @else
                            <span class="badge bg-light text-dark">Escort</span>
                            @endif
                        </div>
                        <a href="{{ route('escort_view',$escort->id) }}" class="btn btn-sm btn-outline-custom rounded-pill px-3">
                            <i class="fas fa-eye"></i>
                            View Profile
                        </a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        @if($escorts->hasPages())
        <div class="d-flex justify-content-center mt-5">
            {{ $escorts->links() }}
        </div>
        @endif
    </div>
</section>

@endsection
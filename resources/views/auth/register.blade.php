@extends('layouts.app')

@section('title', 'Subscription Plans')

@section('content')

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <!-- Header Section -->
            <div class="text-center mb-5">
                <h1 class="fw-bold main-color mb-3">Choose Your Plan</h1>
                <p class="lead text-muted">Select the perfect subscription that fits your needs</p>

                <!-- Billing Toggle -->
                <!-- <div class="d-flex justify-content-center align-items-center mb-4">
                    <span class="me-2 fw-medium">Monthly</span>
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" id="billingToggle" style="width: 3rem; height: 1.5rem;">
                    </div>
                    <span class="ms-2 fw-medium">Annual (Save 20%)</span>
                </div> -->
            </div>

            <!-- Plans Grid -->
            <div class="row g-4" id="plansGrid">
                @foreach($subscription_plans as $plan)
                <div class="col-md-4">
                    <div class="card plan-card h-100 border-0 shadow-sm 
                        {{ $plan->is_featured ? 'border-primary border-2 featured-plan' : '' }}"
                        data-plan-id="{{ $plan->id }}"
                        data-plan-name="{{ $plan->name }}"
                        data-plan-price="{{ $plan->formatted_price }}"
                        data-plan-duration="{{ $plan->duration_in_words }}">

                        @if($plan->is_featured)
                        <div class="position-absolute top-0 start-50 translate-middle mt-2">
                            <span class="badge main-bg rounded-pill px-3 py-2">Most Popular</span>
                        </div>
                        @endif

                        <div class="card-body p-4 d-flex flex-column">
                            <h3 class="card-title fw-bold mb-3 text-center">{{ $plan->name }}</h3>

                            <div class="text-center mb-4">
                                <span class="display-4 fw-bold main-color">{{ $plan->formatted_price }}</span>
                                <span class="text-muted">/{{ $plan->duration_in_words }}</span>
                            </div>

                            <ul class="list-unstyled mb-4">
                                <li class="mb-2 d-flex align-items-center">
                                    <i class="fas fa-check-circle main-color me-2"></i>
                                    <span>Priority listing for {{ $plan->duration_in_words }}</span>
                                </li>
                                <li class="mb-2 d-flex align-items-center">
                                    <i class="fas fa-check-circle main-color me-2"></i>
                                    <span>{{ $plan->is_featured ? 'Featured profile' : 'Standard visibility' }}</span>
                                </li>
                                <li class="mb-2 d-flex align-items-center">
                                    <i class="fas fa-check-circle main-color me-2"></i>
                                    <span>24/7 customer support</span>
                                </li>
                            </ul>

                            <div class="mt-auto text-center">
                                <button class="btn btn-outline-custom select-plan-btn w-100 py-2">
                                    {{ $plan->is_featured ? 'Get Started' : 'Choose Plan' }}
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            <!-- Registration Section (Initially Hidden) -->
            <div class="row mt-5" id="registerSection" style="display: none;">
                <div class="col-lg-8 mx-auto">
                    <form id="register-form" action="{{ route('register') }}" method="post">
                        @csrf

                        <div class="card border-0 shadow-sm">
                            <div class="card-body p-4">

                                <h4 class="fw-bold mb-4 text-center">Complete Your Subscription</h4>

                                <div class="alert alert-primary d-flex align-items-center mb-4">
                                    <i class="fas fa-info-circle me-2"></i>
                                    <div id="selectedPlanInfo"></div>
                                </div>

                                <h5 class="fw-bold mb-3">Billing Address</h5>

                                <!-- name Field -->
                                <div class="mb-3">
                                    <label for="name" class="form-label">{{ __('Full Name') }}</label>
                                    <div class="input-group">
                                        <span class="input-group-text">
                                            <i class="fas fa-user"></i>
                                        </span>
                                        <input id="name" type="name" class="form-control @error('name') is-invalid @enderror"
                                            name="name" value="{{ old('name') }}" required autocomplete="name"
                                            placeholder="Full Name">
                                        @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Email Field -->
                                <div class="mb-3">
                                    <label for="email" class="form-label">{{ __('Email Address') }}</label>
                                    <div class="input-group">
                                        <span class="input-group-text">
                                            <i class="fas fa-envelope"></i>
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
                                <div class="mb-3">
                                    <label for="password" class="form-label">{{ __('Password') }}</label>
                                    <div class="input-group">
                                        <span class="input-group-text">
                                            <i class="fas fa-lock"></i>
                                        </span>
                                        <input id="password" type="password" class="form-control @error('password') is-invalid @enderror"
                                            name="password" required autocomplete="new-password"
                                            placeholder="Enter your password">
                                        <button class="btn btn-outline-secondary toggle-password" type="button">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        @error('password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Password Field -->
                                <div class="mb-3">
                                    <label for="password_confirmation" class="form-label">{{ __('Confirm Password') }}</label>
                                    <div class="input-group">
                                        <span class="input-group-text">
                                            <i class="fas fa-lock"></i>
                                        </span>
                                        <input id="password_confirmation" type="password" class="form-control @error('password_confirmation') is-invalid @enderror"
                                            name="password_confirmation" required autocomplete="new-password"
                                            placeholder="Confirm Password">
                                        @error('password_confirmation')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Payment Method -->
                                <h5 class="fw-bold mb-3">Payment Method</h5>
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <div class="payment-option-wrapper">
                                            <input type="radio" name="paymentMethod" id="mpesa" value="mpesa" class="payment-radio" checked hidden>
                                            <label for="mpesa" class="payment-option-btn active">
                                                <img src="{{ asset('assets/images/mpesa-logo.png') }}" alt="M-Pesa" width="40" class="me-2">
                                                <span>M-Pesa</span>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="payment-option-wrapper">
                                            <input type="radio" name="paymentMethod" id="card" value="card" class="payment-radio" hidden>
                                            <label for="card" class="payment-option-btn">
                                                <i class="fab fa-cc-visa main-color me-2"></i>
                                                <span>Credit/Debit Card</span>
                                            </label>
                                        </div>
                                    </div>
                                </div>

                                <div class="mt-4" id="mpesaForm">
                                    <input type="hidden" id="planId" name="planId">
                                    <div class="mb-3">
                                        <label for="phoneNumber" class="form-label">{{ __('M-Pesa Phone Number') }}</label>
                                        <div class="input-group">
                                            <span class="input-group-text">
                                                <i class="fas fa-mobile-alt"></i>
                                            </span>
                                            <input id="phoneNumber" type="phoneNumber" class="form-control @error('phoneNumber') is-invalid @enderror"
                                                name="phoneNumber" value="{{ old('phoneNumber') }}" required
                                                placeholder="e.g. 254712345678">
                                            @error('phoneNumber')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <button type="submit" class="btn btn-custom btn-lg w-100">
                                        <i class="fas fa-mobile-alt me-2"></i> Pay with M-Pesa
                                    </button>
                                </div>

                                <div class="mt-4" id="cardForm" style="display: none;">
                                    <div class="alert alert-info">
                                        Card payments coming soon. Please use M-Pesa for now.
                                    </div>
                                </div>

                                <div class="text-center mt-4">
                                    <button class="btn btn-link text-muted" id="backToPlans">
                                        <i class="fas fa-arrow-left me-1"></i> Back to plans
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        // Plan selection handler
        $('.plan-card, .select-plan-btn').on('click', function(e) {
            e.stopPropagation();

            const card = $(this).hasClass('plan-card') ? $(this) : $(this).closest('.plan-card');
            const planId = card.data('plan-id');
            const planName = card.data('plan-name');
            const planPrice = card.data('plan-price');
            const planDuration = card.data('plan-duration');

            // Update selected plan info
            $('#planId').val(planId);

            $('#selectedPlanInfo').html(`
                You've selected: <strong>${planName}</strong> 
                (<span>${planDuration}</span>) for 
                <strong>${planPrice}</strong>
            `);

            // Hide plans grid and show payment section with animation
            $('#plansGrid').hide();
            $('#registerSection').css('display', 'flex').hide().fadeIn(300).addClass('fade-in');
            $('html, body').animate({
                scrollTop: 0
            }, 'smooth');
        });

        // Back to plans button
        $('#backToPlans').on('click', function() {
            $('#registerSection').fadeOut(300, function() {
                $('#plansGrid').fadeIn(300);
            });
        });

        // Enhanced payment method toggle with animation and visual feedback
        $('.payment-option-btn').on('click', function() {
            // Get the associated radio input
            const radio = $(this).prev('.payment-radio');
            const paymentMethod = radio.attr('id');

            // Skip if already active
            if (radio.is(':checked')) return;

            // Update active state
            $('.payment-option-btn').removeClass('active');
            $(this).addClass('active');
            radio.prop('checked', true);

            // Handle form visibility with better animations
            if (paymentMethod === 'mpesa') {
                $('#cardForm').slideUp(250, function() {
                    $('#mpesaForm').slideDown(250);
                });
            } else {
                $('#mpesaForm').slideUp(250, function() {
                    $('#cardForm').slideDown(250);
                });
            }

            // Add visual feedback
            $(this).addClass('pulse-animation');
            setTimeout(() => {
                $(this).removeClass('pulse-animation');
            }, 300);
        });

        // Initialize forms based on default selection
        if ($('#mpesa').is(':checked')) {
            $('#mpesaForm').show();
            $('#cardForm').hide();
        } else {
            $('#mpesaForm').hide();
            $('#cardForm').show();
        }

        // M-Pesa payment handler
        $('#register-form').validate({
            submitHandler: function(form, event) {
                event.preventDefault();

                const phoneNumber = $('#phoneNumber').val().trim();
                const btn = $(this);

                // Validate Kenyan phone number format
                if (!/^(\+254|0)[17]\d{8}$/.test(phoneNumber)) {
                    Swal.fire('Error', 'Please enter a valid Kenyan phone number (e.g. 254712345678 or 0712345678).', 'error');
                    return;
                }

                // Handle payment here
                Swal.fire({
                    icon: 'warning',
                    title: 'Create An Account',
                    text: 'Are you sure you want to create an account for raha tele escort group?.',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, Initiate'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Loader
                        Swal.fire({
                            icon: 'info',
                            title: 'Submitting Information',
                            text: 'Please wait while your Account is being created',
                            allowOutsideClick: false,
                            didOpen: () => {
                                Swal.showLoading();
                            }
                        });
                        // Submit
                        $.ajax({
                            url: $(form).attr('action'),
                            type: 'POST',
                            data: new FormData(form),
                            contentType: false,
                            processData: false,
                            success: function(response) {
                                Swal.fire('Success', response.message, 'success').then(() => {
                                    window.location.href = response.redirect;
                                });
                            },
                            error: function(xhr) {
                                Swal.fire('Error!', xhr.responseJSON.error || xhr.responseJSON.message, 'error');
                            }
                        });
                    }
                });
            }
        });

        // Billing toggle handler
        $('#billingToggle').on('change', function() {
            const isAnnual = $(this).is(':checked');

            // In a real app, you would update prices here
            console.log('Billing cycle changed to:', isAnnual ? 'Annual' : 'Monthly');

            // Example of how you might toggle prices
            if (isAnnual) {
                $('.plan-card').each(function() {
                    const monthlyPrice = parseFloat($(this).data('monthly-price'));
                    const annualPrice = monthlyPrice * 12 * 0.8; // 20% discount
                    $(this).find('.display-4').text('Ksh ' + annualPrice.toFixed(2));
                });
            } else {
                $('.plan-card').each(function() {
                    const monthlyPrice = parseFloat($(this).data('monthly-price'));
                    $(this).find('.display-4').text('Ksh ' + monthlyPrice.toFixed(2));
                });
            }
        });

        // Hover effects for plan cards
        $('.plan-card').hover(
            function() {
                $(this).css({
                    'transform': 'translateY(-5px)',
                    'box-shadow': '0 10px 25px rgba(0, 0, 0, 0.1)'
                });
            },
            function() {
                $(this).css({
                    'transform': '',
                    'box-shadow': ''
                });
            }
        );
    });
</script>
@endpush
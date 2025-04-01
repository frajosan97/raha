@extends('layouts.app')

@section('title', 'Subscriptions Payment')

@section('content')

<div class="container py-5">

    @php
    $balance = $subscription->plan->price-$subscription->amount_paid;
    @endphp

    <style>
        .payment-card .card {
            border-radius: 12px;
            transition: all 0.3s ease;
        }

        .payment-card .card:hover {
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }

        .payment-card .input-group-text {
            border-right: none;
        }

        .payment-card #phoneNumber {
            border-left: none;
        }

        .payment-card .btn-lg {
            padding: 0.75rem 1.5rem;
            font-size: 1.1rem;
            border-radius: 8px;
        }

        .payment-card .list-unstyled li {
            border-bottom: 1px dashed #eee;
        }

        .payment-card .list-unstyled li:last-child {
            border-bottom: none;
        }
    </style>

    <div class="col-lg-8 mx-auto payment-card">
        <div class="card border-0 shadow-lg overflow-hidden">
            <!-- Card Header -->
            <div class="card-header main-bg text-white py-3">
                <div class="d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">
                        <i class="fas fa-file-invoice-dollar me-2"></i>
                        Payment Summary
                    </h4>
                    <span class="badge bg-white main-color fs-6">
                        #{{ $subscription->invoice_number }}
                    </span>
                </div>
            </div>

            <!-- Card Body -->
            <div class="card-body p-4">
                <!-- Plan Details -->
                <div class="mb-4">
                    <h5 class="main-color mb-3">
                        <i class="fas fa-cube me-2"></i>Subscription Plan
                    </h5>
                    <div class="ps-4">
                        <h4 class="fw-bold text-dark">{{ $subscription->plan->name }}</h4>
                        <div class="d-flex align-items-center text-muted mb-2">
                            <i class="fas fa-calendar-alt me-2"></i>
                            <span>Billing Cycle: {{ $subscription->plan->duration_days }} days</span>
                        </div>
                    </div>
                </div>

                <!-- Payment Breakdown -->
                <div class="border-top border-bottom py-4 mb-4">
                    <div class="row">
                        <div class="col-md-6 mb-3 mb-md-0">
                            <h5 class="main-color mb-3">
                                <i class="fas fa-receipt me-2"></i>Amount Details
                            </h5>
                            <ul class="list-unstyled ps-4">
                                <li class="d-flex justify-content-between py-2">
                                    <span>Billed Amount:</span>
                                    <span class="fw-bold">{{ number_format($subscription->plan->price, 2) }}</span>
                                </li>
                                <li class="d-flex justify-content-between py-2">
                                    <span>Paid Amount:</span>
                                    <span class="fw-bold text-success">{{ number_format($subscription->amount_paid, 2) }}</span>
                                </li>
                                <li class="d-flex justify-content-between py-2">
                                    <span>Balance Due:</span>
                                    <span class="fw-bold text-danger">{{ number_format($balance, 2) }}</span>
                                </li>
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <h5 class="main-color mb-3">
                                <i class="fas fa-credit-card me-2"></i>Payment Method
                            </h5>
                            <div class="ps-4">
                                <div class="d-flex align-items-center mb-3">
                                    <img src="{{ asset('assets/images/mpesa-logo.png') }}" alt="M-Pesa" height="30" class="me-3">
                                    <div>
                                        <h6 class="mb-0">M-Pesa</h6>
                                        <small class="text-muted">Pay via STK Push</small>
                                    </div>
                                </div>
                                <div class="alert alert-info mb-0">
                                    <i class="fas fa-info-circle me-2"></i>
                                    You will receive a payment request on your phone
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Payment Form -->
                <form id="payment-form" action="{{ route('mpesa.stkPush') }}" method="post">
                    @csrf
                    <input type="hidden" name="reference" value="{{ auth()->user()->id }}">
                    <input type="hidden" name="amount" value="{{ number_format($balance, 0) }}">

                    <div class="mb-4">
                        <label for="phoneNumber" class="form-label fw-bold">
                            <i class="fas fa-mobile-alt me-2"></i>M-Pesa Phone Number
                        </label>
                        <div class="input-group">
                            <span class="input-group-text bg-light">
                                <i class="fas fa-phone main-color"></i>
                            </span>
                            <input type="tel"
                                class="form-control"
                                id="phoneNumber"
                                name="phoneNumber"
                                value="{{ auth()->user()->phone }}"
                                placeholder="2547XXXXXXXX"
                                required>
                        </div>
                        <small class="text-muted">Enter your M-Pesa registered phone number</small>
                    </div>

                    <div class="d-grid">
                        <button type="submit" class="btn btn-custom btn-lg shadow">
                            <i class="fas fa-paper-plane me-2"></i>
                            PAY KES {{ number_format($balance, 0) }} NOW
                        </button>
                    </div>
                </form>
            </div>

            <!-- Card Footer -->
            <div class="card-footer bg-light">
                <div class="text-center text-muted small">
                    <i class="fas fa-lock me-2"></i> Secure payment processing
                    <span class="mx-2">•</span>
                    <i class="fas fa-shield-alt me-2"></i> 256-bit SSL encryption
                </div>
            </div>
        </div>
    </div>

</div>

@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        // Submit form
        $('#payment-form').validate({
            submitHandler: function(form, event) {
                event.preventDefault();

                Swal.fire({
                    title: 'Subscription Payment?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, Pay'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Loader
                        Swal.fire({
                            icon: 'info',
                            title: 'Subscription Payment',
                            text: 'Please wait while M-Pesa payment is being Payed.',
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
                                Swal.fire('Success', response.success, 'success').then(() => {
                                    $('#gameModal').modal('hide');
                                    table.ajax.reload();
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
    });
</script>
@endpush
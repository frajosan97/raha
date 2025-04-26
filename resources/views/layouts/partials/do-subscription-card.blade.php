@php
    $user = auth()->user();
    $inactiveSubscription = $user->inactiveSubscription;
@endphp

@if ($inactiveSubscription && $inactiveSubscription->plan)
    <div class="card border-danger shadow-sm mb-4">
        <div class="card-header bg-danger text-white d-flex align-items-center">
            <i class="fas fa-exclamation-triangle me-2"></i>
            <span>Payment Required</span>
        </div>

        <div class="card-body">
            <div class="d-flex align-items-start">
                <i class="fas fa-credit-card text-danger fa-2x me-3"></i>

                <div class="flex-grow-1">
                    <h5 class="card-title mb-3">Subscription Pending Payment</h5>

                    <div class="alert alert-warning d-flex align-items-center mb-4" role="alert">
                        <i class="fas fa-info-circle me-2"></i>
                        <div>Your subscription is inactive due to unpaid invoice. Please complete payment to restore access.</div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-6 mb-2">
                            <p class="mb-1"><strong>Plan:</strong> {{ $inactiveSubscription->plan->name }}</p>
                            <p class="mb-1"><strong>Amount Billed:</strong> KES {{ number_format($inactiveSubscription->amount_billed, 2) }}</p>
                            <p class="mb-1"><strong>Amount Paid:</strong> KES {{ number_format($inactiveSubscription->amount_paid, 2) }}</p>
                            <p class="mb-1"><strong>Amount Due:</strong> KES {{ number_format($inactiveSubscription->amount_billed - $inactiveSubscription->amount_paid, 2) }}</p>
                        </div>
                        <div class="col-md-6 mb-2">
                            <p class="mb-1"><strong>Duration:</strong> {{ $inactiveSubscription->plan->duration_in_words }}</p>
                            <p class="mb-1"><strong>Status:</strong> {{ ucfirst($inactiveSubscription->payment_status) }}</p>
                        </div>
                    </div>

                    <div class="d-flex flex-wrap gap-2">
                        <a href="{{ route('subscription.pay', $inactiveSubscription->id) }}" class="btn btn-danger">
                            <i class="fas fa-credit-card me-1"></i> Pay Now
                        </a>
                        <a href="{{ route('subscription.index') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-file-invoice me-1"></i> View Subscriptions
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif

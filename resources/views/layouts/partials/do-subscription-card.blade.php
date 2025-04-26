@php
$user = auth()->user();
$inactiveSubscription = $user->inactiveSubscription;
@endphp

@if($inactiveSubscription && $inactiveSubscription->plan)
<div class="card border-danger shadow-sm mb-4">
    <div class="card-header bg-danger text-white d-flex justify-content-between align-items-center">
        <span>
            <i class="fas fa-exclamation-triangle me-2"></i>
            Payment Required
        </span>
    </div>
    <div class="card-body">
        <div class="d-flex">
            <div class="flex-shrink-0 me-3">
                <i class="fas fa-credit-card text-danger fa-2x"></i>
            </div>
            <div class="flex-grow-1">
                <h5 class="card-title">Subscription Pending Payment</h5>
                <div class="alert alert-warning mb-3">
                    <i class="fas fa-info-circle me-2"></i>
                    Your subscription is inactive due to unpaid invoice. Complete payment to restore access.
                </div>

                <div class="subscription-details mb-4">
                    <div class="row">
                        <div class="col-md-6">
                            <p class="mb-1"><strong>Plan:</strong> {{ $inactiveSubscription->plan->name }}</p>
                            <p class="mb-1"><strong>Amount Due:</strong> KES {{ number_format($inactiveSubscription->plan->price, 2) }}</p>
                        </div>
                        <div class="col-md-6">
                            <p class="mb-1"><strong>Duration:</strong> {{ $inactiveSubscription->plan->duration_in_words }}</p>
                            <p class="mb-1"><strong>Status:</strong> {{ ucfirst($inactiveSubscription->payment_status) }}</p>
                        </div>
                    </div>
                </div>

                <div class="d-grid gap-2 d-md-flex justify-content-md-start">
                    <a href="{{ route('subscription.pay', $inactiveSubscription->id) }}" class="btn btn-danger me-md-2">
                        <i class="fas fa-credit-card me-1"></i> Pay Now
                    </a>
                    <a href="{{ route('subscription.index') }}" class="btn btn-outline-secondary me-md-2">
                        <i class="fas fa-file-invoice me-1"></i> View Subscriptions
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endif
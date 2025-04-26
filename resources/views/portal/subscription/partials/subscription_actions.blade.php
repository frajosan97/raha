@php
$payRoute = route('subscription.pay', $subscription->id);
$supportRoute = '/';
@endphp

<div class="btn-group btn-group-sm" role="group" aria-label="Subscription actions">
    @switch($subscription->payment_status)
    @case('paid')
    <button class="btn btn-outline-primary" disabled>
        <i class="fas fa-file-invoice me-1"></i> View Receipt
    </button>
    @break

    @case('pending')
    <a href="{{ $payRoute }}" class="btn btn-outline-primary">
        <i class="fas fa-credit-card me-1"></i> Pay Now
    </a>
    <button class="btn btn-outline-danger cancel-subscription" data-id="{{ $subscription->id }}">
        <i class="fas fa-times-circle me-1"></i> Cancel
    </button>
    @break

    @case('failed')
    <button class="btn btn-outline-primary retry-payment" data-id="{{ $subscription->id }}">
        <i class="fas fa-sync-alt me-1"></i> Retry
    </button>
    <a href="{{ $supportRoute }}" class="btn btn-outline-secondary">
        <i class="fas fa-headset me-1"></i> Support
    </a>
    @break

    @case('partial')
    <a href="{{ $payRoute }}" class="btn btn-outline-primary">
        <i class="fas fa-wallet me-1"></i> Pay Balance
    </a>
    <button class="btn btn-outline-success view-invoice" data-id="{{ $subscription->id }}">
        <i class="fas fa-receipt me-1"></i> Invoice
    </button>
    @break

    @default
    <button class="btn btn-outline-secondary review-subscription" data-id="{{ $subscription->id }}">
        <i class="fas fa-search me-1"></i> Review
    </button>
    @endswitch
</div>
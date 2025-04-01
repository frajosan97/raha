@if(isset($subscription))
<div class="d-flex text-nowrap">
    <!-- Edit Button (Triggers AJAX Edit) -->
    @if($subscription->amount_paid < $subscription->plan->price)
        <a href="{{ route('subscription.pay',$subscription->id) }}" class="btn btn-sm btn-outline-custom p-0 me-2">
            <i class="fas fa-wallet"></i> Pay
        </a>
        @endif

        <!-- Delete Button (AJAX) -->
        <button type="button" class="btn btn-sm btn-outline-danger delete-subscription" data-id="{{ $subscription->id }}" title="Delete subscription">
            <i class="fas fa-trash-alt"></i>
        </button>
</div>
@endif
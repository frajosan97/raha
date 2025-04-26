@php
$badgeConfig = [
'paid' => [
'class' => 'btn-success',
'icon' => 'fa-check-circle',
'dropdown' => false
],
'pending' => [
'class' => 'btn-warning text-dark',
'icon' => 'fa-clock',
'dropdown' => false
],
'failed' => [
'class' => 'btn-danger',
'icon' => 'fa-exclamation-circle',
'dropdown' => false
],
'partial' => [
'class' => 'btn-info',
'icon' => 'fa-money-bill-wave',
'dropdown' => true,
'items' => [
'Paid: ' . number_format($subscription->amount_paid, 2),
'Due: ' . number_format($subscription->plan->price - $subscription->amount_paid, 2)
]
],
'default' => [
'class' => 'btn-secondary',
'icon' => 'fa-question-circle',
'dropdown' => false
]
];

$status = array_key_exists($subscription->payment_status, $badgeConfig)
? $subscription->payment_status
: 'default';
$config = $badgeConfig[$status];
@endphp

<div class="btn-group" role="group">
    <button type="button" class="btn btn-sm {{ $config['class'] }} text-start" style="min-width: 100px;" disabled>
        <i class="fas {{ $config['icon'] }} me-1"></i>
        {{ ucfirst($subscription->payment_status) }}
    </button>

    @if($config['dropdown'] ?? false)
    <button type="button" class="btn btn-sm {{ $config['class'] }} dropdown-toggle dropdown-toggle-split"
        data-bs-toggle="dropdown"
        aria-expanded="false">
        <span class="visually-hidden">Toggle Details</span>
    </button>
    <ul class="dropdown-menu">
        @foreach($config['items'] as $item)
        <li><span class="dropdown-item-text small">{{ $item }}</span></li>
        @endforeach
    </ul>
    @endif
</div>
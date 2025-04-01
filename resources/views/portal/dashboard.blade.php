@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')

<div class="container py-5">
    <!-- Salutation -->
    <h2 class="main-color border-bottom-custom text-capitalize pb-3 mb-3">welcome back, {{ explode(' ', auth()->user()->name)[0] }}!</h2>

    @if(auth()->user()->hasActiveSubscription())
    <!-- Display nothing or active subscription details if needed -->
    @elseif(auth()->user()->inactiveSubscription())
    <!-- Dormant subscription -->
    @include('layouts.partials.do-subscription-card')
    @else
    <!-- Previous no subscription card implementation -->
    @include('layouts.partials.no-subscription-card')
    @endif

</div>

@endsection
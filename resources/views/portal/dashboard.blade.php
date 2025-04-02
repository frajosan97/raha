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

@push('scripts')
<script>
    $(document).ready(function() {
        navigator.geolocation.getCurrentPosition(
            function(position) {
                let latitude = position.coords.latitude;
                let longitude = position.coords.longitude;
                updateUserLocation(latitude, longitude);
            },
            function(error) {
                console.error("Geolocation error:", error.message);
            }
        );

        function updateUserLocation(lat, lng) {
            $.ajax({
                url: "{{ route('updateUserLocation') }}",
                type: "GET",
                data: {
                    latitude: lat,
                    longitude: lng
                },
                success: function(response) {
                    const Toast = Swal.mixin({
                        toast: true,
                        position: "top-end",
                        showConfirmButton: false,
                        timer: 3000,
                        timerProgressBar: true,
                        didOpen: (toast) => {
                            toast.onmouseenter = Swal.stopTimer;
                            toast.onmouseleave = Swal.resumeTimer;
                        }
                    });
                    Toast.fire({
                        icon: "success",
                        title: "Your location saved successfully"
                    });
                },
                error: function(xhr) {
                    const Toast = Swal.mixin({
                        toast: true,
                        position: "top-end",
                        showConfirmButton: false,
                        timer: 3000,
                        timerProgressBar: true,
                        didOpen: (toast) => {
                            toast.onmouseenter = Swal.stopTimer;
                            toast.onmouseleave = Swal.resumeTimer;
                        }
                    });
                    Toast.fire({
                        icon: "error",
                        title: "Error updating your location!",
                    });
                    console.error("Error:", xhr.responseText);
                }
            });
        }
    });
</script>
@endpush
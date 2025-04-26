@extends('layouts.app')

@section('title', 'Singles Near Me')

@section('content')

<div class="banner">
    <h1>Singles Near You ❤️</h1>
    <p>Find and connect with amazing people near you!</p>
</div>

<section class="featured-escorts py-5">
    <div class="container">
        <div class="d-md-flex justify-content-between align-items-center bg-light p-2 rounded-3 mb-3">
            <p class="mb-3 mb-md-0">Showing <span id="escort-count"></span> of <span id="total-count"></span> escorts</p>
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

        <div class="row g-4 justify-content-center" id="escorts-list">
            <!-- Dynamically loaded profiles will be appended here -->
        </div>

        <div id="pagination-container">
            <!-- Pagination links will be dynamically added here if applicable -->
        </div>
    </div>
</section>

@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        // Helper: Generate escort profile card
        function generateEscortProfile(escort) {
            const planName = escort?.active_subscription?.plan?.name || 'No Plan';
            const badgeClass = planName.toLowerCase() === 'premium' ? 'bg-success' : 'bg-danger';
            const imageUrl = escort?.primary_image?.image_path || 'https://images.unsplash.com/photo-1438761681033-6461ffad8d80?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=80';
            const firstName = escort?.name?.split(' ')[0] || 'Unnamed';

            return `
                <div class="col-lg-3 col-md-6">
                    <div class="member-card card border-0 shadow-sm h-100 overflow-hidden rounded-3 transition-all">
                        <div class="member-badge position-absolute text-capitalize ${badgeClass} text-white px-3 py-1 rounded-end">
                            ${planName}
                        </div>
                        <div class="member-image ratio ratio-1x1">
                            <img src="${imageUrl}" class="img-fluid object-fit-cover" alt="Member profile">
                        </div>
                        <div class="card-body text-start text-capitalize p-4">
                            <h5 class="fw-bold mb-1"><strong>Name:</strong> ${firstName}</h5>
                            <p class="text-muted small mb-3">${createEscortStatement(escort)}</p>
                            <a href="{{ route('escort_view', '') }}/${escort.id}" class="btn btn-sm btn-outline-custom rounded-pill p-2 w-100">
                                <i class="fas fa-eye"></i> View Profile
                            </a>
                        </div>
                    </div>
                </div>`;
        }

        // Helper: Generate "No Singles" alert
        function generateNoSinglesAlert() {
            return `
                <div class="col-md-12">
                    <div class="alert alert-warning text-center p-5">
                        <h3><i class="fas fa-exclamation-triangle"></i> No Singles Found</h3>
                        <p class="lead">It looks like there are no singles nearby at the moment.</p>
                        <p>Try adjusting your location or come back later.</p>
                        <a href="/dashboard" class="btn btn-outline-custom p-2 mt-3">Update Location</a>
                    </div>
                </div>`;
        }

        // Main function: Fetch singles based on geolocation
        function fetchSingles(lat, lng) {
            $.ajax({
                url: "{{ route('singles-near-me') }}",
                type: "GET",
                data: {
                    latitude: lat,
                    longitude: lng
                },
                dataType: "json",
                beforeSend: function() {
                    $("#escorts-list").html('<div class="text-center p-5">Loading singles near you...</div>');
                },
                success: function(response) {
                    $("#escorts-list").empty();
                    $("#escort-count").text(response.data.length);
                    $("#total-count").text(response.total);

                    if (response.data.length > 0) {
                        response.data.forEach(function(escort) {
                            $("#escorts-list").append(generateEscortProfile(escort));
                        });
                    } else {
                        $("#escorts-list").append(generateNoSinglesAlert());
                    }

                    if (response.pagination) {
                        $("#pagination-container").html(`
                            <div class="d-flex justify-content-center mt-5">
                                ${response.pagination}
                            </div>
                        `);
                    } else {
                        $("#pagination-container").empty();
                    }
                },
                error: function(xhr) {
                    alert("Error fetching singles. Please try again later.");
                    console.error("Fetch error:", xhr.responseText);
                }
            });
        }

        // Detect user location and trigger singles fetch
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(
                function(position) {
                    fetchSingles(position.coords.latitude, position.coords.longitude);
                },
                function(error) {
                    alert("Failed to retrieve your location. Please allow GPS permission.");
                    console.error("Geolocation error:", error.message);
                }, {
                    enableHighAccuracy: true,
                    timeout: 10000,
                    maximumAge: 0
                }
            );
        } else {
            alert("Geolocation is not supported by your browser.");
        }
    });
</script>
@endpush
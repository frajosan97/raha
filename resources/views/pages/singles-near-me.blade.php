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
        if (!navigator.geolocation) {
            alert("Geolocation is not supported by your browser.");
            return;
        }

        navigator.geolocation.getCurrentPosition(
            function(position) {
                let latitude = position.coords.latitude;
                let longitude = position.coords.longitude;
                fetchSingles(latitude, longitude);
            },
            function(error) {
                alert("Failed to fetch your location. Please enable GPS.");
                console.error("Geolocation error:", error.message);
            }
        );

        function fetchSingles(lat, lng) {
            $.ajax({
                url: "{{ route('singles-near-me') }}",
                type: "GET",
                data: {
                    latitude: lat,
                    longitude: lng
                },
                success: function(response) {
                    $("#escorts-list").empty(); // Clear previous results
                    $("#escort-count").text(response.data.length);
                    $("#total-count").text(response.total);

                    if (response.data.length > 0) {
                        console.log("Full response:", response.data);
                        $.each(response.data, function(index, escort) {
                            // variabes
                            const badgeClass = escort.active_subscription?.plan?.name === 'premium' ? 'bg-success' : 'bg-danger';
                            const imageUrl = escort.primary_image?.image_path || 'https://images.unsplash.com/photo-1438761681033-6461ffad8d80?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=80';
                            // html to render
                            let profileHtml = `
                                <div class="col-lg-3 col-md-6">
                                    <div class="member-card card border-0 shadow-sm h-100 overflow-hidden rounded-3 transition-all">
                                        <div class="member-badge position-absolute text-capitalize ${badgeClass} text-white px-3 py-1 rounded-end">
                                            ${escort.active_subscription.plan.name}
                                        </div>
                                        <div class="member-image ratio ratio-1x1">
                                            <img src="${imageUrl}" class="img-fluid object-fit-cover" alt="Member profile">
                                        </div>
                                        <div class="card-body text-start text-capitalize p-4">
                                            <h5 class="fw-bold mb-1"><strong>Name:</strong> ${escort.name.split(' ')[0]}</h5>
                                            <p class="text-muted small mb-3">${createEscortStatement(escort)}</p>
                                            <a href="{{ route('escort_view', '') }}/${escort.id}" class="btn btn-sm btn-outline-custom rounded-pill p-2 w-100">
                                                <i class="fas fa-eye"></i> View Profile
                                            </a>
                                        </div>
                                    </div>
                                </div>`;

                            $("#escorts-list").append(profileHtml);
                        });
                    } else {
                        $("#escorts-list").append(`
                        <div class="col-md-12">
                            <div class="alert alert-warning text-center p-5">
                                <h3><i class="fas fa-exclamation-triangle"></i> No Singles Found</h3>
                                <p class="lead">It looks like there are no singles nearby at the moment.</p>
                                <p>Try adjusting your location or come back later.</p>
                                <a href="/portal/dashboard" class="btn btn-outline-custom p-2 mt-3">Update Location</a>
                            </div>
                        </div>`);
                    }

                    if (response.pagination) {
                        let paginationHtml = `
                            <div class="d-flex justify-content-center mt-5">
                                ${response.pagination}
                            </div>`;
                        $("#pagination-container").html(paginationHtml);
                    }
                },
                error: function(xhr) {
                    alert("Error fetching singles. Please try again.");
                    console.error("Error:", xhr.responseText);
                }
            });
        }
    });
</script>
@endpush
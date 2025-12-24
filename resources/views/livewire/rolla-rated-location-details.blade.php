<div>
    <title>Rolla-Rated Location Details</title>
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center py-4">
        <div class="d-block mb-4 mb-md-0">
            <nav aria-label="breadcrumb" class="d-none d-md-inline-block">
                <ol class="breadcrumb breadcrumb-dark breadcrumb-transparent">
                    <li class="breadcrumb-item"><a href="/dashboard">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="/rolla-rated-locations">Rolla-Rated Locations</a></li>
                    <li class="breadcrumb-item active">Location Details</li>
                </ol>
            </nav>
            <h2 class="h4">Rolla-Rated Location Details</h2>
        </div>
        <div class="btn-toolbar mb-2 mb-md-0">
            <a href="/rolla-rated-location/edit/{{ $location->id }}" class="btn btn-sm btn-gray-800 d-inline-flex align-items-center">
                <svg class="icon icon-xs me-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                </svg>
                Edit Location
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-12 col-xl-8">
            <div class="card card-body border-0 shadow mb-4">
                <h2 class="h5 mb-4">Location Information</h2>
                <div class="row">
                    <div class="col-md-12 mb-3">
                        <label class="form-label">Address</label>
                        <p class="form-control-plaintext">{{ $location->address }}</p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Latitude</label>
                        <p class="form-control-plaintext">{{ $location->latitude }}</p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Longitude</label>
                        <p class="form-control-plaintext">{{ $location->longitude }}</p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 mb-3">
                        <label class="form-label">Business Website URL</label>
                        <p class="form-control-plaintext">
                            <a href="{{ $location->business_url }}" target="_blank" rel="noopener noreferrer">
                                {{ $location->business_url }}
                            </a>
                        </p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Status</label>
                        <p class="form-control-plaintext">
                            @if($location->is_active)
                                <span class="badge bg-success">Active</span>
                            @else
                                <span class="badge bg-secondary">Inactive</span>
                            @endif
                        </p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Created By</label>
                        <p class="form-control-plaintext">
                            {{ $location->admin->first_name ?? '' }} {{ $location->admin->last_name ?? '' }}
                        </p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Created At</label>
                        <p class="form-control-plaintext">{{ $location->created_at->format('Y-m-d H:i:s') }}</p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Updated At</label>
                        <p class="form-control-plaintext">{{ $location->updated_at->format('Y-m-d H:i:s') }}</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-xl-4">
            <div class="card card-body border-0 shadow mb-4">
                <h2 class="h5 mb-4">Location on Map</h2>
                <div wire:ignore id="map" style="width: 100%; height: 300px; border-radius: 8px; border: 1px solid #ddd;"></div>
            </div>
        </div>
    </div>
</div>

<script src="https://api.mapbox.com/mapbox-gl-js/v2.15.0/mapbox-gl.js"></script>
<link href="https://api.mapbox.com/mapbox-gl-js/v2.15.0/mapbox-gl.css" rel="stylesheet" />

<script>
document.addEventListener('DOMContentLoaded', function() {
    mapboxgl.accessToken = 'pk.eyJ1Ijoicm9sbGExIiwiYSI6ImNseGppNHN5eDF3eHoyam9oN2QyeW5mZncifQ.iLIVq7aRpvMf6J3NmQTNAw';
    
    var lat = {{ $location->latitude ?? 37.7749 }};
    var lng = {{ $location->longitude ?? -122.4194 }};
    
    var map = new mapboxgl.Map({
        container: 'map',
        style: 'mapbox://styles/mapbox/streets-v11',
        center: [lng, lat],
        zoom: 15
    });

    // Add marker
    new mapboxgl.Marker({
        color: '#ef4444'
    })
    .setLngLat([lng, lat])
    .addTo(map);
});
</script>

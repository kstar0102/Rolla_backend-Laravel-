<div>
    <title>Create Rolla-Rated Location</title>
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center py-4">
        <div class="d-block mb-4 mb-md-0">
            <nav aria-label="breadcrumb" class="d-none d-md-inline-block">
                <ol class="breadcrumb breadcrumb-dark breadcrumb-transparent">
                    <li class="breadcrumb-item"><a href="/dashboard">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="/rolla-rated-locations">Rolla-Rated Locations</a></li>
                    <li class="breadcrumb-item active">Create Location</li>
                </ol>
            </nav>
            <h2 class="h4">Create Rolla-Rated Location</h2>
        </div>
    </div>

    @if (session()->has('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <form wire:submit.prevent="save">
        <div class="row">
            <div class="col-12 col-xl-12">
                <div class="card card-body border-0 shadow mb-4">
                    <h2 class="h5 mb-4">Location Information</h2>
                    
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label for="address">Address <span class="text-danger">*</span></label>
                            <input 
                                wire:model.debounce.500ms="address" 
                                class="form-control" 
                                id="address" 
                                type="text" 
                                placeholder="Enter business address or search on map"
                                autocomplete="off">
                            @error('address') <span class="text-danger">{{ $message }}</span> @enderror
                            <small class="form-text text-muted">Enter an address and it will be geocoded, or select a location on the map below.</small>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label>Select Location on Map <span class="text-danger">*</span></label>
                            <div id="map" style="width: 100%; height: 400px; border-radius: 8px; border: 1px solid #ddd;"></div>
                            <small class="form-text text-muted mt-2">Click on the map to select the exact location, or search for an address above.</small>
                            @error('latitude') <span class="text-danger d-block">{{ $message }}</span> @enderror
                            @error('longitude') <span class="text-danger d-block">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="row" id="coordinates-display" style="display: none;">
                        <div class="col-md-6 mb-3">
                            <label>Latitude</label>
                            <input type="text" class="form-control" id="latitude-display" readonly>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>Longitude</label>
                            <input type="text" class="form-control" id="longitude-display" readonly>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label for="business_url">Business Website URL <span class="text-danger">*</span></label>
                            <input wire:model="business_url" class="form-control" id="business_url" type="url" placeholder="https://example.com">
                            @error('business_url') <span class="text-danger">{{ $message }}</span> @enderror
                            <small class="form-text text-muted">The website URL that users will be directed to when they tap on the star on the map.</small>
                        </div>
                    </div>
                    
                    <div class="mt-3">
                        <button type="submit" class="btn btn-gray-800 mt-2 animate-up-2">
                            <svg class="icon icon-xs me-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            Submit
                        </button>
                        <a href="/rolla-rated-locations" class="btn btn-secondary mt-2 ms-2">Cancel</a>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<script src="https://api.mapbox.com/mapbox-gl-js/v2.15.0/mapbox-gl.js"></script>
<link href="https://api.mapbox.com/mapbox-gl-js/v2.15.0/mapbox-gl.css" rel="stylesheet" />
<script src="https://api.mapbox.com/mapbox-gl-js/plugins/mapbox-gl-geocoder/v5.0.0/mapbox-gl-geocoder.min.js"></script>
<link rel="stylesheet" href="https://api.mapbox.com/mapbox-gl-js/plugins/mapbox-gl-geocoder/v5.0.0/mapbox-gl-geocoder.css" type="text/css">

<script>
document.addEventListener('DOMContentLoaded', function() {
    mapboxgl.accessToken = 'pk.eyJ1Ijoicm9sbGExIiwiYSI6ImNseGppNHN5eDF3eHoyam9oN2QyeW5mZncifQ.iLIVq7aRpvMf6J3NmQTNAw';
    
    var map = new mapboxgl.Map({
        container: 'map',
        style: 'mapbox://styles/mapbox/streets-v11',
        center: [-122.4194, 37.7749], // Default to San Francisco
        zoom: 12
    });

    var marker = null;
    var geocoder = new MapboxGeocoder({
        accessToken: mapboxgl.accessToken,
        mapboxgl: mapboxgl,
        marker: false,
        placeholder: 'Search for an address...'
    });

    // Add geocoder to map
    map.addControl(geocoder);

    // Handle geocoder result
    geocoder.on('result', function(e) {
        var coordinates = e.result.center; // [lng, lat]
        setLocation(coordinates[1], coordinates[0], e.result.place_name);
    });

    // Handle map click
    map.on('click', function(e) {
        var coordinates = e.lngLat; // {lng, lat}
        setLocation(coordinates.lat, coordinates.lng);
        
        // Reverse geocode to get address
        fetch(`https://api.mapbox.com/geocoding/v5/mapbox.places/${coordinates.lng},${coordinates.lat}.json?access_token=${mapboxgl.accessToken}`)
            .then(response => response.json())
            .then(data => {
                if (data.features && data.features.length > 0) {
                    var address = data.features[0].place_name;
                    @this.set('address', address);
                }
            })
            .catch(error => console.error('Error reverse geocoding:', error));
    });

    function setLocation(lat, lng, address = null) {
        // Update Livewire properties
        @this.set('latitude', lat);
        @this.set('longitude', lng);
        
        if (address) {
            @this.set('address', address);
        }

        // Update display
        document.getElementById('latitude-display').value = lat.toFixed(8);
        document.getElementById('longitude-display').value = lng.toFixed(8);
        document.getElementById('coordinates-display').style.display = 'block';

        // Remove existing marker
        if (marker) {
            marker.remove();
        }

        // Add new marker
        marker = new mapboxgl.Marker({
            color: '#ef4444',
            draggable: true
        })
        .setLngLat([lng, lat])
        .addTo(map);

        // Handle marker drag
        marker.on('dragend', function() {
            var lngLat = marker.getLngLat();
            setLocation(lngLat.lat, lngLat.lng);
            
            // Reverse geocode on drag
            fetch(`https://api.mapbox.com/geocoding/v5/mapbox.places/${lngLat.lng},${lngLat.lat}.json?access_token=${mapboxgl.accessToken}`)
                .then(response => response.json())
                .then(data => {
                    if (data.features && data.features.length > 0) {
                        var address = data.features[0].place_name;
                        @this.set('address', address);
                    }
                })
                .catch(error => console.error('Error reverse geocoding:', error));
        });
    }

    // Listen for address changes from Livewire (when user types)
    window.addEventListener('address-updated', function() {
        var address = @this.get('address');
        if (address && address.length > 3) {
            // Geocode address
            fetch(`https://api.mapbox.com/geocoding/v5/mapbox.places/${encodeURIComponent(address)}.json?access_token=${mapboxgl.accessToken}`)
                .then(response => response.json())
                .then(data => {
                    if (data.features && data.features.length > 0) {
                        var coordinates = data.features[0].center; // [lng, lat]
                        setLocation(coordinates[1], coordinates[0], data.features[0].place_name);
                        map.flyTo({
                            center: coordinates,
                            zoom: 15
                        });
                    }
                })
                .catch(error => console.error('Error geocoding:', error));
        }
    });

    // Watch for address changes
    document.getElementById('address').addEventListener('input', function() {
        var address = this.value;
        if (address && address.length > 3) {
            // Debounce geocoding
            clearTimeout(window.geocodeTimeout);
            window.geocodeTimeout = setTimeout(function() {
                fetch(`https://api.mapbox.com/geocoding/v5/mapbox.places/${encodeURIComponent(address)}.json?access_token=${mapboxgl.accessToken}`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.features && data.features.length > 0) {
                            var coordinates = data.features[0].center; // [lng, lat]
                            setLocation(coordinates[1], coordinates[0], data.features[0].place_name);
                            map.flyTo({
                                center: coordinates,
                                zoom: 15
                            });
                        }
                    })
                    .catch(error => console.error('Error geocoding:', error));
            }, 500);
        }
    });
});
</script>

<title>Trip Detail</title>
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center py-4">
    <div class="d-block mb-4 mb-md-0">
        <nav aria-label="breadcrumb" class="d-none d-md-inline-block">
            <ol class="breadcrumb breadcrumb-dark breadcrumb-transparent">
                <li class="breadcrumb-item">
                    <a href="#">
                        <svg class="icon icon-xxs" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                            xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6">
                            </path>
                        </svg>
                    </a>
                </li>
                <li class="breadcrumb-item"><a href="/dashboard">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="/trips">Trips List</a></li>
                <li class="breadcrumb-item active" aria-current="page">Trip Detail</li>
            </ol>
        </nav>
        <h2 class="h4">Trip Detail</h2>
    </div>
    <div class="btn-toolbar mb-2 mb-md-0">
        
    </div>
</div>
<div>
    <div class="row">
        <div class="col-12 col-xl-8">
            <div class="card card-body border-0 shadow mb-4">
                <h2>Details</h2>
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <div>
                            <label for="pickup_addr">Start Address</label>
                            <p>{{ $trip->start_address }}</p>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div>
                            <label for="drop_addr">Stop Address</label>
                            @php
                                $locations = $trip->stop_address ? json_decode($trip->stop_address) : [];
                            @endphp
                            @for ($i = 0; $i < count($locations); $i++)
                                <p>{{ $i + 1 }}. {{ $locations[$i] }}</p>
                            @endfor
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div>
                            <label for="drop_addr">Destination Address</label>
                            <p>{{ $trip->destination_address }}</p>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <div>
                            <label for="sound">Sound</label>
                            @php
                                $soundList = $trip->trip_sound ? explode(',', $trip->trip_sound) : [];
                                $hasSound = !empty($soundList) && $soundList[0] !== 'tripSound' && $soundList[0] !== 'null' && trim($soundList[0]) !== '';
                            @endphp
                            @if($hasSound)
                                <p>
                                    <a href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#soundModal" class="text-primary" style="cursor: pointer;">
                                        <i class="fas fa-music me-2"></i>View Playlist ({{ count($soundList) }} songs)
                                    </a>
                                </p>
                            @else
                                <p class="text-muted">No playlist available</p>
                            @endif
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div>
                            <label for="start_date">Start Date</label>
                            <p>{{ $trip->trip_start_date ? \Carbon\Carbon::parse($trip->trip_start_date)->format('M d, Y') : 'N/A' }}</p>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div>
                            <label for="end_date">End Date</label>
                            <p>{{ $trip->trip_end_date ? \Carbon\Carbon::parse($trip->trip_end_date)->format('M d, Y') : 'N/A' }}</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card card-body border-0 shadow mb-4">
                <h2>Comments</h2>
                @foreach ($trip->comments as $comment)
                    <div class="row mb-3">
                        <div class="col-12 col-md-12">
                            <div class="d-flex justify-content-start align-items-center" style="gap: 10px;">
                                <div>
                                    @if($comment->user->photo)
                                        <img src="{{ $comment->user->photo }}" class="rounded avatar-lg" alt="User Photo">
                                    @else
                                        <img class="rounded avatar-lg" src="{{ asset('assets/img/profile_default.jpg') }}" alt="Default Photo">
                                    @endif
                                </div>
                                <div>
                                    <h5>{{ $comment->user->first_name }} {{ $comment->user->last_name }}</h5>
                                    <span>{{ $comment->user->rolla_username }}</span>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12 col-md-12 mt-3" style="padding-left: 3rem;">
                                    <p>{{ $comment->content }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="card card-body border-0 shadow mb-4">
                <h2>Droppins</h2>
                <div class="row">
                    @foreach ($trip->droppins as $droppin)
                        <div class="col-md-3 col-12 mb-3">
                            <img src="{{ $droppin->image_path }}" class="droppin-image rounded" style="width: 100%; height: 200px; object-fit: cover; cursor: pointer;" onclick="showImage({{ $droppin->id }});" />
                        </div>
                    @endforeach
                </div>
            </div>
            
            @if($trip->trip_coordinates && $trip->droppins->count() > 0)
            <div class="card card-body border-0 shadow mb-4">
                <div id="map" style="height: 250px; width: 100%; border: 0.5px solid black; position: relative;"></div>
            </div>
            @endif
        </div>
        <div class="col-12 col-xl-4">
            <div class="card card-body border-0 shadow mb-4">
                <h2 class="h5 mb-4">User Detail</h2>
                <div class="row">
                    <div class="col-3">
                        @if($trip->user->photo)
                            <img src="{{ $trip->user->photo }}" class="rounded avatar-xl" alt="User Photo" style="width: 80px; height: 80px; object-fit: cover;">
                        @else
                            <img class="rounded avatar-xl" src="{{ asset('assets/img/profile_default.jpg') }}" alt="Default Photo" style="width: 80px; height: 80px; object-fit: cover;">
                        @endif
                    </div>
                    <div class="col-9">
                        <p class="mb-0">{{ $trip->user->first_name }} {{ $trip->user->last_name }}</p>
                        <p class="mb-0">{{ $trip->user->rolla_username }}</p>
                        <p class="mb-0">{{ $trip->user->email }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modal" tabindex="-1" aria-labelledby="modal" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal-title"></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body pt-0">
                <div class="row">
                    <div class="col-md-7 col-12 py-3 px-5 text-center">
                        <img src="../../assets/img/logo.png" id="modal-image" />
                    </div>
                    <div class="col-md-5 col-12 pt-3" id="like-user-container"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Sound Modal -->
<div class="modal fade" id="soundModal" tabindex="-1" aria-labelledby="soundModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="soundModalLabel">Trip Playlist</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                @php
                    $soundList = $trip->trip_sound ? explode(',', $trip->trip_sound) : [];
                    $soundList = array_filter($soundList, function($song) {
                        return trim($song) !== '' && $song !== 'tripSound' && $song !== 'null';
                    });
                @endphp
                @if(count($soundList) > 0)
                    <ul class="list-group">
                        @foreach($soundList as $index => $song)
                            <li class="list-group-item d-flex align-items-center">
                                <i class="fas fa-music me-3 text-primary"></i>
                                <span>{{ trim($song) }}</span>
                            </li>
                        @endforeach
                    </ul>
                @else
                    <p class="text-muted">No songs in playlist</p>
                @endif
            </div>
        </div>
    </div>
</div>

<script src="https://api.mapbox.com/mapbox-gl-js/v2.15.0/mapbox-gl.js"></script>
<link href="https://api.mapbox.com/mapbox-gl-js/v2.15.0/mapbox-gl.css" rel="stylesheet" />

<script>
    var droppins = {!! json_encode($trip->droppins) !!};
    var tripCoordinates = {!! json_encode($trip->trip_coordinates) !!};
    var stopLocations = {!! json_encode($trip->stop_locations) !!};
    var mapStyleValue = '{{ $trip->map_style ?? "0" }}';
    
    // Convert map style value to Mapbox style ID (like frontend)
    function getMapStyleId(styleValue) {
        switch(styleValue) {
            case "1": return 'satellite-v9';
            case "2": return 'light-v10';
            case "3": return 'dark-v10';
            case "0":
            default: return 'streets-v11';
        }
    }
    var mapStyleId = getMapStyleId(mapStyleValue);

    function showImage(id) {
        let droppin = droppins.find(d => d.id === id);
    
        if (droppin) {
            $('#modal-image').attr('src', droppin.image_path);
            $('#modal-title').text(droppin.image_caption || "Image");

            $('#like-user-container').empty();

            if (droppin.liked_users && droppin.liked_users.length > 0) {
                droppin.liked_users.forEach(user => {
                    console.log(user);
                    let userHtml = `
                        <div class="d-flex justify-content-start align-items-center mb-3" style="gap: 10px;">
                            <div>
                                <img class="rounded like-user-image" src="${user.photo || '{{ asset("assets/img/profile_default.jpg") }}'}" alt="User Photo">
                            </div>
                            <div class="like-user">
                                <p>${user.first_name + ' ' + user.last_name || "Unknown User"}</p>
                                <span>${user.rolla_username || "unknown"}</span>
                            </div>
                        </div>`;
                    $('#like-user-container').append(userHtml);
                });
            } else {
                $('#like-user-container').append('<p class="text-muted">No likes yet.</p>');
            }

            $('#modal').modal('show');
        } else {
            console.error('Droppin not found for id:', id);
        }
    }

    // Initialize Map - Styled like frontend
    @if($trip->trip_coordinates && $trip->droppins->count() > 0)
    document.addEventListener('DOMContentLoaded', function() {
        mapboxgl.accessToken = 'pk.eyJ1Ijoicm9sbGExIiwiYSI6ImNseGppNHN5eDF3eHoyam9oN2QyeW5mZncifQ.iLIVq7aRpvMf6J3NmQTNAw';
        
        // Use the converted map style ID
        var styleUrl = 'mapbox://styles/mapbox/' + mapStyleId.replace('-v11', '').replace('-v9', '').replace('-v10', '');
        
        // Calculate initial center and zoom from coordinates
        var initialCenter = [-122.4194, 37.7749]; // Default SF
        var initialZoom = 12;
        
        if (tripCoordinates && tripCoordinates.length > 0) {
            var firstCoord = tripCoordinates[0];
            initialCenter = [firstCoord[1], firstCoord[0]]; // [lng, lat]
        } else if (stopLocations && stopLocations.length > 0) {
            var firstLoc = stopLocations[0];
            initialCenter = [firstLoc[1], firstLoc[0]]; // [lng, lat]
        }
        
        var map = new mapboxgl.Map({
            container: 'map',
            style: styleUrl,
            center: initialCenter,
            zoom: initialZoom
        });

        map.on('load', function() {
            // Add route line (polyline)
            if (tripCoordinates && tripCoordinates.length > 0) {
                var coordinates = tripCoordinates.map(function(coord) {
                    return [coord[1], coord[0]]; // [lng, lat]
                });

                map.addSource('route', {
                    'type': 'geojson',
                    'data': {
                        'type': 'Feature',
                        'properties': {},
                        'geometry': {
                            'type': 'LineString',
                            'coordinates': coordinates
                        }
                    }
                });

                map.addLayer({
                    'id': 'route',
                    'type': 'line',
                    'source': 'route',
                    'layout': {
                        'line-join': 'round',
                        'line-cap': 'round'
                    },
                    'paint': {
                        'line-color': '#4285F4', // Blue like frontend
                        'line-width': 4.0
                    }
                });
            }

            // Add droppin markers - styled like frontend
            if (stopLocations && stopLocations.length > 0) {
                stopLocations.forEach(function(location, index) {
                    var droppin = droppins.find(function(d) {
                        return d.stop_index == (index + 1);
                    });

                    // Create marker element matching frontend style
                    var el = document.createElement('div');
                    el.style.width = '14px';
                    el.style.height = '14px';
                    el.style.borderRadius = '50%';
                    el.style.backgroundColor = 'white';
                    el.style.border = '1px solid black';
                    el.style.display = 'flex';
                    el.style.alignItems = 'center';
                    el.style.justifyContent = 'center';
                    el.style.fontWeight = 'bold';
                    el.style.fontSize = '11px';
                    el.style.color = 'black';
                    el.style.cursor = 'pointer';
                    el.style.boxShadow = '0 2px 4px rgba(0,0,0,0.4)';
                    
                    var stopIndex = droppin ? droppin.stop_index : (index + 1);
                    el.textContent = stopIndex.toString();

                    // Make marker clickable
                    if (droppin) {
                        el.addEventListener('click', function() {
                            showImage(droppin.id);
                        });
                    }

                    new mapboxgl.Marker({
                        element: el,
                        anchor: 'center'
                    })
                        .setLngLat([location[1], location[0]]) // [lng, lat]
                        .addTo(map);
                });
            }

            // Fit map to show all markers and route
            if (stopLocations && stopLocations.length > 0) {
                var bounds = new mapboxgl.LngLatBounds();
                
                // Add all stop locations to bounds
                stopLocations.forEach(function(location) {
                    bounds.extend([location[1], location[0]]); // [lng, lat]
                });
                
                // Add route coordinates to bounds if available
                if (tripCoordinates && tripCoordinates.length > 0) {
                    tripCoordinates.forEach(function(coord) {
                        bounds.extend([coord[1], coord[0]]); // [lng, lat]
                    });
                }
                
                map.fitBounds(bounds, {
                    padding: { top: 20, bottom: 20, left: 20, right: 20 }
                });
            }
        });
    });
    @endif
</script>
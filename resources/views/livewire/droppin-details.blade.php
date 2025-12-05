<title>Droppin Detail</title>
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
                <li class="breadcrumb-item"><a href="/droppins">Droppins List</a></li>
                <li class="breadcrumb-item active" aria-current="page">Droppin Detail</li>
            </ol>
        </nav>
        <h2 class="h4">Droppin Detail</h2>
    </div>
</div>

<div>
    <div class="row">
        <div class="col-12 col-xl-8">
            <div class="card card-body border-0 shadow mb-4">
                <h2 class="h5 mb-4">Droppin Image</h2>
                @if($droppin->image_path)
                    <div class="text-center">
                        <img src="{{ $droppin->image_path }}" alt="Droppin Image" class="img-fluid rounded" style="max-height: 600px; width: auto;">
                    </div>
                @else
                    <p class="text-muted text-center">No image available</p>
                @endif
            </div>

            <div class="card card-body border-0 shadow mb-4">
                <h2 class="h5 mb-4">Details</h2>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <div>
                            <label for="image_caption">Caption</label>
                            <p>{{ $droppin->image_caption ?? 'N/A' }}</p>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <div>
                            <label for="stop_index">Stop Index</label>
                            <p>{{ $droppin->stop_index }}</p>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <div>
                            <label for="likes">Likes</label>
                            <p class="h5 mb-0">{{ $likedUsers->count() }}</p>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <div>
                            <label for="views">Views</label>
                            @if($droppin->view_count)
                                @php
                                    $viewIds = array_filter(explode(',', $droppin->view_count));
                                    $viewCount = count($viewIds);
                                @endphp
                                <p class="h5 mb-0">{{ $viewCount }}</p>
                            @else
                                <p class="h5 mb-0">0</p>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 mb-3">
                        <div>
                            <label for="delay_time">Delay Time</label>
                            <p>
                                @if($droppin->deley_time && $droppin->created_at)
                                    @php
                                        $createdAt = \Carbon\Carbon::parse($droppin->created_at);
                                        $delayTime = \Carbon\Carbon::parse($droppin->deley_time);
                                    @endphp
                                    From <strong>{{ $createdAt->format('M d, Y H:i:s') }}</strong> to <strong>{{ $delayTime->format('M d, Y H:i:s') }}</strong>
                                @elseif($droppin->deley_time)
                                    @try
                                        To: {{ \Carbon\Carbon::parse($droppin->deley_time)->format('M d, Y H:i:s') }}
                                    @catch
                                        {{ $droppin->deley_time }}
                                    @endtry
                                @else
                                    N/A
                                @endif
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            @if($likedUsers->count() > 0)
            <div class="card card-body border-0 shadow mb-4">
                <h2 class="h5 mb-4">Users Who Liked ({{ $likedUsers->count() }})</h2>
                <div class="row">
                    @foreach($likedUsers as $user)
                        <div class="col-md-4 mb-3">
                            <div class="d-flex align-items-center">
                                <div class="me-3">
                                    @if($user->photo)
                                        <img src="{{ $user->photo }}" class="rounded-circle" alt="User Photo" style="width: 50px; height: 50px; object-fit: cover;">
                                    @else
                                        <img class="rounded-circle" src="{{ asset('assets/img/profile_default.jpg') }}" alt="Default Photo" style="width: 50px; height: 50px; object-fit: cover;">
                                    @endif
                                </div>
                                <div>
                                    <p class="mb-0 fw-bold">{{ $user->first_name }} {{ $user->last_name }}</p>
                                    <p class="mb-0 text-muted small">@{{ $user->rolla_username }}</p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            @endif
        </div>

        <div class="col-12 col-xl-4">
            <div class="card card-body border-0 shadow mb-4">
                <h2 class="h5 mb-4">Trip Information</h2>
                @if($droppin->trip)
                    <div class="mb-3">
                        <label for="trip_id">Trip ID</label>
                        <p>
                            <a href="/trip/details/{{ $droppin->trip->id }}" class="text-primary">
                                Trip #{{ $droppin->trip->id }}
                                <i class="fas fa-external-link-alt ms-1"></i>
                            </a>
                        </p>
                    </div>
                    <div class="mb-3">
                        <label for="destination">Destination</label>
                        <p>{{ $droppin->trip->destination_address ?? 'N/A' }}</p>
                    </div>
                    <div class="mb-3">
                        <label for="start_date">Start Date</label>
                        <p>{{ $droppin->trip->trip_start_date ? \Carbon\Carbon::parse($droppin->trip->trip_start_date)->format('M d, Y') : 'N/A' }}</p>
                    </div>
                    <div class="mb-3">
                        <label for="end_date">End Date</label>
                        <p>{{ $droppin->trip->trip_end_date ? \Carbon\Carbon::parse($droppin->trip->trip_end_date)->format('M d, Y') : 'N/A' }}</p>
                    </div>
                @else
                    <p class="text-muted">Trip not found</p>
                @endif
            </div>

        </div>
    </div>
</div>


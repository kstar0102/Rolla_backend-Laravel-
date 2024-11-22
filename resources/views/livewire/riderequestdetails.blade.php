<title>Ride Request Detail</title>
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
                <li class="breadcrumb-item"><a href="/">Volt</a></li>
                <li class="breadcrumb-item"><a href="/orders">Order List</a></li>
                <li class="breadcrumb-item active" aria-current="page">Order Detail</li>
            </ol>
        </nav>
        <h2 class="h4">Order Detail</h2>
    </div>
    <div class="btn-toolbar mb-2 mb-md-0">
        
    </div>
</div>
<div>
    <div class="row">
        <div class="col-12 col-xl-8">
            <div class="card card-body border-0 shadow mb-4">
                <h2 class="h5 mb-4 border-bottom">Ride Request</h2>
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <div>
                            <label for="pickup_addr">Pickup Address</label>
                            <p>{{ $start_location }}</p>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div>
                            <label for="drop_addr">Stop Address</label>
                            @php
                                $locations = $stop_location ? json_decode($stop_location) : [];
                            @endphp
                            @for ($i = 0; $i < count($locations); $i++)
                                <p>{{ $i + 1 }}. {{ $locations[$i]->address }}</p>
                            @endfor
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div>
                            <label for="drop_addr">Drop Address</label>
                            <p>{{ $end_location }}</p>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <div>
                            <label for="route_distance">Route Distance</label>
                            <p>{{ $route_distance }}</p>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <div>
                            <label for="last_name">Driver Earning</label>
                            <p>$ {{ $cost }}</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card card-body border-0 shadow mb-4">
                <h2 class="h5 mb-4">Activity Timeline</h2>
                <div class="row">
                    <div class="col-12">
                        <div class="row mb-2">
                            <div class="col-1">
                                <svg class="icon icon-xs me-2" fill="none" stroke="#1F2937" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 10.5h.01m-4.01 0h.01M8 10.5h.01M5 5h14a1 1 0 0 1 1 1v9a1 1 0 0 1-1 1h-6.6a1 1 0 0 0-.69.275l-2.866 2.723A.5.5 0 0 1 8 18.635V17a1 1 0 0 0-1-1H5a1 1 0 0 1-1-1V6a1 1 0 0 1 1-1Z"></path>
                                </svg>
                            </div>
                            <div class="col-8">
                                New Ride Requested
                            </div>
                            <div class="col-3">
                                <small>{{ $order_date }}</small>
                            </div>
                        </div>
                        @if ($driver_name)
                        <div class="row mb-2">
                            <div class="col-1">
                                <svg class="icon icon-xs me-2" fill="none" stroke="#2361ce" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16.153 19 21 12l-4.847-7H3l4.848 7L3 19h13.153Z"></path>
                                </svg>
                            </div>
                            <div class="col-8">
                                Ongoing
                            </div>
                            <div class="col-3">
                                <small></small>
                            </div>
                        </div>
                        @endif
                        @if($status == 2)
                            <div class="row mb-2">
                                <div class="col-1">
                                    <svg class="icon icon-xs me-2" fill="none" stroke="#10B981" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 2c-.791 0-1.55.314-2.11.874l-.893.893a.985.985 0 0 1-.696.288H7.04A2.984 2.984 0 0 0 4.055 7.04v1.262a.986.986 0 0 1-.288.696l-.893.893a2.984 2.984 0 0 0 0 4.22l.893.893a.985.985 0 0 1 .288.696v1.262a2.984 2.984 0 0 0 2.984 2.984h1.262c.261 0 .512.104.696.288l.893.893a2.984 2.984 0 0 0 4.22 0l.893-.893a.985.985 0 0 1 .696-.288h1.262a2.984 2.984 0 0 0 2.984-2.984V15.7c0-.261.104-.512.288-.696l.893-.893a2.984 2.984 0 0 0 0-4.22l-.893-.893a.985.985 0 0 1-.288-.696V7.04a2.984 2.984 0 0 0-2.984-2.984h-1.262a.985.985 0 0 1-.696-.288l-.893-.893A2.984 2.984 0 0 0 12 2Zm3.683 7.73a1 1 0 1 0-1.414-1.413l-4.253 4.253-1.277-1.277a1 1 0 0 0-1.415 1.414l1.985 1.984a1 1 0 0 0 1.414 0l4.96-4.96Z"></path>
                                    </svg>
                                </div>
                                <div class="col-8">
                                    Completed
                                </div>
                                <div class="col-3">
                                    <small>{{ $arrived_date }}</small>
                                </div>
                            </div>
                        @elseif($status == 1)
                            <div class="row mb-2">
                                <div class="col-1">
                                    <svg class="icon icon-xs me-2" fill="none" stroke="#E11D48" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m15 9-6 6m0-6 6 6m6-3a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"></path>
                                    </svg>
                                </div>
                                <div class="col-8">
                                    Canceled
                                </div>
                                <div class="col-3">
                                    <small>{{ $end_date }}</small>
                                </div>
                            </div>
                        @else
                            <div></div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-xl-4">
            <div class="card card-body border-0 shadow mb-4">
                <h2 class="h5 mb-4">Rider Detail</h2>
                <div class="row">
                    <div class="col-3">
                        @if($rider_photo)
                            <img src="{{ asset('storage/' . $rider_photo) }}" class="rounded avatar-xl" alt="Driver Photo">
                        @else
                            <img class="rounded avatar-xl" src="{{ asset('assets/img/profile_default.jpg') }}" alt="change avatar">
                        @endif
                    </div>
                    <div class="col-9">
                        <p class="mb-0">{{ $rider_name }}</p>
                        <p class="mb-0">{{ $rider_phone_number }}</p>
                        <p class="mb-0">{{ $rider_email }}</p>
                        <div class="rating-container">
                            @php
                                $fullStars = floor($rider_rating); // Get the integer part for full stars
                                $decimalPart = $rider_rating - $fullStars; // Get the decimal part for partial star
                            @endphp

                            @for ($i = 0; $i < $fullStars; $i++)
                                <span><i class="fas fa-star fs-7" style="color: rgb(248, 198, 20)"></i></span>
                            @endfor

                            @if ($decimalPart > 0)
                                <span><i class="fas fa-star-half-alt fs-7" style="color: rgb(248, 198, 20)"></i></span>
                            @endif

                            @for ($i = 0; $i < 5 - ceil($rider_rating); $i++)
                                <span><i class="fas fa-star fs-7" style="color: grey"></i></span>
                            @endfor
                        </div>
                    </div>
                </div>
            </div>
            <div class="card card-body border-0 shadow mb-4">
                <h2 class="h5 mb-4">Driver Detail</h2>
                <div class="row">
                    <div class="col-3">
                        @if($driver_photo)
                            <img src="{{ $driver_photo }}" class="rounded avatar-xl" alt="Driver Photo">
                        @else
                            <img class="rounded avatar-xl" src="{{ asset('assets/img/profile_default.jpg') }}" alt="change avatar">
                        @endif
                    </div>
                    <div class="col-9">
                        <p class="mb-0">{{ $driver_name }}</p>
                        <p class="mb-0">{{ $driver_phone_number }}</p>
                        <p class="mb-0">{{ $driver_email }}</p>
                        <div class="rating-container">
                            @php
                                $fullStars = floor($driver_rating); // Get the integer part for full stars
                                $decimalPart = $driver_rating - $fullStars; // Get the decimal part for partial star
                            @endphp

                            @for ($i = 0; $i < $fullStars; $i++)
                                <span><i class="fas fa-star fs-7" style="color: rgb(248, 198, 20)"></i></span>
                            @endfor

                            @if ($decimalPart > 0)
                                <span><i class="fas fa-star-half-alt fs-7" style="color: rgb(248, 198, 20)"></i></span>
                            @endif

                            @for ($i = 0; $i < 5 - ceil($driver_rating); $i++)
                                <span><i class="fas fa-star fs-7" style="color: grey"></i></span>
                            @endfor
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

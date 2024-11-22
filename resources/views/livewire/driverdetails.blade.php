<title>Driver Details</title>
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
                <li class="breadcrumb-item"><a href="/drivers">Drivers List</a></li>
                <li class="breadcrumb-item active" aria-current="page">Driver Details</li>
            </ol>
        </nav>
        <h2 class="h4">Drivers Detail</h2>
    </div>
    <div class="btn-toolbar mb-2 mb-md-0">
        
    </div>
</div>
<div>
    <div class="row">
        <div class="col-12 col-xl-4">
            <div class="row">
                <div class="col-12 mb-4">
                    <div class="card shadow border-0 text-center p-0">
                        <div class="profile-cover rounded-top" data-background="../../assets/img/profile-cover.jpg"></div>
                        <div class="card-body pb-5">
                            @if($driver_photo)
                                <img src="{{ $driver_photo }}" class="rounded avatar-xl mt-n6" alt="Driver Photo">
                            @else
                                <img class="rounded avatar-xl mt-n6" src="{{ asset('assets/img/profile_default.jpg') }}" alt="change avatar">
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-xl-8">
            <div class="card card-body border-0 shadow mb-4">
                <h2 class="h5 mb-4">General information</h2>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <div>
                            <label for="first_name">Name</label>
                            <h4>{{$full_name}}</h4>
                        </div>
                    </div>
                </div>
                <div class="row align-items-center">
                    <div class="col-md-6 mb-3">
                        <div class="form-group">
                            <label for="email">Email</label>
                            <h4>{{$email}}</h4>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <div class="form-group">
                            <label for="email">Contact Number</label>
                            <h4>{{$phone_number}}</h4>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-4 mb-3">
                        <div class="form-group">
                            <label for="car_type">Car Type</label>
                            <h5>{{$car_type}}</h5>
                        </div>
                    </div>
                    <div class="col-sm-4 mb-3">
                        <div class="form-group">
                            <label for="car_color">Car Color</label>
                            <h5>{{$car_color}}</h5>
                        </div>
                    </div>
                    <div class="col-sm-4 mb-3">
                        <div class="form-group">
                            <label for="car_number">Car Number</label>
                            <h5>{{$car_number}}</h5>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-6">
                        <label for="car_number">Rating</label>
                        <div class="rating-container">
                            @php
                                $fullStars = floor($rating); // Get the integer part for full stars
                                $decimalPart = $rating - $fullStars; // Get the decimal part for partial star
                            @endphp

                            @for ($i = 0; $i < $fullStars; $i++)
                                <span><i class="fas fa-star fs-7" style="color: rgb(248, 198, 20)"></i></span>
                            @endfor

                            @if ($decimalPart > 0)
                                <span><i class="fas fa-star-half-alt fs-7" style="color: rgb(248, 198, 20)"></i></span>
                            @endif

                            @for ($i = 0; $i < 5 - ceil($rating); $i++)
                                <span><i class="fas fa-star fs-7" style="color: grey"></i></span>
                            @endfor
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12 col-xl-12">
            <div class="card card-body border-0 shadow mb-4">
                <h2 class="h5 mb-4">Lisence Verification</h2>
                <div class="row">
                    <div class="col-12 col-xl-9">
                        <div class="d-flex flex-wrap align-items-center">
                            <div class="lisence-container">
                                @if($passport_frontend)
                                    <img src="{{ asset('storage/' . $passport_frontend) }}" class="rounded avatar-xl" alt="Driver Photo">
                                @else
                                    <img class="rounded avatar-xl" src="{{ asset('assets/img/profile_default.jpg') }}" alt="change avatar">
                                @endif
                            </div>
                            <div class="lisence-container">
                                @if($passport_backend)
                                    <img src="{{ asset('storage/' . $passport_backend) }}" class="rounded avatar-xl" alt="Driver Photo">
                                @else
                                    <img class="rounded avatar-xl" src="{{ asset('assets/img/profile_default.jpg') }}" alt="change avatar">
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-xl-3">
                        @if($license_verification == 0)
                            <div class="d-flex align-items-center">
                                <svg class="icon icon-lg" fill="#10B981" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="0"
                                        d="M7.757 12h8.486M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z">
                                    </path>
                                </svg>
                                <span class="pending">Pending</span>
                            </div>
                        @elseif($license_verification == 1)
                            <div class="d-flex align-items-center">
                                <svg class="icon icon-lg" fill="#10B981" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="0"
                                        d="M20.285 2l-11.285 11.567-5.286-5.011-3.714 3.716 9 8.728 15-15.285z">
                                    </path>
                                </svg>
                                <span class="verified">Verified</span>
                            </div>
                        @else
                            <div class="d-flex align-items-center">
                                <svg class="icon icon-lg" fill="#E11D48" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="0"
                                    d="M 12 2 C 6.4889971 2 2 6.4889971 2 12 C 2 17.511003 6.4889971 22 12 22 C 17.511003 22 22 17.511003 22 12 C 22 6.4889971 17.511003 2 12 2 z M 12 4 C 16.430123 4 20 7.5698774 20 12 C 20 16.430123 16.430123 20 12 20 C 7.5698774 20 4 16.430123 4 12 C 4 7.5698774 7.5698774 4 12 4 z M 8.7070312 7.2929688 L 7.2929688 8.7070312 L 10.585938 12 L 7.2929688 15.292969 L 8.7070312 16.707031 L 12 13.414062 L 15.292969 16.707031 L 16.707031 15.292969 L 13.414062 12 L 16.707031 8.7070312 L 15.292969 7.2929688 L 12 10.585938 L 8.7070312 7.2929688 z">
                                    </path>
                                </svg>
                                <span class="unverified">Rejected</span>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="card card-body shadow border-0 table-wrapper table-responsive">
    <h2 class="h5 mb-4">Driver Earning</h2>
    <table class="table table-flush" id="datatable">
        <thead class="thead-light">
            <tr>
                <th>Date</th>
                <th>Earning</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($earning as $item)
                <tr>
                    <td>{{ $item->updated_at }}</td>
                    <td>$ {{ $item->type }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

<div class="modal fade" id="modal-lisence" tabindex="-1" aria-labelledby="modal-lisence" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-tertiary modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-body pt-0">
                <div class="py-3 px-5 text-center">
                    <img src="../../assets/img/lisence-front.png" id="modal-lisence-image" />
                </div>
            </div>
        </div>
    </div>
</div>

<script src="../../assets/js/drivers.js"></script>

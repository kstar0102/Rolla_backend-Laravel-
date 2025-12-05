<title>User Details</title>
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
                <li class="breadcrumb-item"><a href="/users">User List</a></li>
                <li class="breadcrumb-item active" aria-current="page">User Details</li>
            </ol>
        </nav>
        <h2 class="h4">User Detail</h2>
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
                            @if($photo)
                                <img src="{{ $photo }}" class="rounded avatar-xl mt-n6" alt="User Photo" style="width: 120px; height: 120px; object-fit: cover; border: 3px solid white;">
                            @else
                                <img class="rounded avatar-xl mt-n6" src="{{ asset('assets/img/profile_default.jpg') }}" alt="Default Photo" style="width: 120px; height: 120px; object-fit: cover; border: 3px solid white;">
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
                    <div class="col-md-4 mb-3">
                        <div>
                            <label for="first_name">First Name</label>
                            <h4>{{$first_name}}</h4>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div>
                            <label for="last_name">Last Name</label>
                            <h4>{{$last_name}}</h4>
                        </div>
                    </div>
                </div>
                <div class="row align-items-center">
                    <div class="col-md-4 mb-3">
                        <div class="form-group">
                            <label for="email">Email</label>
                            <h5>{{$email}}</h5>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="form-group">
                            <label for="rolla_username">Nick Name</label>
                            <h5>{{$rolla_username}}</h5>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="form-group">
                            <label for="happy_place">Happy Place</label>
                            <h5>{{$happy_place}}</h5>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-4 mb-3">
                        <div class="form-group">
                            <label for="hear_rolla">Hear From</label>
                            <h5>{{$hear_rolla}}</h5>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="state_status">Bio</label>
                            <p>{{$bio}}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Statistics Cards -->
    <div class="row mt-4">
        <div class="col-md-4 mb-4">
            <div class="card border-0 shadow">
                <div class="card-body text-center">
                    <h3 class="h2 mb-0">{{ $tripsCount }}</h3>
                    <p class="text-muted mb-0">Trips</p>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-4">
            <div class="card border-0 shadow">
                <div class="card-body text-center">
                    <h3 class="h2 mb-0">{{ $followersCount }}</h3>
                    <p class="text-muted mb-0">Followers</p>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-4">
            <div class="card border-0 shadow">
                <div class="card-body text-center">
                    <h3 class="h2 mb-0">{{ $followingsCount }}</h3>
                    <p class="text-muted mb-0">Following</p>
                </div>
            </div>
        </div>
    </div>
</div>

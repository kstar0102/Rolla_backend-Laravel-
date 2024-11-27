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
                    <div class="col-md-3 mb-3">
                        <div>
                            <label for="route_distance">Trip Miles</label>
                            <p>{{ $trip->trip_miles }}</p>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div>
                            <label for="last_name">Sound</label>
                            <p>{{ $trip->trip_sound }}</p>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div>
                            <label for="last_name">Start Date</label>
                            <p>{{ $trip->trip_start_date }}</p>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div>
                            <label for="last_name">End Date</label>
                            <p>{{ $trip->trip_end_date }}</p>
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
                        <div class="col-md-3 col-12">
                            <img src="{{ $droppin->image_path }}" class="droppin-image rounded" style="width: 100%; height: 100%; cursor: pointer;" onclick="showImage({{ $droppin->id }});" />
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
        <div class="col-12 col-xl-4">
            <div class="card card-body border-0 shadow mb-4">
                <h2 class="h5 mb-4">User Detail</h2>
                <div class="row">
                    <div class="col-3">
                        @if($trip->user->photo)
                            <img src="{{ asset('storage/' . $trip->user->photo) }}" class="rounded avatar-xl" alt="User Photo">
                        @else
                            <img class="rounded avatar-xl" src="{{ asset('assets/img/profile_default.jpg') }}" alt="Default Photo">
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

<script>
    var droppins = {!! json_encode($trip->droppins) !!};

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
</script>
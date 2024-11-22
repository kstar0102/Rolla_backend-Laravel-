<div>
    <title>Driver Rating Details</title>
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
                    <li class="breadcrumb-item"><a href="/driverratings">Driver Rating List</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Driver Rating Details</li>
                </ol>
            </nav>
            <h2 class="h4">Driver Rating Detail</h2>
        </div>
        <div class="btn-toolbar mb-2 mb-md-0">
            
        </div>
    </div>
    <div>
        <div class="row">
            <div class="col-12 col-xl-12">
                <div class="card shadow border-0 p-0">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center">
                            <img src="../../assets/img/team/profile-picture-1.jpg" class="avatar-lg rounded-circle me-3" alt="Neil Portrait">
                            <h3>{{ $details->driver_name }}</h3>
                        </div>
                        <div class="">
                            <div class="rating-container">
                                @php
                                    $fullStars = floor($details->avg_rating); // Get the integer part for full stars
                                    $decimalPart = $details->avg_rating - $fullStars; // Get the decimal part for partial star
                                @endphp
    
                                @for ($i = 0; $i < $fullStars; $i++)
                                    <span><i class="fas fa-star fs-7" style="color: rgb(248, 198, 20)"></i></span>
                                @endfor
    
                                @if ($decimalPart > 0)
                                    <span><i class="fas fa-star-half-alt fs-7" style="color: rgb(248, 198, 20)"></i></span>
                                @endif
    
                                @for ($i = 0; $i < 5 - ceil($details->avg_rating); $i++)
                                    <span><i class="fas fa-star fs-7" style="color: grey"></i></span>
                                @endfor
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-xl-12">
                @foreach($details->reviews as $item)
                    <div class="card shadow border-0 p-0 ms-6 mt-sm-md" wire:key="{{ $item->id }}">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <h5>{{ $item->rider_name }}</h5>
                                <button type="button" class="btn btn-danger btn-sm" wire:click="$emit('triggerDelete',{{ $item->id }})">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                            <div>
                                <div class="rating-container">
                                    @php
                                        $fullStars = floor($item->rating); // Get the integer part for full stars
                                        $decimalPart = $item->rating - $fullStars; // Get the decimal part for partial star
                                    @endphp
    
                                    @for ($i = 0; $i < $fullStars; $i++)
                                        <span><i class="fas fa-star fs-7" style="color: rgb(248, 198, 20)"></i></span>
                                    @endfor
    
                                    @if ($decimalPart > 0)
                                        <span><i class="fas fa-star-half-alt fs-7" style="color: rgb(248, 198, 20)"></i></span>
                                    @endif
    
                                    @for ($i = 0; $i < 5 - ceil($item->rating); $i++)
                                        <span><i class="fas fa-star fs-7" style="color: grey"></i></span>
                                    @endfor
                                </div>
                            </div>
                            <div>
                                <h6>{{ $item->comment }}</h6>
                                <small><i>{{ $item->created_at }}</i></small>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

<script>
    const swalWithBootstrapButtons = Swal.mixin({
        customClass: {
            confirmButton: 'btn btn-primary',
            cancelButton: 'btn btn-gray'
        },
        buttonsStyling: false
    });
    
    document.addEventListener('DOMContentLoaded', function () {
        @this.on('triggerDelete', id => {
            swalWithBootstrapButtons.fire({
                title: 'Are you sure you want to delete?',
                showCancelButton: true,
                confirmButtonClass: "btn-danger me-2",
                confirmButtonText: "Yes",
                cancelButtonText: "No",
                closeOnConfirm: false,
                closeOnCancel: false
            }).then((res) => {
                if(res.isConfirmed) {
                    @this.call('delete', id)
                }
            });
        });
    });
</script>

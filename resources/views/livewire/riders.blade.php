<div>
    <title>Rider management</title>
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
                    <li class="breadcrumb-item active" aria-current="page">Rider List</li>
                </ol>
            </nav>
            <h2 class="h4">Rider List</h2>
        </div>
        <div class="btn-toolbar mb-2 mb-md-0">
            <a href="/rider/create" class="btn btn-sm btn-gray-800 d-inline-flex align-items-center">
                <svg class="icon icon-xs me-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                    xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6">
                    </path>
                </svg>
                New Rider
            </a>
        </div>
    </div>
    <div class="card card-body shadow border-0 table-wrapper table-responsive">
        <table class="table table-flush" id="datatable">
            <thead class="thead-light">
                <tr>
                    <th></th>
                    <th>Name</th>
                    <th>Contact Number</th>
                    <th>Email</th>
                    <th>Rating</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($riders as $item)
                    <tr>
                        <td>
                            @if($item->photo)
                                <img src="{{ asset('storage/' . $item->photo) }}" alt="Rider Photo" class="img-fluid rounded-circle avatar-item" width="50" height="50">
                            @else
                                <img class="img-fluid rounded-circle avatar-item" src="../../assets/img/profile_default.jpg" alt="change avatar">
                            @endif
                        </td>
                        <td>{{ $item->first_name }} {{ $item->last_name }}</td>
                        <td>{{ $item->phone_number }}</td>
                        <td>{{ $item->email }}</td>
                        <td>
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
                        </td>
                        <td>
                            <div class="d-flex align-items-center">
                                <a href="/rider/edit/{{ $item->id }}" class="me-md-1">
                                    <i class="fas fa-edit text-info"></i>
                                </a>
                                <a href="javascript: remove_rider({{ $item->id }});" class="me-md-1 rider-del-btn" data-id="{{ $item->id }}">
                                    <i class="fas fa-trash text-danger"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
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

    function remove_rider(id) {
        swalWithBootstrapButtons.fire({
            title: 'Are you sure you want to delete?',
            showCancelButton: true,
            confirmButtonClass: "btn-danger me-2",
            confirmButtonText: "Yes",
            cancelButtonText: "No",
        }).then((res) => {
            if(res.isConfirmed) {
                @this.call('remove', id);
            }
        });
    }
</script>
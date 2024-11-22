<div>
    <title>Rider Rating Management</title>
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
                    <li class="breadcrumb-item active" aria-current="page">Rider Rating List</li>
                </ol>
            </nav>
            <h2 class="h4">Rider Rating List</h2>
        </div>
        <div class="btn-toolbar mb-2 mb-md-0">
            
        </div>
    </div>
    <div class="card card-body shadow border-0 table-wrapper table-responsive">
        <table class="table table-flush" id="datatable">
            <thead class="thead-light">
                <tr>
                    <th>Rider</th>
                    <th>Rating</th>
                    <th>Review Count</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($ratings as $item)
                    <tr>
                        <td>{{ $item->name }}</td>
                        <td>
                            <div class="rating-container">
                                @php
                                    $fullStars = floor($item->avg_rating); // Get the integer part for full stars
                                    $decimalPart = $item->avg_rating - $fullStars; // Get the decimal part for partial star
                                @endphp

                                @for ($i = 0; $i < $fullStars; $i++)
                                    <span><i class="fas fa-star fs-7" style="color: rgb(248, 198, 20)"></i></span>
                                @endfor

                                @if ($decimalPart > 0)
                                    <span><i class="fas fa-star-half-alt fs-7" style="color: rgb(248, 198, 20)"></i></span>
                                @endif

                                @for ($i = 0; $i < 5 - ceil($item->avg_rating); $i++)
                                    <span><i class="fas fa-star fs-7" style="color: grey"></i></span>
                                @endfor
                            </div>
                        </td>
                        <td>{{ $item->total_reviews }}</td>
                        <td>
                            <div class="d-flex aligh-items-center">
                                <a href="/riderratings/{{ $item->id }}" class="me-md-1">
                                    <i class="fas fa-eye text-primary"></i>
                                </a>
                                <a href="javascript: remove_ratings({{ $item->id }});" class="me-md-1">
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

    function remove_ratings(id) {
        swalWithBootstrapButtons.fire({
            title: 'Do you want to delete all reviews for this rider?',
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

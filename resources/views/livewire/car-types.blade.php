<div>
    <title>Car Type Management</title>
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
                    <li class="breadcrumb-item active" aria-current="page">Car Types List</li>
                </ol>
            </nav>
            <h2 class="h4">Car Types List</h2>
            <p class="mb-0">Manage car types.</p>
        </div>
        <div class="btn-toolbar mb-2 mb-md-0">
            <a href="/car-type/create" class="btn btn-sm btn-gray-800 d-inline-flex align-items-center">
                <svg class="icon icon-xs me-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                    xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6">
                    </path>
                </svg>
                New Car Type
            </a>
        </div>
    </div>
    
    <div class="card card-body shadow border-0 table-wrapper table-responsive">
        <table class="table table-flush" id="datatable">
            <thead class="thead-light">
                <tr>
                    <th>ID</th>
                    <th>Logo</th>
                    <th>Car Type</th>
                    <th>Logo Path</th>
                    <th>Created At</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($carTypes as $carType)
                    <tr wire:key="{{ $carType->id }}">
                        <td>{{ $carType->id }}</td>
                        <td>
                            @if($carType->logo_path)
                                <img src="{{ $carType->logo_path }}" alt="Car Logo" class="img-fluid" width="50" height="50">
                            @else
                                <span class="text-muted">No Logo</span>
                            @endif
                        </td>
                        <td>{{ $carType->car_type }}</td>
                        <td><small>{{ Str::limit($carType->logo_path ?? '', 50) }}</small></td>
                        <td>{{ $carType->created_at ? $carType->created_at->format('Y-m-d H:i') : 'N/A' }}</td>
                        <td>
                            <div class="d-flex align-items-center">
                                <a href="/car-type/edit/{{ $carType->id }}" class="me-md-1">
                                    <i class="fas fa-edit text-info"></i>
                                </a>
                                <a href="javascript: void(0);" class="me-md-1 car-type-del-btn" data-id="{{ $carType->id }}">
                                    <i class="fas fa-trash text-danger"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center">No car types found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<script>
    $(function () {
        const swalWithBootstrapButtons = Swal.mixin({
            customClass: {
                confirmButton: 'btn btn-primary',
                cancelButton: 'btn btn-gray'
            },
            buttonsStyling: false
        });

        $('.car-type-del-btn').on('click', function () {
            const id = $(this).data('id');
            swalWithBootstrapButtons.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'No, cancel!',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    @this.remove(id);
                    swalWithBootstrapButtons.fire(
                        'Deleted!',
                        'Car type has been deleted.',
                        'success'
                    );
                }
            });
        });
    });
</script>


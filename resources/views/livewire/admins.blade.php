<div>
    <title>Admin Management</title>
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
                    <li class="breadcrumb-item active" aria-current="page">Admins List</li>
                </ol>
            </nav>
            <h2 class="h4">Admins List</h2>
            <p class="mb-0">Manage admin accounts.</p>
        </div>
        <div class="btn-toolbar mb-2 mb-md-0">
            <a href="/admin/create" class="btn btn-sm btn-gray-800 d-inline-flex align-items-center">
                <svg class="icon icon-xs me-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                    xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6">
                    </path>
                </svg>
                New Admin
            </a>
        </div>
    </div>
    
    <div class="card card-body shadow border-0 table-wrapper table-responsive">
        <table class="table table-flush" id="datatable">
            <thead class="thead-light">
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Status</th>
                    <th>Created At</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($admins as $admin)
                    <tr wire:key="{{ $admin->id }}">
                        <td>{{ $admin->id }}</td>
                        <td>{{ $admin->first_name }} {{ $admin->last_name }}</td>
                        <td>{{ $admin->email }}</td>
                        <td>
                            @if($admin->is_active)
                                <span class="badge bg-success">Active</span>
                            @else
                                <span class="badge bg-danger">Inactive</span>
                            @endif
                        </td>
                        <td>{{ $admin->created_at ? $admin->created_at->format('Y-m-d H:i') : 'N/A' }}</td>
                        <td>
                            <div class="d-flex align-items-center">
                                <a href="/admin/edit/{{ $admin->id }}" class="me-md-1">
                                    <i class="fas fa-edit text-info"></i>
                                </a>
                                <a href="javascript: void(0);" class="me-md-1 admin-toggle-active-btn" data-id="{{ $admin->id }}" data-active="{{ $admin->is_active }}">
                                    @if($admin->is_active)
                                        <i class="fas fa-toggle-on text-success" title="Deactivate"></i>
                                    @else
                                        <i class="fas fa-toggle-off text-secondary" title="Activate"></i>
                                    @endif
                                </a>
                                <a href="javascript: void(0);" class="me-md-1 admin-del-btn" data-id="{{ $admin->id }}">
                                    <i class="fas fa-trash text-danger"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center">No admins found.</td>
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

        $('.admin-del-btn').click(function() {
            let del_id = $(this).attr('data-id');

            swalWithBootstrapButtons.fire({
                title: 'Are you sure you want to delete?',
                showCancelButton: true,
                confirmButtonClass: "btn-danger me-2",
                confirmButtonText: "Yes",
                cancelButtonText: "No",
            }).then((res) => {
                if(res.isConfirmed) {
                    @this.call('remove', del_id);
                }
            });
        });

        $('.admin-toggle-active-btn').click(function() {
            let admin_id = $(this).attr('data-id');
            let is_active = $(this).attr('data-active') == '1';
            let action = is_active ? 'deactivate' : 'activate';

            swalWithBootstrapButtons.fire({
                title: `Are you sure you want to ${action} this admin?`,
                showCancelButton: true,
                confirmButtonClass: "btn-primary me-2",
                confirmButtonText: "Yes",
                cancelButtonText: "No",
            }).then((res) => {
                if(res.isConfirmed) {
                    @this.call('toggleActive', admin_id);
                }
            });
        });
    });
</script>


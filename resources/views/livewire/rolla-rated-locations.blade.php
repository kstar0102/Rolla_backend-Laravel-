<div>
    <title>Rolla-Rated Locations Management</title>
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center py-4">
        <div class="d-block mb-4 mb-md-0">
            <nav aria-label="breadcrumb" class="d-none d-md-inline-block">
                <ol class="breadcrumb breadcrumb-dark breadcrumb-transparent">
                    <li class="breadcrumb-item"><a href="/dashboard">Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Rolla-Rated Locations</li>
                </ol>
            </nav>
            <h2 class="h4">Rolla-Rated Locations</h2>
            <p class="mb-0">Manage business locations that appear as stars on the map.</p>
        </div>
        <div class="btn-toolbar mb-2 mb-md-0">
            <a href="/rolla-rated-location/create" class="btn btn-sm btn-gray-800 d-inline-flex align-items-center">
                <svg class="icon icon-xs me-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                New Location
            </a>
        </div>
    </div>
    
    @if (session()->has('message'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('message') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if (session()->has('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card card-body shadow border-0 table-wrapper table-responsive">
        <table class="table table-flush" id="datatable">
            <thead class="thead-light">
                <tr>
                    <th>ID</th>
                    <th>Address</th>
                    <th>Business URL</th>
                    <th>Admin</th>
                    <th>Status</th>
                    <th>Created At</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($locations as $location)
                    <tr>
                        <td>{{ $location->id }}</td>
                        <td>{{ Str::limit($location->address, 50) }}</td>
                        <td>
                            <a href="{{ $location->business_url }}" target="_blank" rel="noopener noreferrer">
                                {{ Str::limit($location->business_url, 40) }}
                            </a>
                        </td>
                        <td>
                            {{ $location->admin->first_name ?? '' }} {{ $location->admin->last_name ?? '' }}
                        </td>
                        <td>
                            @if($location->is_active)
                                <span class="badge bg-success">Active</span>
                            @else
                                <span class="badge bg-secondary">Inactive</span>
                            @endif
                        </td>
                        <td>{{ $location->created_at->format('Y-m-d H:i') }}</td>
                        <td>
                            <div class="btn-group" role="group">
                                <a 
                                    href="/rolla-rated-location/details/{{ $location->id }}" 
                                    class="btn btn-sm btn-outline-info"
                                    title="View Details">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a 
                                    href="/rolla-rated-location/edit/{{ $location->id }}" 
                                    class="btn btn-sm btn-outline-primary"
                                    title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <button 
                                    onclick="if(confirm('Are you sure you want to delete this location?')) { @this.call('remove', {{ $location->id }}) }" 
                                    class="btn btn-sm btn-outline-danger"
                                    title="Delete">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center py-4">
                            <p class="text-muted">No Rolla-rated locations found. <a href="/rolla-rated-location/create">Create one now</a>.</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

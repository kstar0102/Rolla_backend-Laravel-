<div>
    <title>Terms and Conditions Management</title>
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center py-4">
        <div class="d-block mb-4 mb-md-0">
            <nav aria-label="breadcrumb" class="d-none d-md-inline-block">
                <ol class="breadcrumb breadcrumb-dark breadcrumb-transparent">
                    <li class="breadcrumb-item"><a href="/dashboard">Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Terms and Conditions</li>
                </ol>
            </nav>
            <h2 class="h4">Terms and Conditions</h2>
            <p class="mb-0">Manage Terms and Conditions for user registration.</p>
        </div>
        <div class="btn-toolbar mb-2 mb-md-0">
            <a href="/terms-and-conditions/create" class="btn btn-sm btn-gray-800 d-inline-flex align-items-center">
                <svg class="icon icon-xs me-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                New Terms
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
                    <th>Title</th>
                    <th>Content</th>
                    <th>Content Type</th>
                    <th>PDF URL</th>
                    <th>Status</th>
                    <th>Created At</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($terms as $term)
                    <tr wire:key="{{ $term->id }}">
                        <td>{{ $term->id }}</td>
                        <td>{{ Str::limit($term->title, 30) }}</td>
                        <td>{{ Str::limit($term->content, 50) }}</td>
                        <td>
                            <span class="badge bg-info">{{ strtoupper($term->content_type) }}</span>
                        </td>
                        <td>
                            @if($term->pdf_url)
                                <a href="{{ $term->pdf_url }}" target="_blank" class="text-primary">
                                    <i class="fas fa-file-pdf"></i> View PDF
                                </a>
                            @else
                                <span class="text-muted">No PDF</span>
                            @endif
                        </td>
                        <td>
                            @if($term->is_active)
                                <span class="badge bg-success">Active</span>
                            @else
                                <span class="badge bg-warning">Inactive</span>
                            @endif
                        </td>
                        <td>{{ $term->created_at->format('Y-m-d H:i') }}</td>
                        <td>
                            <div class="d-flex align-items-center">
                                <a href="/terms-and-conditions/edit/{{ $term->id }}" class="me-md-1" title="Edit">
                                    <i class="fas fa-edit text-info"></i>
                                </a>
                                <button onclick="if(confirm('Are you sure you want to toggle the status of this Terms and Conditions?')) { @this.call('toggleActive', {{ $term->id }}) }" 
                                        class="btn btn-link text-warning p-0 me-md-1" 
                                        title="Toggle Status"
                                        style="border: none; background: none;">
                                    <i class="fas fa-toggle-{{ $term->is_active ? 'on' : 'off' }}"></i>
                                </button>
                                <button onclick="if(confirm('Are you sure you want to delete this Terms and Conditions?')) { @this.call('remove', {{ $term->id }}) }" 
                                        class="btn btn-link text-danger p-0 me-md-1" 
                                        title="Delete"
                                        style="border: none; background: none;">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center">No Terms and Conditions found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

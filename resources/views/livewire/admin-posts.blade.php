<div>
    <title>Admin Posts Management</title>
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center py-4">
        <div class="d-block mb-4 mb-md-0">
            <nav aria-label="breadcrumb" class="d-none d-md-inline-block">
                <ol class="breadcrumb breadcrumb-dark breadcrumb-transparent">
                    <li class="breadcrumb-item"><a href="/dashboard">Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Admin Posts</li>
                </ol>
            </nav>
            <h2 class="h4">Admin Posts</h2>
            <p class="mb-0">Manage announcements and posts for all users.</p>
        </div>
        <div class="btn-toolbar mb-2 mb-md-0">
            <a href="/admin-post/create" class="btn btn-sm btn-gray-800 d-inline-flex align-items-center">
                <svg class="icon icon-xs me-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                New Post
            </a>
        </div>
    </div>
    
    @if (session()->has('message'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('message') }}
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
                    <th>Image</th>
                    <th>Admin</th>
                    <th>Status</th>
                    <th>Created At</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($posts as $post)
                    <tr wire:key="{{ $post->id }}">
                        <td>{{ $post->id }}</td>
                        <td>{{ Str::limit($post->title, 30) }}</td>
                        <td>{{ Str::limit($post->content, 50) }}</td>
                        <td>
                            @if($post->image_path)
                                <img src="{{ $post->image_path }}" alt="Post image" style="max-width: 50px; max-height: 50px; object-fit: cover;">
                            @else
                                <span class="text-muted">No image</span>
                            @endif
                        </td>
                        <td>{{ $post->admin->first_name }} {{ $post->admin->last_name }}</td>
                        <td>
                            @if($post->is_active)
                                <span class="badge bg-success">Active</span>
                            @else
                                <span class="badge bg-warning">Inactive</span>
                            @endif
                        </td>
                        <td>{{ $post->created_at->format('Y-m-d H:i') }}</td>
                        <td>
                            <div class="d-flex align-items-center">
                                <a href="/admin-post/edit/{{ $post->id }}" class="me-md-1" title="View Details">
                                    <i class="fas fa-eye text-info"></i>
                                </a>
                                <button wire:click="remove({{ $post->id }})" class="btn btn-link text-danger" onclick="return confirm('Are you sure you want to delete this post?')" title="Delete">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center">No admin posts found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

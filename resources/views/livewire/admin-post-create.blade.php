<div>
    <title>Create Admin Post</title>
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center py-4">
        <div class="d-block mb-4 mb-md-0">
            <nav aria-label="breadcrumb" class="d-none d-md-inline-block">
                <ol class="breadcrumb breadcrumb-dark breadcrumb-transparent">
                    <li class="breadcrumb-item"><a href="/dashboard">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="/admin-posts">Admin Posts</a></li>
                    <li class="breadcrumb-item active">Create Post</li>
                </ol>
            </nav>
            <h2 class="h4">Create Admin Post</h2>
        </div>
    </div>

    @if (session()->has('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <form wire:submit.prevent="save">
        <div class="row">
            <div class="col-12 col-xl-12">
                <div class="card card-body border-0 shadow mb-4">
                    <h2 class="h5 mb-4">Post Information</h2>
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label for="title">Title</label>
                            <input wire:model="title" class="form-control" id="title" type="text" placeholder="Enter post title">
                            @error('title') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label for="content">Content</label>
                            <textarea wire:model="content" class="form-control" id="content" rows="5" placeholder="Enter post content"></textarea>
                            @error('content') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label for="image">Image (Optional)</label>
                            <input wire:model="image" type="file" class="form-control" id="image" accept="image/jpeg,image/jpg,image/png,image/gif,image/webp">
                            <small class="form-text text-muted">Only JPEG, PNG, GIF, and WEBP images are allowed. Max size: 2MB.</small>
                            @error('image') <span class="text-danger d-block">{{ $message }}</span> @enderror
                            @if($image)
                                <img src="{{ $image->temporaryUrl() }}" alt="Preview" style="max-width: 200px; margin-top: 10px; border-radius: 4px;">
                            @endif
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <div class="form-check form-switch">
                                <input wire:model="is_active" class="form-check-input" type="checkbox" id="is_active">
                                <label class="form-check-label" for="is_active">Active Status</label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <button type="submit" class="btn btn-primary" wire:loading.attr="disabled" wire:target="save">
            <span wire:loading.remove wire:target="save">Create Post</span>
            <span wire:loading wire:target="save">Creating Post...</span>
        </button>
        <a href="/admin-posts" class="btn btn-secondary">Cancel</a>
    </form>
</div>

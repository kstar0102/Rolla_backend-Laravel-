<div>
    <title>Edit Admin Post</title>
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center py-4">
        <div class="d-block mb-4 mb-md-0">
            <nav aria-label="breadcrumb" class="d-none d-md-inline-block">
                <ol class="breadcrumb breadcrumb-dark breadcrumb-transparent">
                    <li class="breadcrumb-item"><a href="/dashboard">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="/admin-posts">Admin Posts</a></li>
                    <li class="breadcrumb-item active">Edit Post</li>
                </ol>
            </nav>
            <h2 class="h4">Edit Admin Post</h2>
        </div>
    </div>

    <form wire:submit.prevent="update">
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
                            <label for="image">Image (Optional - leave blank to keep current)</label>
                            <input wire:model="image" type="file" class="form-control" id="image" accept="image/*">
                            @error('image') <span class="text-danger">{{ $message }}</span> @enderror
                            @if($image)
                                <img src="{{ $image->temporaryUrl() }}" alt="Preview" style="max-width: 200px; margin-top: 10px;">
                            @elseif($existing_image)
                                <img src="{{ asset('storage/' . $existing_image) }}" alt="Current image" style="max-width: 200px; margin-top: 10px;">
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
        <button type="submit" class="btn btn-primary">Update Post</button>
        <a href="/admin-posts" class="btn btn-secondary">Cancel</a>
    </form>
</div>

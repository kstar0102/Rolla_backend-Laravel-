<div>
    <title>Create Rolla-Rated Location</title>
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center py-4">
        <div class="d-block mb-4 mb-md-0">
            <nav aria-label="breadcrumb" class="d-none d-md-inline-block">
                <ol class="breadcrumb breadcrumb-dark breadcrumb-transparent">
                    <li class="breadcrumb-item"><a href="/dashboard">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="/rolla-rated-locations">Rolla-Rated Locations</a></li>
                    <li class="breadcrumb-item active">Create Location</li>
                </ol>
            </nav>
            <h2 class="h4">Create Rolla-Rated Location</h2>
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
                    <h2 class="h5 mb-4">Location Information</h2>
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label for="address">Address <span class="text-danger">*</span></label>
                            <input wire:model="address" class="form-control" id="address" type="text" placeholder="Enter business address">
                            @error('address') <span class="text-danger">{{ $message }}</span> @enderror
                            <small class="form-text text-muted">The physical address of the business location.</small>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label for="business_url">Business Website URL <span class="text-danger">*</span></label>
                            <input wire:model="business_url" class="form-control" id="business_url" type="url" placeholder="https://example.com">
                            @error('business_url') <span class="text-danger">{{ $message }}</span> @enderror
                            <small class="form-text text-muted">The website URL that users will be directed to when they tap on the star on the map.</small>
                        </div>
                    </div>
                    <div class="mt-3">
                        <button type="submit" class="btn btn-gray-800 mt-2 animate-up-2">
                            <svg class="icon icon-xs me-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            Submit
                        </button>
                        <a href="/rolla-rated-locations" class="btn btn-secondary mt-2 ms-2">Cancel</a>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

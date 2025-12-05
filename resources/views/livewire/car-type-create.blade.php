<div>
    <title>Car Type Create</title>
    @if (session()->has('message'))
        <div class="alert alert-success">{{ session('message') }}</div>
    @endif
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
                    <li class="breadcrumb-item"><a href="/car-types">Car Types List</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Car Type Create</li>
                </ol>
            </nav>
            <h2 class="h4">Create Car Type</h2>
        </div>
    </div>

    <form wire:submit.prevent="save">
        <div class="row">
            <div class="col-12 col-xl-12">
                <div class="card card-body border-0 shadow mb-4">
                    <h2 class="h5 mb-4">General information</h2>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="car_type">Car Type</label>
                            <input wire:model.lazy="car_type" class="form-control" id="car_type" type="text" placeholder="e.g., Sedan, SUV, Truck">
                            @error('car_type') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="logo_path">Logo Path (URL)</label>
                            <input wire:model.lazy="logo_path" class="form-control" id="logo_path" type="text" placeholder="https://example.com/logo.png">
                            @error('logo_path') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <button type="submit" class="btn btn-primary">Save All</button>
        <a href="/car-types" class="btn btn-gray-800">Cancel</a>
    </form>
</div>


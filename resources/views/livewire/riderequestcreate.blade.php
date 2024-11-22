<div>
    <title>Driver Create</title>
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
                    <li class="breadcrumb-item"><a href="/">Volt</a></li>
                    <li class="breadcrumb-item"><a href="/orders">Ride Request List</a></li>
                    <li class="breadcrumb-item active" aria-current="page">New Ride Request</li>
                </ol>
            </nav>
            <h2 class="h4">New Ride Request</h2>
        </div>
    </div>

    <form wire:submit.prevent="save">
        <div class="row">
            <div class="col-12 col-xl-12">
                <div class="card card-body border-0 shadow mb-4">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="driver">Driver Name</label>
                            <input id="groupname" type="text" class="form-control" placeholder="search members..." wire:model.lazy="driverQuery" wire:keydown.tab="resetDrivers" wire:keydown.escape="resetDrivers" />
                            <div class="max-h-44 overflow-y-scroll absolute bg-white w-full z-40 rounded-b-lg shadow-xl serachCard " wire:loading>
                                <ul class="list-outside list-none">
                                    <li class="p-1.5 border-b-1.5 border-slate-200 cursor-pointer divide-y m-2 hover:rounded-lg">Searching...</li>
                                </ul>
                            </div>
                            <div class="max-h-44 overflow-y-scroll absolute bg-white w-full z-40 rounded-b-lg shadow-xl serachCard">
                                @if (!empty($driverQuery))
                                    <div class="fixed top-0 right-0 bottom-0 left-0 -z-10" wire:click="resetDrivers"></div>
                                    @if (!empty($drivers))
                                        @foreach ($drivers as $item)
                                            <ul class="list-outside list-none">
                                                <li class="p-1.5 border-b-1.5 border-slate-200 cursor-pointer divide-y hover:bg-slate-100 m-2 hover:rounded-lg z-40">{{ $item['first_name'] }} {{ $item['last_name'] }}</li>
                                            </ul>
                                        @endforeach
                                    @else
                                        <ul class="list-outside list-none">
                                            <li class="p-1.5 border-b-1.5 border-slate-200 cursor-pointer divide-y hover:bg-slate-100 m-2 hover:rounded-lg">No result found</li>
                                        </ul>
                                    @endif
                                @endif
                            </div>
                            @error('driver') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="rider">Rider Name</label>
                            
                            @error('rider') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="start_location">Start Location</label>
                            <input wire:model.lazy="start_location" class="form-control" id="start_location" type="text">
                            @error('start_location') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="end_location">End Location</label>
                            <input wire:model.lazy="end_location" class="form-control" id="end_location" type="text">
                            @error('end_location') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <button type="submit" class="btn btn-primary">Save All</button>
    </form>
</div>

<div>
    <title>Droppin Edit</title>
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
                    <li class="breadcrumb-item"><a href="/droppins">Droppins List</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Droppin Edit</li>
                </ol>
            </nav>
            <h2 class="h4">Droppin Edit</h2>
        </div>
    </div>
    
    <form wire:submit.prevent="update">
        <div class="row">
            <div class="col-12 col-xl-12">
                <div class="card card-body border-0 shadow mb-4">
                    <h2 class="h5 mb-4">General information</h2>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="trip_id">Trip</label>
                            <select wire:model.lazy="trip_id" class="form-select" id="trip_id">
                                <option value="">Select a Trip</option>
                                @foreach($trips as $trip)
                                    <option value="{{ $trip->id }}" {{ $trip_id == $trip->id ? 'selected' : '' }}>Trip #{{ $trip->id }} - {{ $trip->destination_text ?? 'N/A' }}</option>
                                @endforeach
                            </select>
                            @error('trip_id') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="stop_index">Stop Index</label>
                            <input wire:model.lazy="stop_index" class="form-control" id="stop_index" type="number" min="0" placeholder="0" value="{{ $stop_index }}">
                            @error('stop_index') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label for="image_path">Image Path (URL)</label>
                            <input wire:model.lazy="image_path" class="form-control" id="image_path" type="text" placeholder="https://example.com/image.jpg" value="{{ $image_path }}">
                            @error('image_path') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="image_caption">Image Caption</label>
                            <textarea wire:model.lazy="image_caption" class="form-control" id="image_caption" rows="3" placeholder="Enter caption">{{ $image_caption }}</textarea>
                            @error('image_caption') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="deley_time">Delay Time (Optional)</label>
                            <input wire:model.lazy="deley_time" class="form-control" id="deley_time" type="datetime-local" value="{{ $deley_time ? date('Y-m-d\TH:i', strtotime($deley_time)) : '' }}">
                            @error('deley_time') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <button type="submit" class="btn btn-primary">Save All</button>
        <a href="/droppins" class="btn btn-gray-800">Cancel</a>
    </form>
</div>


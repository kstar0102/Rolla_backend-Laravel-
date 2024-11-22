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
                    <li class="breadcrumb-item"><a href="/drivers">Drivers List</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Driver Create</li>
                </ol>
            </nav>
            <h2 class="h4">Driver Create</h2>
        </div>
    </div>

    <form wire:submit.prevent="save">
        <div class = "row">
            <!-- <div class="col-12 col-xl-4">
                <div class="col-12">
                    <div class="card card-body border-0 shadow mb-4">
                        <h2 class="h5 mb-4">Select profile photo</h2>
                        <div class="d-flex align-items-center">
                            <div class="me-3">
                                @if ($driver_photo)
                                    <img class="rounded avatar-xl" src="{{ $driver_photo->temporaryUrl() }}">
                                @else
                                     <img class="rounded avatar-xl" src="../../assets/img/profile_default.jpg" alt="change avatar">
                                @endif
                            </div>
                            <div class="file-field">
                                <div class="d-flex justify-content-xl-center ms-xl-3">
                                    <div class="d-flex">
                                        <svg class="icon text-gray-500 me-2" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                            <path fill-rule="evenodd"
                                                d="M8 4a3 3 0 00-3 3v4a5 5 0 0010 0V7a1 1 0 112 0v4a7 7 0 11-14 0V7a5 5 0 0110 0v4a3 3 0 11-6 0V7a1 1 0 012 0v4a1 1 0 102 0V7a3 3 0 00-3-3z"
                                                clip-rule="evenodd">
                                            </path>
                                        </svg>
                                        <input wire:model="driver_photo" class="form-control" id="driver_photo" type="file" accept="image/*">
                                        @error('driver_photo') <span class="text-danger">{{ $message }}</span> @enderror
                                        <div class="d-md-block text-left">
                                            <div class="fw-normal text-dark mb-1">Choose Image</div>
                                            <div class="text-gray small">JPG, GIF or PNG. Max size of 800K</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card card-body border-0 shadow mb-4">
                        <h2 class="h5 mb-4">Select car photo</h2>
                        <div class="d-flex align-items-center">
                            <div class="me-3">
                                @if ($car_photo)
                                    <img class="rounded avatar-xl" src="{{ $car_photo->temporaryUrl() }}">
                                @else
                                    <img class="rounded avatar-xl" src="../../assets/img/car_default.png" alt="change avatar">
                                @endif
                            </div>
                            <div class="file-field">
                                <div class="d-flex justify-content-xl-center ms-xl-3">
                                    <div class="d-flex">
                                        <svg class="icon text-gray-500 me-2" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                            <path fill-rule="evenodd"
                                                d="M8 4a3 3 0 00-3 3v4a5 5 0 0010 0V7a1 1 0 112 0v4a7 7 0 11-14 0V7a5 5 0 0110 0v4a3 3 0 11-6 0V7a1 1 0 012 0v4a1 1 0 102 0V7a3 3 0 00-3-3z"
                                                clip-rule="evenodd">
                                            </path>
                                        </svg>
                                        <input wire:model="car_photo" class="form-control" id="car_photo" type="file" accept="image/*">
                                        @error('car_photo') <span class="text-danger">{{ $message }}</span> @enderror
                                        <div class="d-md-block text-left">
                                            <div class="fw-normal text-dark mb-1">Choose Image</div>
                                            <div class="text-gray small">JPG, GIF or PNG. Max size of 800K</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div> -->

            <div class="col-12 col-xl-12">
                <div class="card card-body border-0 shadow mb-4">
                    <h2 class="h5 mb-4">General information</h2>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="full_name">Full Name</label>
                            <input wire:model.lazy="full_name" class="form-control" id="full_name" type="text" placeholder="Enter your first name">
                            @error('full_name') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="email">Email</label>
                            <input wire:model.lazy="email" class="form-control" id="email" type="email" placeholder="name@company.com">
                            @error('email') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="phone_number">Phone Number</label>
                            <input wire:model.lazy="phone_number" class="form-control" id="phone_number" type="text" placeholder="+1-234 567 8910">
                            @error('phone_number') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="license_number">License Number</label>
                            <input wire:model.lazy="license_number" class="form-control" id="license_number" type="text" placeholder="">
                            @error('license_number') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="password">Password</label>
                            <input wire:model.lazy="password" class="form-control" id="password" type="password" placeholder="Enter new password">
                            @error('password') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="password_confirmation">Confirm Password</label>
                            <input wire:model.lazy="password_confirmation" class="form-control" id="password_confirmation" type="password" placeholder="Confirm your password" autocomplete="new-password">
                            @error('password_confirmation') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="car_type">Car Type</label>
                            <input wire:model.lazy="car_type" class="form-control" id="car_type" type="text" placeholder="Enter your car's type">
                            @error('car_type') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="car_color">Car Color</label>
                            <input wire:model.lazy="car_color" class="form-control" id="car_color" type="text" placeholder="Enter your car's color">
                            @error('car_color') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="car_number">Car Number</label>
                            <input wire:model.lazy="car_number" class="form-control" id="car_number" type="text" placeholder="Enter your car's number">
                            @error('car_number') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="rating">Rating</label>
                            <input wire:model.lazy="rating" class="form-control" id="rating" type="number" placeholder="Enter your rating">
                            @error('rating') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                        <!-- <div class="col-md-6 mb-3">
                            <label for="license_verification">License Verification</label>
                            <select wire:model.lazy="license_verification" class="form-select" id="license_verification">
                                <option value="0">Pending</option>
                                <option value="1">Verified</option>
                                <option value="2">Rejected</option>
                            </select>
                            @error('license_verification') <span class="text-danger">{{ $message }}</span> @enderror    
                        </div> -->
                    </div>
                </div>
            </div>
        </div>

        <!-- <div class="row">
            <div class="col-12 col-xl-12">
                <div class="card card-body border-0 shadow mb-4">
                    <h2 class="h5 mb-4">Lisence Verification</h2>
                    <div class="row">
                        <div class="col-12 col-xl-12">
                            <div class="d-flex flex-wrap align-items-center">
                                <div class="card card-body border-0 shadow mb-4">
                                    <h2 class="h5 mb-4">Select Passport Frontend photo</h2>
                                    <div class="d-flex align-items-center">
                                        <div class="me-3">
                                            @if ($passport_frontend)
                                                <img class="rounded avatar-xl" src="{{ $passport_frontend->temporaryUrl() }}">
                                            @else
                                                <img class="rounded avatar-xl" src="../../assets/img/lisence-front.png" alt="change avatar">
                                            @endif 
                                        </div>
                                        <div class="file-field">
                                            <div class="d-flex justify-content-xl-center ms-xl-3">
                                                <div class="d-flex">
                                                    <svg class="icon text-gray-500 me-2" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                                        <path fill-rule="evenodd"
                                                            d="M8 4a3 3 0 00-3 3v4a5 5 0 0010 0V7a1 1 0 112 0v4a7 7 0 11-14 0V7a5 5 0 0110 0v4a3 3 0 11-6 0V7a1 1 0 012 0v4a1 1 0 102 0V7a3 3 0 00-3-3z"
                                                            clip-rule="evenodd">
                                                        </path>
                                                    </svg>
                                                    <input wire:model="passport_frontend" class="form-control" id="passport_frontend" type="file" accept="image/*">
                                                    @error('passport_frontend') <span class="text-danger">{{ $message }}</span> @enderror
                                                    <div class="d-md-block text-left">
                                                        <div class="fw-normal text-dark mb-1">Choose Image</div>
                                                        <div class="text-gray small">JPG, GIF or PNG. Max size of 800K</div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="card card-body border-0 shadow mb-4">
                                    <h2 class="h5 mb-4">Select Passport Backend photo</h2>
                                    <div class="d-flex align-items-center">
                                        <div class="me-3">
                                            @if ($passport_backend)
                                                <img class="rounded avatar-xl" src="{{ $passport_backend->temporaryUrl() }}">
                                            @else
                                                <img class="rounded avatar-xl" src="../../assets/img/lisence-back.png" alt="change avatar">
                                            @endif 
                                        </div>
                                        <div class="file-field">
                                            <div class="d-flex justify-content-xl-center ms-xl-3">
                                                <div class="d-flex">
                                                    <svg class="icon text-gray-500 me-2" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                                        <path fill-rule="evenodd"
                                                            d="M8 4a3 3 0 00-3 3v4a5 5 0 0010 0V7a1 1 0 112 0v4a7 7 0 11-14 0V7a5 5 0 0110 0v4a3 3 0 11-6 0V7a1 1 0 012 0v4a1 1 0 102 0V7a3 3 0 00-3-3z"
                                                            clip-rule="evenodd">
                                                        </path>
                                                    </svg>
                                                    <input wire:model="passport_backend" class="form-control" id="passport_backend" type="file" accept="image/*">
                                                    @error('passport_backend') <span class="text-danger">{{ $message }}</span> @enderror
                                                    <div class="d-md-block text-left">
                                                        <div class="fw-normal text-dark mb-1">Choose Image</div>
                                                        <div class="text-gray small">JPG, GIF or PNG. Max size of 800K</div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div> -->
        
        <!-- <div class="row">
            <div class="col-md-6 mb-3">
                <label for="driver_photo">Profile Photo</label>
                <input wire:model="driver_photo" class="form-control" id="driver_photo" type="file" accept="image/*">
                @if ($driver_photo)
                    <img class="img-thumbnail mt-2" src="{{ $driver_photo->temporaryUrl() }}">
                @endif
                @error('driver_photo') <span class="text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="col-md-6 mb-3">
                <label for="car_photo">Car Photo</label>
                <input wire:model="car_photo" class="form-control" id="car_photo" type="file" accept="image/*">
                @if ($car_photo)
                    <img class="img-thumbnail mt-2" src="{{ $car_photo->temporaryUrl() }}">
                @endif
                @error('car_photo') <span class="text-danger">{{ $message }}</span> @enderror
            </div>
        </div> -->

        <!-- <div class="row">
            <div class="col-md-6 mb-3">
                <label for="passport_frontend">Passport Frontend</label>
                <input wire:model="passport_frontend" class="form-control" id="passport_frontend" type="file" accept="image/*">
                @if ($passport_frontend)
                    <img class="img-thumbnail mt-2" src="{{ $passport_frontend->temporaryUrl() }}">
                @endif
                @error('passport_frontend') <span class="text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="col-md-6 mb-3">
                <label for="passport_backend">Passport Backend</label>
                <input wire:model="passport_backend" class="form-control" id="passport_backend" type="file" accept="image/*">
                @if ($passport_backend)
                    <img class="img-thumbnail mt-2" src="{{ $passport_backend->temporaryUrl() }}">
                @endif
                @error('passport_backend') <span class="text-danger">{{ $message }}</span> @enderror
            </div>
        </div> -->
        <button type="submit" class="btn btn-primary">Save All</button>
    </form>
</div>
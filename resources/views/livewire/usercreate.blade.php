<div>
    <title>User Create</title>
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
                    <li class="breadcrumb-item"><a href="/users">User List</a></li>
                    <li class="breadcrumb-item active" aria-current="page">User Create</li>
                </ol>
            </nav>
            <h2 class="h4">Create User</h2>
        </div>
    </div>

    <form wire:submit.prevent="save">
        <div class = "row">
            <div class="col-12 col-xl-12">
                <div class="card card-body border-0 shadow mb-4">
                    <h2 class="h5 mb-4">General information</h2>
                    <div class="row">
                        <div class="col-md-3 mb-3">
                            <label for="first_name">First Name</label>
                            <input wire:model.lazy="first_name" class="form-control" id="first_name" type="text" placeholder="">
                            @error('first_name') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                        <div class="col-md-3 mb-3">
                            <label for="last_name">Last Name</label>
                            <input wire:model.lazy="last_name" class="form-control" id="last_name" type="text" placeholder="">
                            @error('last_name') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="email">Email</label>
                            <input wire:model.lazy="email" class="form-control" id="email" type="email" placeholder="">
                            @error('email') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="rolla_username">Nick Name</label>
                            <input wire:model.lazy="rolla_username" class="form-control" id="rolla_username" type="rolla_username" placeholder="">
                            @error('rolla_username') <span class="text-danger">{{ $message }}</span> @enderror
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
                            <input wire:model.lazy="password_confirmation" class="form-control" id="password_confirmation" type="password" placeholder="Confirm your password">
                            @error('password_confirmation') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="hear_rolla">Hear From</label>
                            <input wire:model.lazy="hear_rolla" class="form-control" id="hear_rolla" type="text" placeholder="I saw an ad" value="{{ $hear_rolla ?? 'I saw an ad' }}">
                            @error('hear_rolla') <span class="text-danger">{{ $message }}</span> @enderror
                            <small class="form-text text-muted">Default: "I saw an ad"</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <button type="submit" class="btn btn-primary">Save All</button>
    </form>
</div>
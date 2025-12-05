 <main>
    <title>Rolla Travel Admin - Sign Up page</title>
        <!-- Section -->
        <section class="vh-lg-100 mt-5 mt-lg-0 bg-soft d-flex align-items-center">
            <div class="container">
                {{-- <p class="text-center"><a href="{{ route('dashboard') }}" class="text-gray-700"><i class="fas fa-angle-left me-2"></i> Back to homepage</a></p> --}}
                <div wire:ignore.self class="row justify-content-center form-bg-image" data-background-lg="/assets/img/illustrations/signin.svg">
                    <div class="col-12 d-flex align-items-center justify-content-center">
                        <div class="bg-white shadow border-0 rounded border-light p-4 p-lg-5 w-100 fmxw-500">
                            <div class="text-center text-md-center mb-4 mt-md-0">
                                <h1 class="mb-0 h3">Create Admin Account</h1>
                            </div>
                            <form wire:submit.prevent="register" action="#" method="POST">
                                <div class="form-group mt-4 mb-4">
                                    <label for="first_name">First Name</label>
                                    <div class="input-group">
                                        <input wire:model="first_name" id="first_name" type="text" class="form-control" placeholder="" autofocus required>
                                    </div>
                                    @error('first_name') <div class="invalid-feedback"> {{ $message }} </div> @enderror 
                                </div>
                                <div class="form-group mt-4 mb-4">
                                    <label for="last_name">Last Name</label>
                                    <div class="input-group">
                                        <input wire:model="last_name" id="last_name" type="text" class="form-control" placeholder="" autofocus required>
                                    </div>
                                    @error('last_name') <div class="invalid-feedback"> {{ $message }} </div> @enderror 
                                </div>
                                <div class="form-group mt-4 mb-4">
                                    <label for="email">Your Email</label>
                                    <div class="input-group">
                                        <span class="input-group-text" id="basic-addon3"><svg class="icon icon-xs text-gray-600" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"></path><path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"></path></svg></span>
                                        <input wire:model="email" id="email" type="email" class="form-control" placeholder="example@company.com" autofocus required>
                                    </div>
                                    @error('email') <div class="invalid-feedback"> {{ $message }} </div> @enderror 
                                </div>
                                <div class="form-group">
                                    <div class="form-group mb-4">
                                        <label for="password">Your Password</label>
                                        <div class="input-group">
                                            <span class="input-group-text" id="basic-addon4"><svg class="icon icon-xs text-gray-600" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"></path></svg></span>
                                            <input wire:model.lazy="password" type="password" placeholder="Password" class="form-control" id="password" required>
                                        </div>  
                                        @error('password') <div class="invalid-feedback"> {{ $message }} </div> @enderror
                                    </div>
                                    <div class="form-group mb-4">
                                        <label for="confirm_password">Confirm Password</label>
                                        <div class="input-group">
                                            <span class="input-group-text" id="basic-addon5"><svg class="icon icon-xs text-gray-600" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"></path></svg></span>
                                            <input wire:model.lazy="passwordConfirmation" type="password" placeholder="Confirm Password" class="form-control" id="confirm_password" required>
                                        </div>  
                                    </div>
                                </div>
                                <div class="d-grid">
                                    <button type="submit" class="btn btn-gray-800">Sign Up</button>
                                </div>
                            </form>
                            <div class="d-flex justify-content-center align-items-center mt-4">
                                <span class="fw-normal">
                                    Already have an account?
                                    <a href="{{ route('login') }}" class="fw-bold">Login here</a>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>


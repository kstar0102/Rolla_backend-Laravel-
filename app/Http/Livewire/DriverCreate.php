<?php
namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Driver;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\QueryException;

class DriverCreate extends Component
{
    use WithFileUploads;

    public $driver;
    public $full_name;
    public $email;
    public $phone_number;
    public $password;
    public $password_confirmation;
    public $car_type;
    public $car_color;
    public $car_number;
    public $rating;
    public $license_verification;
    public $driver_photo;
    public $car_photo;
    public $passport_frontend;
    public $passport_backend;
    public $license_number;

    public function mount(Driver $driver)
    {
        $this->driver = $driver;
        $this->full_name = $driver->full_name;
        $this->email = $driver->email;
        $this->phone_number = $driver->phone_number;
        $this->car_type = $driver->car_type;
        $this->car_color = $driver->car_color;
        $this->car_number = $driver->car_number;
        $this->rating = $driver->rating;
        $this->license_number = $driver->license_number;
    }

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName, [
            'full_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone_number' => 'required|string|max:20',
            'license_number' => 'required|string',
            'password' => 'nullable|string|min:8|confirmed',
            'car_type' => 'nullable|string|max:255',
            'car_color' => 'nullable|string|max:255',
            'car_number' => 'nullable|string|max:255',
            'rating' => 'nullable|integer|min:0|max:5',
            // 'license_verification' => 'nullable|integer|in:0,1,2',
            // 'driver_photo' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:800',
            // 'car_photo' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:800',
            // 'passport_frontend' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:800',
            // 'passport_backend' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:800',
        ]);
    }

    public function save()
    {
        $validatedData = $this->validate([
            'full_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone_number' => 'required|string|max:20',
            'license_number' => 'required|string',
            'password' => 'required|string|min:8|confirmed',
            'car_type' => 'nullable|string|max:255',
            'car_color' => 'nullable|string|max:255',
            'car_number' => 'nullable|string|max:255',
            'rating' => 'nullable|numeric|min:0|max:5',
            // 'license_verification' => 'nullable|integer|in:0,1,2',
            // 'driver_photo' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:800',
            // 'car_photo' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:800',
            // 'passport_frontend' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:800',
            // 'passport_backend' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:800',
        ]);

        // if ($this->driver_photo) {
        //     $validatedData['driver_photo'] = $this->driver_photo->store('assets/img', 'public');
        // }

        // if ($this->car_photo) {
        //     $validatedData['car_photo'] = $this->car_photo->store('assets/img', 'public');
        // }

        // if ($this->passport_frontend) {
        //     $validatedData['passport_frontend'] = $this->passport_frontend->store('assets/img', 'public');
        // }

        // if ($this->passport_backend) {
        //     $validatedData['passport_backend'] = $this->passport_backend->store('assets/img', 'public');
        // }

        if ($this->password) {
            $validatedData['password'] = Hash::make($this->password);
        } else {
            unset($validatedData['password']);
        }

        try {
            // Attempt to create a new driver record
            $this->driver->create($validatedData);
            session()->flash('message', 'Driver information saved successfully.');
        } catch (QueryException $e) {
            if ($e->errorInfo[1] == 1062) {
                $this->addError('email', 'The email has already been taken.');
            } else {
                $this->addError('general', 'Failed to create new driver due to unexpected error.');
            }
        }
    }

    public function render()
    {
        return view('livewire.drivercreate');
    }
}

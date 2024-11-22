<?php
namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Driver;
use Livewire\WithFileUploads;

class DriverEdit extends Component
{
    use WithFileUploads;

    public $driver;
    public $full_name;
    public $email;
    public $phone_number;
    public $car_type;
    public $car_color;
    public $car_number;
    public $rating;
    public $license_verification;
    public $driver_photo;
    public $car_photo;
    public $passport_frontend;
    public $passport_backend;
    public $password;
    public $password_confirmation;

    public function mount($id)
    {
        $this->driver = Driver::find($id);

        $this->full_name = $this->driver->full_name;
        $this->email = $this->driver->email;
        $this->phone_number = $this->driver->phone_number;
        $this->car_type = $this->driver->car_type;
        $this->car_color = $this->driver->car_color;
        $this->car_number = $this->driver->car_number;
        $this->rating = $this->driver->rating;
        $this->license_verification = $this->driver->license_verification;
        $this->driver_photo = $this->driver->driver_photo;
        $this->car_photo = $this->driver->car_photo;
        $this->passport_frontend = $this->driver->passport_frontend;
        $this->passport_backend = $this->driver->passport_backend;
    }

    public function updateDriver()
    {
        $validatedData = $this->validate([
            'full_name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:drivers,email,' . $this->driver->id,
            'phone_number' => 'required|string|max:20',
            'rating' => 'nullable|numeric|min:0|max:5',
        ]);

        $this->driver->update($validatedData + [
            'car_type' => $this->car_type,
            'car_color' => $this->car_color,
            'car_number' => $this->car_number,
            // 'license_verification' => $this->license_verification,
            // 'driver_photo' => gettype($this->driver_photo) != 'string' ? $this->driver_photo->store('assets/img', 'public') : $this->driver->driver_photo,
            // 'car_photo' => gettype($this->car_photo) != 'string' ? $this->car_photo->store('assets/img', 'public') : $this->driver->car_photo,
            // 'passport_frontend' => gettype($this->passport_frontend) != 'string' ? $this->passport_frontend->store('assets/img', 'public') : $this->driver->passport_frontend,
            // 'passport_backend' => gettype($this->passport_backend) != 'string' ? $this->passport_backend->store('assets/img', 'public') : $this->driver->passport_backend,
        ]);

        session()->flash('message', 'Driver updated successfully.');

        return redirect()->to('/drivers');
    }

    public function render()
    {
        return view('livewire.driveredit');
    }
}

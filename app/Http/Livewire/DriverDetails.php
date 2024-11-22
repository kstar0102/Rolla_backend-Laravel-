<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Driver;
use App\Models\Review;

class DriverDetails extends Component
{
    public $driver;
    public $earning;
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
        $this->earning = Review::select('type', 'updated_at')->where('driver_id', $id)->get();

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

    public function render()
    {
        return view('livewire.driverdetails');
    }
}

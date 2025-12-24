<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\RollaRatedLocation;
use Illuminate\Support\Facades\Auth;

class RollaRatedLocationCreate extends Component
{
    public $address = '';
    public $latitude = null;
    public $longitude = null;
    public $business_url = '';

    protected $rules = [
        'address' => 'required|string|max:500',
        'latitude' => 'required|numeric|between:-90,90',
        'longitude' => 'required|numeric|between:-180,180',
        'business_url' => 'required|url|max:500',
    ];

    protected $messages = [
        'address.required' => 'The address field is required.',
        'address.max' => 'The address must not exceed 500 characters.',
        'latitude.required' => 'Please select a location on the map.',
        'latitude.numeric' => 'Latitude must be a valid number.',
        'latitude.between' => 'Latitude must be between -90 and 90.',
        'longitude.required' => 'Please select a location on the map.',
        'longitude.numeric' => 'Longitude must be a valid number.',
        'longitude.between' => 'Longitude must be between -180 and 180.',
        'business_url.required' => 'The business URL field is required.',
        'business_url.url' => 'The business URL must be a valid URL.',
        'business_url.max' => 'The business URL must not exceed 500 characters.',
    ];

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }

    public function save()
    {
        $this->validate();

        try {
            RollaRatedLocation::create([
                'admin_id' => Auth::guard('admin')->id(),
                'address' => $this->address,
                'latitude' => $this->latitude,
                'longitude' => $this->longitude,
                'business_url' => $this->business_url,
                'is_active' => true,
            ]);

            session()->flash('message', 'Rolla-rated location created successfully.');
            return redirect()->to('/rolla-rated-locations');
        } catch (\Exception $e) {
            session()->flash('error', 'Error creating location: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.rolla-rated-location-create');
    }
}

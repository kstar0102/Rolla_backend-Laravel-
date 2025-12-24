<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\RollaRatedLocation;

class RollaRatedLocationEdit extends Component
{
    public $location;
    public $location_id;
    public $address = '';
    public $latitude = null;
    public $longitude = null;
    public $business_url = '';
    public $is_active = true;

    protected $rules = [
        'address' => 'required|string|max:500',
        'latitude' => 'required|numeric|between:-90,90',
        'longitude' => 'required|numeric|between:-180,180',
        'business_url' => 'required|url|max:500',
        'is_active' => 'boolean',
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

    public function mount($id)
    {
        $this->location = RollaRatedLocation::find($id);
        if (!$this->location) {
            abort(404, 'Location not found');
        }
        $this->location_id = $id;
        $this->address = $this->location->address;
        $this->latitude = $this->location->latitude;
        $this->longitude = $this->location->longitude;
        $this->business_url = $this->location->business_url;
        $this->is_active = $this->location->is_active ?? true;
    }

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }

    public function update()
    {
        $this->validate();

        try {
            $this->location->update([
                'address' => $this->address,
                'latitude' => $this->latitude,
                'longitude' => $this->longitude,
                'business_url' => $this->business_url,
                'is_active' => $this->is_active,
            ]);

            session()->flash('message', 'Rolla-rated location updated successfully.');
            return redirect()->to('/rolla-rated-locations');
        } catch (\Exception $e) {
            session()->flash('error', 'Error updating location: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.rolla-rated-location-edit');
    }
}

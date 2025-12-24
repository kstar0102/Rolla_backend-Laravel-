<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\RollaRatedLocation;

class RollaRatedLocationDetails extends Component
{
    public $location;

    public function mount($id)
    {
        $this->location = RollaRatedLocation::with('admin')->find($id);
        if (!$this->location) {
            abort(404, 'Location not found');
        }
    }

    public function render()
    {
        return view('livewire.rolla-rated-location-details');
    }
}

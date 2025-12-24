<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\RollaRatedLocation;

class RollaRatedLocations extends Component
{
    public $locations;

    public function mount()
    {
        $this->locations = RollaRatedLocation::with('admin')->orderBy('created_at', 'desc')->get();
    }

    public function remove($id)
    {
        try {
            $location = RollaRatedLocation::find($id);
            if ($location) {
                $location->delete();
                $this->locations = RollaRatedLocation::with('admin')->orderBy('created_at', 'desc')->get();
                session()->flash('message', 'Rolla-rated location deleted successfully.');
            } else {
                session()->flash('error', 'Location not found.');
            }
        } catch (\Exception $e) {
            session()->flash('error', 'Error deleting location: ' . $e->getMessage());
        }
    }


    public function render()
    {
        return view('livewire.rolla-rated-locations');
    }
}

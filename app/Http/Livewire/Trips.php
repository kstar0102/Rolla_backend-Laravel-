<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Trip;

class Trips extends Component
{
    public $trips;

    public function mount()
    {
        $this->trips = Trip::with(['user:id,first_name,last_name'])->get();
    }

    public function remove($id) {
        $trip = Trip::find($id);
        $trip->delete();
        $this->trips = Trip::with(['user:id,first_name,last_name'])->get();
    }

    public function render()
    {
        return view('livewire.trips');
    }
}

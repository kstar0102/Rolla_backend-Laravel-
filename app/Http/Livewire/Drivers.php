<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Driver;

class Drivers extends Component
{
    public $drivers;

    public function mount()
    {
        $this->drivers = Driver::all();
    }

    public function remove($id) {
        $driver = Driver::find($id);
        $driver->delete();
        $this->drivers = Driver::all();
    }

    public function render()
    {
        return view('livewire.drivers');
    }
}

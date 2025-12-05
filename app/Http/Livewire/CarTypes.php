<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\CarType;

class CarTypes extends Component
{
    public $carTypes;

    public function mount()
    {
        $this->carTypes = CarType::all();
    }

    public function remove($id) {
        $carType = CarType::find($id);
        if ($carType) {
            $carType->delete();
            $this->carTypes = CarType::all();
        }
    }

    public function render()
    {
        return view('livewire.car-types');
    }
}


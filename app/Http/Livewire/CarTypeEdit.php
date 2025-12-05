<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\CarType;

class CarTypeEdit extends Component
{
    public $carType;
    public $car_type = '';
    public $logo_path = '';

    public function mount($id)
    {
        $this->carType = CarType::findOrFail($id);
        $this->car_type = $this->carType->car_type;
        $this->logo_path = $this->carType->logo_path;
    }

    protected $rules = [
        'car_type' => 'required|string|max:255',
        'logo_path' => 'required|string|max:500',
    ];

    public function update()
    {
        $this->validate();

        $this->carType->update([
            'car_type' => $this->car_type,
            'logo_path' => $this->logo_path,
        ]);

        return redirect()->route('cartypes');
    }

    public function render()
    {
        return view('livewire.car-type-edit');
    }
}


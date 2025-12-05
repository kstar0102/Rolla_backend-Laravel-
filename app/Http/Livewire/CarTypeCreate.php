<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\CarType;

class CarTypeCreate extends Component
{
    public $car_type = '';
    public $logo_path = '';

    protected $rules = [
        'car_type' => 'required|string|max:255',
        'logo_path' => 'required|string|max:500',
    ];

    public function save()
    {
        $this->validate();

        CarType::create([
            'car_type' => $this->car_type,
            'logo_path' => $this->logo_path,
        ]);

        return redirect()->route('cartypes');
    }

    public function render()
    {
        return view('livewire.car-type-create');
    }
}


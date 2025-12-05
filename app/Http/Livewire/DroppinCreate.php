<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Droppin;
use App\Models\Trip;

class DroppinCreate extends Component
{
    public $trip_id = '';
    public $stop_index = 0;
    public $image_path = '';
    public $image_caption = '';
    public $deley_time = '';
    public $format = '';
    public $trips;

    public function mount()
    {
        $this->trips = Trip::all();
    }

    protected $rules = [
        'trip_id' => 'required|integer|exists:trips,id',
        'stop_index' => 'required|integer|min:0',
        'image_path' => 'required|string|max:500',
        'image_caption' => 'nullable|string|max:500',
        'deley_time' => 'nullable|date',
        'format' => 'nullable|string|max:50',
    ];

    public function save()
    {
        $this->validate();

        Droppin::create([
            'trip_id' => $this->trip_id,
            'stop_index' => $this->stop_index,
            'image_path' => $this->image_path,
            'image_caption' => $this->image_caption ?? '',
            'deley_time' => $this->deley_time ?: null,
            'format' => $this->format ?? null,
        ]);

        return redirect()->route('droppins');
    }

    public function render()
    {
        return view('livewire.droppin-create');
    }
}


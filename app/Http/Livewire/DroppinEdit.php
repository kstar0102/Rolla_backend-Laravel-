<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Droppin;
use App\Models\Trip;

class DroppinEdit extends Component
{
    public $droppin;
    public $trip_id = '';
    public $stop_index = 0;
    public $image_path = '';
    public $image_caption = '';
    public $deley_time = '';
    public $format = '';
    public $trips;

    public function mount($id)
    {
        $this->droppin = Droppin::findOrFail($id);
        $this->trip_id = $this->droppin->trip_id;
        $this->stop_index = $this->droppin->stop_index;
        $this->image_path = $this->droppin->image_path;
        $this->image_caption = $this->droppin->image_caption;
        $this->deley_time = $this->droppin->deley_time;
        $this->format = $this->droppin->format;
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

    public function update()
    {
        $this->validate();

        $this->droppin->update([
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
        return view('livewire.droppin-edit');
    }
}


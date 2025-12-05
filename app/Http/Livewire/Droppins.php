<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Droppin;
use App\Models\Trip;

class Droppins extends Component
{
    public $droppins;

    public function mount()
    {
        try {
            $this->droppins = Droppin::orderBy('created_at', 'desc')->get();
        } catch (\Exception $e) {
            $this->droppins = collect([]);
        }
    }

    public function remove($id) {
        $droppin = Droppin::find($id);
        if ($droppin) {
            $droppin->delete();
            $this->droppins = Droppin::orderBy('created_at', 'desc')->get();
        }
    }

    public function render()
    {
        return view('livewire.droppins');
    }
}


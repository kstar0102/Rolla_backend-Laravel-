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
        $this->droppins = Droppin::with('trip')->orderBy('created_at', 'desc')->get();
    }

    public function remove($id) {
        $droppin = Droppin::find($id);
        if ($droppin) {
            $droppin->delete();
            $this->droppins = Droppin::with('trip')->orderBy('created_at', 'desc')->get();
        }
    }

    public function render()
    {
        return view('livewire.droppins');
    }
}


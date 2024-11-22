<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\User;

class Riders extends Component
{
    public $riders;

    public function mount()
    {
        $this->riders = User::all();
    }

    public function remove($id) {
        $rider = User::find($id);
        $rider->delete();
        $this->riders = User::all();
    }

    public function render()
    {
        return view('livewire.riders');
    }
}

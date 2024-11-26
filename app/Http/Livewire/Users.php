<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\User;

class Users extends Component
{
    public $users;

    public function mount()
    {
        $this->users = User::all();
    }

    public function remove($id) {
        $user = User::find($id);
        $user->delete();
        $this->users = User::all();
    }

    public function render()
    {
        return view('livewire.users');
    }
}

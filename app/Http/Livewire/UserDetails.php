<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\User;

class UserDetails extends Component
{
    public $user;
    public $first_name;
    public $last_name;
    public $email;
    public $password;
    public $rolla_username;
    public $happy_place;
    public $country;
    public $hear_rolla;
    public $photo;
    public $bio;
    public $state_status;
    public $garage;
    public $following_user_id;

    public function mount($id)
    {
        $this->user = User::find($id);

        $this->first_name = $this->user->first_name;
        $this->last_name = $this->user->last_name;
        $this->email = $this->user->email;
        $this->rolla_username = $this->user->rolla_username;
        $this->happy_place = $this->user->happy_place;
        $this->country = $this->user->country;
        $this->hear_rolla = $this->user->hear_rolla;
        $this->photo = $this->user->photo;
        $this->bio = $this->user->bio;
        $this->state_status = $this->user->state_status;
    }

    public function render()
    {
        return view('livewire.userdetails');
    }
}

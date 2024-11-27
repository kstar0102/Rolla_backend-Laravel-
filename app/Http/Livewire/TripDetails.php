<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Trip;
use App\Models\User;

class TripDetails extends Component
{
    public $trip;

    public function mount($id)
    {
        $this->trip = Trip::with(['droppins', 'comments.user', 'user'])->findOrFail($id);
        foreach ($this->trip->droppins as $droppin) {
            $likesUserIds = collect(explode(',', $droppin->likes_user_id))
                ->filter()
                ->map(fn($id) => intval(trim($id)))
                ->unique();

            $likedUsers = User::whereIn('id', $likesUserIds)
                ->select('id', 'photo', 'first_name', 'last_name', 'rolla_username')
                ->get();

            $droppin->liked_users = $likedUsers;
        }
    }

    public function render()
    {
        return view('livewire.tripdetails');
    }
}

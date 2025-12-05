<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Droppin;
use App\Models\User;

class DroppinDetails extends Component
{
    public $droppin;
    public $likedUsers;

    public function mount($id)
    {
        $this->droppin = Droppin::with('trip.user')->findOrFail($id);
        
        // Get liked users
        $likesUserIds = $this->droppin->likes_user_id 
            ? collect(explode(',', $this->droppin->likes_user_id))
                ->filter()
                ->map(fn($id) => intval(trim($id)))
                ->unique()
                ->toArray()
            : [];
        
        if (!empty($likesUserIds)) {
            $this->likedUsers = User::whereIn('id', $likesUserIds)
                ->select('id', 'photo', 'first_name', 'last_name', 'rolla_username')
                ->get();
        } else {
            $this->likedUsers = collect([]);
        }
    }

    public function render()
    {
        return view('livewire.droppin-details');
    }
}


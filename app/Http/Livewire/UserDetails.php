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
    public $hear_rolla;
    public $photo;
    public $bio;
    public $garage;
    public $tripsCount;
    public $followersCount;
    public $followingsCount;

    public function mount($id)
    {
        $this->user = User::find($id);

        $this->first_name = $this->user->first_name;
        $this->last_name = $this->user->last_name;
        $this->email = $this->user->email;
        $this->rolla_username = $this->user->rolla_username;
        $this->happy_place = $this->user->happy_place;
        $this->hear_rolla = $this->user->hear_rolla ?? 'I saw an ad';
        $this->photo = $this->user->photo;
        $this->bio = $this->user->bio;
        
        // Get counts only (more efficient)
        $this->tripsCount = $this->user->trips()->count();
        $this->followersCount = $this->getFollowersCount();
        $this->followingsCount = $this->getFollowingsCount();
    }

    private function getFollowersCount()
    {
        $followedUserIds = json_decode($this->user->followed_user_id, true);
        
        // Handle both JSON array and null/empty cases
        if (empty($followedUserIds)) {
            return 0;
        }
        
        // Extract user IDs from the JSON array (handle both object format and simple array)
        $userIds = [];
        foreach ($followedUserIds as $item) {
            if (is_array($item) && isset($item['id'])) {
                $userIds[] = intval($item['id']);
            } elseif (is_numeric($item)) {
                $userIds[] = intval($item);
            }
        }
        
        return count(array_unique($userIds));
    }

    private function getFollowingsCount()
    {
        $followingUserIds = json_decode($this->user->following_user_id, true);
        
        // Handle both JSON array and comma-separated string (for backward compatibility)
        if (empty($followingUserIds)) {
            // Try comma-separated format as fallback
            if (!empty($this->user->following_user_id) && !is_array($followingUserIds)) {
                $userIds = collect(explode(',', $this->user->following_user_id))
                    ->filter()
                    ->map(fn($id) => intval(trim($id)))
                    ->unique()
                    ->toArray();
                
                return count($userIds);
            }
            return 0;
        }
        
        // Extract user IDs from the JSON array (handle both object format and simple array)
        $userIds = [];
        foreach ($followingUserIds as $item) {
            if (is_array($item) && isset($item['id'])) {
                $userIds[] = intval($item['id']);
            } elseif (is_numeric($item)) {
                $userIds[] = intval($item);
            }
        }
        
        return count(array_unique($userIds));
    }

    public function render()
    {
        return view('livewire.userdetails');
    }
}

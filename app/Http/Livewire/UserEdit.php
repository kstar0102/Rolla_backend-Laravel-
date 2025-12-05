<?php
namespace App\Http\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use App\Models\User;

class UserEdit extends Component
{
    public $user;
    public $user_id;
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
    public $following_user_id;

    public function mount($id)
    {
        $this->user = User::find($id);
        $this->user_id = $id;
        $this->first_name = $this->user->first_name;
        $this->last_name = $this->user->last_name;
        $this->email = $this->user->email;
        $this->rolla_username = $this->user->rolla_username;
        $this->happy_place = $this->user->happy_place;
        $this->hear_rolla = $this->user->hear_rolla;
        $this->photo = $this->user->photo;
        $this->bio = $this->user->bio;
        $this->garage = $this->user->garage;
        $this->following_user_id = $this->user->following_user_id;
    }

    public function updateUser()
    {
        $validatedData = $this->validate([
            'first_name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $this->user_id,
            'last_name' => 'required|string|max:255',
            'rolla_username' => 'required|string|max:255',
            'happy_place' => 'nullable|string|max:255',
            'photo' => 'nullable|string|max:255',
            'bio' => 'nullable|string|max:255',
            'garage' => 'nullable|string|max:255',
            'hear_rolla' => 'nullable|string|max:255'
        ]);

        $this->user->update($validatedData + [
            'following_user_id' => $this->following_user_id,
        ]);

        session()->flash('message', 'User updated successfully.');

        // return redirect()->to('/users');
    }

    public function resetPassword() {
        $this->rider = User::where('id', $this->user_id)->update(['password' => Hash::make('12345678*')]);
        session()->flash('message', 'Password updated successfully.');
    }

    public function render()
    {
        return view('livewire.useredit');
    }
}

<?php
namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\User;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class RiderEdit extends Component
{
    use WithFileUploads;

    public $rider;
    public $rider_id;
    public $first_name;
    public $last_name;
    public $email;
    public $phone_number;
    public $password;
    public $password_confirmation;
    public $photo;
    public $approval_date;
    public $status;

    public function mount($id)
    {
        $this->rider = User::find($id);
        $this->rider_id = $id;
        $this->first_name = $this->rider->first_name;
        $this->last_name = $this->rider->last_name;
        $this->email = $this->rider->email;
        $this->phone_number = $this->rider->phone_number;
        // $this->photo = $this->rider->photo;
        // $this->join_date = $this->rider->join_date;
        // $this->approval_date = $this->rider->approval_date;
        // $this->status = $this->rider->status;
    }

    public function updateRider()
    {
        $validatedData = $this->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:riders,email,' . $this->rider_id,
            'phone_number' => 'required|string|max:20',
        ]);
        
        $this->rider->update($validatedData);

        session()->flash('message', 'Rider updated successfully.');

        return redirect()->to('/riders');
    }

    public function resetPassword() {
        $this->rider = User::where('id', $this->rider_id)->update(['password' => Hash::make('12345678*')]);
        session()->flash('message', 'Password updated successfully.');
    }

    public function render()
    {
        return view('livewire.rideredit');
    }
}

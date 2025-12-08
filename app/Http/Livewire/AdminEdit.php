<?php
namespace App\Http\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Hash;
use App\Models\Admin;

class AdminEdit extends Component
{
    public $admin;
    public $admin_id;
    public $first_name;
    public $last_name;
    public $email;
    public $password;
    public $password_confirmation;
    public $is_active;

    public function mount($id)
    {
        $this->admin = Admin::find($id);
        if (!$this->admin) {
            abort(404, 'Admin not found');
        }
        $this->admin_id = $id;
        $this->first_name = $this->admin->first_name;
        $this->last_name = $this->admin->last_name;
        $this->email = $this->admin->email;
        $this->is_active = $this->admin->is_active ?? 0;
    }

    public function updateAdmin()
    {
        $validatedData = $this->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:admins,email,' . $this->admin_id,
            'password' => 'nullable|string|min:6|same:password_confirmation',
            'is_active' => 'boolean',
        ]);

        if (!empty($this->password)) {
            $validatedData['password'] = Hash::make($this->password);
        } else {
            unset($validatedData['password']);
        }

        // Ensure is_active is set (handle checkbox boolean)
        $validatedData['is_active'] = ($this->is_active == true || $this->is_active == 1 || $this->is_active == '1') ? 1 : 0;

        $this->admin->update($validatedData);

        session()->flash('message', 'Admin updated successfully.');
    }

    public function resetPassword() {
        $this->admin->update(['password' => Hash::make('12345678*')]);
        session()->flash('message', 'Password reset successfully to: 12345678*');
    }

    public function render()
    {
        return view('livewire.admin-edit');
    }
}


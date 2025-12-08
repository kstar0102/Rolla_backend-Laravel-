<?php
namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Admin;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\QueryException;

class AdminCreate extends Component
{
    public $first_name;
    public $last_name;
    public $email;
    public $password;
    public $password_confirmation;
    public $is_active = 0;

    public function mount()
    {
        $this->first_name = '';
        $this->last_name = '';
        $this->email = '';
        $this->is_active = 0;
    }

    public function updated($request)
    {
        $this->validateOnly($request, [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|string|max:255|unique:admins,email',
            'password' => 'required|string|min:6|same:password_confirmation',
        ]);
    }

    public function save()
    {
        $validatedData = $this->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|string|max:255|unique:admins,email',
            'password' => 'required|string|min:6|same:password_confirmation',
            'is_active' => 'boolean',
        ]);

        if ($this->password) {
            $validatedData['password'] = Hash::make($this->password);
        } else {
            unset($validatedData['password']);
        }

        // Ensure is_active is set (handle checkbox boolean)
        $validatedData['is_active'] = ($this->is_active == true || $this->is_active == 1 || $this->is_active == '1') ? 1 : 0;

        try {
            Admin::create($validatedData);
            session()->flash('message', 'Admin created successfully.');
            return redirect()->to('/admins');
        } catch (QueryException $e) {
            if ($e->errorInfo[1] == 1062) {
                $this->addError('email', 'The email has already been taken.');
            } else {
                $this->addError('general', 'Failed to create new admin due to unexpected error.');
            }
        }
    }

    public function render()
    {
        return view('livewire.admin-create');
    }
}


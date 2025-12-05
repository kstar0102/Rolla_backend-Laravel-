<?php

namespace App\Http\Livewire\Auth;

use Livewire\Component;
use App\Models\Admin;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class AdminRegister extends Component
{
    public $first_name = '';
    public $last_name = '';
    public $email = '';
    public $password = '';
    public $passwordConfirmation = '';

    public function mount()
    {
        if (Auth::guard('admin')->check()) {
            return redirect()->intended('/dashboard');
        }
    }

    public function updatedEmail()
    {
        $this->validate(['email'=>'required|email:rfc,dns|unique:admins']);
    }
    
    public function register()
    {
        $this->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|string|max:255|unique:admins,email',
            'password' => 'required|same:passwordConfirmation|min:6',
        ]);

        $admin = Admin::create([
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'email' => $this->email,
            'password' => Hash::make($this->password),
        ]);

        Auth::guard('admin')->login($admin);

        return redirect('/dashboard');
    }

    public function render()
    {
        return view('livewire.auth.admin-register');
    }
}


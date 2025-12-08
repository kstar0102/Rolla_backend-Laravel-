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
            'is_active' => 0, // New admins are inactive by default
        ]);

        // Don't auto-login inactive admins
        // They need to be activated by an active admin first
        session()->flash('message', 'Registration successful! Please wait for an active admin to activate your account.');

        return redirect('/login');
    }

    public function render()
    {
        return view('livewire.auth.admin-register');
    }
}


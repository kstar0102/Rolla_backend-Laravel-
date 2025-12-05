<?php

namespace App\Http\Livewire\Auth;

use App\Models\Admin;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class Login extends Component
{

    public $email = '';
    public $password = '';
    public $remember_me = false;

    protected $rules = [
        'email' => 'required|email:rfc,dns',
        'password' => 'required|min:6',
    ];

    //This mounts the default credentials for the admin. Remove this section if you want to make it public.
    public function mount()
    {
        if (Auth::guard('admin')->check()) {
            return redirect()->intended('/dashboard');
        }
        $this->fill([
            'email' => '',
            'password' => '',
        ]);
    }

    public function login()
    {
        $credentials = $this->validate();
        if (Auth::guard('admin')->attempt(['email' => $this->email, 'password' => $this->password], $this->remember_me)) {
            $admin = Admin::where(['email' => $this->email])->first();
            Auth::guard('admin')->login($admin, $this->remember_me);
            return redirect()->intended('/dashboard');
        } else {
            return $this->addError('email', trans('auth.failed'));
        }
    }

    public function render()
    {
        return view('livewire.auth.login');
    }
}

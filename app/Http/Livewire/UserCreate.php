<?php
namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\QueryException;

class UserCreate extends Component
{
    use WithFileUploads;

    public $user;
    public $first_name;
    public $last_name;
    public $email;
    public $password;
    public $password_confirmation;
    public $rolla_username;
    public $hear_rolla = 'I saw an ad';

    public function mount(User $user)
    {
        $this->user = $user;
        $this->first_name = $user->first_name;
        $this->last_name = $user->last_name;
        $this->email = $user->email;
        $this->rolla_username = $user->rolla_username;
        $this->hear_rolla = $user->hear_rolla ?? 'I saw an ad';
    }

    public function updated($request)
    {
        $this->validateOnly($request, [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|string|max:255|unique:users,email',
            'password' => 'required|string|min:6',
            'rolla_username' => 'required|string|max:255|unique:users,rolla_username',
            'hear_rolla' => 'nullable|string|max:100'
        ]);
    }

    public function save()
    {
        $validatedData = $this->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|string|max:255|unique:users,email',
            'password' => 'required|string|min:6',
            'rolla_username' => 'required|string|max:255|unique:users,rolla_username',
            'hear_rolla' => 'nullable|string|max:100'
        ]);

        if ($this->password) {
            $validatedData['password'] = Hash::make($this->password);
        } else {
            unset($validatedData['password']);
        }

        try {
            // Set default hear_rolla if not provided
            if (empty($validatedData['hear_rolla'])) {
                $validatedData['hear_rolla'] = 'I saw an ad';
            }
            $this->user->create($validatedData);
            session()->flash('message', 'user information saved successfully.');
        } catch (QueryException $e) {
            if ($e->errorInfo[1] == 1062) {
                $this->addError('email', 'The email has already been taken.');
            } else {
                $this->addError('general', 'Failed to create new user due to unexpected error.');
            }
        }
    }

    public function render()
    {
        return view('livewire.usercreate');
    }
}

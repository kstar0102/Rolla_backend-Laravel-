<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Admin;
use Illuminate\Support\Facades\Auth;

class Admins extends Component
{
    public $admins;

    public function mount()
    {
        $this->admins = Admin::all();
    }

    public function toggleActive($id) {
        $currentAdmin = Auth::guard('admin')->user();
        
        // Only active admins can activate/deactivate other admins
        if (!$currentAdmin || !$currentAdmin->is_active) {
            session()->flash('error', 'Only active admins can activate other admins.');
            return;
        }
        
        $admin = Admin::find($id);
        if ($admin) {
            // Prevent deactivating yourself
            if ($admin->id === $currentAdmin->id) {
                session()->flash('error', 'You cannot deactivate your own account.');
                return;
            }
            
            $admin->is_active = !$admin->is_active;
            $admin->save();
            $this->admins = Admin::all();
            
            $status = $admin->is_active ? 'activated' : 'deactivated';
            session()->flash('message', "Admin {$status} successfully.");
        }
    }

    public function remove($id) {
        $admin = Admin::find($id);
        if ($admin) {
            $admin->delete();
            $this->admins = Admin::all();
        }
    }

    public function render()
    {
        return view('livewire.admins');
    }
}


<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\TermsAndConditions as TermsAndConditionsModel;

class TermsAndConditions extends Component
{
    public $terms;

    public function mount()
    {
        try {
            $this->terms = TermsAndConditionsModel::orderBy('created_at', 'desc')->get();
        } catch (\Exception $e) {
            // If table doesn't exist yet, set empty array
            $this->terms = collect([]);
            session()->flash('error', 'Database table not found. Please run migrations: php artisan migrate');
        }
    }

    public function remove($id)
    {
        try {
            $term = TermsAndConditionsModel::find($id);
            if ($term) {
                $term->delete();
                $this->terms = TermsAndConditionsModel::orderBy('created_at', 'desc')->get();
                session()->flash('message', 'Terms and Conditions deleted successfully.');
            } else {
                session()->flash('error', 'Terms and Conditions not found.');
            }
        } catch (\Exception $e) {
            session()->flash('error', 'Error deleting Terms and Conditions: ' . $e->getMessage());
        }
    }

    public function toggleActive($id)
    {
        $term = TermsAndConditionsModel::find($id);
        if ($term) {
            // Deactivate all other terms
            TermsAndConditionsModel::where('id', '!=', $id)->update(['is_active' => false]);
            
            // Toggle current term
            $term->is_active = !$term->is_active;
            $term->save();
            $this->terms = TermsAndConditionsModel::orderBy('created_at', 'desc')->get();
            session()->flash('message', 'Terms and Conditions status updated successfully.');
        }
    }

    public function render()
    {
        return view('livewire.terms-and-conditions');
    }
}

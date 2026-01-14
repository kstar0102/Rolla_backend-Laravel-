<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\TermsAndConditions;

class TermsAndConditionsEdit extends Component
{
    public $term;
    public $term_id;
    public $title;
    public $content;
    public $content_type;
    public $pdf_url;
    public $is_active;

    public function mount($id)
    {
        $this->term = TermsAndConditions::find($id);
        if (!$this->term) {
            abort(404, 'Terms and Conditions not found');
        }
        $this->term_id = $id;
        $this->title = $this->term->title;
        $this->content = $this->term->content;
        $this->content_type = $this->term->content_type ?? 'html'; // Default to HTML when using WYSIWYG editor
        $this->pdf_url = $this->term->pdf_url ?? '';
        $this->is_active = $this->term->is_active ?? true;
    }

    protected $rules = [
        'title' => 'required|string|max:255',
        'content' => 'required|string',
        'content_type' => 'required|string|in:text,html',
        'pdf_url' => 'nullable|url',
        'is_active' => 'boolean',
    ];

    protected $messages = [
        'title.required' => 'Title is required.',
        'content.required' => 'Content is required.',
        'content_type.required' => 'Content type is required.',
        'pdf_url.url' => 'PDF URL must be a valid URL.',
    ];

    public function update()
    {
        $this->validate();

        // If activating this term, deactivate all others
        if ($this->is_active) {
            TermsAndConditions::where('id', '!=', $this->term_id)
                ->where('is_active', true)
                ->update(['is_active' => false]);
        }

        $this->term->update([
            'title' => $this->title,
            'content' => $this->content,
            'content_type' => $this->content_type,
            'pdf_url' => $this->pdf_url ?: null,
            'is_active' => $this->is_active,
        ]);

        session()->flash('message', 'Terms and Conditions updated successfully.');
        return redirect()->route('termsandconditions');
    }

    public function render()
    {
        return view('livewire.terms-and-conditions-edit');
    }
}

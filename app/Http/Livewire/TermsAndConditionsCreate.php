<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\TermsAndConditions;

class TermsAndConditionsCreate extends Component
{
    public $title = '';
    public $content = '';
    public $content_type = 'html'; // Default to HTML when using WYSIWYG editor
    public $pdf_url = '';
    public $is_active = true;

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

    public function save()
    {
        $this->validate();

        // Deactivate all existing terms
        TermsAndConditions::where('is_active', true)->update(['is_active' => false]);

        TermsAndConditions::create([
            'title' => $this->title,
            'content' => $this->content,
            'content_type' => $this->content_type,
            'pdf_url' => $this->pdf_url ?: null,
            'is_active' => $this->is_active,
        ]);

        session()->flash('message', 'Terms and Conditions created successfully.');
        return redirect()->route('termsandconditions');
    }

    public function render()
    {
        return view('livewire.terms-and-conditions-create');
    }
}

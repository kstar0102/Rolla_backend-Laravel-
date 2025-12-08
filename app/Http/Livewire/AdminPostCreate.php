<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\AdminPost;
use Illuminate\Support\Facades\Auth;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;

class AdminPostCreate extends Component
{
    use WithFileUploads;

    public $title = '';
    public $content = '';
    public $image;
    public $is_active = true;

    protected $rules = [
        'title' => 'required|string|max:255',
        'content' => 'required|string',
        'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        'is_active' => 'boolean',
    ];

    public function save()
    {
        $this->validate();

        $imagePath = null;
        if ($this->image) {
            $imagePath = $this->image->store('admin_posts', 'public');
        }

        AdminPost::create([
            'admin_id' => Auth::guard('admin')->id(),
            'title' => $this->title,
            'content' => $this->content,
            'image_path' => $imagePath,
            'is_active' => $this->is_active,
        ]);

        session()->flash('message', 'Admin post created successfully.');
        return redirect()->route('adminposts');
    }

    public function render()
    {
        return view('livewire.admin-post-create');
    }
}

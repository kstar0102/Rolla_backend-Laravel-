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
    public $uploading = false;

    protected $rules = [
        'title' => 'required|string|max:255',
        'content' => 'required|string',
        'image' => 'nullable|image|mimes:jpeg,jpg,png,gif,webp|max:2048',
        'is_active' => 'boolean',
    ];

    protected $messages = [
        'image.image' => 'The file must be an image.',
        'image.mimes' => 'Only JPEG, JPG, PNG, GIF, and WEBP images are allowed.',
        'image.max' => 'The image size must not exceed 2MB.',
    ];

    public function save()
    {
        $this->validate();

        $imageUrl = null;
        if ($this->image) {
            // Additional validation: ensure it's actually an image
            $allowedMimes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp'];
            $mimeType = $this->image->getMimeType();
            
            if (!in_array($mimeType, $allowedMimes)) {
                session()->flash('error', 'Only image files (JPEG, PNG, GIF, WEBP) are allowed.');
                return;
            }

            $this->uploading = true;
            try {
                // Upload directly to AWS S3
                $path = $this->image->store('admin_posts', 's3');
                Storage::disk('s3')->setVisibility($path, 'public');
                $imageUrl = Storage::disk('s3')->url($path);
            } catch (\Exception $e) {
                session()->flash('error', 'Error uploading image: ' . $e->getMessage());
                $this->uploading = false;
                return;
            }
            $this->uploading = false;
        }

        AdminPost::create([
            'admin_id' => Auth::guard('admin')->id(),
            'title' => $this->title,
            'content' => $this->content,
            'image_path' => $imageUrl,
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

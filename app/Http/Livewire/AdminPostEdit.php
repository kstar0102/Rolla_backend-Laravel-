<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\AdminPost;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;

class AdminPostEdit extends Component
{
    use WithFileUploads;

    public $post;
    public $post_id;
    public $title;
    public $content;
    public $image;
    public $existing_image;
    public $is_active;
    public $uploading = false;

    public function mount($id)
    {
        $this->post = AdminPost::find($id);
        if (!$this->post) {
            abort(404, 'Admin post not found');
        }
        $this->post_id = $id;
        $this->title = $this->post->title;
        $this->content = $this->post->content;
        $this->existing_image = $this->post->image_path;
        $this->is_active = $this->post->is_active ?? true;
    }

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

    public function update()
    {
        $this->validate();

        $imageUrl = $this->existing_image;
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

        $this->post->update([
            'title' => $this->title,
            'content' => $this->content,
            'image_path' => $imageUrl,
            'is_active' => $this->is_active,
        ]);

        session()->flash('message', 'Admin post updated successfully.');
        return redirect()->route('adminposts');
    }

    public function render()
    {
        return view('livewire.admin-post-edit');
    }
}

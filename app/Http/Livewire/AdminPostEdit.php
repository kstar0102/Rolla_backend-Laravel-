<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\AdminPost;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Http;

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
        'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        'is_active' => 'boolean',
    ];

    public function update()
    {
        $this->validate();

        $imageUrl = $this->existing_image;
        if ($this->image) {
            $this->uploading = true;
            try {
                // Upload to AWS S3 via API endpoint
                $response = Http::attach(
                    'image',
                    file_get_contents($this->image->getRealPath()),
                    $this->image->getClientOriginalName()
                )->post(url('/api/upload-image'));

                if ($response->successful() && $response->json('url')) {
                    $imageUrl = $response->json('url');
                } else {
                    session()->flash('error', 'Failed to upload image. Please try again.');
                    $this->uploading = false;
                    return;
                }
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

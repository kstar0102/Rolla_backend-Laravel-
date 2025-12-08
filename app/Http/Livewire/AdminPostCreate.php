<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\AdminPost;
use Illuminate\Support\Facades\Auth;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Http;

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
        'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        'is_active' => 'boolean',
    ];

    public function save()
    {
        $this->validate();

        $imageUrl = null;
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

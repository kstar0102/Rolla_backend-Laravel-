<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\AdminPost;
use Illuminate\Support\Facades\Auth;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;
use Aws\S3\S3Client;
use Illuminate\Support\Facades\Log;

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
                // Upload to AWS S3 using AWS SDK
                $s3Client = new S3Client([
                    'version' => 'latest',
                    'region' => env('AWS_DEFAULT_REGION'),
                    'credentials' => [
                        'key' => env('AWS_ACCESS_KEY_ID'),
                        'secret' => env('AWS_SECRET_ACCESS_KEY'),
                    ],
                ]);

                $fileName = 'admin_posts/' . uniqid() . '_' . time() . '.' . $this->image->getClientOriginalExtension();
                $fileContents = file_get_contents($this->image->getRealPath());

                $result = $s3Client->putObject([
                    'Bucket' => env('AWS_BUCKET'),
                    'Key' => $fileName,
                    'Body' => $fileContents,
                    'ACL' => 'public-read',
                    'ContentType' => $this->image->getMimeType(),
                ]);

                $imageUrl = $result['ObjectURL'] ?? env('AWS_URL') . '/' . $fileName;
            } catch (\Exception $e) {
                Log::error('S3 Upload Error: ' . $e->getMessage());
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

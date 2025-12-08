<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\AdminPost;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;
use Aws\S3\S3Client;
use Illuminate\Support\Facades\Log;

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
                    'ContentType' => $this->image->getMimeType(),
                ]);

                // Generate public URL (bucket should have public read policy)
                $region = env('AWS_DEFAULT_REGION', 'us-east-2');
                $bucket = env('AWS_BUCKET');
                $imageUrl = "https://{$bucket}.s3.{$region}.amazonaws.com/{$fileName}";
            } catch (\Exception $e) {
                Log::error('S3 Upload Error: ' . $e->getMessage());
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

<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ImageUploadController extends Controller
{
    public function __construct()
    {
    }

    public function upload(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,jpg,png,gif,webp|max:10240', // Increased to 10MB for high-quality images
        ], [
            'image.required' => 'An image file is required.',
            'image.image' => 'The file must be an image.',
            'image.mimes' => 'Only JPEG, JPG, PNG, GIF, and WEBP images are allowed.',
            'image.max' => 'The image size must not exceed 10MB.',
        ]);

        $image = $request->file('image');
        $path = $image->store('images', 's3');
        
        Storage::disk('s3')->setVisibility($path, 'public');

        $url = Storage::disk('s3')->url($path);
        
        return response()->json(['url' => $url]);
    }
}

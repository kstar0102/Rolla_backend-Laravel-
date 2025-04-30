<?php

namespace App\Http\Controllers\Api;

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
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $image = $request->file('image');
        $path = $image->store('images', 's3');
        
        Storage::disk('s3')->setVisibility($path, 'public');

        $url = Storage::disk('s3')->url($path);
        
        return response()->json(['url' => $url]);
    }
}

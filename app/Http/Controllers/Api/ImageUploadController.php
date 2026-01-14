<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class ImageUploadController extends Controller
{
    public function __construct()
    {
    }

    public function upload(Request $request)
    {
        try {
            $request->validate([
                'image' => 'required|image|mimes:jpeg,jpg,png,gif,webp|max:10240', // Increased to 10MB for high-quality images
            ], [
                'image.required' => 'An image file is required.',
                'image.image' => 'The file must be an image.',
                'image.mimes' => 'Only JPEG, JPG, PNG, GIF, and WEBP images are allowed.',
                'image.max' => 'The image size must not exceed 10MB.',
            ]);

            $image = $request->file('image');
            
            // Check if S3 disk is configured
            if (!config('filesystems.disks.s3.key') || !config('filesystems.disks.s3.secret')) {
                Log::error('S3 credentials not configured');
                return response()->json([
                    'error' => 'S3 storage is not configured. Please contact administrator.',
                    'message' => 'Storage configuration error'
                ], 500);
            }

            // Upload to S3
            try {
                $path = $image->store('images', 's3');
                
                if (!$path) {
                    Log::error('Failed to store image on S3 - path is null');
                    return response()->json([
                        'error' => 'Failed to upload image to storage.',
                        'message' => 'Storage upload failed'
                    ], 500);
                }
                
                // Set visibility
                Storage::disk('s3')->setVisibility($path, 'public');

                // Get URL
                $url = Storage::disk('s3')->url($path);
                
                if (!$url) {
                    Log::error('Failed to get S3 URL for path: ' . $path);
                    return response()->json([
                        'error' => 'Failed to generate image URL.',
                        'message' => 'URL generation failed'
                    ], 500);
                }
                
                Log::info('Image uploaded successfully to S3: ' . $url);
                return response()->json(['url' => $url]);
                
            } catch (\Exception $e) {
                // Check if it's an AWS S3 exception
                if (str_contains($e->getMessage(), 'AWS') || str_contains($e->getMessage(), 'S3')) {
                    Log::error('S3/AWS Exception: ' . $e->getMessage());
                    return response()->json([
                        'error' => 'Failed to upload image to cloud storage.',
                        'message' => 'Storage service error. Please check S3 configuration.'
                    ], 500);
                }
                Log::error('Storage Exception: ' . $e->getMessage());
                Log::error('Storage Exception Trace: ' . $e->getTraceAsString());
                return response()->json([
                    'error' => 'An error occurred while uploading the image.',
                    'message' => $e->getMessage()
                ], 500);
            }
            
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Validation error: ' . json_encode($e->errors()));
            return response()->json([
                'error' => 'Validation failed',
                'message' => 'Please check your image file.',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Unexpected error in ImageUploadController: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            return response()->json([
                'error' => 'An unexpected error occurred.',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}

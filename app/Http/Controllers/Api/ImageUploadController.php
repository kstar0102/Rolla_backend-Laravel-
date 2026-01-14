<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Aws\S3\S3Client;

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
            
            // Check if S3 credentials are configured
            $awsKey = env('AWS_ACCESS_KEY_ID');
            $awsSecret = env('AWS_SECRET_ACCESS_KEY');
            $awsRegion = env('AWS_DEFAULT_REGION', 'us-east-2');
            $awsBucket = env('AWS_BUCKET');
            
            if (!$awsKey || !$awsSecret || !$awsBucket) {
                Log::error('S3 credentials not configured');
                return response()->json([
                    'error' => 'S3 storage is not configured. Please contact administrator.',
                    'message' => 'Storage configuration error'
                ], 500);
            }

            // Upload to S3 using AWS SDK directly (same as AdminPostCreate)
            try {
                $s3Client = new S3Client([
                    'version' => 'latest',
                    'region' => $awsRegion,
                    'credentials' => [
                        'key' => $awsKey,
                        'secret' => $awsSecret,
                    ],
                ]);

                $fileName = 'images/' . uniqid() . '_' . time() . '.' . $image->getClientOriginalExtension();
                $fileContents = file_get_contents($image->getRealPath());

                $result = $s3Client->putObject([
                    'Bucket' => $awsBucket,
                    'Key' => $fileName,
                    'Body' => $fileContents,
                    'ContentType' => $image->getMimeType(),
                    'ACL' => 'public-read', // Make the file publicly accessible
                ]);

                // Generate public URL
                $imageUrl = "https://{$awsBucket}.s3.{$awsRegion}.amazonaws.com/{$fileName}";
                
                Log::info('Image uploaded successfully to S3: ' . $imageUrl);
                return response()->json(['url' => $imageUrl]);
                
            } catch (\Aws\Exception\AwsException $e) {
                Log::error('AWS S3 Exception: ' . $e->getMessage());
                Log::error('AWS Error Code: ' . $e->getAwsErrorCode());
                return response()->json([
                    'error' => 'Failed to upload image to cloud storage.',
                    'message' => 'S3 upload error: ' . $e->getAwsErrorCode()
                ], 500);
            } catch (\Exception $e) {
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

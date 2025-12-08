<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AdminPost;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class AdminPostController extends Controller
{
    /**
     * Get all active admin posts
     */
    public function getActivePosts()
    {
        $posts = AdminPost::where('is_active', true)
            ->with('admin:id,first_name,last_name')
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'statusCode' => true,
            'message' => 'Admin posts retrieved successfully',
            'data' => $posts,
        ]);
    }

    /**
     * Get all admin posts (for admin panel)
     */
    public function getAllPosts()
    {
        $posts = AdminPost::with('admin:id,first_name,last_name')
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'statusCode' => true,
            'message' => 'Admin posts retrieved successfully',
            'data' => $posts,
        ]);
    }

    /**
     * Create a new admin post
     */
    public function createPost(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'admin_id' => 'required|exists:admins,id',
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_active' => 'boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'statusCode' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('admin_posts', 'public');
        }

        $post = AdminPost::create([
            'admin_id' => $request->admin_id,
            'title' => $request->title,
            'content' => $request->content,
            'image_path' => $imagePath,
            'is_active' => $request->input('is_active', true),
        ]);

        $post->load('admin:id,first_name,last_name');

        return response()->json([
            'statusCode' => true,
            'message' => 'Admin post created successfully',
            'data' => $post,
        ], 201);
    }

    /**
     * Update an admin post
     */
    public function updatePost(Request $request, $id)
    {
        $post = AdminPost::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'title' => 'sometimes|required|string|max:255',
            'content' => 'sometimes|required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_active' => 'boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'statusCode' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        if ($request->hasFile('image')) {
            // Delete old image
            if ($post->image_path) {
                Storage::disk('public')->delete($post->image_path);
            }
            $post->image_path = $request->file('image')->store('admin_posts', 'public');
        }

        $post->update($request->only(['title', 'content', 'is_active']));
        $post->load('admin:id,first_name,last_name');

        return response()->json([
            'statusCode' => true,
            'message' => 'Admin post updated successfully',
            'data' => $post,
        ]);
    }

    /**
     * Delete an admin post
     */
    public function deletePost($id)
    {
        $post = AdminPost::findOrFail($id);

        // Delete image if exists
        if ($post->image_path) {
            Storage::disk('public')->delete($post->image_path);
        }

        $post->delete();

        return response()->json([
            'statusCode' => true,
            'message' => 'Admin post deleted successfully',
        ]);
    }
}

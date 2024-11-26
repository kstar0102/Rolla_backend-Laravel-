<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    /**
     * Get user information.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response|\Symfony\Component\HttpFoundation\JsonResponse
     */
    public function getUserInfo(Request $request)
    {
        try {
            $validated = $request->validate([
                'user_id' => 'required|integer|exists:users,id',
            ]);

            $user = User::find($validated['user_id']);

            if ($user) {
                $response = [
                    'statusCode' => true,
                    'message' => "success",
                    'data' => $user,
                ];
                return response()->json($response, 200);
            } else {
                $response = [
                    'statusCode' => false,
                    'message' => "User not found",
                ];
                return response()->json($response, 404);
            }
        } catch (\Exception $e) {
            $response = [
                'statusCode' => false,
                'message' => $e->getMessage(),
            ];
            return response()->json($response, 500);
        }
    }

    /**
     * Update user information.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response|\Symfony\Component\HttpFoundation\JsonResponse
     */
    public function updateUserInfo(Request $request)
    {
        try {
            $validated = $request->validate([
                'user_id' => 'required|integer|exists:users,id',
                'first_name' => 'required|string|max:255',
                'last_name' => 'required|string|max:255',
                'rolla_username' => 'required|string|max:255',
                'happy_place' => 'nullable|string|max:255',
                'photo' => 'nullable|string|max:255',
                'bio' => 'nullable|string|max:255',
                'garage' => 'nullable|string|max:255',
                'country' => 'nullable|string|max:255',
                'hear_rolla' => 'nullable|string|max:255'
            ]);

            $user = User::find($validated['user_id']);

            if ($user) {
                $user->update(array_filter($validated));

                $response = [
                    'statusCode' => true,
                    'message' => "User information updated successfully",
                    'data' => $user,
                ];
                return response()->json($response, 200);
            } else {
                $response = [
                    'statusCode' => false,
                    'message' => "User not found",
                ];
                return response()->json($response, 404);
            }
        } catch (\Exception $e) {
            $response = [
                'statusCode' => false,
                'message' => $e->getMessage(),
            ];
            return response()->json($response, 500);
        }
    }

    /**
     * Get following users' information.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response|\Symfony\Component\HttpFoundation\JsonResponse
     */
    public function getFollowingUsers(Request $request)
    {
        try {
            $validated = $request->validate([
                'user_id' => 'required|integer|exists:users,id',
            ]);

            $user = User::find($validated['user_id']);

            if ($user) {
                $followingIds = collect(explode(',', $user->following_user_id))
                    ->filter()
                    ->map(fn($id) => intval(trim($id)))
                    ->unique()
                    ->values();

                $followingUsers = User::whereIn('id', $followingIds)
                    ->select('id', 'photo', 'first_name', 'last_name', 'rolla_username')
                    ->get();

                $response = [
                    'statusCode' => true,
                    'message' => "Following users retrieved successfully",
                    'data' => $followingUsers,
                ];
                return response()->json($response, 200);
            } else {
                $response = [
                    'statusCode' => false,
                    'message' => "User not found",
                ];
                return response()->json($response, 404);
            }
        } catch (\Exception $e) {
            $response = [
                'statusCode' => false,
                'message' => $e->getMessage(),
            ];
            return response()->json($response, 500);
        }
    }

    /**
     * Delete user account.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response|\Symfony\Component\HttpFoundation\JsonResponse
     */
    public function deleteUserAccount(Request $request)
    {
        try {
            $validated = $request->validate([
                'user_id' => 'required|integer|exists:users,id',
            ]);

            $user = User::find($validated['user_id']);

            if ($user) {
                $user->delete();

                $response = [
                    'statusCode' => true,
                    'message' => "User account deleted successfully",
                ];
                return response()->json($response, 200);
            } else {
                $response = [
                    'statusCode' => false,
                    'message' => "User not found",
                ];
                return response()->json($response, 404);
            }
        } catch (\Exception $e) {
            $response = [
                'statusCode' => false,
                'message' => $e->getMessage(),
            ];
            return response()->json($response, 500);
        }
    }
}

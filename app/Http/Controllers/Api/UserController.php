<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Droppin;
use App\Models\Trip;

class UserController extends Controller
{

        /**
     * Get user information.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response|\Symfony\Component\HttpFoundation\JsonResponse
     */
    public function getAllUsers(Request $request)
    {
        try {
            $users = User::all();

            if ($users) {
                $response = [
                    'statusCode' => true,
                    'message' => "success",
                    'data' => $users,
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
     * Block User
     *
     * @param Request $request
     * @return \Illuminate\Http\Response|\Symfony\Component\HttpFoundation\JsonResponse
     */
    public function blockUser(Request $request)
    {
        try {
            $validated = $request->validate([
                'user_id' => 'required|integer|exists:users,id',
                'block_id' => 'required|integer|exists:users,id',
            ]);
    
            $user = User::find($validated['user_id']);
    
            if (!$user) {
                return response()->json([
                    'statusCode' => false,
                    'message' => "User not found",
                ], 404);
            }

            $blockList = $user->block_users ? explode(',', $user->block_users) : [];
            
            $flag = false;
            if (!in_array($validated['block_id'], $blockList)) {
                $blockList[] = $validated['block_id'];
                $flag = true;
            } else if (in_array($validated['block_id'], $blockList)) {
                $blockList = array_diff($blockList, [$validated['block_id']]);
            }
            
            $user->block_users = implode(',', $blockList);
            $user->save();
    
            return response()->json([
                'statusCode' => true,
                'message' => $flag ? "User Block successfully" : "User Unblock successfully",
                'data' => $user,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'statusCode' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Add a like to a droppin by a user.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response|\Symfony\Component\HttpFoundation\JsonResponse
     */
    public function followedUsers(Request $request)
    {
        try {
            $validated = $request->validate([
                'user_id' => 'required|integer|exists:users,id',
            ]);
    
            $user = User::find($validated['user_id']);
    
            if (!$user) {
                return response()->json([
                    'statusCode' => false,
                    'message' => "User not found",
                ], 404);
            }

            $usersFollowing = User::whereRaw("FIND_IN_SET(?, following_user_id)", [$validated['user_id']])->get();

            if ($usersFollowing->isEmpty()) {
                return response()->json([
                    'statusCode' => false,
                    'message' => "No users following this user",
                ], 404);
            }
    
            return response()->json([
                'statusCode' => true,
                'message' => "Users found successfully",
                'data' => $usersFollowing,
            ], 200);
    
            return response()->json([
                'statusCode' => true,
                'message' => $flag ? "User following successfully" : "User unfollowing successfully",
                'data' => $user,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'statusCode' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Add a like to a droppin by a user.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response|\Symfony\Component\HttpFoundation\JsonResponse
     */
    public function followedUserTrips(Request $request)
    {
        try {
            $validated = $request->validate([
                'user_id' => 'required|integer|exists:users,id',
            ]);
    
            $user = User::find($validated['user_id']);
    
            if (!$user) {
                return response()->json([
                    'statusCode' => false,
                    'message' => "User not found",
                ], 404);
            }

            $usersFollowing = User::whereRaw("FIND_IN_SET(?, following_user_id)", [$validated['user_id']])->get();
            
            $usersIncludingRequest = $usersFollowing->push($user);
            
            if ($usersIncludingRequest->isEmpty()) {
                return response()->json([
                    'statusCode' => false,
                    'message' => "No users following this user",
                ], 404);
            }

            $tripData = Trip::whereIn('user_id', $usersIncludingRequest->pluck('id'))->with([
                'user:id,photo,rolla_username,first_name,last_name,following_user_id,block_users',
                'droppins',
                'comments.user:id,photo,rolla_username,first_name,last_name',
            ])->get();

            $tripData->each(function ($trip) {
                $trip->droppins->transform(function ($droppin) {
                    $userIds = collect(explode(',', $droppin->likes_user_id))
                        ->filter()
                        ->map(fn($id) => intval(trim($id)))
                        ->unique();
    
                    $likedUsers = User::whereIn('id', $userIds)
                        ->select('id', 'photo', 'rolla_username', 'first_name', 'last_name')
                        ->get();
    
                    $droppin->liked_users = $likedUsers;
    
                    return $droppin;
                });
            });

            return response()->json([
                'statusCode' => true,
                'message' => "Users found successfully",
                'data' => $tripData,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'statusCode' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Add a like to a droppin by a user.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response|\Symfony\Component\HttpFoundation\JsonResponse
     */
    public function followingUser(Request $request)
    {
        try {
            $validated = $request->validate([
                'user_id' => 'required|integer|exists:users,id',
                'following_id' => 'required|integer|exists:users,id',
            ]);
    
            $user = User::find($validated['user_id']);
    
            if (!$user) {
                return response()->json([
                    'statusCode' => false,
                    'message' => "User not found",
                ], 404);
            }

            $likes = $user->following_user_id ? explode(',', $user->following_user_id) : [];
            
            $flag = false;
            if (!in_array($validated['following_id'], $likes)) {
                $likes[] = $validated['following_id'];
                $flag = true;
            } else if (in_array($validated['following_id'], $likes)) {
                $likes = array_diff($likes, [$validated['following_id']]);
            }
            
            $user->following_user_id = implode(',', $likes);
            $user->save();
    
            return response()->json([
                'statusCode' => true,
                'message' => $flag ? "User following successfully" : "User unfollowing successfully",
                'data' => $user,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'statusCode' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Add a like to a droppin by a user.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response|\Symfony\Component\HttpFoundation\JsonResponse
     */
    public function droppinLike(Request $request)
    {
        try {
            $validated = $request->validate([
                'user_id' => 'required|integer|exists:users,id',
                'droppin_id' => 'required|integer|exists:droppins,id',
                'flag' => 'required|boolean',
            ]);
    
            $droppin = Droppin::find($validated['droppin_id']);
    
            if (!$droppin) {
                return response()->json([
                    'statusCode' => false,
                    'message' => "Droppin not found",
                ], 404);
            }

            $likes = $droppin->likes_user_id ? explode(',', $droppin->likes_user_id) : [];
    
            if ($validated['flag']) {
                if (!in_array($validated['user_id'], $likes)) {
                    $likes[] = $validated['user_id'];
                }
            } else {
                if (in_array($validated['user_id'], $likes)) {
                    $likes = array_diff($likes, [$validated['user_id']]);
                }
            }
            
            $droppin->likes_user_id = implode(',', $likes);
            $droppin->save();
    
            return response()->json([
                'statusCode' => true,
                'message' => $validated['flag'] ? "Droppin liked successfully" : "Droppin unliked successfully",
                'data' => $droppin,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'statusCode' => false,
                'message' => $e->getMessage(),
            ], 500);
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

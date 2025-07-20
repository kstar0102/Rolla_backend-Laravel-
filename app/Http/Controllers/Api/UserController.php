<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Droppin;
use App\Models\Trip;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

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
                'id' => 'required|integer|exists:users,id',
            ]);

            $user = User::find($validated['id']);

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
                // $followingIds = collect(explode(',', $user->following_user_id))
                //     ->filter()
                //     ->map(fn($id) => intval(trim($id)))
                //     ->unique()
                //     ->values();
                $followingData = json_decode($user->following_user_id, true);

                if (!is_array($followingData)) {
                    return response()->json([
                        'statusCode' => false,
                        'message' => "Invalid following_user_id format.",
                    ], 400);
                }

                $followingIds = collect($followingData)
                    ->pluck('id')
                    ->filter()
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

    public function getNotificationUsers(Request $request)
    {
        try {
            $validated = $request->validate([
                'id' => 'required|integer|exists:users,id',
            ]);

            $user = User::find($validated['id']);

            if (!$user) {
                return response()->json([
                    'statusCode' => false,
                    'message' => "User not found",
                ], 404);
            }

            // --- From pending list ---
            $pendingItems = collect(json_decode($user->following_pending_userid))
                ->filter(fn($item) => isset($item->id, $item->date, $item->viewedBool))
                ->map(fn($item) => [
                    'id' => intval($item->id),
                    'date' => $item->date,
                    'from' => 'pending',
                    'viewed' => $item->viewedBool
                ]);

            // --- From following_user_id with notificationBool === false ---
            $followItems = collect(json_decode($user->following_user_id))
                ->filter(fn($item) => isset($item->id, $item->date, $item->notificationBool, $item->viewedBool) && $item->notificationBool === false)
                ->map(fn($item) => [
                    'id' => intval($item->id),
                    'date' => $item->date,
                    'from' => 'follow',
                    'viewed' => $item->viewedBool
                ]);

            $tagItems = collect(json_decode($user->tag_notification))
                ->filter(fn($item) => isset($item->id, $item->date, $item->trip_id, $item->notificationBool, $item->viewedBool) && $item->notificationBool === false)
                ->map(fn($item) => [
                    'id' => intval($item->id),
                    'date' => $item->date,
                    'tripId' => $item->trip_id,
                    'from' => 'tag',
                    'viewed' => $item->viewedBool
                ]);

            $commentItems = collect(json_decode($user->comment_notification))
                ->filter(fn($item) => isset($item->id, $item->date, $item->tripid, $item->notificationBool, $item->viewedBool) && $item->notificationBool === false)
                ->map(fn($item) => [
                    'id' => intval($item->id),
                    'date' => $item->date,
                    'trip' => $item->tripid,
                    'from' => 'comment',
                    'viewed' => $item->viewedBool
                ]);

            $likeItems = collect(json_decode($user->like_notification))
                ->filter(fn($item) => isset($item->id, $item->date, $item->trip_id, $item->likeid, $item->notificationBool, $item->viewedBool) && $item->notificationBool === false)
                ->map(fn($item) => [
                    'id' => intval($item->id),
                    'date' => $item->date,
                    'likeId' => $item->likeid,
                    'tripId' => $item->trip_id,
                    'from' => 'like',
                    'viewed' => $item->viewedBool
                ]);

            $followedItems = collect(json_decode($user->followed_user_id))
                ->filter(fn($item) => isset($item->id, $item->date, $item->notificationBool, $item->viewedBool) && $item->notificationBool === false)
                ->map(fn($item) => [
                    'id' => intval($item->id),
                    'date' => $item->date,
                    'from' => 'followed',
                    'viewed' => $item->viewedBool
                ]);

            // Merge all
            $allItems = $pendingItems->merge($followItems)->merge($tagItems)->merge($commentItems)->merge($likeItems)->merge($followedItems);

            // Get unique user IDs
            $allUserIds = $allItems->pluck('id')->unique();

            // Get user details
            $fetchedUsers = User::whereIn('id', $allUserIds)
                ->select('id', 'photo', 'first_name', 'last_name', 'rolla_username')
                ->get();

            // Merge extra info
            $finalResult = $allItems->map(function ($item) use ($fetchedUsers) {
                $user = $fetchedUsers->firstWhere('id', $item['id']);
                if (!$user) return null;
            
                $base = [
                    'id' => $user->id,
                    'first_name' => $user->first_name,
                    'last_name' => $user->last_name,
                    'rolla_username' => $user->rolla_username,
                    'photo' => $user->photo,
                    'follow_date' => $item['date'],
                    'from' => $item['from'],
                    'viewed' => $item['viewed'],
                ];
            
                // Add trip ID only if it's a comment notification
                if ($item['from'] === 'comment' && isset($item['trip'])) {
                    $base['trip'] = $item['trip'];
                }

                if ($item['from'] === 'tag' && isset($item['tripId'])) {
                    $base['tripId'] = $item['tripId'];
                }

                if ($item['from'] === 'like' && isset($item['likeId'])) {
                    $base['likeid'] = $item['likeId'];
                    $base['tripId'] = $item['tripId'];
                }
            
                return $base;
            })->filter()->sortByDesc('follow_date')->values();
            

            return response()->json([
                'statusCode' => true,
                'message' => "Users retrieved successfully",
                'data' => $finalResult,
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'statusCode' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }


    public function getPendingFollowingUsers(Request $request)
    {
        try {
            $validated = $request->validate([
                'user_id' => 'required|integer|exists:users,id',
            ]);

            $user = User::find($validated['user_id']);

            if ($user) {
                // Step 1: Decode and collect id + date
                $followingItems = collect(json_decode($user->following_pending_userid))
                    ->filter(function ($item) {
                        return isset($item->id, $item->date);
                    })
                    ->map(function ($item) {
                        return [
                            'id' => intval($item->id),
                            'date' => $item->date,
                        ];
                    });

                // Step 2: Extract unique IDs
                $followingIds = $followingItems->pluck('id')->unique();

                // Step 3: Get users from DB
                $followingUsers = User::whereIn('id', $followingIds)
                    ->select('id', 'photo', 'first_name', 'last_name', 'rolla_username')
                    ->get();

                // Step 4: Merge date into each user
                $followingUsersWithDate = $followingUsers->map(function ($user) use ($followingItems) {
                    $dateItem = $followingItems->firstWhere('id', $user->id);
                    return [
                        'id' => $user->id,
                        'first_name' => $user->first_name,
                        'last_name' => $user->last_name,
                        'rolla_username' => $user->rolla_username,
                        'photo' => $user->photo,
                        'follow_date' => $dateItem['date'] ?? null,
                    ];
                });

                return response()->json([
                    'statusCode' => true,
                    'message' => "Following users retrieved successfully",
                    'data' => $followingUsersWithDate,
                ], 200);
            } else {
                return response()->json([
                    'statusCode' => false,
                    'message' => "User not found",
                ], 404);
            }
        } catch (\Exception $e) {
            return response()->json([
                'statusCode' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function viewedTagNotification(Request $request)
    {
        try {
            $validated = $request->validate([
                'user_id' => 'required|integer|exists:users,id',
                'tag_id' => 'required|integer|exists:users,id',
            ]);

            $user = User::find($validated['user_id']);

            if (!$user) {
                return response()->json([
                    'statusCode' => false,
                    'message' => "User not found",
                ], 404);
            }

            $notifications = collect(json_decode($user->tag_notification)) ?? collect();

            $updatedNotifications = $notifications->map(function ($item) use ($validated) {
                if (isset($item->id) && $item->id == $validated['tag_id']) {
                    $item->viewedBool = true;
                }
                return $item;
            });

            $user->tag_notification = $updatedNotifications->toJson();
            $user->save();

            return response()->json([
                'statusCode' => true,
                'message' => "viewed tag notification",
                'data' => $user,
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'statusCode' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function markTagNotificationAsRead(Request $request)
    {
        try {
            $validated = $request->validate([
                'user_id' => 'required|integer|exists:users,id',
                'tag_id' => 'required|integer|exists:users,id',
            ]);

            $user = User::find($validated['user_id']);

            if (!$user) {
                return response()->json([
                    'statusCode' => false,
                    'message' => "User not found",
                ], 404);
            }

            $notifications = collect(json_decode($user->tag_notification)) ?? collect();

            $updatedNotifications = $notifications->map(function ($item) use ($validated) {
                if (isset($item->id) && $item->id == $validated['tag_id']) {
                    $item->notificationBool = true;
                }
                return $item;
            });

            $user->tag_notification = $updatedNotifications->toJson();
            $user->save();

            return response()->json([
                'statusCode' => true,
                'message' => "Tag notification marked as read",
                'data' => $user,
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'statusCode' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function viewedCommentNotification (Request $request)
    {
        try {
            $validated = $request->validate([
                'user_id' => 'required|integer|exists:users,id',
                'commenter_id' => 'required|integer|exists:users,id',
            ]);

            $user = User::find($validated['user_id']);

            if (!$user) {
                return response()->json([
                    'statusCode' => false,
                    'message' => "User not found",
                ], 404);
            }

            $notifications = collect(json_decode($user->comment_notification)) ?? collect();

            $matchFound = $notifications->contains(function ($item) use ($validated) {
                return isset($item->id) && $item->id == $validated['commenter_id'];
            });
    
            if (!$matchFound) {
                return response()->json([
                    'statusCode' => false,
                    'message' => "No matching like notification found for this commenter_id",
                ], 404);
            };

            $updatedNotifications = $notifications->map(function ($item) use ($validated) {
                if (isset($item->id) && $item->id == $validated['commenter_id']) {
                    $item->viewedBool = true;
                }
                return $item;
            });

            $user->comment_notification = $updatedNotifications->toJson();
            $user->save();

            return response()->json([
                'statusCode' => true,
                'message' => "Comment notification(s) marked as read",
                'data' => $user,
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'statusCode' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function markCommentNotificationAsRead(Request $request)
    {
        try {
            $validated = $request->validate([
                'user_id' => 'required|integer|exists:users,id',
                'commenter_id' => 'required|integer|exists:users,id',
            ]);

            $user = User::find($validated['user_id']);

            if (!$user) {
                return response()->json([
                    'statusCode' => false,
                    'message' => "User not found",
                ], 404);
            }

            $notifications = collect(json_decode($user->comment_notification)) ?? collect();

            $matchFound = $notifications->contains(function ($item) use ($validated) {
                return isset($item->id) && $item->id == $validated['commenter_id'];
            });
    
            if (!$matchFound) {
                return response()->json([
                    'statusCode' => false,
                    'message' => "No matching like notification found for this commenter_id",
                ], 404);
            };

            $updatedNotifications = $notifications->map(function ($item) use ($validated) {
                if (isset($item->id) && $item->id == $validated['commenter_id']) {
                    $item->notificationBool = true;
                }
                return $item;
            });

            $user->comment_notification = $updatedNotifications->toJson();
            $user->save();

            return response()->json([
                'statusCode' => true,
                'message' => "Comment notification(s) marked as read",
                'data' => $user,
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'statusCode' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function viewedLikeNotification(Request $request)
    {
        try {
            $validated = $request->validate([
                'user_id' => 'required|integer|exists:users,id', 
                'like_id' => 'required|integer|exists:users,id', 
            ]);

            $user = User::find($validated['user_id']);

            if (!$user) {
                return response()->json([
                    'statusCode' => false,
                    'message' => "User not found",
                ], 404);
            }

            $notifications = collect(json_decode($user->like_notification)) ?? collect();
            
            $matchFound = $notifications->contains(function ($item) use ($validated) {
                return isset($item->id) && $item->id == $validated['like_id'];
            });
    
            if (!$matchFound) {
                return response()->json([
                    'statusCode' => false,
                    'message' => "No matching like notification found for this like_id",
                ], 404);
            };

            $updatedNotifications = $notifications->map(function ($item) use ($validated) {
                if (isset($item->id) && $item->id == $validated['like_id']) {
                    $item->viewedBool = true;
                }
                return $item;
            });

            $user->like_notification = $updatedNotifications->values()->toJson();
            $user->save();

            return response()->json([
                'statusCode' => true,
                'message' => "Like notification(s) marked as read",
                'data' => $user,
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'statusCode' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function markLikeNotificationAsRead(Request $request)
    {
        try {
            $validated = $request->validate([
                'user_id' => 'required|integer|exists:users,id', 
                'like_id' => 'required|integer|exists:users,id', 
            ]);

            $user = User::find($validated['user_id']);

            if (!$user) {
                return response()->json([
                    'statusCode' => false,
                    'message' => "User not found",
                ], 404);
            }

            $notifications = collect(json_decode($user->like_notification)) ?? collect();
            
            $matchFound = $notifications->contains(function ($item) use ($validated) {
                return isset($item->id) && $item->id == $validated['like_id'];
            });
    
            if (!$matchFound) {
                return response()->json([
                    'statusCode' => false,
                    'message' => "No matching like notification found for this like_id",
                ], 404);
            };

            $updatedNotifications = $notifications->map(function ($item) use ($validated) {
                if (isset($item->id) && $item->id == $validated['like_id']) {
                    $item->notificationBool = true;
                }
                return $item;
            });

            $user->like_notification = $updatedNotifications->values()->toJson();
            $user->save();

            return response()->json([
                'statusCode' => true,
                'message' => "Like notification(s) marked as read",
                'data' => $user,
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'statusCode' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function viewedFollowingNotification(Request $request)
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

            $followList = collect(json_decode($user->following_user_id)) ?? collect();

            $updatedList = $followList->map(function ($item) use ($validated) {
                if (isset($item->id) && $item->id == $validated['following_id']) {
                    $item->viewedBool = true;
                }
                return $item;
            });

            $user->following_user_id = $updatedList->toJson();
            $user->save();

            return response()->json([
                'statusCode' => true,
                'message' => "Notification marked as sent",
                'data' => $user,
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'statusCode' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function markFollowNotificationAsSent(Request $request)
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

            $followList = collect(json_decode($user->following_user_id)) ?? collect();

            $updatedList = $followList->map(function ($item) use ($validated) {
                if (isset($item->id) && $item->id == $validated['following_id']) {
                    $item->notificationBool = true;
                }
                return $item;
            });

            $user->following_user_id = $updatedList->toJson();
            $user->save();

            return response()->json([
                'statusCode' => true,
                'message' => "Notification marked as sent",
                'data' => $user,
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'statusCode' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function viewedFollowPendingNotification(Request $request)
    {
        try {
            $validated = $request->validate([
                'user_id' => 'required|integer|exists:users,id',
                'followpending_id' => 'required|integer|exists:users,id',
            ]);

            $user = User::find($validated['user_id']);

            if (!$user) {
                return response()->json([
                    'statusCode' => false,
                    'message' => "User not found",
                ], 404);
            }

            $followList = collect(json_decode($user->following_pending_userid)) ?? collect();

            $updatedList = $followList->map(function ($item) use ($validated) {
                if (isset($item->id) && $item->id == $validated['followpending_id']) {
                    $item->viewedBool = true;
                }
                return $item;
            });

            $user->following_pending_userid = $updatedList->toJson();
            $user->save();

            return response()->json([
                'statusCode' => true,
                'message' => "Notification marked as sent",
                'data' => $user,
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'statusCode' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function viewedFollowedNotification(Request $request)
    {
        try {
            $validated = $request->validate([
                'user_id' => 'required|integer|exists:users,id',
                'followed_id' => 'required|integer|exists:users,id',
            ]);

            $user = User::find($validated['user_id']);

            if (!$user) {
                return response()->json([
                    'statusCode' => false,
                    'message' => "User not found",
                ], 404);
            }

            $followList = collect(json_decode($user->followed_user_id)) ?? collect();

            $updatedList = $followList->map(function ($item) use ($validated) {
                if (isset($item->id) && $item->id == $validated['followed_id']) {
                    $item->viewedBool = true;
                }
                return $item;
            });

            $user->followed_user_id = $updatedList->toJson();
            $user->save();

            return response()->json([
                'statusCode' => true,
                'message' => "Notification marked as sent",
                'data' => $user,
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'statusCode' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

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
            $followingList = $user->following_user_id ? explode(',', $user->following_user_id) : [];
            
            $flag = false;
            if (!in_array($validated['block_id'], $blockList)) {
                $blockList[] = $validated['block_id'];
                if (in_array($validated['block_id'], $followingList)) {
                    $followingList = array_diff($followingList, [$validated['block_id']]);
                    $user->following_user_id = implode(',', $followingList);
                }
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
     * Get Block Users.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response|\Symfony\Component\HttpFoundation\JsonResponse
     */
    public function getBlockUsers(Request $request)
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

            $blockUserIds = $user->block_users ? explode(',', $user->block_users) : [];

            $blockUsers = User::whereIn('id', $blockUserIds)
                                ->select('id', 'photo', 'rolla_username', 'first_name', 'last_name')
                                ->get();
            
            if ($blockUsers->isEmpty()) {
                return response()->json([
                    'statusCode' => false,
                    'message' => "No blocked users",
                ], 404);
            }
    
            return response()->json([
                'statusCode' => true,
                'message' => "Users found successfully",
                'data' => $blockUsers,
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
    public function getBlockUserTrips(Request $request)
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

            $blockUsers = User::whereRaw("FIND_IN_SET(?, block_users)", [$validated['user_id']])->get();
            
            $usersIncludingRequest = $blockUsers->push($user);
            
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
    // public function followedUsers(Request $request)
    // {
    //     try {
    //         $validated = $request->validate([
    //             'user_id' => 'required|integer|exists:users,id',
    //         ]);
    
    //         $user = User::find($validated['user_id']);
    
    //         if (!$user) {
    //             return response()->json([
    //                 'statusCode' => false,
    //                 'message' => "User not found",
    //             ], 404);
    //         }

    //         $usersFollowing = User::whereRaw("FIND_IN_SET(?, following_user_id)", [$validated['user_id']])->get();

    //         if ($usersFollowing->isEmpty()) {
    //             return response()->json([
    //                 'statusCode' => false,
    //                 'message' => "No users following this user",
    //             ], 404);
    //         }
    
    //         return response()->json([
    //             'statusCode' => true,
    //             'message' => "Users found successfully",
    //             'data' => $usersFollowing,
    //         ], 200);
    
    //         return response()->json([
    //             'statusCode' => true,
    //             'message' => $flag ? "User following successfully" : "User unfollowing successfully",
    //             'data' => $user,
    //         ], 200);
    //     } catch (\Exception $e) {
    //         return response()->json([
    //             'statusCode' => false,
    //             'message' => $e->getMessage(),
    //         ], 500);
    //     }
    // }

    public function followedUsers(Request $request)
    {
        try {
            $validated = $request->validate([
                'id' => 'required|integer|exists:users,id',
            ]);

            $user = User::find($validated['id']);

            if ($user) {
                $followingData = json_decode($user->followed_user_id, true);

                if (!is_array($followingData)) {
                    return response()->json([
                        'statusCode' => false,
                        'message' => "Invalid followed_user_id format.",
                        'data' => $followingData
                    ], 400);
                }

                $followingIds = collect($followingData)
                    ->pluck('id')
                    ->filter()
                    ->unique()
                    ->values();

                $followingUsers = User::whereIn('id', $followingIds)
                    ->select('id', 'photo', 'first_name', 'last_name', 'rolla_username')
                    ->get();

                return response()->json([
                    'statusCode' => true,
                    'message' => "Following users retrieved successfully",
                    'data' => $followingUsers,
                ], 200);
            } else {
                return response()->json([
                    'statusCode' => false,
                    'message' => "User not found",
                ], 404);
            }
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
     
             $user = User::select(
                 'id',
                 'photo',
                 'rolla_username',
                 'first_name',
                 'last_name',
                 'following_user_id',
                 'followed_user_id',
                 'block_users',
                 'following_pending_userid',
                 'tag_notification',
                 'comment_notification',
                 'like_notification'
             )->find($validated['user_id']);
     
             if (!$user) {
                 return response()->json([
                     'statusCode' => false,
                     'message' => "User not found",
                 ], 404);
             }
     
             // Step 1: Get followed users from JSON
             $followingJson = json_decode($user->followed_user_id, true) ?? [];
     
             // Step 2: Extract user IDs
             $followingIds = collect($followingJson)
                 ->pluck('id')
                 ->filter()
                 ->map(fn($id) => intval($id))
                 ->unique()
                 ->toArray();
     
             // Step 3: Add the current user's ID
             $allUserIds = array_unique(array_merge([$user->id], $followingIds));
     
             // Step 4: Get trips
             $tripData = Trip::whereIn('user_id', $allUserIds)->with([
                 'user:id,photo,rolla_username,first_name,last_name,following_user_id,followed_user_id,block_users,following_pending_userid,tag_notification,comment_notification,like_notification',
                 'droppins',
                 'comments.user:id,photo,rolla_username,first_name,last_name',
             ])->get();
     
             // Step 5: Attach liked users to droppins
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
                 'message' => "Trips retrieved successfully",
                 'userinfo' => $user,
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

    public function removeFollowRequest(Request $request)
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

            $pending = $user->following_pending_userid 
                ? json_decode($user->following_pending_userid, true) 
                : [];

            $updatedPending = collect($pending)
                ->reject(fn ($item) => $item['id'] == $validated['following_id'])
                ->values()
                ->toArray();

            if (count($pending) !== count($updatedPending)) {
                $user->following_pending_userid = json_encode($updatedPending);
                $user->save();

                return response()->json([
                    'statusCode' => true,
                    'message' => "Follow request removed",
                    'data' => $updatedPending,
                ]);
            }

            return response()->json([
                'statusCode' => false,
                'message' => "Follow request not found",
            ], 404);

        } catch (\Exception $e) {
            return response()->json([
                'statusCode' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }


    public function requestToFollowUser(Request $request)
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

            $pending = $user->following_pending_userid 
                ? json_decode($user->following_pending_userid, true) 
                : [];

            $alreadyRequested = collect($pending)->pluck('id')->contains($validated['following_id']);

            if (!$alreadyRequested) {
                $pending[] = [
                    'id' => $validated['following_id'],
                    'date' => now()->toDateTimeString(),
                    'viewedBool' => false,
                ];
                $user->following_pending_userid = json_encode($pending);
                $user->save();
            }

            return response()->json([
                'statusCode' => true,
                'message' => "Follow request sent",
                'data' => $pending,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'statusCode' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function acceptFollowRequest(Request $request)
    {
        try {
            $validated = $request->validate([
                'user_id' => 'required|integer|exists:users,id',         
                'following_id' => 'required|integer|exists:users,id',    
            ]);

            $user = User::find($validated['user_id']);         // User A
            $followedUser = User::find($validated['following_id']); // User B

            if (!$user || !$followedUser) {
                return response()->json([
                    'statusCode' => false,
                    'message' => "User not found",
                ], 404);
            }

            // Remove from pending list
            $pendingList = json_decode($user->following_pending_userid, true) ?? [];

            $filteredPending = collect($pendingList)
                ->filter(fn($item) => isset($item['id']) && $item['id'] != $validated['following_id'])
                ->values();

            if (count($pendingList) === $filteredPending->count()) {
                return response()->json([
                    'statusCode' => false,
                    'message' => "No such follow request found",
                ], 400);
            }

            $user->following_pending_userid = json_encode($filteredPending);

            // Add to following_user_id for current user
            $followingList = json_decode($user->following_user_id, true) ?? [];

            $alreadyFollowing = collect($followingList)
                ->contains(fn($item) => $item['id'] == $validated['following_id']);

            if (!$alreadyFollowing) {
                $followingList[] = [
                    'id' => $validated['following_id'],
                    'date' => Carbon::now()->toIso8601String(),
                    'notificationBool' => false,
                    'viewedBool' => false,
                ];
                $user->following_user_id = json_encode($followingList);
            }

            // Add to followed_user_id for the followed user
            $followedList = json_decode($followedUser->followed_user_id, true) ?? [];

            $alreadyFollowed = collect($followedList)
                ->contains(fn($item) => $item['id'] == $validated['user_id']);

            if (!$alreadyFollowed) {
                $followedList[] = [
                    'id' => $validated['user_id'],
                    'date' => Carbon::now()->toIso8601String(),
                    'notificationBool' => false,
                    'viewedBool' => false,
                ];
                $followedUser->followed_user_id = json_encode($followedList);
            }

            $user->save();
            $followedUser->save();

            return response()->json([
                'statusCode' => true,
                'message' => "Follow request accepted",
                'data' => $user,
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'statusCode' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function removeUserFollow(Request $request)
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

            $followingList = json_decode($user->following_user_id, true) ?? [];

            $updatedList = collect($followingList)
                ->filter(fn($item) => isset($item['id']) && $item['id'] != $validated['following_id'])
                ->values(); // re-index array

            // If no item was removed
            if (count($followingList) === $updatedList->count()) {
                return response()->json([
                    'statusCode' => false,
                    'message' => "User was not in the following list",
                ], 400);
            }

            $user->following_user_id = json_encode($updatedList);
            $user->save();

            return response()->json([
                'statusCode' => true,
                'message' => "Follow removed successfully",
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
    // public function droppinLike(Request $request)
    // {
    //     try {
    //         $validated = $request->validate([
    //             'user_id' => 'required|integer|exists:users,id',
    //             'droppin_id' => 'required|integer|exists:droppins,id',
    //             'flag' => 'required|boolean',
    //         ]);
    
    //         $droppin = Droppin::find($validated['droppin_id']);
    
    //         if (!$droppin) {
    //             return response()->json([
    //                 'statusCode' => false,
    //                 'message' => "Droppin not found",
    //             ], 404);
    //         }

    //         $likes = $droppin->likes_user_id ? explode(',', $droppin->likes_user_id) : [];
    
    //         if ($validated['flag']) {
    //             if (!in_array($validated['user_id'], $likes)) {
    //                 $likes[] = $validated['user_id'];
    //             }
    //         } else {
    //             if (in_array($validated['user_id'], $likes)) {
    //                 $likes = array_diff($likes, [$validated['user_id']]);
    //             }
    //         }
            
    //         $droppin->likes_user_id = implode(',', $likes);
    //         $droppin->save();
    
    //         return response()->json([
    //             'statusCode' => true,
    //             'message' => $validated['flag'] ? "Droppin liked successfully" : "Droppin unliked successfully",
    //             'data' => $droppin,
    //         ], 200);
    //     } catch (\Exception $e) {
    //         return response()->json([
    //             'statusCode' => false,
    //             'message' => $e->getMessage(),
    //         ], 500);
    //     }
    // }    

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

            // 💡 Handle like/unlike logic
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

            // 💡 Get target user (owner of the trip related to the droppin)
            $tripId = DB::table('droppins')
                ->where('id', $validated['droppin_id'])
                ->value('trip_id');

            $tripOwnerId = DB::table('trips')
                ->where('id', $tripId)
                ->value('user_id');

            if ($tripOwnerId) {
                $tripOwner = User::find($tripOwnerId);

                if ($tripOwner) {
                    $notifications = collect(json_decode($tripOwner->like_notification)) ?? collect();

                    if ($validated['flag']) {
                        // Add new notification
                        $notifications->push([
                            'id' => $validated['user_id'],
                            'date' => now()->toDateTimeString(),
                            'likeid' => $droppin->id,
                            'trip_id' => $tripId,
                            'notificationBool' => false,
                            'viewedBool' => false,
                        ]);
                    } else {
                        // Remove notification from that user and droppin
                        $notifications = $notifications->reject(function ($item) use ($validated, $droppin) {
                            return isset($item->id, $item->likeid) &&
                                $item->id == $validated['user_id'] &&
                                $item->likeid == $droppin->id;
                        });
                    }

                    $tripOwner->like_notification = $notifications->values()->toJson();
                    $tripOwner->save();
                }
            }

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

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
                ->filter(fn($item) => isset($item->id, $item->date, $item->viewedBool, $item->clickedBool))
                ->map(fn($item) => [
                    'id' => intval($item->id),
                    'date' => $item->date,
                    'from' => 'pending',
                    'viewed' => $item->viewedBool,
                    'clicked' => $item->clickedBool
                ]);

            // --- From following_user_id with notificationBool === false ---
            $followItems = collect(json_decode($user->following_user_id))
                ->filter(fn($item) => isset($item->id, $item->date, $item->notificationBool, $item->viewedBool, $item->clickedBool) && $item->notificationBool === false)
                ->map(fn($item) => [
                    'id' => intval($item->id),
                    'date' => $item->date,
                    'from' => 'follow',
                    'viewed' => $item->viewedBool,
                    'clicked' => $item->clickedBool
                ]);

            $tagItems = collect(json_decode($user->tag_notification))
                ->filter(fn($item) => isset($item->id, $item->date, $item->trip_id, $item->notificationBool, $item->viewedBool, $item->clickedBool) && $item->notificationBool === false)
                ->map(fn($item) => [
                    'id' => intval($item->id),
                    'date' => $item->date,
                    'tripId' => $item->trip_id,
                    'from' => 'tag',
                    'viewed' => $item->viewedBool,
                    'clicked' => $item->clickedBool
                ]);

            $commentItems = collect(json_decode($user->comment_notification))
                ->filter(fn($item) => isset($item->id, $item->date, $item->tripid, $item->notificationBool, $item->viewedBool, $item->clickedBool) && $item->notificationBool === false)
                ->map(fn($item) => [
                    'id' => intval($item->id),
                    'date' => $item->date,
                    'trip' => $item->tripid,
                    'from' => 'comment',
                    'viewed' => $item->viewedBool,
                    'clicked' => $item->clickedBool
                ]);

            $likeItems = collect(json_decode($user->like_notification))
                ->filter(fn($item) => isset($item->id, $item->date, $item->trip_id, $item->likeid, $item->notificationBool, $item->viewedBool, $item->clickedBool) && $item->notificationBool === false)
                ->map(fn($item) => [
                    'id' => intval($item->id),
                    'date' => $item->date,
                    'likeId' => $item->likeid,
                    'tripId' => $item->trip_id,
                    'from' => 'like',
                    'viewed' => $item->viewedBool,
                    'clicked' => $item->clickedBool
                ]);

            $followedItems = collect(json_decode($user->followed_user_id))
                ->filter(fn($item) => isset($item->id, $item->date, $item->notificationBool, $item->viewedBool, $item->clickedBool) && $item->notificationBool === false)
                ->map(fn($item) => [
                    'id' => intval($item->id),
                    'date' => $item->date,
                    'from' => 'followed',
                    'viewed' => $item->viewedBool,
                    'clicked' => $item->clickedBool
                ]);

            // Merge all
            $allItems = $pendingItems
                ->merge($followItems)
                ->merge($tagItems)
                ->merge($commentItems)
                ->merge($likeItems)
                ->merge($followedItems);

            // Get unique user IDs
            $allUserIds = $allItems->pluck('id')->unique()->values();

            // Get user details
            $fetchedUsers = User::whereIn('id', $allUserIds)
                ->select('id', 'photo', 'first_name', 'last_name', 'rolla_username')
                ->get();

            /**
             * PRUNE NOTIFICATIONS THAT REFERENCE DELETED USERS
             * Any ID in $allUserIds that is NOT in $fetchedUsers is considered deleted.
             * Remove those entries from all related JSON fields and persist.
             */
            $missingIds = $allUserIds->diff($fetchedUsers->pluck('id'));
            if ($missingIds->isNotEmpty()) {
                $missingIdSet = $missingIds->values(); // Collection of ints

                $prune = function ($json) use ($missingIdSet) {
                    // decode safely to associative array
                    $arr = json_decode($json ?? '[]', true);
                    if (!is_array($arr)) {
                        $arr = [];
                    }
                    $filtered = array_values(array_filter($arr, function ($item) use ($missingIdSet) {
                        // keep if it has no 'id' (malformed) OR id not in missing set
                        $id = isset($item['id']) ? (int)$item['id'] : null;
                        return $id === null || !$missingIdSet->contains($id);
                    }));
                    return json_encode($filtered, JSON_UNESCAPED_UNICODE);
                };

                $dirty = false;
                $new_following_pending_userid = $prune($user->following_pending_userid);
                $dirty = $dirty || $new_following_pending_userid !== $user->following_pending_userid;
                $user->following_pending_userid = $new_following_pending_userid;

                $new_following_user_id = $prune($user->following_user_id);
                $dirty = $dirty || $new_following_user_id !== $user->following_user_id;
                $user->following_user_id = $new_following_user_id;

                $new_tag_notification = $prune($user->tag_notification);
                $dirty = $dirty || $new_tag_notification !== $user->tag_notification;
                $user->tag_notification = $new_tag_notification;

                $new_comment_notification = $prune($user->comment_notification);
                $dirty = $dirty || $new_comment_notification !== $user->comment_notification;
                $user->comment_notification = $new_comment_notification;

                $new_like_notification = $prune($user->like_notification);
                $dirty = $dirty || $new_like_notification !== $user->like_notification;
                $user->like_notification = $new_like_notification;

                $new_followed_user_id = $prune($user->followed_user_id);
                $dirty = $dirty || $new_followed_user_id !== $user->followed_user_id;
                $user->followed_user_id = $new_followed_user_id;

                if ($dirty) {
                    $user->save();
                }
            }

            // Merge extra info for response (already ignores missing users)
            $finalResult = $allItems->map(function ($item) use ($fetchedUsers) {
                $u = $fetchedUsers->firstWhere('id', $item['id']);
                if (!$u) return null;

                $base = [
                    'id' => $u->id,
                    'first_name' => $u->first_name,
                    'last_name' => $u->last_name,
                    'rolla_username' => $u->rolla_username,
                    'photo' => $u->photo,
                    'follow_date' => $item['date'],
                    'from' => $item['from'],
                    'viewed' => $item['viewed'],
                    'clicked' => $item['clicked'],
                ];

                if ($item['from'] === 'comment' && isset($item['trip'])) {
                    $base['trip'] = $item['trip'];
                }
                if ($item['from'] === 'tag' && isset($item['tripId'])) {
                    $base['tripId'] = $item['tripId'];
                }
                if ($item['from'] === 'like') {
                    if (isset($item['likeId'])) $base['likeid'] = $item['likeId'];
                    if (isset($item['tripId'])) $base['tripId'] = $item['tripId'];
                }

                return $base;
            })
            ->filter()
            ->sortBy([['viewed', 'asc'], ['follow_date', 'desc']])
            ->values();

            return response()->json([
                'statusCode' => true,
                'message' => "Users retrieved successfully",
                'data' => $finalResult,
                // Optional: return which IDs were pruned this call
                // 'pruned_user_ids' => $missingIds->values(),
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

    public function clickedTagNotification(Request $request)
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
                    $item->clickedBool = true;
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

    public function closeFollowedUser(Request $request)
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

            $followeds = collect(json_decode($user->followed_user_id)) ?? collect();

            $found = false;

            $updatedNotifications = $followeds->map(function ($item) use ($validated, &$found) {
                if (isset($item->id) && $item->id == $validated['followed_id']) {
                    $item->notificationBool = true;
                    $found = true;
                }
                return $item;
            });

            if (!$found) {
                return response()->json([
                    'statusCode' => false,
                    'message' => "Followed ID not found in user's followed list",
                ], 404);
            }

            $user->followed_user_id = $updatedNotifications->toJson();
            $user->save();

            return response()->json([
                'statusCode' => true,
                'message' => "Taped followed id",
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

    public function clickedCommentNotification (Request $request)
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
                    $item->clickedBool = true;
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

    public function clickedLikeNotification(Request $request)
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
                    $item->clickedBool = true;
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

    public function clickedFollowingNotification(Request $request)
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
                    $item->clickedBool = true;
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

    public function clickedFollowPendingNotification(Request $request)
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
                    $item->clickedBool = true;
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

    public function clickedFollowedNotification(Request $request)
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
                    $item->clickedBool = true;
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
                    'clickedBool' => false,
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
                    'clickedBool' => false,
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
                    'clickedBool' => false,
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

    public function droppinLike(Request $request)
    {
        try {
            $validated = $request->validate([
                'user_id'    => 'required|integer|exists:users,id',
                'droppin_id' => 'required|integer|exists:droppins,id',
                'flag'       => 'required|boolean', // true = like, false = unlike
            ]);

            $notified = false;

            DB::beginTransaction();

            // Lock droppin row while updating likes
            $droppin = Droppin::where('id', $validated['droppin_id'])->lockForUpdate()->first();

            if (!$droppin) {
                DB::rollBack();
                return response()->json([
                    'statusCode' => false,
                    'message'    => "Droppin not found",
                ], 404);
            }

            // Parse and update likes list
            $likes = $droppin->likes_user_id
                ? array_values(array_filter(explode(',', $droppin->likes_user_id), fn ($v) => $v !== ''))
                : [];

            $uidStr = (string) $validated['user_id'];

            if ($validated['flag']) {
                if (!in_array($uidStr, $likes, true)) {
                    $likes[] = $uidStr;
                }
            } else {
                $likes = array_values(array_diff($likes, [$uidStr]));
            }

            $droppin->likes_user_id = implode(',', $likes);
            $droppin->save();

            // Get trip owner
            $tripId = $droppin->trip_id;
            $tripOwnerId = DB::table('trips')->where('id', $tripId)->value('user_id');

            // Only notify if liker is NOT the trip owner
            $shouldNotify = $tripOwnerId && ((int)$tripOwnerId !== (int)$validated['user_id']);

            if ($shouldNotify) {
                // Lock owner row while updating notification JSON
                $ownerRow = DB::table('users')->where('id', $tripOwnerId)->lockForUpdate()->first();
                $existingJson = $ownerRow?->like_notification ?? null;
                $notifications = $existingJson ? json_decode($existingJson, true) : [];

                if ($validated['flag']) {
                    // Add notification on like
                    $notifications[] = [
                        'id'               => (int) $validated['user_id'],
                        'date'             => now()->toDateTimeString(),
                        'likeid'           => (int) $droppin->id,
                        'trip_id'          => (int) $tripId,
                        'notificationBool' => false,
                        'viewedBool'       => false,
                        'clickedBool'       => false,
                    ];
                    $notified = true;
                } else {
                    // Remove any existing notification for this user + droppin on unlike
                    $notifications = array_values(array_filter(
                        $notifications,
                        fn ($n) => !(
                            isset($n['id'], $n['likeid']) &&
                            (int)$n['id'] === (int)$validated['user_id'] &&
                            (int)$n['likeid'] === (int)$droppin->id
                        )
                    ));
                }

                DB::table('users')
                    ->where('id', $tripOwnerId)
                    ->update(['like_notification' => json_encode($notifications)]);
            }

            DB::commit();

            return response()->json([
                'statusCode' => true,
                'message'    => $validated['flag'] ? "Droppin liked successfully" : "Droppin unliked successfully",
                'data'       => $droppin,
                'notified'   => $notified, // helpful for the client
            ], 200);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'statusCode' => false,
                'message'    => $e->getMessage(),
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

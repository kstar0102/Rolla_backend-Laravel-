<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Models\Trip;
use App\Models\Droppin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\QueryException;
use Exception;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Log;
use function Laravel\Prompts\error;

class TripController extends Controller
{

    protected $auth;
    public function __construct()
    {
    }

    public function getDroppins()
    {
        try {
            // $droppins = Droppin::with('trip.user:id,photo,rolla_username,first_name,last_name')->get();
    
            $droppins = Droppin::with(['trip.user:id,photo,rolla_username,first_name,last_name'])->get();

            $droppins->transform(function ($droppin) {
                if ($droppin->trip && $droppin->trip->user) {
                    $droppin->user = [
                        'id' => $droppin->trip->user->id,
                        'photo' => $droppin->trip->user->photo,
                        'rolla_username' => $droppin->trip->user->rolla_username,
                        'first_name' => $droppin->trip->user->first_name,
                        'last_name' => $droppin->trip->user->last_name,
                    ];
                    unset($droppin->trip);
                } else {
                    $droppin->user = null;
                }
                return $droppin;
            });
            return response()->json([
                'status' => 'success',
                'data' => $droppins,
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to fetch droppins.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
    

    public function createTrip(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
            'start_address' => 'required|string|max:255',
            'stop_address' => 'nullable|string|max:255',
            'destination_address' => 'required|string|max:255',
            'destination_text_address' => 'required|string',
            'trip_start_date' => 'required|date',
            'trip_end_date' => 'nullable|date|after_or_equal:trip_start_date',
            'trip_miles' => 'nullable|string',
            'trip_sound' => 'nullable|string',
            'trip_caption' => 'nullable|string',
            'trip_coordinates' => 'nullable|array',
            'trip_coordinates.*.latitude' => 'nullable|numeric',
            'trip_coordinates.*.longitude' => 'nullable|numeric',
            'stop_locations' => 'required|array',
            'trip_tags' => 'nullable|string',
            'stop_locations.*.latitude' => 'required|numeric',
            'stop_locations.*.longitude' => 'required|numeric',
            'droppins' => 'nullable|array',
            'droppins.*.stop_index' => 'nullable|integer',
            'droppins.*.image_path' => 'required|string',
            'droppins.*.image_caption' => 'required|string',
            'start_location' => 'nullable|string',
            'destination_location' => 'nullable|string',
        ]);
    
        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation errors',
                'errors' => $validator->errors(),
            ], 422);
        }
    
        try {
            DB::beginTransaction();
    
            $trip = Trip::create([
                'user_id' => $request->user_id,
                'start_address' => $request->start_address,
                'stop_address' => $request->stop_address,
                'destination_address' => $request->destination_address,
                'destination_text_address' => $request->destination_text_address,
                'trip_start_date' => Carbon::parse($request->trip_start_date),
                'trip_end_date' => $request->trip_end_date ? Carbon::parse($request->trip_end_date) : null,
                'trip_miles' => $request->trip_miles,
                'trip_sound' => $request->trip_sound,
                'trip_tags' => $request->trip_tags,
                'trip_caption' => $request->trip_caption,
                'trip_coordinates' => $request->trip_coordinates,
                'stop_locations' => $request->stop_locations,
                'start_location' => $request->start_location,
                'destination_location' => $request->destination_location,
            ]);
    
            if ($request->has('droppins') && is_array($request->droppins)) {
                foreach ($request->droppins as $droppinData) {
                    $droppin = new Droppin([
                        'stop_index' => $droppinData['stop_index'],
                        'image_path' => $droppinData['image_path'],
                        'image_caption' => $droppinData['image_caption'] ?? null,
                    ]);
                    $trip->droppins()->save($droppin);
                }
            }
    
            DB::commit();
    
            return response()->json([
                'message' => 'Trip created successfully',
                'trip' => $trip->load('droppins'),
            ], 201);
        } catch (QueryException $qe) {
            DB::rollBack();
    
            return response()->json([
                'message' => 'Failed to create trip',
                'error' => $qe->getMessage(),
            ], 500);
        } catch (Exception $e) {
            DB::rollBack();
    
            return response()->json([
                'message' => 'An unexpected error occurred',
                'error' => $e->getMessage(),
            ], 500);
        }
    }    

    public function getAllTrips(Request $request)
    {
        try {
            $username = $request->input('username');
            $destination = $request->input('destination');

            $tripsQuery = Trip::with([
                'user:id,photo,rolla_username,first_name,last_name,following_user_id,block_users',
                'droppins',
                'comments.user:id,photo,rolla_username,first_name,last_name',
            ]);

            if (!empty($username)) {
                $tripsQuery->whereHas('user', function ($query) use ($username) {
                    $query->where('first_name', 'LIKE', "%{$username}%")
                        ->orWhere('last_name', 'LIKE', "%{$username}%")
                        ->orWhere('rolla_username', 'LIKE', "%{$username}%");
                });
            }
    
            // Filter by destination address
            if (!empty($destination)) {
                $tripsQuery->where('destination_address', 'LIKE', "%{$destination}%");
            }
    
            // Get the filtered trips
            $trips = $tripsQuery->get();
    
            $trips->transform(function ($trip) {
                $trip->user = $trip->user ? [
                    'id' => $trip->user->id,
                    'photo' => $trip->user->photo,
                    'rolla_username' => $trip->user->rolla_username,
                    'first_name' => $trip->user->first_name,
                    'last_name' => $trip->user->last_name,
                ] : null;
    
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
    
                $trip->comments->transform(function ($comment) {
                    if ($comment->user) {
                        $comment->user = [
                            'id' => $comment->user->id,
                            'photo' => $comment->user->photo,
                            'rolla_username' => $comment->user->rolla_username,
                            'first_name' => $comment->user->first_name,
                            'last_name' => $comment->user->last_name,
                        ];
                    }
                    return $comment;
                });

                $mutedIds = collect(explode(',', $trip->muted_ids))
                        ->filter()
                        ->map(fn($id) => intval(trim($id)))
                        ->unique();
                
                $mutedUsers = User::whereIn('id', $mutedIds)
                    ->select('id', 'photo', 'rolla_username', 'first_name', 'last_name')
                    ->get();
            
                $trip->muted_users = $mutedUsers;
                return $trip;
            });
    
            return response()->json([
                'message' => 'All trips retrieved successfully',
                'trips' => $trips,
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'An error occurred while fetching trips',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function getTripsByTripId(Request $request)
    {
        try {
            $tripId = $request->input('trip_id');  // Only filter by trip_id

            if (empty($tripId)) {
                return response()->json([
                    'message' => 'Trip ID is required',
                ], 400);
            }

            $trips = Trip::with([
                'user:id,photo,rolla_username,first_name,last_name,following_user_id,block_users',
                'droppins',
                'comments.user:id,photo,rolla_username,first_name,last_name',
            ])
            ->where('id', $tripId)  // Filter by trip_id
            ->get();

            $trips->transform(function ($trip) {
                $trip->user = $trip->user ? [
                    'id' => $trip->user->id,
                    'photo' => $trip->user->photo,
                    'rolla_username' => $trip->user->rolla_username,
                    'first_name' => $trip->user->first_name,
                    'last_name' => $trip->user->last_name,
                ] : null;

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

                $trip->comments->transform(function ($comment) {
                    if ($comment->user) {
                        $comment->user = [
                            'id' => $comment->user->id,
                            'photo' => $comment->user->photo,
                            'rolla_username' => $comment->user->rolla_username,
                            'first_name' => $comment->user->first_name,
                            'last_name' => $comment->user->last_name,
                        ];
                    }
                    return $comment;
                });

                $mutedIds = collect(explode(',', $trip->muted_ids))
                    ->filter()
                    ->map(fn($id) => intval(trim($id)))
                    ->unique();
            
                $mutedUsers = User::whereIn('id', $mutedIds)
                    ->select('id', 'photo', 'rolla_username', 'first_name', 'last_name')
                    ->get();
            
                $trip->muted_users = $mutedUsers;

                return $trip;
            });

            return response()->json([
                'message' => 'Trip retrieved successfully',
                'trips' => $trips,
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'An error occurred while fetching the trip',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function mutedUser(Request $request)
    {
        try {
            $validated = $request->validate([
                'trip_id' => 'required|integer|exists:trips,id',
                'user_id' => 'required|integer|exists:users,id',
            ]);
    
            $trip = Trip::find($validated['trip_id']);
    
            if (!$trip) {
                return response()->json([
                    'statusCode' => false,
                    'message' => "Trip not found",
                ], 404);
            }

            $mutedUsers = $trip->muted_ids ? explode(',', $trip->muted_ids) : [];
            
            $flag = false;
            if (!in_array($validated['user_id'], $mutedUsers)) {
                $mutedUsers[] = $validated['user_id'];
                $flag = true;
            } else if (in_array($validated['user_id'], $mutedUsers)) {
                $mutedUsers = array_diff($mutedUsers, [$validated['user_id']]);
            }
            
            $trip->muted_ids = implode(',', $mutedUsers);
            $trip->save();
    
            return response()->json([
                'statusCode' => true,
                'message' => $flag ? "User muted successfully" : "User unmuted successfully",
                'data' => $trip,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'statusCode' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function getTripsByUserId(Request $request)
    {
        try {
            $userId = $request->input('user_id');

            if (empty($userId)) {
                return response()->json([
                    'message' => 'User ID is required',
                ], 400);
            }

            $userInfo = User::where('id', $userId)
                            ->select('id', 'photo', 'rolla_username', 'first_name', 'last_name', 'following_user_id', 'block_users', 'happy_place', 'garage')
                            ->get();

            $trips = Trip::with([
                'user:id,photo,rolla_username,first_name,last_name,following_user_id,block_users,happy_place,garage',
                'droppins',
                'comments.user:id,photo,rolla_username,first_name,last_name',
            ])
            ->where('user_id', $userId)
            ->get();

            $trips->transform(function ($trip) {
                $trip->user = $trip->user ? [
                    'id' => $trip->user->id,
                    'photo' => $trip->user->photo,
                    'rolla_username' => $trip->user->rolla_username,
                    'first_name' => $trip->user->first_name,
                    'last_name' => $trip->user->last_name,
                    'following_user_id' => $trip->user->following_user_id,
                    'block_users' => $trip->user->block_users,
                    'happy_place' => $trip->user->happy_place,
                    'garage' => $trip->user->garage_raw
                ] : null;
            
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

                $trip->comments->transform(function ($comment) {
                    if ($comment->user) {
                        $comment->user = [
                            'id' => $comment->user->id,
                            'photo' => $comment->user->photo,
                            'rolla_username' => $comment->user->rolla_username,
                            'first_name' => $comment->user->first_name,
                            'last_name' => $comment->user->last_name,
                        ];
                    }
                    return $comment;
                });

                $mutedIds = collect(explode(',', $trip->muted_ids))
                    ->filter()
                    ->map(fn($id) => intval(trim($id)))
                    ->unique();
            
                $mutedUsers = User::whereIn('id', $mutedIds)
                    ->select('id', 'photo', 'rolla_username', 'first_name', 'last_name')
                    ->get();
            
                $trip->muted_users = $mutedUsers;

                return $trip;
            });

            return response()->json([
                'message' => 'Trips retrieved successfully',
                'trips' => $trips,
                'userInfo' => $userInfo
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'An error occurred while fetching trips',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function updateTrip(Request $request)
    {
        $id = $request->id;

        $validator = Validator::make($request->all(), [
            'id' => 'required|integer|exists:trips,id',
            'user_id' => 'required|exists:users,id',
            'start_address' => 'required|string|max:255',
            'stop_address' => 'nullable|string|max:255',
            'destination_address' => 'required|string|max:255',
            'destination_text_address' => 'required|string|max:1000',
            'trip_start_date' => 'required|date',
            'trip_end_date' => 'nullable|date|after_or_equal:trip_start_date',
            'trip_miles' => 'nullable|string',
            'trip_sound' => 'nullable|string',
            'trip_tags' => 'nullable|string',
            'trip_caption' => 'nullable|string',
            'trip_coordinates' => 'nullable|array',
            'trip_coordinates.*.latitude' => 'nullable|numeric',
            'trip_coordinates.*.longitude' => 'nullable|numeric',
            'stop_locations' => 'required|array',
            'stop_locations.*.latitude' => 'required|numeric',
            'stop_locations.*.longitude' => 'required|numeric',
            'droppins' => 'nullable|array',
            'droppins.*.id' => 'nullable|integer',
            'droppins.*.stop_index' => 'nullable|integer',
            'droppins.*.image_path' => 'required|string',
            'droppins.*.image_caption' => 'required|string',
            'start_location' => 'nullable|string',
            'destination_location' => 'nullable|string',
        ]);
    
        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation errors',
                'errors' => $validator->errors(),
            ], 422);
        }
    
        try {
            DB::beginTransaction();

            $trip = Trip::find($id);

            if (!$trip) {
                return response()->json([
                    'message' => 'not exist',
                ], 404);
            }
    
            $trip->update([
                'user_id' => $request->user_id,
                'start_address' => $request->start_address,
                'stop_address' => $request->stop_address,
                'destination_address' => $request->destination_address,
                'destination_text_address' => $request->destination_text_address,
                'trip_start_date' => Carbon::parse($request->trip_start_date),
                'trip_end_date' => $request->trip_end_date ? Carbon::parse($request->trip_end_date) : null,
                'trip_miles' => $request->trip_miles,
                'trip_sound' => $request->trip_sound,
                'trip_caption' => $request->trip_caption,
                'trip_tags' => $request->trip_tags,
                'trip_coordinates' => $request->trip_coordinates,
                'stop_locations' => $request->stop_locations,
                'start_location' => $request->start_location,
                'destination_location' => $request->destination_location
            ]);

            if ($request->has('droppins') && is_array($request->droppins)) {
                foreach ($request->droppins as $droppinData) {
                    if (!empty($droppinData['id'])) {
                        $droppin = Droppin::find($droppinData['id']);
                        if ($droppin) {
                            $droppin->update([
                                'stop_index' => $droppinData['stop_index'],
                                'image_path' => $droppinData['image_path'],
                                'image_caption' => $droppinData['image_caption'],
                            ]);
                        }
                    } else {
                        $trip->droppins()->create([
                            'stop_index' => $droppinData['stop_index'],
                            'image_path' => $droppinData['image_path'],
                            'image_caption' => $droppinData['image_caption'],
                        ]);
                    }
                }
            }
    
            DB::commit();
    
            return response()->json([
                'message' => 'Trip updated successfully',
                'trip' => $trip->load('droppins'),
            ], 200);
        } catch (QueryException $qe) {
            DB::rollBack();
            return response()->json([
                'message' => 'Failed to update trip',
                'error' => $qe->getMessage(),
            ], 500);
        } catch (Exception $e) {
            DB::rollBack(); 
            return response()->json([
                'message' => 'An unexpected error occurred',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function removeTrip(Request $request)
    {
        try {
            $validated = $request->validate([
                'trip_id' => 'required|integer|exists:trips,id',
            ]);

            $trip = Trip::find($validated['trip_id']);
    
            if (!$trip) {
                return response()->json([
                    'statusCode' => false,
                    'message' => "Trip not found",
                ], 404);
            }

            if ($trip->droppins()->exists()) {
                $trip->droppins()->delete();
            }

            $trip->delete();
    
            return response()->json([
                'statusCode' => true,
                'message' => "Trip removed successfully",
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'statusCode' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }    
    
    public function droppinViewed(Request $request)
    {
        try {
            $validated = $request->validate([
                'droppin_id' => 'required|exists:droppins,id',
                'user_id' => 'required|exists:users,id',
            ]);
    
            $droppin = Droppin::find($validated['droppin_id']);
    
            if (!$droppin) {
                return response()->json([
                    'statusCode' => false,
                    'message' => "Droppin not found",
                ], 404);
            }
    
            $likes = $droppin->view_count ? explode(',', $droppin->view_count) : [];
    
            if (!in_array($validated['user_id'], $likes)) {
                $likes[] = $validated['user_id'];
            }
    
            $droppin->view_count = implode(',', $likes);
            $droppin->save();
    
            return response()->json([
                'statusCode' => true,
                'message' => "Droppin viewed successfully",
                'data' => $droppin,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'statusCode' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}

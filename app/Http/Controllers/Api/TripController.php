<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Models\Trip;
use App\Models\Droppin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Http;
use Exception;
use Psy\Readline\Hoa\Console;
use Symfony\Component\Console\Logger\ConsoleLogger;
use Carbon\Carbon;
use Twilio\Rest\Client;

use function Laravel\Prompts\error;

class TripController extends Controller
{

    protected $auth;
    public function __construct()
    {
    }

    public function createTrip(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
            'start_address' => 'required|string|max:255',
            'stop_address' => 'nullable|string|max:255',
            'destination_address' => 'required|string|max:255',
            'trip_start_date' => 'required|date',
            'trip_end_date' => 'nullable|date|after_or_equal:trip_start_date',
            'trip_miles' => 'nullable|string',
            'trip_sound' => 'nullable|string',
            'trip_caption' => 'nullable|string',
            'droppins' => 'nullable|array',
            'droppins.*.stop_index' => 'nullable|integer',
            'droppins.*.image_path' => 'required|string',
            'droppins.*.image_caption' => 'required|string',
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
                'trip_start_date' => Carbon::parse($request->trip_start_date),
                'trip_end_date' => $request->trip_end_date ? Carbon::parse($request->trip_end_date) : null,
                'trip_miles' => $request->trip_miles,
                'trip_sound' => $request->trip_sound,
                'trip_caption' => $request->trip_caption
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
                'user:id,photo,rolla_username,first_name,last_name',
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
                    // Parse likes_user_id and fetch user details
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
}

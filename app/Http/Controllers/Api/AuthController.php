<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Models\Trip;
use App\Models\Droppin;
use Illuminate\Http\Request;
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

class AuthController extends Controller
{

    protected $auth;
    public function __construct()
    {
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|string|max:255|unique:users,email',
            'password' => 'required|string|min:6',
            'rolla_username' => 'required|string|max:255|unique:users,rolla_username',
            'hear_rolla' => 'required|string|max:100'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        try {
            $user = new User([
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'rolla_username' => $request->rolla_username,
                'hear_rolla' => $request->hear_rolla ?? 0,
            ]);

            $user->save();
            $token = $user->createToken('auth_token')->plainTextToken;
            return response()->json([
                'message' => 'Register success',
                'token' => $token,
                'userData' => $user,
                'statusCode' => 200
            ], 200);
        } catch (QueryException $e) {
            if ($e->errorInfo[1] == 1062) {
                return response()->json([
                    'error' => 'Duplicate entry for email or phone number',
                    'statusCode' => 409
                ], 409);
            }

            dd($e);

            return response()->json([
                'error' => 'An error occurred while saving the user',
                'statusCode' => 500
            ], 500);
        } catch (Exception $e) {
            // Catch any other exceptions
            return response()->json([
                'error' => 'An unexpected error occurred',
                'message' => $e->getMessage(),
                'statusCode' => 500
            ], 500);
        }
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'identifier' => 'required|string|max:255',
            'password' => 'required|string|max:255',
        ]);
    
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
    
        try {
            $user = User::where('email', $request->identifier)
                ->orWhere('rolla_username', $request->identifier)
                ->first();
    
            if ($user) {
                if (!Hash::check($request->password, $user->password)) {
                    return response()->json(['message' => 'Invalid credentials'], 401);
                } else {
                    $tripMilesSum = Trip::where('user_id', $user->id)
                        ->get()
                        ->reduce(function ($sum, $trip) {
                            $miles = floatval(str_replace('km', '', $trip->trip_miles));
                            return $sum + $miles;
                        }, 0);
                    $tripMilesSum = number_format($tripMilesSum, 1, '.', '');

                    $trips = Trip::where('user_id', $user->id)
                                    ->where(function ($query) {
                                        $query->where('trip_end_date', '>=', now())
                                            ->orWhereNull('trip_end_date');
                                    })
                                    ->get();

                    $droppins = Droppin::whereIn(
                            'trip_id',
                            Trip::where('user_id', $user->id)->pluck('id')
                        )->get()
                        ->map(function ($droppin) {
                            $likesUserIds = collect(explode(',', $droppin->likes_user_id))
                                ->filter()
                                ->map(fn($id) => intval(trim($id)))
                                ->unique();
        
                            $likedUsers = User::whereIn('id', $likesUserIds)
                                ->select('id', 'photo', 'first_name', 'last_name', 'rolla_username')
                                ->get();
        
                            $droppin->liked_users = $likedUsers;
        
                            return $droppin;
                        });

                    $totalTrips = Trip::where('user_id', $user->id)->count();
                    $followingUsers = $user->getFollowingUsers();
                    $followingCount = collect(explode(',', $user->following_user_id))
                        ->filter()
                        ->map(fn($id) => intval(trim($id)))
                        ->unique()
                        ->count();
                    $garageDetails = $user->getGarageDetails();
                    $token = $user->createToken('auth_token')->plainTextToken;
    
                    return response()->json([
                        'message' => 'Login success',
                        'token' => $token,
                        'userData' => $user,
                        'trip_miles_sum' => $tripMilesSum,
                        'trips' => $trips,
                        'droppins' => $droppins,
                        'total_trips' => $totalTrips,
                        'following_users' => $followingUsers,
                        'following_count' => $followingCount,
                        'garages' => $garageDetails
                    ], 200);
                }
            } else {
                return response()->json(['message' => 'User does not exist'], 401);
            }
        } catch (Exception $e) {
            return response()->json(['message' => 'An error occurred during login'], 500);
        }
    }
    
    public function logout(Request $request)
    {
        
    }
}

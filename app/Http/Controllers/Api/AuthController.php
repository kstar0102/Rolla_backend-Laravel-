<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Models\Trip;
use App\Models\Droppin;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Http\Controllers\Controller;
use App\Notifications\PasswordResetCode;
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
use Illuminate\Support\Facades\Mail;


use function Laravel\Prompts\error;

class AuthController extends Controller
{

    protected $auth;
    public function __construct()
    {
    }
    public function forgotPassword(Request $request)
    {
        $request->validate(['email' => 'required|email']);
        $user = User::where('email', $request->email)->first();
        \Log::info('User found: ' . ($user ? $user->email : 'Not found'));
        if (!$user) {
            return response()->json([
                'statusCode' => false,
                'message'    => 'No account found with that email address.',
            ], 202);
        }
        $now = now();
        $lastSent = $user->reset_code_last_sent_at;
        $recent = $lastSent instanceof Carbon && $lastSent->gt($now->copy()->subMinute());
        if (!$recent) {
            $code = (string) random_int(100000, 999999);
            $user->forceFill([
                'reset_code_hash'         => Hash::make($code),
                'reset_code_expires_at'   => $now->copy()->addMinutes(10),
                'reset_code_attempts'     => 0,
                'reset_code_last_sent_at' => $now,
                'reset_token'             => null,
                'reset_token_expires_at'  => null,
            ])->save();
            try {
                $fromAddress = config('mail.from.address');
                $fromName    = config('mail.from.name');
                Mail::raw(
                    'Use this verification code to reset your password: **' . $code . '**. This code will expire in 10 minutes.', function ($message) use ($user, $fromAddress, $fromName) {
                    $message->from($fromAddress, $fromName);
                    $message->to($user->email)->subject('Your Rolla Password Reset Code');
                });
            } catch (\Throwable $e) {
                \Log::warning('Password code send failed: ' . $e->getMessage());
            }
        }
        return response()->json([
            'statusCode' => true,
            'message'    => 'If the email exists, a verification code has been sent.',
        ], 200);
    }
 
     public function verifyResetCode(Request $request)
     {
        $validated = $request->validate([
            'email' => 'required|email',
            'code'  => 'required|digits:6',
        ]);
        $user = User::where('email', $validated['email'])->first();
        if (!$user || !$user->reset_code_hash || !$user->reset_code_expires_at) {
            return response()->json(['statusCode' => false, 'message' => 'Invalid code or expired.'], 422);
        }
        if ($user->reset_code_attempts >= 5) {
            return response()->json(['statusCode' => false, 'message' => 'Too many attempts. Request a new code.'], 429);
        }
        if ($user->reset_code_expires_at->isPast()) {
            return response()->json(['statusCode' => false, 'message' => 'Code expired. Request a new code.'], 422);
        }
        $user->reset_code_attempts += 1;

        if (! Hash::check($validated['code'], $user->reset_code_hash)) {
            $user->save();
            return response()->json(['statusCode' => false, 'message' => 'Invalid code.'], 422);
        }
        $user->reset_token = Str::random(64);
        $user->reset_token_expires_at = now()->addMinutes(30);
        $user->reset_code_attempts = 0;
        $user->save();
        return response()->json([
            'statusCode' => true,
            'message'    => 'Code verified.',
            'reset_token'=> $user->reset_token,
        ]);
     }
 
    public function resetPassword(Request $request)
    {
        $validated = $request->validate([
            'email'        => 'required|email',
            'reset_token'  => 'required|string',
            'password'     => 'required|min:6|confirmed', 
        ]);
        $user = User::where('email', $validated['email'])->first();
        if (!$user || !$user->reset_token || !$user->reset_token_expires_at) {
            return response()->json(['statusCode' => false, 'message' => 'Invalid or expired reset token.'], 422);
        }
        if (! hash_equals($user->reset_token, $validated['reset_token']) ||
            $user->reset_token_expires_at->isPast()) {
            return response()->json(['statusCode' => false, 'message' => 'Invalid or expired reset token.'], 422);
        }
        $user->password = Hash::make($validated['password']);
        $user->reset_code_hash = null;
        $user->reset_code_expires_at = null;
        $user->reset_code_attempts = 0;
        $user->reset_code_last_sent_at = null;
        $user->reset_token = null;
        $user->reset_token_expires_at = null;
        $user->save();
        return response()->json([
            'statusCode' => true,
            'message'    => 'Password updated successfully.',
        ]);
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
                                        ->pluck('id')
                                        ->toArray();

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

<?php

namespace App\Http\Controllers\Api;

use App\Models\Driver;
use App\Models\User;
use App\Models\TmpPhone;
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
            'country' => 'required|string|max:255',
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
                'country' => $request->country,
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
            'identifier' => 'required|string|max:255', // Identifier can be email or username
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
                    $token = $user->createToken('auth_token')->plainTextToken;
                    return response()->json([
                        'message' => 'Login success',
                        'token' => $token,
                        'userData' => $user
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

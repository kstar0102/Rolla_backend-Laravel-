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

class TripController extends Controller
{

    protected $auth;
    public function __construct()
    {
    }

    public function getAllTrips(Request $request)
    {
        try {
            // Eager load only required fields
            $trips = Trip::with([
                'user:id,photo,rolla_username',
                'droppins',
                'comments.user:id,photo,rolla_username',
            ])->get();
    
            // Transform the trips
            $trips->transform(function ($trip) {
                $trip->user = $trip->user ? [
                    'id' => $trip->user->id,
                    'photo' => $trip->user->photo,
                    'rolla_username' => $trip->user->rolla_username,
                ] : null;
    
                $trip->comments->transform(function ($comment) {
                    if ($comment->user) {
                        $comment->user = [
                            'id' => $comment->user->id,
                            'photo' => $comment->user->photo,
                            'rolla_username' => $comment->user->rolla_username,
                        ];
                    }
                    return $comment;
                });
    
                return $trip;
            });
    
            // Return the response
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

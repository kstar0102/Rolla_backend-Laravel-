<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Models\Trip;
use App\Models\Comments;
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

class CommentController extends Controller
{

    protected $auth;
    public function __construct()
    {
    }
    
    public function createOrUpdateComment(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'trip_id' => 'required|exists:trips,id',
            'user_id' => 'required|exists:users,id',
            'content' => 'required|string|max:1000',
        ]);
    
        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation errors',
                'errors' => $validator->errors(),
            ], 422);
        }
    
        try {
            DB::beginTransaction();
    
            // Always create the comment
            $comment = Comments::create([
                'trip_id' => $request->trip_id,
                'user_id' => $request->user_id,
                'content' => $request->content,
            ]);
    
            // Get the trip owner
            $tripOwnerId = DB::table('trips')
                ->where('id', $request->trip_id)
                ->value('user_id');
    
            // Only notify if the commenter is NOT the trip owner
            $shouldNotify = ((int)$tripOwnerId !== (int)$request->user_id);
    
            if ($shouldNotify) {
                // Lock the user row to avoid lost updates on the JSON field
                $userRow = DB::table('users')
                    ->where('id', $tripOwnerId)
                    ->lockForUpdate()
                    ->first();
    
                $existingNotificationsJson = $userRow->comment_notification ?? null;
                $notifications = $existingNotificationsJson ? json_decode($existingNotificationsJson, true) : [];
    
                $notifications[] = [
                    'id' => (int)$request->user_id, // commenting user
                    'date' => Carbon::now()->toDateTimeString(),
                    'tripid' => (int)$request->trip_id,
                    'notificationBool' => false,
                    'viewedBool' => false,
                    'clickedBool' => false,
                ];
    
                DB::table('users')
                    ->where('id', $tripOwnerId)
                    ->update([
                        'comment_notification' => json_encode($notifications),
                    ]);
            }
    
            DB::commit();
    
            return response()->json([
                'message' => 'Comment added successfully',
                'comment' => $comment,
                'notified' => $shouldNotify, // helpful flag for the client
            ], 200);
    
        } catch (QueryException $qe) {
            DB::rollBack();
            return response()->json([
                'message' => 'Failed to process comment',
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
    
}

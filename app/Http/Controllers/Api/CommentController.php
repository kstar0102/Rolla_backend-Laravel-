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

            $comment = Comments::where('trip_id', $request->trip_id)
                ->where('user_id', $request->user_id)
                ->first();

            if ($comment) {
                $comment->content = $request->content;
                $comment->save();
                $message = 'Comment updated successfully';
            } else {
                $comment = Comments::create([
                    'trip_id' => $request->trip_id,
                    'user_id' => $request->user_id,
                    'content' => $request->content,
                ]);
                $message = 'Comment added successfully';
            }

            // Get trip owner ID
            $tripOwnerId = DB::table('trips')
                ->where('id', $request->trip_id)
                ->value('user_id');

            // Fetch existing notifications
            $existingNotificationsJson = DB::table('users')
                ->where('id', $tripOwnerId)
                ->value('comment_notification');

            $notifications = $existingNotificationsJson ? json_decode($existingNotificationsJson, true) : [];

            // Append new notification
            $notifications[] = [
                'id' => $request->user_id, // Commenting user
                'date' => Carbon::now()->toDateTimeString(),
                'tripid' => $request->trip_id,
                'notificationBool' => false,
            ];

            // Update user with new notifications
            DB::table('users')
                ->where('id', $tripOwnerId)
                ->update([
                    'comment_notification' => json_encode($notifications),
                ]);

            DB::commit();

            return response()->json([
                'message' => $message,
                'comment' => $comment,
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


    // public function createOrUpdateComment(Request $request)
    // {
    //     $validator = Validator::make($request->all(), [
    //         'trip_id' => 'required|exists:trips,id',
    //         'user_id' => 'required|exists:users,id',
    //         'content' => 'required|string|max:1000',
    //     ]);
    
    //     if ($validator->fails()) {
    //         return response()->json([
    //             'message' => 'Validation errors',
    //             'errors' => $validator->errors(),
    //         ], 422);
    //     }
    
    //     try {
    //         $comment = Comments::where('trip_id', $request->trip_id)
    //             ->where('user_id', $request->user_id)
    //             ->first();
    
    //         if ($comment) {
    //             $comment->content = $request->content;
    //             $comment->save();
    
    //             return response()->json([
    //                 'message' => 'Comment updated successfully',
    //                 'comment' => $comment,
    //             ], 200);
    //         } else {
    //             $comment = Comments::create([
    //                 'trip_id' => $request->trip_id,
    //                 'user_id' => $request->user_id,
    //                 'content' => $request->content,
    //             ]);
    
    //             return response()->json([
    //                 'message' => 'Comment added successfully',
    //                 'comment' => $comment,
    //             ], 200);
    //         }
    //     } catch (QueryException $qe) {
    //         return response()->json([
    //             'message' => 'Failed to process comment',
    //             'error' => $qe->getMessage(),
    //         ], 500);
    //     } catch (Exception $e) {
    //         return response()->json([
    //             'message' => 'An unexpected error occurred',
    //             'error' => $e->getMessage(),
    //         ], 500);
    //     }
    // }    
}

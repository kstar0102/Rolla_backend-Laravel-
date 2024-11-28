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
            $comment = Comments::where('trip_id', $request->trip_id)
                ->where('user_id', $request->user_id)
                ->first();
    
            if ($comment) {
                $comment->content = $request->content;
                $comment->save();
    
                return response()->json([
                    'message' => 'Comment updated successfully',
                    'comment' => $comment,
                ], 200);
            } else {
                $comment = Comments::create([
                    'trip_id' => $request->trip_id,
                    'user_id' => $request->user_id,
                    'content' => $request->content,
                ]);
    
                return response()->json([
                    'message' => 'Comment added successfully',
                    'comment' => $comment,
                ], 200);
            }
        } catch (QueryException $qe) {
            return response()->json([
                'message' => 'Failed to process comment',
                'error' => $qe->getMessage(),
            ], 500);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'An unexpected error occurred',
                'error' => $e->getMessage(),
            ], 500);
        }
    }    
}

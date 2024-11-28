<?php

namespace App\Http\Controllers\Api;

use App\Models\CarType;
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

class CarTypeController extends Controller
{

    protected $auth;
    public function __construct()
    {
    }

    public function getCarTypes()
    {
        try {
            $carTypes = CarType::all();
    
            return response()->json([
                'status' => 'success',
                'data' => $carTypes,
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to fetch car types.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }    
}

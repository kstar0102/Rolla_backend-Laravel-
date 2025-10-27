<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Test;

class TestController extends Controller
{
    // CREATE
    public function store(Request $request)
    {
        $request->validate([
            'token' => 'required|string',
            'date' => 'required|date',
        ]);

        $test = Test::create($request->all());
        return response()->json($test, 201);
    }

    // READ (all)
    public function index()
    {
        return response()->json(Test::all());
    }

    // READ (single)
    public function show($id)
    {
        $test = Test::find($id);
        if (!$test) {
            return response()->json(['message' => 'Not found'], 404);
        }
        return response()->json($test);
    }

    // UPDATE
    public function update(Request $request, $id)
    {
        $test = Test::find($id);
        if (!$test) {
            return response()->json(['message' => 'Not found'], 404);
        }

        $test->update($request->only(['token', 'date']));
        return response()->json($test);
    }

    // DELETE
    public function destroy($id)
    {
        $test = Test::find($id);
        if (!$test) {
            return response()->json(['message' => 'Not found'], 404);
        }

        $test->delete();
        return response()->json(['message' => 'Deleted successfully']);
    }

   // TestController.php
    public function latestRecord()
    {
        $test = Test::latest()->first();   // or ->latest()->first()

        if (!$test) {
            return response()->json(['message' => 'No records found'], 504);
        }
        return response()->json($test);
    }

}
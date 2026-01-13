<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\TermsAndConditions;
use Illuminate\Http\Request;

class TermsAndConditionsController extends Controller
{
    /**
     * Get the active Terms and Conditions.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getTermsAndConditions(Request $request)
    {
        try {
            // Get the active Terms and Conditions
            $terms = TermsAndConditions::where('is_active', true)
                ->orderBy('created_at', 'desc')
                ->first();

            if (!$terms) {
                return response()->json([
                    'statusCode' => false,
                    'message' => 'Terms and Conditions not found',
                ], 404);
            }

            return response()->json([
                'statusCode' => true,
                'message' => 'Terms and Conditions retrieved successfully',
                'data' => [
                    'title' => $terms->title,
                    'content' => $terms->content,
                    'content_type' => $terms->content_type ?? 'text',
                    'pdf_url' => $terms->pdf_url,
                ],
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'statusCode' => false,
                'message' => 'Error retrieving Terms and Conditions: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Create or update Terms and Conditions (Admin only - you can add middleware later).
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function createOrUpdate(Request $request)
    {
        try {
            $validated = $request->validate([
                'title' => 'required|string|max:255',
                'content' => 'required|string',
                'content_type' => 'nullable|string|in:text,html',
                'pdf_url' => 'nullable|url',
                'is_active' => 'nullable|boolean',
            ]);

            // Deactivate all existing terms
            TermsAndConditions::where('is_active', true)->update(['is_active' => false]);

            // Create new terms
            $terms = TermsAndConditions::create([
                'title' => $validated['title'],
                'content' => $validated['content'],
                'content_type' => $validated['content_type'] ?? 'text',
                'pdf_url' => $validated['pdf_url'] ?? null,
                'is_active' => $validated['is_active'] ?? true,
            ]);

            return response()->json([
                'statusCode' => true,
                'message' => 'Terms and Conditions saved successfully',
                'data' => $terms,
            ], 201);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'statusCode' => false,
                'message' => 'Validation error',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'statusCode' => false,
                'message' => 'Error saving Terms and Conditions: ' . $e->getMessage(),
            ], 500);
        }
    }
}

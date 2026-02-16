<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreDocumentCheckRequest;
use App\Models\DocumentCheck;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class DocumentCheckController extends Controller
{
    /**
     * Store or update Document Check scores for the authenticated user's school
     */
    /**
     * Store or update Document Check scores
     * Expects payload:
     * {
     *   "school": "MHS-001",          // required school code
     *   "scores": { "1": 2, "2": 1, ... }  // question_id => score (0,1,2)
     * }
     */
    public function store(StoreDocumentCheckRequest $request): JsonResponse
    {
        $validated = $request->validated();

        // Get school code directly from payload (required by validation)
        $schoolCode = $validated['school'];

        // Optional: extra check if school exists (uncomment if needed)
        // if (!School::where('school_code', $schoolCode)->exists()) {
        //     return response()->json([
        //         'message' => 'Invalid school code',
        //         'errors'  => ['school' => ['The selected school code does not exist.']],
        //     ], 422);
        // }

        $savedCount = 0;

        foreach ($validated['scores'] as $question => $score) {
            if ($score !== null) {
                DocumentCheck::updateOrCreate(
                    [
                        'school'   => $schoolCode,
                        'question' => $question,
                    ],
                    [
                        'score' => $score,
                        'date'  => now(),
                    ]
                );
                $savedCount++;
            }
        }

        return response()->json([
            'message'      => 'Document check scores saved successfully',
            'school'       => $schoolCode,
            'saved_count'  => $savedCount,
            'total_scores' => count($validated['scores']),
        ], 200);
    }

    /**
     * Optional: Get existing scores for the current school
     */
    public function show(): JsonResponse
    {
        $schoolCode = Auth::user()->school_code ?? session('school');

        if (!$schoolCode) {
            return response()->json(['error' => 'School code not found'], 400);
        }

        $scores = DocumentCheck::where('school', $schoolCode)
            ->pluck('score', 'question')
            ->toArray();

        return response()->json([
            'school' => $schoolCode,
            'scores' => $scores,
        ]);
    }
}

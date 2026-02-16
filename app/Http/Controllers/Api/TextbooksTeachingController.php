<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTextbooksTeachingRequest;
use App\Models\TextbooksTeaching;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class TextbooksTeachingController extends Controller
{
    /**
     * Store or update Textbooks & Teaching Materials module scores
     * Payload example:
     * {
     *   "school": "MHS-001",
     *   "scores": { "Q1": 1, "Q2": 0, "Q3": 1 }
     * }
     */
    public function store(StoreTextbooksTeachingRequest $request): JsonResponse
    {
        $validated = $request->validated();

        $schoolCode = $validated['school'];
        $savedCount = 0;

        foreach ($validated['scores'] as $question => $score) {
            if ($score !== null) {
                TextbooksTeaching::updateOrCreate(
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
            'message'      => 'Textbooks & teaching materials scores saved successfully',
            'school'       => $schoolCode,
            'saved_count'  => $savedCount,
            'total_scores' => count($validated['scores']),
        ], 200);
    }

    /**
     * Fetch existing textbooks & teaching materials scores for a school
     */
    public function show(Request $request): JsonResponse
    {
        $schoolCode = $request->query('school');

        if (!$schoolCode) {
            return response()->json(['error' => 'School code is required'], 400);
        }

        $scores = TextbooksTeaching::where('school', $schoolCode)
            ->pluck('score', 'question')
            ->toArray();

        return response()->json([
            'school' => $schoolCode,
            'scores' => $scores,
        ]);
    }
}

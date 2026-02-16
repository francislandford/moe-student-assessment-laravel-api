<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreLeadershipRequest;
use App\Models\Leadership;
use Illuminate\Http\JsonResponse;

class LeadershipController extends Controller
{
    /**
     * Store or update Leadership module scores
     * Payload example:
     * {
     *   "school": "MHS-001",
     *   "scores": { "3.1": 2, "3.2": 1, "3.3": 0, ... }
     * }
     */
    public function store(StoreLeadershipRequest $request): JsonResponse
    {
        $validated = $request->validated();

        $schoolCode = $validated['school'];

        $savedCount = 0;

        foreach ($validated['scores'] as $question => $score) {
            if ($score !== null) {
                Leadership::updateOrCreate(
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
            'message'      => 'School Leadership scores saved successfully',
            'school'       => $schoolCode,
            'saved_count'  => $savedCount,
            'total_scores' => count($validated['scores']),
        ], 200);
    }

    /**
     * Optional: Fetch existing scores for a school
     */
    public function show(Request $request): JsonResponse
    {
        $schoolCode = $request->query('school');

        if (!$schoolCode) {
            return response()->json(['error' => 'School code is required'], 400);
        }

        $scores = Leadership::where('school', $schoolCode)
            ->pluck('score', 'question')
            ->toArray();

        return response()->json([
            'school' => $schoolCode,
            'scores' => $scores,
        ]);
    }
}

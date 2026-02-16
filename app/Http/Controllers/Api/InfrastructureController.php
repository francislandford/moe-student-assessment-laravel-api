<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreInfrastructureRequest;
use App\Models\Infrastructure;
use Illuminate\Http\JsonResponse;

class InfrastructureController extends Controller
{
    /**
     * Store or update Physical Infrastructure module scores
     * Payload example:
     * {
     *   "school": "MHS-001",
     *   "scores": { "Q1": 1, "Q2": 0, ... }
     * }
     */
    public function store(StoreInfrastructureRequest $request): JsonResponse
    {
        $validated = $request->validated();

        $schoolCode = $validated['school'];

        $savedCount = 0;

        foreach ($validated['scores'] as $question => $score) {
            if ($score !== null) {
                Infrastructure::updateOrCreate(
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
            'message'      => 'Physical infrastructure scores saved successfully',
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

        $scores = Infrastructure::where('school', $schoolCode)
            ->pluck('score', 'question')
            ->toArray();

        return response()->json([
            'school' => $schoolCode,
            'scores' => $scores,
        ]);
    }
}

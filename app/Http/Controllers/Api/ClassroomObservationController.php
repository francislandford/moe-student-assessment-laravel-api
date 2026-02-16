<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreClassroomObservationRequest;
use App\Models\ClassroomObservation;
use Illuminate\Http\JsonResponse;

class ClassroomObservationController extends Controller
{
    /**
     * Store or update Classroom Observation scores
     * Payload example (for one classroom):
     * {
     *   "school": "MHS-001",
     *   "class_num": 1,
     *   "grade": "Grade 7",
     *   "subject": "Mathematics",
     *   "teacher": "Mr. James Togba",
     *   "scores": { "Q1": 1, "Q2": 0, ... }
     * }
     */
    public function store(StoreClassroomObservationRequest $request): JsonResponse
    {
        $validated = $request->validated();

        $schoolCode = $validated['school'];
        $classNum   = $validated['class_num'];

        $savedCount = 0;

        foreach ($validated['scores'] as $question => $score) {
            if ($score !== null) {
                ClassroomObservation::updateOrCreate(
                    [
                        'school'    => $schoolCode,
                        'class_num' => $classNum,
                        'question'  => $question,
                    ],
                    [
                        'grade'     => $validated['grade'] ?? null,
                        'subject'   => $validated['subject'] ?? null,
                        'teacher'   => $validated['teacher'] ?? null,
                        'nb_male'   => $validated['nb_male'] ?? null,
                        'nb_female'   => $validated['nb_female'] ?? null,
                        'score'     => $score,
                        'date'      => now(),
                    ]
                );
                $savedCount++;
            }
        }

        return response()->json([
            'message'      => "Classroom $classNum observation saved successfully",
            'school'       => $schoolCode,
            'class_num'    => $classNum,
            'saved_count'  => $savedCount,
            'total_scores' => count($validated['scores']),
        ], 200);
    }

    /**
     * Optional: Fetch scores for a specific school and class number
     */
    public function show(Request $request): JsonResponse
    {
        $schoolCode = $request->query('school');
        $classNum   = $request->query('class_num');

        if (!$schoolCode || !$classNum) {
            return response()->json(['error' => 'School code and class number are required'], 400);
        }

        $records = ClassroomObservation::where('school', $schoolCode)
            ->where('class_num', $classNum)
            ->get(['question', 'score', 'grade', 'subject', 'teacher', 'date']);

        $scores = $records->pluck('score', 'question')->toArray();
        $metadata = $records->isNotEmpty() ? $records->first()->only(['grade', 'subject', 'teacher']) : [];

        return response()->json([
            'school'     => $schoolCode,
            'class_num'  => $classNum,
            'scores'     => $scores,
            'metadata'   => $metadata,
        ]);
    }
}

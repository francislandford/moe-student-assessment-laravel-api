<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\LevelClass;
use App\Models\Subject;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class LevelAndSubjectController extends Controller
{
    /**
     * Get all grades/classes for a specific school level
     * GET /api/grades?level=Primary
     */
    public function getGrades(Request $request): JsonResponse
    {
        $level = $request->query('level');

        if (!$level) {
            return response()->json(['error' => 'School level is required'], 400);
        }

        $grades = LevelClass::where('level', $level)
            ->orderBy('name')
            ->get(['id', 'name']);

        return response()->json($grades);
    }

    /**
     * Get all subjects for a specific school level
     * GET /api/subjects?level=Primary
     */
    public function getSubjects(Request $request): JsonResponse
    {
        $level = $request->query('level');

        if (!$level) {
            return response()->json(['error' => 'School level is required'], 400);
        }

        $subjects = Subject::where('level', $level)
            ->orderBy('name')
            ->get(['id', 'name']);

        return response()->json($subjects);
    }
}

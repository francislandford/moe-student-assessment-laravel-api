<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreSchoolLevelRequest;
use App\Http\Resources\SchoolLevelResource;
use App\Models\SchoolLevel;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SchoolLevelController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $levels = SchoolLevel::query()
            ->when($request->search, fn($q) => $q->where('name', 'like', "%{$request->search}%")
                ->orWhere('code', 'like', "%{$request->search}%"))
            ->orderBy('name')
            ->get(); // Usually small list → no pagination needed

        return response()->json(SchoolLevelResource::collection($levels));
    }

    public function store(StoreSchoolLevelRequest $request): JsonResponse
    {
        $level = SchoolLevel::create($request->validated());

        return response()->json(new SchoolLevelResource($level), 201);
    }

    public function show(SchoolLevel $schoolLevel): JsonResponse
    {
        return response()->json(new SchoolLevelResource($schoolLevel));
    }

    // Optional: add update/destroy if admins need to edit levels
}

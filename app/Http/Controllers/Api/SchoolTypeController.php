<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreSchoolTypeRequest;
use App\Http\Resources\SchoolTypeResource;
use App\Models\SchoolType;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SchoolTypeController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $types = SchoolType::query()
            ->when($request->search, fn($q) => $q->where('name', 'like', "%{$request->search}%"))
            ->orderBy('name')
            ->get(); // usually small list → no pagination needed

        return response()->json(SchoolTypeResource::collection($types));
    }

    public function store(StoreSchoolTypeRequest $request): JsonResponse
    {
        $type = SchoolType::create($request->validated());

        return response()->json(new SchoolTypeResource($type), 201);
    }

    public function show(SchoolType $schoolType): JsonResponse
    {
        return response()->json(new SchoolTypeResource($schoolType));
    }

    // Optional: update and destroy can be added if admins need to edit types
}

<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreSchoolOwnershipRequest;
use App\Http\Resources\SchoolOwnershipResource;
use App\Models\SchoolOwnership;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SchoolOwnershipController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $ownerships = SchoolOwnership::query()
            ->when($request->search, fn($q) => $q->where('name', 'like', "%{$request->search}%"))
            ->orderBy('name')
            ->get(); // Small list → no pagination needed

        return response()->json(SchoolOwnershipResource::collection($ownerships));
    }

    public function store(StoreSchoolOwnershipRequest $request): JsonResponse
    {
        $ownership = SchoolOwnership::create($request->validated());

        return response()->json(new SchoolOwnershipResource($ownership), 201);
    }

    public function show(SchoolOwnership $schoolOwnership): JsonResponse
    {
        return response()->json(new SchoolOwnershipResource($schoolOwnership));
    }

    // Optional: update/destroy if admins need to edit
}

<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCountyRequest;
use App\Http\Resources\CountyResource;
use App\Models\County;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CountyController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $counties = County::query()
            ->when($request->search, fn($q) => $q->where('county', 'like', "%{$request->search}%"))
            ->orderBy('county')
            ->get();   // Usually only 15 records → no pagination needed

        return response()->json(CountyResource::collection($counties));
    }

    public function store(StoreCountyRequest $request): JsonResponse
    {
        $county = County::create($request->validated());

        return response()->json(new CountyResource($county), 201);
    }

    public function show(County $county): JsonResponse
    {
        return response()->json(new CountyResource($county));
    }

    // Optional: update / destroy methods if needed (rare for counties)
}

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
        // Get the authenticated user's county
        $county = $request->user()->cat;  // or auth()->user()->cat

        $countyModel = County::where('county', '=', $county)->first();

        // Check if county exists
        if (!$countyModel) {
            return response()->json([
                'message' => 'County not found'
            ], 404);
        }

        // Use new for single resource, not collection
        return response()->json(new CountyResource($countyModel));
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

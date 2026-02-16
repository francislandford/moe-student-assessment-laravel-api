<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreDistrictRequest;
use App\Http\Resources\DistrictResource;
use App\Models\District;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DistrictController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $districts = District::query()
            ->when($request->county, fn($q) => $q->where('county', $request->county))
            ->when($request->search, fn($q) => $q->where('d_name', 'like', "%{$request->search}%"))
            ->orderBy('county')
            ->orderBy('d_name')
            ->get();   // or paginate(50) if list grows large

        return response()->json(DistrictResource::collection($districts));
    }

    public function store(StoreDistrictRequest $request): JsonResponse
    {
        $district = District::create($request->validated());

        return response()->json(new DistrictResource($district), 201);
    }

    public function show(District $district): JsonResponse
    {
        return response()->json(new DistrictResource($district));
    }

    // Optional: update/destroy if admins need to edit districts
}

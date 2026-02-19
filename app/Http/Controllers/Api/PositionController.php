<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Position;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;

class PositionController extends Controller
{
    /**
     * Display a listing of positions
     */
    public function index(): JsonResponse
    {
        $positions = Position::orderBy('name')->get(['id', 'name']);

        return response()->json([
            'success' => true,
            'data' => $positions,
            'message' => 'Positions retrieved successfully'
        ]);
    }

    /**
     * Store a newly created position
     */
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:positions'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $position = Position::create($validator->validated());

        return response()->json([
            'success' => true,
            'data' => ['id' => $position->id, 'name' => $position->name],
            'message' => 'Position created successfully'
        ], 201);
    }

    /**
     * Display the specified position
     */
    public function show(Position $position): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => ['id' => $position->id, 'name' => $position->name],
            'message' => 'Position retrieved successfully'
        ]);
    }

    /**
     * Update the specified position
     */
    public function update(Request $request, Position $position): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:positions,name,' . $position->id
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $position->update($validator->validated());

        return response()->json([
            'success' => true,
            'data' => ['id' => $position->id, 'name' => $position->name],
            'message' => 'Position updated successfully'
        ]);
    }

    /**
     * Remove the specified position
     */
    public function destroy(Position $position): JsonResponse
    {
        $position->delete();

        return response()->json([
            'success' => true,
            'message' => 'Position deleted successfully'
        ]);
    }

    /**
     * Get positions formatted for dropdown
     */
    public function getForDropdown(): JsonResponse
    {
        $positions = Position::getForDropdown();

        return response()->json([
            'success' => true,
            'data' => $positions,
            'message' => 'Positions retrieved successfully'
        ]);
    }
}

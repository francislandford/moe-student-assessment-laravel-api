<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Fee;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;

class FeeController extends Controller
{
    /**
     * Display a listing of fees
     */
    public function index(): JsonResponse
    {
        $fees = Fee::orderBy('id')->get(['id', 'fee']);

        return response()->json([
            'success' => true,
            'data' => $fees,
            'message' => 'Fees retrieved successfully'
        ]);
    }

    /**
     * Store a newly created fee
     */
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'fee' => 'required|string|max:255|unique:fees'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $fee = Fee::create($validator->validated());

        return response()->json([
            'success' => true,
            'data' => ['id' => $fee->id, 'name' => $fee->name],
            'message' => 'Fee created successfully'
        ], 201);
    }

    /**
     * Display the specified fee
     */
    public function show(Fee $fee): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => ['id' => $fee->id, 'name' => $fee->name],
            'message' => 'Fee retrieved successfully'
        ]);
    }

    /**
     * Update the specified fee
     */
    public function update(Request $request, Fee $fee): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:fees,name,' . $fee->id
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $fee->update($validator->validated());

        return response()->json([
            'success' => true,
            'data' => ['id' => $fee->id, 'name' => $fee->name],
            'message' => 'Fee updated successfully'
        ]);
    }

    /**
     * Remove the specified fee
     */
    public function destroy(Fee $fee): JsonResponse
    {
        $fee->delete();

        return response()->json([
            'success' => true,
            'message' => 'Fee deleted successfully'
        ]);
    }

    /**
     * Get fees formatted for dropdown
     */
    public function getForDropdown(): JsonResponse
    {
        $fees = Fee::getForDropdown();

        return response()->json([
            'success' => true,
            'data' => $fees,
            'message' => 'Fees retrieved successfully'
        ]);
    }
}

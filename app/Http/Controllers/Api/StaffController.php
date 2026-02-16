<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Staff;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;

class StaffController extends Controller
{
    /**
     * Display a listing of staff records.
     */
    public function index(): JsonResponse
    {
        $staff = Staff::latest()->get();
        return response()->json([
            'success' => true,
            'data'    => $staff,
        ]);
    }

    /**
     * Store a newly created staff record.
     */
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'school'       => 'required|string|max:100',
            'fname'        => 'required|string|max:200',
            'gender'       => 'nullable|string|max:20|in:Male,Female,Other',
            'position'     => 'nullable|string|max:100',
            'week_load'    => 'nullable|integer|min:0',
            'present'      => 'nullable|string|max:11',
            'bio_id'       => 'nullable|string|max:200',
            'pay_id'       => 'nullable|string|max:150',
            'qualification'=> 'nullable|string',
            'date'         => 'nullable|date',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors'  => $validator->errors(),
            ], 422);
        }

        $record = Staff::create($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Staff record created',
            'data'    => $record,
        ], 201);
    }

    /**
     * Display the specified staff record.
     */
    public function show(Staff $staff): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data'    => $staff,
        ]);
    }

    /**
     * Update the specified staff record.
     */
    public function update(Request $request, Staff $staff): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'school'       => 'sometimes|required|string|max:100',
            'fname'        => 'sometimes|required|string|max:200',
            'gender'       => 'nullable|string|max:20|in:Male,Female,Other',
            'position'     => 'nullable|string|max:100',
            'week_load'    => 'nullable|integer|min:0',
            'present'      => 'nullable|string|max:11',
            'bio_id'       => 'nullable|string|max:200',
            'pay_id'       => 'nullable|string|max:150',
            'qualification'=> 'nullable|string',
            'date'         => 'nullable|date',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors'  => $validator->errors(),
            ], 422);
        }

        $staff->update($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Staff record updated',
            'data'    => $staff,
        ]);
    }

    /**
     * Remove the specified staff record.
     */
    public function destroy(Staff $staff): JsonResponse
    {
        $staff->delete();

        return response()->json([
            'success' => true,
            'message' => 'Staff record deleted',
        ]);
    }
}

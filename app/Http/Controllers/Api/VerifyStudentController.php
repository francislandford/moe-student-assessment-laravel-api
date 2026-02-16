<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\VerifyStudent;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;

class VerifyStudentController extends Controller
{
    /**
     * Display a listing of student verification records.
     */
    public function index(): JsonResponse
    {
        $records = VerifyStudent::latest()->get();
        return response()->json([
            'success' => true,
            'data'    => $records,
        ]);
    }

    /**
     * Store a newly created student verification record.
     */
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'school'       => 'required|string|max:50',
            'classes'      => 'nullable|string|max:20',
            'emis_male'    => 'nullable|integer|min:0',
            'count_male'   => 'nullable|integer|min:0',
            'emis_female'  => 'nullable|integer|min:0',
            'count_female' => 'nullable|integer|min:0',
            'date'         => 'nullable|date',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors'  => $validator->errors(),
            ], 422);
        }

        $record = VerifyStudent::create($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Student verification record created',
            'data'    => $record,
        ], 201);
    }

    /**
     * Display the specified student verification record.
     */
    public function show(VerifyStudent $verifyStudent): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data'    => $verifyStudent,
        ]);
    }

    /**
     * Update the specified student verification record.
     */
    public function update(Request $request, VerifyStudent $verifyStudent): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'school'       => 'sometimes|required|string|max:50',
            'classes'      => 'nullable|string|max:20',
            'emis_male'    => 'nullable|integer|min:0',
            'count_male'   => 'nullable|integer|min:0',
            'emis_female'  => 'nullable|integer|min:0',
            'count_female' => 'nullable|integer|min:0',
            'date'         => 'nullable|date',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors'  => $validator->errors(),
            ], 422);
        }

        $verifyStudent->update($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Student verification record updated',
            'data'    => $verifyStudent,
        ]);
    }

    /**
     * Remove the specified student verification record.
     */
    public function destroy(VerifyStudent $verifyStudent): JsonResponse
    {
        $verifyStudent->delete();

        return response()->json([
            'success' => true,
            'message' => 'Student verification record deleted',
        ]);
    }
}

<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Absent;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;

class AbsentController extends Controller
{
    /**
     * Store a newly created absence record.
     */
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'school'   => 'required|string|max:100',
            'fname'    => 'required|string|max:200',
            'bio_id'   => 'nullable|string|max:200',
            'pay_id'   => 'nullable|string|max:150',
            'reason'   => 'nullable|string',
            'excuse'   => 'nullable|string|max:50',
            'date'     => 'nullable|date',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors'  => $validator->errors(),
            ], 422);
        }

        $absent = Absent::create($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Absence record created successfully',
            'data'    => $absent,
        ], 201);
    }
}

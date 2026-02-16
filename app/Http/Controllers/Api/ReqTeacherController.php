<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ReqTeacher;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;

class ReqTeacherController extends Controller
{
    /**
     * Store a newly created required teachers record.
     */
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'school'       => 'required|string|max:50',
            'level'        => 'nullable|string|max:20',
            'self_contain' => 'nullable|string|max:20',
            'ass_teacher'  => 'nullable|integer|min:0',
            'volunteers'   => 'nullable|integer|min:0',
            'students'     => 'nullable|integer|min:0',
            'num_req'      => 'nullable|integer|min:0',
            'date'         => 'nullable|date',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors'  => $validator->errors(),
            ], 422);
        }

        $record = ReqTeacher::create($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Required teachers record created',
            'data'    => $record,
        ], 201);
    }
}

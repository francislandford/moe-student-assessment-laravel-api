<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\FeePaid;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;

class FeePaidController extends Controller
{
    /**
     * Store a newly created fee payment record.
     */
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'school'  => 'required|string|max:50',
            'fee'     => 'nullable|string|max:100',
            'pay'     => 'nullable|string|max:20',
            'purpose' => 'nullable|string',
            'amount'  => 'nullable|numeric|min:0',
            'date'    => 'nullable|date',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors'  => $validator->errors(),
            ], 422);
        }

        $record = FeePaid::create($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Fee payment recorded successfully',
            'data'    => $record,
        ], 201);
    }
}

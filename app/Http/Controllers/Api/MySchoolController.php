<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\SchoolResource;
use App\Models\School;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class MySchoolController extends Controller
{
    /**
     * Get all schools submitted by the authenticated user
     */
    public function index(): JsonResponse
    {
        $userId = Auth::id();

        $schools = School::where('collector', $userId)->orderBy('id', 'desc')->get();

        return response()->json([
            'success' => true,
            'data' => SchoolResource::collection($schools),
        ]);
    }
}

<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreSchoolRequest;
use App\Http\Resources\SchoolResource;
use App\Models\Question;
use App\Models\School;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SchoolController extends Controller
{
    /**
     * Display a listing of all schools (existing method - unchanged)
     */
    public function index(Request $request): JsonResponse
    {
        $schools = School::query()
            ->when($request->search, fn($q) => $q->where('school_name', 'like', "%{$request->search}%")
                ->orWhere('school_code', 'like', "%{$request->search}%"))
            ->when($request->county, fn($q) => $q->where('county', $request->county))
            ->when($request->district, fn($q) => $q->where('district', $request->district))
            ->latest()
            ->paginate(20);

        return response()->json([
            'data' => SchoolResource::collection($schools),
            'meta' => [
                'current_page' => $schools->currentPage(),
                'last_page' => $schools->lastPage(),
                'total' => $schools->total(),
            ]
        ]);
    }

    /**
     * Store a newly created school (UPDATED: auto-assign collector = authenticated user)
     */
    public function store(StoreSchoolRequest $request): JsonResponse
    {
        $validated = $request->validated();

        // Automatically assign the authenticated user as collector
        $validated['collector'] = Auth::id();

        $school = School::create($validated);

        return response()->json(new SchoolResource($school), 201);
    }

    /**
     * Display the specified school (unchanged)
     */
    public function show(School $school): JsonResponse
    {
        return response()->json(new SchoolResource($school));
    }

    /**
     * Fetch questions by category (unchanged)
     */
    public function questions(Request $request)
    {
        $category = $request->query('cat', 'Document check');
        $questions = Question::where('cat', $category)->orderBy('id')->get(['id', 'name']);

        return response()->json($questions);
    }

    /**
     * NEW: Fetch all schools submitted by the authenticated user (where collector = user ID)
     */

    public function myquestions()
    {
        return response()->json('francis');
    }
}

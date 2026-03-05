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
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

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
        try {
            $validated = $request->validated();

            // Automatically assign the authenticated user as collector
            $validated['collector'] = Auth::id();

            // Check if school with this school_code already exists
            $school = School::where('school_code', $validated['school_code'])->first();

            if ($school) {
                // Update existing school
                $school->update($validated);
                $message = 'School updated successfully';
                $statusCode = 200; // OK for update
                Log::info('School updated', ['school_code' => $school->school_code, 'user_id' => Auth::id()]);
            } else {
                // Create new school
                $school = School::create($validated);
                $message = 'School created successfully';
                $statusCode = 201; // Created for new school
                Log::info('School created', ['school_code' => $school->school_code, 'user_id' => Auth::id()]);
            }

            return response()->json([
                'success' => true,
                'message' => $message,
                'data' => new SchoolResource($school)
            ], $statusCode);

        } catch (\Exception $e) {
            Log::error('School store/update error: ' . $e->getMessage());
            Log::error($e->getTraceAsString());

            return response()->json([
                'success' => false,
                'message' => 'Failed to save school data',
                'error' => $e->getMessage()
            ], 500);
        }
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

    public function updateVerification(Request $request)
    {
        // Validate the request
        $validator = Validator::make($request->all(), [
            'school' => 'required|string|exists:schools,school_code',
            'teachers_present' => 'required|in:Yes,No',
            'verify_comment' => 'nullable|string|max:500',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            // Find the school by school_code
            $school = School::where('school_code', $request->school)->first();

            if (!$school) {
                return response()->json([
                    'success' => false,
                    'message' => 'School not found with code: ' . $request->school
                ], 404);
            }

            // Update the school with verification data
            $school->all_teacher_present = $request->teachers_present;
            $school->verify_comment = $request->verify_comment;

            $school->save();

            return response()->json([
                'success' => true,
                'message' => 'School verification data saved successfully',
                'data' => [
                    'school_code' => $school->school_code,
                    'school_name' => $school->school_name,
                    'all_teacher_present' => $school->teachers_present,
                    'verify_comment' => $school->verification_comment,
                ]
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update school verification data',
                'error' => $e->getMessage()
            ], 500);
        }
    }


    /**
     * Check if a school has complete data
     */
    public function checkCompleteness($schoolCode)
    {
        try {
            $school = School::where('school_code', $schoolCode)->first();

            if (!$school) {
                return response()->json([
                    'success' => false,
                    'message' => 'School not found'
                ], 404);
            }

            // Define all related tables that should have records for a complete school
            $tables = [
                'staff' => \App\Models\Staff::where('school', $schoolCode)->exists(),
                'doc_check' => \App\Models\DocumentCheck::where('school', $schoolCode)->exists(),
                'fees_paid' => \App\Models\FeePaid::where('school', $schoolCode)->exists(),
                'leadership' => \App\Models\Leadership::where('school', $schoolCode)->exists(),
                'parents' => \App\Models\ParentParticipation::where('school', $schoolCode)->exists(),
                'req_teachers' => \App\Models\ReqTeacher::where('school', $schoolCode)->exists(),
                'students' => \App\Models\StudentParticipation::where('school', $schoolCode)->exists(),
                'textbooks' => \App\Models\TextbooksTeaching::where('school', $schoolCode)->exists(),
                'verify_students' => \App\Models\VerifyStudent::where('school', $schoolCode)->exists(),
                'infrastructures' => \App\Models\Infrastructure::where('school', $schoolCode)->exists(),
                'classroom' => \App\Models\ClassroomObservation::where('school', $schoolCode)->exists(),
            ];

            // Find which tables are missing records
            $missingTables = [];
            $presentTables = [];

            foreach ($tables as $table => $exists) {
                if ($exists) {
                    $presentTables[] = $table;
                } else {
                    $missingTables[] = $table;
                }
            }

            $isComplete = count($missingTables) === 0;

            return response()->json([
                'success' => true,
                'school_code' => $schoolCode,
                'school_name' => $school->school_name,
                'is_complete' => $isComplete,
                'missing_tables' => $missingTables,
                'present_tables' => $presentTables,
                'message' => $isComplete
                    ? 'School has complete data'
                    : 'School is incomplete. Missing data in: ' . implode(', ', $missingTables)
            ], 200);

        } catch (\Exception $e) {
            Log::error('Error checking school completeness: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to check school completeness',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete an incomplete school and all its related records
     */
    public function deleteIncompleteSchool(Request $request, $schoolCode)
    {
        try {
            $school = School::where('school_code', $schoolCode)->first();

            if (!$school) {
                return response()->json([
                    'success' => false,
                    'message' => 'School not found'
                ], 404);
            }

            // First check if the school is complete
            $tables = [
                'staff' => \App\Models\Staff::where('school', $schoolCode)->exists(),
                'doc_check' => \App\Models\DocumentCheck::where('school', $schoolCode)->exists(),
                'fees_paid' => \App\Models\FeePaid::where('school', $schoolCode)->exists(),
                'leadership' => \App\Models\Leadership::where('school', $schoolCode)->exists(),
                'parents' => \App\Models\ParentParticipation::where('school', $schoolCode)->exists(),
                'req_teachers' => \App\Models\ReqTeacher::where('school', $schoolCode)->exists(),
                'students' => \App\Models\StudentParticipation::where('school', $schoolCode)->exists(),
                'textbooks' => \App\Models\TextbooksTeaching::where('school', $schoolCode)->exists(),
                'verify_students' => \App\Models\VerifyStudent::where('school', $schoolCode)->exists(),
                'infrastructures' => \App\Models\Infrastructure::where('school', $schoolCode)->exists(),
                'classroom' => \App\Models\ClassroomObservation::where('school', $schoolCode)->exists(),
            ];

            $missingTables = [];
            foreach ($tables as $table => $exists) {
                if (!$exists) {
                    $missingTables[] = $table;
                }
            }

            $isComplete = count($missingTables) === 0;

            // If school is complete, don't allow deletion
            if ($isComplete) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot delete a complete school. This school has all required data.',
                    'school_code' => $schoolCode,
                    'school_name' => $school->school_name,
                    'is_complete' => true
                ], 400);
            }

            // Begin transaction to ensure all deletions succeed or none
            DB::beginTransaction();

            try {
                // Delete from all related tables (following your PHP example)
                \App\Models\Staff::where('school', $schoolCode)->delete();
                \App\Models\DocumentCheck::where('school', $schoolCode)->delete();
                \App\Models\FeePaid::where('school', $schoolCode)->delete();
                \App\Models\Infrastructure::where('school', $schoolCode)->delete();
                \App\Models\Leadership::where('school', $schoolCode)->delete();
                \App\Models\ParentParticipation::where('school', $schoolCode)->delete();
                \App\Models\ReqTeacher::where('school', $schoolCode)->delete();
                \App\Models\StudentParticipation::where('school', $schoolCode)->delete();
                \App\Models\TextbooksTeaching::where('school', $schoolCode)->delete();
                \App\Models\VerifyStudent::where('school', $schoolCode)->delete();
                \App\Models\ClassroomObservation::where('school', $schoolCode)->delete();

                // Finally delete the school itself
                $school->delete();

                DB::commit();

                Log::info('School deleted successfully', [
                    'school_code' => $schoolCode,
                    'school_name' => $school->school_name,
                    'user_id' => Auth::id(),
                    'missing_tables' => $missingTables
                ]);

                return response()->json([
                    'success' => true,
                    'message' => 'School deleted successfully',
                    'school_code' => $schoolCode,
                    'school_name' => $school->school_name,
                    'deleted_tables' => array_keys(array_filter($tables, function($exists) { return $exists; }))
                ], 200);

            } catch (\Exception $e) {
                DB::rollBack();
                throw $e;
            }

        } catch (\Exception $e) {
            Log::error('Error deleting school: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete school',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}

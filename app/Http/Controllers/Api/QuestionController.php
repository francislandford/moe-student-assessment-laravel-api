<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreQuestionRequest;
use App\Http\Resources\QuestionResource;
use App\Models\Question;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class QuestionController extends Controller
{
    /**
     * Display a listing of questions (optionally filtered by category)
     */
    public function index(Request $request): JsonResponse
    {
        $category = $request->query('cat', 'Document check');
        $questions = Question::where('cat', $category)->orderBy('id')->get(['id', 'name']);

        return response()->json($questions);
    }

    /**
     * Store a newly created question
     */
    public function store(StoreQuestionRequest $request): JsonResponse
    {
        $question = Question::create($request->validated());

        return response()->json(new QuestionResource($question), 201);
    }

    /**
     * Display the specified question
     */
    public function show(Question $question): JsonResponse
    {
        return response()->json(new QuestionResource($question));
    }

    /**
     * Update the specified question
     */
    public function update(StoreQuestionRequest $request, Question $question): JsonResponse
    {
        $question->update($request->validated());

        return response()->json(new QuestionResource($question));
    }

    /**
     * Remove the specified question
     */
    public function destroy(Question $question): JsonResponse
    {
        $question->delete();

        return response()->json(null, 204);
    }
}

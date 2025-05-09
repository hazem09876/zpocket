<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Question;

class QuestionApiController extends Controller
{
    // Get all questions
    public function getQuestions()
    {
        $records = Question::with(['module', 'answers'])->get();
        return response()->json($records);
    }

    // Get a single question by ID
    public function getQuestionById($id)
    {
        $record = Question::with(['module', 'answers'])->find($id);
        if (!$record) {
            return response()->json(['message' => 'Question not found'], 404);
        }
        return response()->json($record);
    }
}

<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Answer;

class AnswerApiController extends Controller
{
    // Get all answers
    public function getAnswers()
    {
        $records = Answer::with('question')->get();
        return response()->json($records);
    }

    // Get a single answer by ID
    public function getAnswerById($id)
    {
        $record = Answer::with('question')->find($id);
        if (!$record) {
            return response()->json(['message' => 'Answer not found'], 404);
        }
        return response()->json($record);
    }
}

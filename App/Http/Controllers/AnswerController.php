<?php

namespace App\Http\Controllers;

use App\Models\Answer;
use Illuminate\Http\Request;

class AnswerController extends Controller
{
    // Display a listing of answers
    public function index()
    {
        return response()->json(Answer::all());
    }

    // Store a newly created answer in storage
    public function store(Request $request)
    {
        $validated = $request->validate([
            'question_id' => 'required|exists:questions,question_id',
            'answer_text' => 'required|string',
            'is_correct' => 'required|boolean',
        ]);

        $answer = Answer::create($validated);

        return response()->json($answer, 201);
    }

    // Display the specified answer
    public function show($id)
    {
        $answer = Answer::findOrFail($id);
        return response()->json($answer);
    }

    // Update the specified answer in storage
    public function update(Request $request, $id)
    {
        $answer = Answer::findOrFail($id);

        $validated = $request->validate([
            'question_id' => 'sometimes|exists:questions,question_id',
            'answer_text' => 'sometimes|string',
            'is_correct' => 'sometimes|boolean',
        ]);

        $answer->update($validated);

        return response()->json($answer);
    }

    // Remove the specified answer from storage
    public function destroy($id)
    {
        $answer = Answer::findOrFail($id);
        $answer->delete();

        return response()->json(['message' => 'Answer deleted successfully']);
    }
}
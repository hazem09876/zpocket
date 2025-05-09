<?php

namespace App\Http\Controllers;

use App\Models\Question;
use Illuminate\Http\Request;

class QuestionController extends Controller
{
    // Display a listing of questions
    public function index()
    {
        return response()->json(Question::all());
    }

    // Store a newly created question in storage
    public function store(Request $request)
    {
        $validated = $request->validate([
            'module_id' => 'required|exists:modules,module_id',
            'content' => 'required|string',
            'type' => 'required|in:mcq,true_false',
        ]);

        $question = Question::create($validated);

        return response()->json($question, 201);
    }

    // Display the specified question
    public function show($id)
    {
        $question = Question::findOrFail($id);
        return response()->json($question);
    }

    // Update the specified question in storage
    public function update(Request $request, $id)
    {
        $question = Question::findOrFail($id);

        $validated = $request->validate([
            'module_id' => 'sometimes|exists:modules,module_id',
            'content' => 'sometimes|string',
            'type' => 'sometimes|in:mcq,true_false',
        ]);

        $question->update($validated);

        return response()->json($question);
    }

    // Remove the specified question from storage
    public function destroy($id)
    {
        $question = Question::findOrFail($id);
        $question->delete();

        return response()->json(['message' => 'Question deleted successfully']);
    }
}
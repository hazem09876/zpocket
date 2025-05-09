<?php

namespace App\Http\Controllers;

use App\Models\Quiz;
use Illuminate\Http\Request;

class QuizController extends Controller
{
    // Display a listing of quizzes
    public function index()
    {
        return response()->json(Quiz::all());
    }

    // Store a newly created quiz in storage
    public function store(Request $request)
    {
        $validated = $request->validate([
            'module_id' => 'required|exists:modules,module_id',
            'content' => 'required|string',
            'theme' => 'nullable|string',
        ]);

        $quiz = Quiz::create($validated);

        return response()->json($quiz, 201);
    }

    // Display the specified quiz
    public function show($id)
    {
        $quiz = Quiz::findOrFail($id);
        return response()->json($quiz);
    }

    // Update the specified quiz in storage
    public function update(Request $request, $id)
    {
        $quiz = Quiz::findOrFail($id);

        $validated = $request->validate([
            'module_id' => 'sometimes|exists:modules,module_id',
            'content' => 'sometimes|string',
            'theme' => 'nullable|string',
        ]);

        $quiz->update($validated);

        return response()->json($quiz);
    }

    // Remove the specified quiz from storage
    public function destroy($id)
    {
        $quiz = Quiz::findOrFail($id);
        $quiz->delete();

        return response()->json(['message' => 'Quiz deleted successfully']);
    }
}
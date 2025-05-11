<?php

namespace App\Http\Controllers;

use App\Models\Score;
use Illuminate\Http\Request;

class ScoreController extends Controller
{
    // Display a listing of scores
    public function index()
    {
        return response()->json(Score::all());
    }

    // Store a newly created score in storage
    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,user_id',
            'module_id' => 'required|exists:modules,module_id',
            'quiz_id' => 'required|exists:quizzes,quiz_id',
            'level_id' => 'required|exists:levels,level_id',
            'grade' => 'required|integer',
        ]);

        $score = Score::create($validated);

        return response()->json($score, 201);
    }

    // Display the specified score
    public function show($id)
    {
        $score = Score::findOrFail($id);
        return response()->json($score);
    }

    // Update the specified score in storage
    public function update(Request $request, $id)
    {
        $score = Score::findOrFail($id);

        $validated = $request->validate([
            'user_id' => 'sometimes|exists:users,user_id',
            'module_id' => 'sometimes|exists:modules,module_id',
            'quiz_id' => 'sometimes|exists:quizzes,quiz_id',
            'grade' => 'sometimes|integer',
        ]);

        $score->update($validated);

        return response()->json($score);
    }

    // Remove the specified score from storage
    public function destroy($id)
{
    $score = Score::where('score_id', $id)->firstOrFail();
    $score->delete();
    return response()->json(['message' => 'Score deleted successfully']);
}
    }

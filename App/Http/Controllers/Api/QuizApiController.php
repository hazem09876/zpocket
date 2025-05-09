<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Quiz;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class QuizApiController extends Controller
{
    // List all quizzes (GET /quizzes)
    public function index()
    {
        $quizzes = Quiz::all();
        return response()->json($quizzes);
    }

    // Show a single quiz (GET /quizzes/{id})
    public function show($quiz_id)
    {
        $quiz = Quiz::with('module', 'scores')->find($quiz_id);

        if (!$quiz) {
            return response()->json(['message' => 'Quiz not found'], 404);
        }

        return response()->json($quiz);
    }

    // Create a new quiz (POST /quizzes)
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'module_id' => 'required|exists:modules,module_id',
            'content' => 'required|string',
            'theme' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        $quiz = Quiz::create([
            'module_id' => $request->module_id,
            'content' => $request->content,
            'theme' => $request->theme,
        ]);

        return response()->json($quiz, 201);
    }

    // Update an existing quiz (PUT/PATCH /quizzes/{id})
    public function update(Request $request, $quiz_id)
    {
        $quiz = Quiz::find($quiz_id);

        if (!$quiz) {
            return response()->json(['message' => 'Quiz not found'], 404);
        }

        $validator = Validator::make($request->all(), [
            'module_id' => 'sometimes|required|exists:modules,module_id',
            'content' => 'sometimes|required|string',
            'theme' => 'sometimes|required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        $quiz->update($request->all());

        return response()->json($quiz);
    }

    // Delete a quiz (DELETE /quizzes/{id})
    public function destroy($quiz_id)
    {
        $quiz = Quiz::find($quiz_id);

        if (!$quiz) {
            return response()->json(['message' => 'Quiz not found'], 404);
        }

        $quiz->delete();

        return response()->json(['message' => 'Quiz deleted successfully']);
    }
}

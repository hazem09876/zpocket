<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Module;
use App\Models\Question;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Answer; // Add this import
use App\Models\score; // Add this import


class QuestionApiController extends Controller
{
    /**
     * Create questions for a specific module
     */
    public function createQuestions($module_id, Request $request)
    {
        try {
            // Validate module exists
            $module = Module::find($module_id);
            if (!$module) {
                return response()->json([
                    'success' => false,
                    'message' => 'Module not found'
                ], 404);
            }

            // Validate request data
            $validator = Validator::make($request->all(), [
                'content' => 'required|string',
                'type' => 'required|in:mcq,true_false'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation error',
                    'errors' => $validator->errors()
                ], 422);
            }

            // Create question
            $question = Question::create([
                'module_id' => $module_id,
                'content' => $request->content,
                'type' => $request->type
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Question created successfully',
                'data' => $question
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create question',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove a specific question
     */
    public function removeQuestion($question_id)
    {
        try {
            $question = Question::find($question_id);
            
            if (!$question) {
                return response()->json([
                    'success' => false,
                    'message' => 'Question not found'
                ], 404);
            }

            $question->delete();

            return response()->json([
                'success' => true,
                'message' => 'Question deleted successfully',
                'deleted_question_id' => $question_id
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete question',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get all questions for a specific module
     */
    public function getModuleQuestions($module_id)
    {
        try {
            // Verify module exists
            $module = Module::find($module_id);
            if (!$module) {
                return response()->json([
                    'success' => false,
                    'message' => 'Module not found'
                ], 404);
            }

            // Get all questions for this module
            $questions = Question::where('module_id', $module_id)->get();

            return response()->json([
                'success' => true,
                'module_id' => $module_id,
                'count' => $questions->count(),
                'data' => $questions
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve questions',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function getModuleQuestionsWithAnswers($module_id)
{
    try {
        // Verify module exists
        $module = Module::find($module_id);
        if (!$module) {
            return response()->json([
                'success' => false,
                'message' => 'Module not found'
            ], 404);
        }

        // Get all questions for this module with their answers
        $questions = Question::where('module_id', $module_id)
            ->with(['answers' => function($query) {
                $query->orderBy('created_at', 'asc'); // or any other ordering you prefer
            }])
            ->get()
            ->map(function($question) {
                // Structure the response with question and its answers
                return [
                    'question_id' => $question->question_id,
                    'question_text' => $question->content,
                    'question_type' => $question->type, // 'mcq' or 'true_false'
                    'answers' => $question->answers->map(function($answer) {
                        return [
                            'answer_id' => $answer->id,
                            'answer_text' => $answer->answer_text,
                            'is_correct' => (bool)$answer->is_correct
                        ];
                    })
                ];
            });

        return response()->json([
            'success' => true,
            'module_id' => $module_id,
            'count' => $questions->count(),
            'data' => $questions
        ]);

    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Failed to retrieve questions with answers',
            'error' => $e->getMessage()
        ], 500);
    }
}


public function checkUserAnswer(Request $request, $question_id, $user_id)
{
    try {
        $user_answer = $request->input('answer');

        // Validate inputs
        if (empty($question_id) || empty($user_id) || !isset($user_answer)) {
            return response()->json([
                'success' => false,
                'message' => 'Missing required parameters'
            ], 400);
        }

        // Get the question and its correct answers
        $question = Question::with('module')->find($question_id);
        if (!$question) {
            return response()->json([
                'success' => false,
                'message' => 'Question not found'
            ], 404);
        }

        // Get all correct answers for this question
        $correctAnswers = Answer::where('question_id', $question_id)
                              ->where('is_correct', 1)
                              ->get();

        // Check if user's answer matches any correct answer
        $isCorrect = false;
        $grade = 0; // Default grade
        
        if ($question->question_type === 'true_false') {
            $userAnswerBool = filter_var($user_answer, FILTER_VALIDATE_BOOLEAN);
            $isCorrect = $correctAnswers->contains('answer_text', $userAnswerBool ? '1' : '0');
        } else {
            $isCorrect = $correctAnswers->contains('answer_text', $user_answer);
        }

        // Set grade based on correctness
        $grade = $isCorrect ? 10 : 0;

        // Record the score in scores table
        Score::updateOrCreate(
            [
                'question_id' => $question_id,
                'user_id' => $user_id
            ],
            [
                'module_id' => $question->module_id,
                'grade' => $grade,
                'answered_at' => now()
            ]
        );

        return response()->json([
            'success' => true,
            'is_correct' => $isCorrect,
            'question_id' => $question_id,
            'user_id' => $user_id,
            'module_id' => $question->module_id,
            'grade' => $grade,
            'correct_answers' => $correctAnswers->pluck('answer_text')
        ]);

    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Failed to check answer',
            'error' => $e->getMessage()
        ], 500);
    }
}
}
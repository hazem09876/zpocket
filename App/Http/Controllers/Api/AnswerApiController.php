<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Answer;
use App\Models\Question;
use App\Models\Module;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

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

    /**
     * Submit a user's answer for a question, check correctness, and update user_answer field.
     *
     * @param int $question_id
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function submitUserAnswer($question_id, Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_answer' => 'required|string'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        // Get all answers for the question
        $answers = Answer::where('question_id', $question_id)->get();

        if ($answers->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'No answers found for this question'
            ], 404);
        }

        $isCorrect = false;

        foreach ($answers as $answer) {
            if ($answer->is_correct && $answer->answer_text === $request->user_answer) {
                // Correct answer selected
                $answer->user_answer = 1;
                $answer->save();
                $isCorrect = true;
            } else {
                // Reset user_answer for all other answers
                if ($answer->user_answer != 0) {
                    $answer->user_answer = 0;
                    $answer->save();
                }
            }
        }

        return response()->json([
            'success' => true,
            'question_id' => $question_id,
            'user_answer' => $request->user_answer,
            'is_correct' => $isCorrect
        ]);
    }

    /**
     * Create answers for a specific question
     *
     * @param int $question_id
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function createAnswers($question_id, Request $request)
    {
        try {
            // Verify question exists
            $question = Question::find($question_id);
            if (!$question) {
                return response()->json([
                    'success' => false,
                    'message' => 'Question not found'
                ], 404);
            }

            // Validate request data
            $validator = Validator::make($request->all(), [
                'answers' => 'required|array',
                'answers.*.answer_text' => 'required|string',
                'answers.*.is_correct' => 'required|boolean'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation error',
                    'errors' => $validator->errors()
                ], 422);
            }

            // Create answers
            $createdAnswers = [];
            foreach ($request->answers as $answerData) {
                $answer = Answer::create([
                    'question_id' => $question_id,
                    'answer_text' => $answerData['answer_text'],
                    'is_correct' => $answerData['is_correct']
                ]);
                $createdAnswers[] = $answer;
            }

            return response()->json([
                'success' => true,
                'message' => 'Answers created successfully',
                'question_id' => $question_id,
                'count' => count($createdAnswers),
                'data' => $createdAnswers
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create answers',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove a specific answer
     *
     * @param int $answer_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function removeAnswer($answer_id)
    {
        try {
            $answer = Answer::find($answer_id);

            if (!$answer) {
                return response()->json([
                    'success' => false,
                    'message' => 'Answer not found'
                ], 404);
            }

            $answer->delete();

            return response()->json([
                'success' => true,
                'message' => 'Answer deleted successfully',
                'deleted_answer_id' => $answer_id
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete answer',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get all answers for a specific module with their questions
     *
     * @param int $module_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function getModuleAnswers($module_id)
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
            $questions = Question::with('answers')
                               ->where('module_id', $module_id)
                               ->get();

            // Format the response
            $formattedQuestions = $questions->map(function($question) {
                return [
                    'question_id' => $question->question_id,
                    'content' => $question->content,
                    'type' => $question->type,
                    'answers' => $question->answers->map(function($answer) {
                        return [
                            'answer_id' => $answer->answer_id,
                            'answer_text' => $answer->answer_text,
                            'is_correct' => $answer->is_correct
                        ];
                    })
                ];
            });

            return response()->json([
                'success' => true,
                'module_id' => $module_id,
                'count' => $questions->count(),
                'data' => $formattedQuestions
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve module answers',
                'error' => $e->getMessage()
            ], 500);
        }
    }
} 
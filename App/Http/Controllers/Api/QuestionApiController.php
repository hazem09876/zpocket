<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Module;
use App\Models\Question;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

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
}
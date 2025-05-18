<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Score;
use App\Models\UserProgress;
use App\Models\Answer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ScoreApiController extends Controller
{
    /**
     * Auto-calculate & store score + track progress
     */
    public function storeScoreWithProgress(Request $request)
    {
        try {
            $validated = $request->validate([
                'user_id' => 'required|exists:users,user_id',
                'module_id' => 'required|exists:modules,module_id',
                'question_id' => 'required|exists:questions,question_id',
                'grade' => 'required|integer|min:0|max:100'
            ]);

            // Store score
            $score = Score::updateOrCreate(
                [
                    'user_id' => $validated['user_id'],
                    'module_id' => $validated['module_id'],
                    'question_id' => $validated['question_id'],
                ],
                [
                    'grade' => $validated['grade'],
                ]
            );

            // Store progress
            $progress = UserProgress::updateOrCreate(
                [
                    'user_id' => $validated['user_id'],
                    'module_id' => $validated['module_id']
                ],
                [
                    'grade' => $validated['grade'],
                    'is_completed' => true,
                    'completed_at' => now()
                ]
            );

            // Check if this was a new score or an update
            $wasRecentlyCreated = $score->wasRecentlyCreated;

            return response()->json([
                'message' => $wasRecentlyCreated ? 'Score created successfully' : 'Score updated successfully',
                'score' => $score,
                'progress' => $progress
            ], $wasRecentlyCreated ? 201 : 200);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to store score',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get user progress with scores
     */
    public function getUserProgress($user_id, $module_id = null)
{
    $query = UserProgress::with(['module', 'score'])
        ->where('user_id', $user_id);

    if ($module_id) {
        $query->where('module_id', $module_id);
    }

    $progress = $query->get();

    if ($progress->isEmpty()) {
        return response()->json(['message' => 'Progress not found'], 404);
    }

    return response()->json($progress);
}

    /**
     * Get all scores for a user in a module (for score tracking)
     */
    public function getUserModuleScores($user_id, $module_id)
    {
        $scores = Score::where('user_id', $user_id)
            ->where('module_id', $module_id)
            ->with('question')
            ->get();

        $totalGrade = $scores->sum('grade');
        $averageGrade = $scores->avg('grade');

        return response()->json([
            'scores' => $scores,
            'total_grade' => $totalGrade,
            'average_grade' => $averageGrade,
        ]);
    }

    /**
     * Get the score for a specific question for a user in a module
     */
    public function getUserQuestionScore($user_id, $module_id, $question_id)
    {
        $score = Score::where('user_id', $user_id)
            ->where('module_id', $module_id)
            ->where('question_id', $question_id)
            ->first();

        if (!$score) {
            return response()->json(['message' => 'Score not found'], 404);
        }

        return response()->json($score);
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
    public function getUserProgressById($user_id, $progress_id)
{
    $progress = UserProgress::where('user_id', $user_id)
                            ->where('progress_id', $progress_id)
                            ->first();

    if (!$progress) {
        return response()->json(['message' => 'Progress not found'], 404);
    }

    return response()->json($progress);
}
}
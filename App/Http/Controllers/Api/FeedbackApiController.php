<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Feedback;

class FeedbackApiController extends Controller
{
 
public function store(Request $request)
{
    $validated = $request->validate([
        'user_id' => 'required|exists:users,user_id',
        'module_id' => 'required|exists:modules,module_id',
        'content' => 'required|string',
    ]);

    $feedback = Feedback::create($validated);

    return response()->json($feedback, 201);
}
public function getUserModuleFeedback($user_id, $module_id)
{
    $feedbacks = Feedback::where('user_id', $user_id)
        ->where('module_id', $module_id)
        ->get();

    return response()->json($feedbacks);
}



}


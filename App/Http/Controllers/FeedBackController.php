<?php

namespace App\Http\Controllers;

use App\Models\Feedback;
use Illuminate\Http\Request;

class FeedbackController extends Controller
{
    // Display a listing of feedback
    public function index()
    {
        return response()->json(Feedback::all());
    }

    // Store a newly created feedback in storage
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

    // Display the specified feedback
    public function show($id)
    {
        $feedback = Feedback::findOrFail($id);
        return response()->json($feedback);
    }

    // Update the specified feedback in storage
    public function update(Request $request, $id)
    {
        $feedback = Feedback::findOrFail($id);

        $validated = $request->validate([
            'user_id' => 'sometimes|exists:users,user_id',
            'module_id' => 'sometimes|exists:modules,module_id',
            'content' => 'sometimes|string',
        ]);

        $feedback->update($validated);

        return response()->json($feedback);
    }

    // Remove the specified feedback from storage
    public function destroy($id)
    {
        $feedback = Feedback::findOrFail($id);
        $feedback->delete();

        return response()->json(['message' => 'Feedback deleted successfully']);
    }
}
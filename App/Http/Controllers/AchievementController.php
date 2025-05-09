<?php

namespace App\Http\Controllers;

use App\Models\Achievement;
use Illuminate\Http\Request;

class AchievementController extends Controller
{
    // Display a listing of achievements
    public function index()
    {
        return response()->json(Achievement::all());
    }

    // Store a newly created achievement in storage
    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,user_id',
            'date_achieved' => 'nullable|date',
            'description' => 'required|string',
        ]);

        $achievement = Achievement::create($validated);

        return response()->json($achievement, 201);
    }

    // Display the specified achievement
    public function show($id)
    {
        $achievement = Achievement::findOrFail($id);
        return response()->json($achievement);
    }

    // Update the specified achievement in storage
    public function update(Request $request, $id)
    {
        $achievement = Achievement::findOrFail($id);

        $validated = $request->validate([
            'user_id' => 'sometimes|exists:users,user_id',
            'date_achieved' => 'nullable|date',
            'description' => 'sometimes|string',
        ]);

        $achievement->update($validated);

        return response()->json($achievement);
    }

    // Remove the specified achievement from storage
    public function destroy($id)
    {
        $achievement = Achievement::findOrFail($id);
        $achievement->delete();

        return response()->json(['message' => 'Achievement deleted successfully']);
    }
}
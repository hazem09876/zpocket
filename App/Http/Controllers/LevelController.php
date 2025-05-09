<?php

namespace App\Http\Controllers;

use App\Models\Level;
use Illuminate\Http\Request;

class LevelController extends Controller
{
    // Display a listing of levels
    public function index()
    {
        return response()->json(Level::all());
    }

    // Store a newly created level in storage
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|unique:levels,name',
            'advancement' => 'required|integer',
        ]);

        $level = Level::create($validated);

        return response()->json($level, 201);
    }

    // Display the specified level
    public function show($id)
    {
        $level = Level::findOrFail($id);
        return response()->json($level);
    }

    // Update the specified level in storage
    public function update(Request $request, $id)
    {
        $level = Level::findOrFail($id);

        $validated = $request->validate([
            'name' => 'sometimes|string|unique:levels,name,' . $id . ',level_id',
            'advancement' => 'sometimes|integer',
        ]);

        $level->update($validated);

        return response()->json($level);
    }

    // Remove the specified level from storage
    public function destroy($id)
    {
        $level = Level::findOrFail($id);
        $level->delete();

        return response()->json(['message' => 'Level deleted successfully']);
    }
}
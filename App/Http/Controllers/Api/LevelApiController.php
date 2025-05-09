<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Level;

class LevelApiController extends Controller
{
    // Get all levels
    public function getLevels()
    {
        $records = Level::with('scores')->get();
        return response()->json($records);
    }

    // Get a single level by ID
    public function getLevelById($id)
    {
        $record = Level::with('scores')->find($id);
        if (!$record) {
            return response()->json(['message' => 'Level not found'], 404);
        }
        return response()->json($record);
    }
}

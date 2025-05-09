<?php

namespace App\Http\Controllers;

use App\Models\Video;
use Illuminate\Http\Request;

class VideoController extends Controller
{
    // Display a listing of videos
    public function index()
    {
        return response()->json(Video::all());
    }

    // Store a newly created video in storage
    public function store(Request $request)
    {
        $validated = $request->validate([
            'module_id' => 'required|exists:modules,module_id',
            'embed_code' => 'required|string',
            'title' => 'required|string',
        ]);

        $video = Video::create($validated);

        return response()->json($video, 201);
    }

    // Display the specified video
    public function show($id)
    {
        $video = Video::findOrFail($id);
        return response()->json($video);
    }

    // Update the specified video in storage
    public function update(Request $request, $id)
    {
        $video = Video::findOrFail($id);

        $validated = $request->validate([
            'module_id' => 'sometimes|exists:modules,module_id',
            'embed_code' => 'sometimes|string',
            'title' => 'sometimes|string',
        ]);

        $video->update($validated);

        return response()->json($video);
    }

    // Remove the specified video from storage
    public function destroy($id)
    {
        $video = Video::findOrFail($id);
        $video->delete();

        return response()->json(['message' => 'Video deleted successfully']);
    }
}
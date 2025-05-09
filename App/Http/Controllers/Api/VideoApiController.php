<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Video;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class VideoApiController extends Controller
{
    // List all videos (GET /videos)
    public function index()
    {
        $videos = Video::all();
        return response()->json($videos);
    }

    // Show a single video (GET /videos/{id})
    public function show($video_id)
    {
        $video = Video::find($video_id);

        if (!$video) {
            return response()->json(['message' => 'Video not found'], 404);
        }

        return response()->json($video);
    }

    // Create a new video (POST /videos)
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'module_id' => 'required|exists:modules,module_id',
            'embed_code' => 'required|string',
            'title' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        $video = Video::create([
            'module_id' => $request->module_id,
            'embed_code' => $request->embed_code,
            'title' => $request->title,
        ]);

        return response()->json($video, 201);
    }

    // Update an existing video (PUT/PATCH /videos/{id})
    public function update(Request $request, $video_id)
    {
        $video = Video::find($video_id);

        if (!$video) {
            return response()->json(['message' => 'Video not found'], 404);
        }

        $validator = Validator::make($request->all(), [
            'module_id' => 'sometimes|required|exists:modules,module_id',
            'embed_code' => 'sometimes|required|string',
            'title' => 'sometimes|required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        $video->update($request->all());

        return response()->json($video);
    }

    // Delete a video (DELETE /videos/{id})
    public function destroy($video_id)
    {
        $video = Video::find($video_id);

        if (!$video) {
            return response()->json(['message' => 'Video not found'], 404);
        }

        $video->delete();

        return response()->json(['message' => 'Video deleted successfully']);
    }
}

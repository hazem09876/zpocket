<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Video;
use App\Models\Module;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class VideoApiController extends Controller
{
    /**
 * Get all videos
 * 
 * @return \Illuminate\Http\JsonResponse
 */
public function AllVideos()
{
    try {
        $videos = Video::all();
        
        return response()->json([
            'success' => true,
            'data' => $videos
        ]);
        
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Failed to retrieve videos',
            'error' => $e->getMessage()
        ], 500);
    }
}

    // Show a single video (GET /videos/{id})
    
    /**
 * Get a single video by ID
 * 
 * @param int $video_id
 * @return \Illuminate\Http\JsonResponse
 */
public function show($video_id)
{
    try {
        $video = Video::find($video_id);

        if (!$video) {
            return response()->json([
                'success' => false,
                'message' => 'Video not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $video
        ]);

    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Failed to retrieve video',
            'error' => $e->getMessage()
        ], 500);
    }
}

/**
 * Get all videos for a specific module
 * 
 * @param int $module_id
 * @return \Illuminate\Http\JsonResponse
 */
public function getVideosByModule($module_id)
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

        // Get all videos for this module
        $videos = Video::where('module_id', $module_id)->get();

        return response()->json([
            'success' => true,
            'module_id' => $module_id,
            'count' => $videos->count(),
            'data' => $videos
        ]);

    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Failed to retrieve videos',
            'error' => $e->getMessage()
        ], 500);
    }
}

    // Create a new video for a specific module (POST /modules/{module_id}/videos)
    public function createForModule($module_id, Request $request)
    {
        // Verify module exists
        $module = Module::find($module_id);
        if (!$module) {
            return response()->json(['message' => 'Module not found'], 404);
        }

        $validator = Validator::make($request->all(), [
            'embed_code' => 'required|string',
            'title' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        $video = Video::create([
            'module_id' => $module_id,
            'embed_code' => $request->embed_code,
            'title' => $request->title
        ]);

        return response()->json([
            'message' => 'Video created successfully for module',
            'video' => $video
        ], 201);
    }

    // Remove a specific video (DELETE /videos/{video_id})
    public function removeVideo($video_id)
    {
        $video = Video::find($video_id);

        if (!$video) {
            return response()->json(['message' => 'Video not found'], 404);
        }

        $video->delete();

        return response()->json([
            'message' => 'Video deleted successfully',
            'deleted_video_id' => $video_id
        ]);
    }

    // Original store method (POST /videos)
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

        $video = Video::create($request->all());

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
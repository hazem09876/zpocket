<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Module;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;

class ModuleApiController extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    public function __construct()
    {
        $this->middleware('jwt.verify');
    }

    // List all modules (GET /modules)
    public function index(): JsonResponse
    {
        $modules = Module::with('admin')->get();
        return response()->json($modules);
    }

    // Show a single module (GET /modules/{id})
    public function show(int $id): JsonResponse
    {
        $module = Module::with('admin')->find($id);

        if (!$module) {
            return response()->json(['message' => 'Module not found'], 404);
        }

        return response()->json($module);
    }

    // Create a new module (POST /modules)
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'admin_id' => 'required|exists:admins,admin_id'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        $module = Module::create($request->all());
        return response()->json([
            'message' => 'Module created successfully',
            'module' => $module
        ], 201);
    }

    // Update an existing module (PUT/PATCH /modules/{id})
    public function update(Request $request, int $id): JsonResponse
    {
        $module = Module::find($id);

        if (!$module) {
            return response()->json(['message' => 'Module not found'], 404);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'admin_id' => 'required|exists:admins,admin_id'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        $module->update($request->all());
        return response()->json([
            'message' => 'Module updated successfully',
            'module' => $module
        ]);
    }

    // Delete a module (DELETE /modules/{id})
    public function destroy(int $id): JsonResponse
    {
        $module = Module::find($id);

        if (!$module) {
            return response()->json(['message' => 'Module not found'], 404);
        }

        $module->delete();

        return response()->json(['message' => 'Module deleted successfully']);
    }
}

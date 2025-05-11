<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Module;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ModuleApiController extends Controller
{
    // List all modules (GET /modules)
    public function index()
    {
        $modules = Module::all();
        return response()->json($modules);
    }

    // Show a single module (GET /modules/{id})
    public function show($module_id)
    {
        $module = Module::with(['videos', 'questions', 'feedbacks', 'scores', 'userModules'])->find($module_id);

        if (!$module) {
            return response()->json(['message' => 'Module not found'], 404);
        }

        return response()->json($module);
    }

    // Create a new module (POST /modules)
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        $module = Module::create([
            'name' => $request->name,
            'description' => $request->description,
        ]);

        return response()->json($module, 201);
    }

    // Update an existing module (PUT/PATCH /modules/{id})
    public function update(Request $request, $module_id)
    {
        $module = Module::find($module_id);

        if (!$module) {
            return response()->json(['message' => 'Module not found'], 404);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|required|string|max:255',
            'description' => 'sometimes|required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        $module->update($request->all());

        return response()->json($module);
    }

    // Delete a module (DELETE /modules/{id})
    public function destroy($module_id)
    {
        $module = Module::find($module_id);

        if (!$module) {
            return response()->json(['message' => 'Module not found'], 404);
        }

        $module->delete();

        return response()->json(['message' => 'Module deleted successfully']);
    }
}

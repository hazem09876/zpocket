<?php

namespace App\Http\Controllers;

use App\Models\Module;
use Illuminate\Http\Request;

class ModuleController extends Controller
{
    // Display a listing of modules
    public function index()
    {
        return response()->json(Module::all());
    }

    // Store a newly created module in storage
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|unique:modules,name',
            'description' => 'nullable|string',
        ]);

        $module = Module::create($validated);

        return response()->json($module, 201);
    }

    // Display the specified module
    public function show($id)
    {
        $module = Module::findOrFail($id);
        return response()->json($module);
    }

    // Update the specified module in storage
    public function update(Request $request, $id)
    {
        $module = Module::findOrFail($id);

        $validated = $request->validate([
            'name' => 'sometimes|string|unique:modules,name,' . $id . ',module_id',
            'description' => 'nullable|string',
        ]);

        $module->update($validated);

        return response()->json($module);
    }

    // Remove the specified module from storage
    public function destroy($id)
    {
        $module = Module::findOrFail($id);
        $module->delete();

        return response()->json(['message' => 'Module deleted successfully']);
    }
}
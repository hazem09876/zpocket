<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\User;
use App\Models\Module;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AdminApiController extends Controller
{
    // Get all users
    public function getUsers()
    {
        $records = User::all();
        return response()->json($records);
    }

    // Get all admins
    public function getAdmins()
    {
        $records = Admin::with('user')->get();
        return response()->json($records);
    }

    // Get a single admin by ID
    public function getAdminById($id)
    {
        $record = Admin::with('user')->find($id);
        if (!$record) {
            return response()->json(['message' => 'Admin not found'], 404);
        }
        return response()->json($record);
    }

    /**
     * Create a new module
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function createModule(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:modules,name',
            'description' => 'nullable|string'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation errors',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $module = Module::create([
                'name' => $request->name,
                'description' => $request->description
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Module created successfully',
                'data' => $module
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create module',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove a module
     * 
     * @param int $id Module ID
     * @return \Illuminate\Http\JsonResponse
     */
    public function removeModule($id)
    {
        try {
            $module = Module::find($id);

            if (!$module) {
                return response()->json([
                    'success' => false,
                    'message' => 'Module not found'
                ], 404);
            }

            $module->delete();

            return response()->json([
                'success' => true,
                'message' => 'Module deleted successfully',
                'deleted_id' => $id
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete module',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get all modules (optional)
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function getModules()
    {
        $modules = Module::all();
        return response()->json([
            'success' => true,
            'data' => $modules
        ]);
    }
}
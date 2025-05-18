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

    // Get all admins (admin fields only)
    public function getAdmins()
    {
        $admins = Admin::all(['admin_id', 'user_id', 'permission', 'created_at', 'updated_at']);
        return response()->json($admins);
    }

    // Get a single admin by ID (with nested user info)
    public function getAdminById($id)
    {
        $admin = Admin::with(['user', 'modules'])->find($id);
        if (!$admin) {
            return response()->json(['message' => 'Admin not found'], 404);
        }
        $result = [
            'admin_id' => $admin->admin_id,
            'user_id' => $admin->user_id,
            'permission' => $admin->permission,
            'created_at' => $admin->created_at,
            'updated_at' => $admin->updated_at,
            'user' => $admin->user ? [
                'user_id' => $admin->user->user_id,
                'username' => $admin->user->username,
                'date_of_birth' => $admin->user->date_of_birth,
                'email' => $admin->user->email,
                'created_at' => $admin->user->created_at,
                'updated_at' => $admin->user->updated_at,
            ] : null,
            // Optionally include modules if you want:
            // 'modules' => $admin->modules,
        ];
        return response()->json($result);
    }

    /**
     * Create a new module
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
   public function createModule(Request $request)
{
    $request->validate([
        'module_name' => 'required|string|max:255',
        'description' => 'nullable|string',
        'admin_id' => 'required|exists:admins,admin_id'
    ]);
    
    $validated = $request->only(['module_name', 'description', 'admin_id']);
    $module = Module::create($validated);

    return response()->json([
        'success' => true,
        'message' => 'Module created successfully',
        'data' => $module
    ]);
}
    

    /**
     * Remove a module
     * 
     * @param int $id Module ID
     * @return \Illuminate\Http\JsonResponse
     */

    public function removeModule($id)
    {
        $module = Module::find($id);
        if (!$module) {
            return response()->json(['message' => 'Module not found'], 404);
        }
        $module->delete();
        return response()->json([
            'success' => true,
            'message' => 'Module deleted successfully',
            'deleted_id' => $id
        ]);
    }

    /**
     * Get all modules (optional)
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function getModules()
    {
        return response()->json(['success' => true, 'data' => Module::all()]);
    }
}
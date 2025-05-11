<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\UserModule;
use App\Models\User;
use App\Models\Module;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class UserModuleApiController extends Controller
{
    public function getModules()
    {
        $modules = Module::all();
        return response()->json([
            'success' => true,
            'data' => $modules
        ]);
    }

    public function registerModule($user_id, Request $request)
    {
        try {
            // Validate request data
            $validator = Validator::make($request->all(), [
                'module_id' => 'required|integer|exists:modules,module_id'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation error',
                    'errors' => $validator->errors()
                ], 422);
            }

            // Verify the user exists (direct query)
            $userExists = DB::table('users')
                          ->where('user_id', $user_id)
                          ->exists();

            if (!$userExists) {
                return response()->json([
                    'success' => false,
                    'message' => 'User not found'
                ], 404);
            }

            // Verify the module exists (direct query)
            $moduleExists = DB::table('modules')
                           ->where('module_id', $request->module_id)
                           ->exists();

            if (!$moduleExists) {
                return response()->json([
                    'success' => false,
                    'message' => 'Module not listed'
                ], 404);
            }

            // Check if registration already exists (direct query)
            $registrationExists = DB::table('user_modules')
                                 ->where('user_id', $user_id)
                                 ->where('module_id', $request->module_id)
                                 ->exists();

            if ($registrationExists) {
                return response()->json([
                    'success' => false,
                    'message' => 'User is already registered to this module'
                ], 409);
            }

            // Create the registration record (direct insert)
            $userModuleId = DB::table('user_modules')->insertGetId([
                'user_id' => $user_id,
                'module_id' => $request->module_id,
                'created_at' => now(),
                'updated_at' => now()
            ]);

            // Get the created record
            $userModule = DB::table('user_modules')
                          ->where('user_module_id', $userModuleId)
                          ->first();

            return response()->json([
                'success' => true,
                'message' => 'Module registered successfully',
                'data' => [
                    'user_module_id' => $userModule->user_module_id,
                    'user_id' => $userModule->user_id,
                    'module_id' => $userModule->module_id,
                    'created_at' => $userModule->created_at
                ]
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to register module',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get all registered modules for a specific user
     * 
     * @param int $user_id User ID from URL params
     * @return \Illuminate\Http\JsonResponse
     */
    public function takeModule($user_id)
    {
        try {
            // Verify the user exists
            $userExists = DB::table('users')
                          ->where('user_id', $user_id)
                          ->exists();

            if (!$userExists) {
                return response()->json([
                    'success' => false,
                    'message' => 'User not found'
                ], 404);
            }

            // Get all module registrations for this user
            $registeredModules = DB::table('user_modules')
                                 ->join('modules', 'user_modules.module_id', '=', 'modules.module_id')
                                 ->where('user_modules.user_id', $user_id)
                                 ->select('modules.*', 'user_modules.created_at as registration_date')
                                 ->get();

            if ($registeredModules->isEmpty()) {
                return response()->json([
                    'success' => true,
                    'message' => 'No modules registered for this user',
                    'data' => []
                ]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Registered modules retrieved successfully',
                'user_id' => $user_id,
                'count' => $registeredModules->count(),
                'data' => $registeredModules
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve registered modules',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
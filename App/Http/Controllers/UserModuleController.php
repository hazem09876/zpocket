<?php

namespace App\Http\Controllers;

use App\Models\UserModule;
use Illuminate\Http\Request;

class UserModuleController extends Controller
{
    // Display a listing of user-module enrollments
    public function index()
    {
        return response()->json(UserModule::all());
    }

    // Store a newly created user-module enrollment in storage
    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,user_id',
            'module_id' => 'required|exists:modules,module_id',
        ]);

        $userModule = UserModule::create($validated);

        return response()->json($userModule, 201);
    }

    // Display the specified user-module enrollment
    public function show($id)
    {
        $userModule = UserModule::findOrFail($id);
        return response()->json($userModule);
    }

    // Update the specified user-module enrollment in storage
    public function update(Request $request, $id)
    {
        $userModule = UserModule::findOrFail($id);

        $validated = $request->validate([
            'user_id' => 'sometimes|exists:users,user_id',
            'module_id' => 'sometimes|exists:modules,module_id',
        ]);

        $userModule->update($validated);

        return response()->json($userModule);
    }

    // Remove the specified user-module enrollment from storage
    public function destroy($id)
    {
        $userModule = UserModule::findOrFail($id);
        $userModule->delete();

        return response()->json(['message' => 'User-Module enrollment deleted successfully']);
    }
}
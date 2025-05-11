<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    // Display a listing of admins
    public function index()
    {
        return response()->json(Admin::all());
    }

    // Store a newly created admin in storage
    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,user_id|unique:admins,user_id',
            'permission' => 'required|string',
        ]);

        $admin = Admin::create($validated);

        return response()->json($admin, 201);
    }

    // Display the specified admin
    public function show($id)
    {
        $admin = Admin::findOrFail($id);
        return response()->json($admin);
    }

    // Update the specified admin in storage
    public function update(Request $request, $id)
    {
        $admin = Admin::findOrFail($id);

        $validated = $request->validate([
            'user_id' => 'sometimes|exists:users,user_id|unique:admins,user_id,' . $id . ',admin_id',
            'permission' => 'sometimes|string',
        ]);

        $admin->update($validated);

        return response()->json($admin);
    }

    // Remove the specified admin from storage
    public function destroy($id)
    {
        $admin = Admin::findOrFail($id);
        $admin->delete();

        return response()->json(['message' => 'Admin deleted successfully']);
    }
}

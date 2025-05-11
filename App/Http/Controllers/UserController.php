<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    // Display a listing of users
    public function index()
    {
        return response()->json(User::all());
    }

    // Store a newly created user in storage
    // Creates a new user (renamed from 'store' to 'createUser')
public function createUser(Request $request)
{
    $validated = $request->validate([
        'username' => 'required|string|unique:users,username',
        'email' => 'required|email|unique:users,email',
        'password' => 'required|string|min:6',
        'date_of_birth' => 'nullable|date',
    ]);

    $validated['password'] = Hash::make($validated['password']);
    $user = User::create($validated);
    return response()->json($user, 201);
}

public function userLogin(Request $request)
{
    $credentials = $request->validate([
        'email' => 'required|email',
        'password' => 'required|string', // Plain text from request
    ]);

    if (Auth::attempt($credentials)) { // Auto-handles hash comparison
        return response()->json([
            'user' => Auth::user(),
            'message' => 'Login successful'
        ]);
    }

    return response()->json(['message' => 'Invalid credentials'], 401);
}

    // Display the specified user
    public function show($id)
    {
        $user = User::findOrFail($id);
        return response()->json($user);
    }

    // Update the specified user in storage
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $validated = $request->validate([
            'username' => 'sometimes|string|unique:users,username,' . $id . ',user_id',
            'email' => 'sometimes|email|unique:users,email,' . $id . ',user_id',
            'password' => 'sometimes|string|min:6',
            'date_of_birth' => 'nullable|date',
        ]);

        if (isset($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        }

        $user->update($validated);

        return response()->json($user);
    }

    // Remove the specified user from storage
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return response()->json(['message' => 'User deleted successfully']);
    }
}
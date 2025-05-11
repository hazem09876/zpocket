<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Models\Module;


class UserApiController extends Controller
{
    // Get a single user by ID
    public function getUserById($id)
    {
        $record = User::find($id);
        if (!$record) {
            return response()->json(['message' => 'User not found'], 404);
        }
        return response()->json($record);
    }

    // Create a new user
    public function createUser(Request $request)
{
    // First check if email already exists
    if (User::where('email', $request->email)->exists()) {
        return response()->json([
            'message' => 'This email is already registered',
            'error' => 'email_exists'
        ], 409); // 409 Conflict status code
    }

    // Then check if username exists (already in your validation)
    if (User::where('username', $request->username)->exists()) {
        return response()->json([
            'message' => 'This username is already taken',
            'error' => 'username_exists'
        ], 409);
    }

    // Validate the request data
    $validated = $request->validate([
        'username' => 'required|string|unique:users,username',
        'email' => 'required|email|unique:users,email',
        'password' => 'required|string|min:6',
        'date_of_birth' => 'nullable|date',
    ]);

    // Hash the password before saving
    $validated['password'] = Hash::make($validated['password']);

    // Create the user
    $user = User::create($validated);

    // Return the created user with HTTP 201 status
    return response()->json([
        'user' => $user,
        'message' => 'User registered successfully'
    ], 201);
}

    // User login with email and password
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
}
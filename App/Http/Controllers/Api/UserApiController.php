<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;

class UserApiController extends Controller
{
    // Get all users
    public function getUsers()
    {
        $records = User::all();
        return response()->json($records);
    }

    // Get a single user by ID
    public function getUserById($id)
    {
        $record = User::find($id);
        if (!$record) {
            return response()->json(['message' => 'User not found'], 404);
        }
        return response()->json($record);
    }
}

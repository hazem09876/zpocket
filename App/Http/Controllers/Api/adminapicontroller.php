<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Admin;

class AdminApiController extends Controller
{
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
}

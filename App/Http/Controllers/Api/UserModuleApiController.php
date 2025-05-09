<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\UserModule;

class UserModuleApiController extends Controller
{
    public function getUserModules()
    {
        $records = UserModule::all();
        return response()->json($records);
    }
}

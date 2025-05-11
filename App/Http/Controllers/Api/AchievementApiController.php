<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Achievement;

class AchievementApiController extends Controller
{
    public function getAchievements()
    {
        $records = Achievement::all();
        return response()->json($records);
    }
}

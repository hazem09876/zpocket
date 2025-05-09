<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Score;

class ScoreApiController extends Controller
{
    public function getScores()
    {
        $records = Score::all();
        return response()->json($records);
    }
}

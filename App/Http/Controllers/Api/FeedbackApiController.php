<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Feedback;

class FeedbackApiController extends Controller
{
    public function getFeedbacks()
    {
        $records = Feedback::all();
        return response()->json($records);
    }
}

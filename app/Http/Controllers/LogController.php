<?php

namespace App\Http\Controllers;

use App\Models\Log;

class LogController extends Controller
{
    public function index()
    {
        return Log::all();
    }
}

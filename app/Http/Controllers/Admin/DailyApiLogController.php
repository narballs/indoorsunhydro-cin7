<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\DailyApiLog;

class DailyApiLogController extends Controller
{
    public function index(Request $request) {

        $date = $request->date;
        if (empty($date)) {
            $date = date('Y-m-d');
        }

        $daily_api_logs = DailyApiLog::where('date', $date)->get();

        return view('admin.daily_api_logs.index', compact('daily_api_logs'));
    }
}

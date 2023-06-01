<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ApiSyncLog;

class LogsController extends Controller
{
    public function index() {
        $api_logs = ApiSyncLog::all();
        return view('admin/logs', compact('api_logs'));

    }
}

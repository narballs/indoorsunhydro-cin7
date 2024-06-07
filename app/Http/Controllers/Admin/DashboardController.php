<?php

namespace App\Http\Controllers\Admin;

use \App\Http\Controllers\Controller;
use App\Http\Middleware\IsAdmin;
use App\Models\GmcLog;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    function __construct()
    {
        $this->middleware(['role:Admin']);
    }

    public function index()
    {
        $last_google_sync =  GmcLog::first();
        return view('admin/dashboard' , compact('last_google_sync'));
    }
}

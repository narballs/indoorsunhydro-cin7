<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\DailyApiLog;
use Illuminate\Support\Facades\Artisan;

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

     public function update_all_products($update_all_products = null) {
        try {
            if ($update_all_products === null) {
                $update_all_products = 'yes';
            }
            Artisan::call('Sync:ApiData', ['update_all_products' => $update_all_products]);
            return redirect()->back()->with('success', 'Command executed successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
}

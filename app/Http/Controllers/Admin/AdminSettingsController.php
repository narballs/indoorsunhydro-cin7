<?php

namespace App\Http\Controllers\Admin;
use \App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Middleware\IsAdmin;
use App\Models\AdminSetting;





class AdminSettingsController extends Controller
{
    function __construct()
    {
        $this->middleware(['role:Admin']);

    }
    
    public function autoFullfill(Request $request) {
        // dd($request->all());
        $option = AdminSetting::where('option_name', 'auto_full_fill')->first();
        if ($option->option_value == 1) {
            $option->option_value = 0;
        }
        else {
            $option->option_value = 1;
        }
        $option->save();
        return response()->json([
                'success' => true, 
                'msg' => 'Updated!'
            ]);
    }
}
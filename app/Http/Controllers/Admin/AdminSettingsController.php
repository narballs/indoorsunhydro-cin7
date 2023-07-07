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

    public function settings() {
        $settings = AdminSetting::all();
        return view('admin.settings.settings', compact('settings'));
    }

    public function create_settings() {
        return view('admin.settings.create_settings');
    }

    public function store_settings(Request $request) {
        $request->validate([
            'option_name' => 'required',
            'option_value' => 'required'
        ]);
        $setting = AdminSetting::create($request->all());
        return redirect()->route('admin.settings')->with('success', 'Setting created successfully.');
    }

    public function edit_settings($id) {
        $setting = AdminSetting::findOrFail($id);
        return view('admin.settings.edit_settings', compact('setting'));
    }

    public function update_settings(Request $request, $id) {
        $request->validate([
            'option_name' => 'required',
            'option_value' => 'required'
        ]);
        $setting = AdminSetting::where('id', $id)->first();
        $setting->update([
            'option_name' => $request->option_name,
            'option_value' => $request->option_value
        ]); 
        return redirect()->route('admin.settings')->with('success', 'Setting updated successfully.');
    }

    public function delete_settings($id) {
        $setting = AdminSetting::findOrFail($id);
        $setting->delete();
        return redirect()->route('admin.settings')->with('success', 'Setting deleted successfully.');
    }
}
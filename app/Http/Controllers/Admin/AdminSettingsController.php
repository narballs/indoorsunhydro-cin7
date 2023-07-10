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

    public function index() {
        $settings = AdminSetting::all();
        return view('admin.settings.index', compact('settings'));
    }

    public function create() {
        return view('admin.settings.create');
    }

    public function store(Request $request) {
        $request->validate([
            'option_name' => 'required',
            'type' => 'required',
           // 'option_value' => 'required'
        ]);
        $setting = new AdminSetting();
        $setting->option_name = $request->option_name;
        $setting->type = $request->type;
        if($request->type == 'text') {
            $request->validate(
                [
                    'option_value_text' => 'required'
                ],
                [
                    'option_value_text.required' => 'Option value is required'
                ]
            );
            $setting->option_value = $request->option_value_text;
        }
        elseif($request->type == 'boolean') {
            $request->validate(
                [
                    'option_value_boolean' => 'required'
                ],
                [
                    'option_value_boolean.required' => 'Option value is required'
                ]
            );
            $setting->option_value = $request->option_value_boolean;
        }
        elseif($request->type == 'number') {
            $request->validate(
                [
                    'option_value_number' => 'required'
                ],
                [
                    'option_value_number.required' => 'Option value is required'
                ]
            );
            $setting->option_value = $request->option_value_number;
        }
        elseif($request->type == 'yes/no') {
            $request->validate(
                [
                    'option_value_yes_no' => 'required'
                ],
                [
                    'option_value_yes_no.required' => 'Option value is required'
                ]
            );
            $setting->option_value = $request->option_value_yes_no;
        }
        $setting->save();
        return redirect()->route('admin.settings.index')->with('success', 'Setting created successfully.');
    }

    public function edit($id) {
        $setting = AdminSetting::findOrFail($id);
        return view('admin.settings.edit', compact('setting'));
    }

    public function update(Request $request, $id) {
        $request->validate([
            'option_name' => 'required',
            'type' => 'required'
        ]);
        $setting = AdminSetting::where('id', $id)->first();
        $setting->option_name = $request->option_name;
        $setting->type = $request->type;
        if($request->type == 'text') {
            $request->validate(
                [
                    'option_value_text' => 'required'
                ],
                [
                    'option_value_text.required' => 'Option value is required'
                ]
            );
            $setting->option_value = $request->option_value_text;
        }
        elseif($request->type == 'boolean') {
            $request->validate(
                [
                    'option_value_boolean' => 'required'
                ],
                [
                    'option_value_boolean.required' => 'Option value is required'
                ]
            );
            $setting->option_value = $request->option_value_boolean;
        }
        elseif($request->type == 'number') {
            $request->validate(
                [
                    'option_value_number' => 'required'
                ],
                [
                    'option_value_number.required' => 'Option value is required'
                ]
            );
            $setting->option_value = $request->option_value_number;
        }
        elseif($request->type == 'yes/no') {
            $request->validate(
                [
                    'option_value_yes_no' => 'required'
                ],
                [
                    'option_value_yes_no.required' => 'Option value is required'
                ]
            );
            $setting->option_value = $request->option_value_yes_no;
        }
        $setting->save(); 
        return redirect()->route('admin.settings.index')->with('success', 'Setting updated successfully.');
    }

    public function delete($id) {
        $setting = AdminSetting::findOrFail($id);
        $setting->delete();
        return redirect()->route('admin.settings.index')->with('success', 'Setting deleted successfully.');
    }
}
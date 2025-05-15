<?php

namespace App\Http\Controllers\Admin;

use \App\Http\Controllers\Controller;
use App\Http\Middleware\IsAdmin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

class AdminCommandsController extends Controller
{
    
    public function import_contacts(Request $request) {

        try {
            Artisan::call('sync:supplier');
            Artisan::call('ContactsTo:Users');
            Artisan::call('Assign:UserToContacts');

            return response()->json([
                'status' => 'success', 
                'message' => 'Contacts imported successfully.'
            ]);
        }
        catch (\Exception $e) {
            return response()->json([
                'status' => 'error', 
                'message' => $e->getMessage()
            ]);
        }

        

    }

    public function update_product_prices () {
        try {
            Artisan::call('Sync:ProductOptions');
            return response()->json([
                'status' => 'success', 
                'message' => 'Product prices updated successfully.'
            ]);
        }
        catch (\Exception $e) {
            return response()->json([
                'status' => 'error', 
                'message' => $e->getMessage()
            ]);
        }
    }

    public function reset_cin7_api_keys () {
        try {
            Artisan::call('reset:cin7_api_keys');
            return redirect()->back()->with('success', 'Cin7 API keys reset successfully.');
        }
        catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }


    public function send_stock_summary_emails () {
        try {
            Artisan::call('report:daily-user-stock-requests');
            return redirect()->back()->with('success', 'Stock summary command executed successfully.');
        }
        catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
    
}
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
    
}
<?php

namespace App\Http\Controllers\Admin;

use \App\Http\Controllers\Controller;
use App\Http\Middleware\IsAdmin;
use Illuminate\Http\Request;

use Artisan;


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
    
}
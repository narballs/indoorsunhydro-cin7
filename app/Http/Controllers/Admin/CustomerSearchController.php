<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Contact;
use DB;
// use App\Models\Order;
// use App\Models\OrderStatus;
// use App\Models\ApiOrder;
// use App\Jobs\SyncContacts;



class CustomerSearchController extends Controller
{
	 function __construct()
    {
        $this->middleware(['role:Admin']);

    }

	public function customerSearch(Request $request) {
		$rows = 6;

		$contacts = DB::table('contacts')->where('firstName', 'LIKE', '%' . $request->value . '%')->get();

		return view('admin.customers.search_results', compact('contacts'));
	}

}
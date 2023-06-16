<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ShippingMethod;
use App\Models\State;
use App\Models\ShippingState;
use App\Jobs\SalesOrders;

class ShippingMethodController extends Controller
{
    function __construct()
    {
        $this->middleware(['role:Admin']);
    }

    public function index()
    {
        $shippingmethods = ShippingMethod::paginate(10);

        
        return view('admin/shipping-methods', compact('shippingmethods'));
    }

    public function edit($id)
    {
        $states = ShippingState::all();
        $shipping_states = ShippingState::where('method_id', $id)->get();

        $shippingmethod = ShippingMethod::where('id', $id)->first();
        return view('admin/edit-shipping', compact('shippingmethod', 'states', 'shipping_states'));
    }

    public function store(Request $request)
    {
        $state_ids = $request->input('states');
        $status = $request->input('status');
        $cost = $request->input('cost');
        $title = $request->input('title');

        if ($request->input('shippingmethodId')) {
            $method_id = $request->input('shippingmethodId');
            $shippingstates = ShippingState::where('method_id', $method_id)->get();
            foreach ($shippingstates as $shippingstate) {
                $shippingstate->delete();
            }
            foreach ($state_ids as $state_id) {
                $shipping_state = new ShippingState;
                $shipping_state->state_id = $state_id;
                $shipping_state->method_id = $method_id;
                $shipping_state->save();
            }

            $shippingmethod = ShippingMethod::where('id', $method_id)->first();
            $shippingmethod->title = $title;
            $shippingmethod->cost = $cost;
            $shippingmethod->status = $status;
            $shippingmethod->save();
        } else {
            $shippingmethod = new ShippingMethod;
            $shippingmethod->title = $title;
            $shippingmethod->cost = $cost;
            $shippingmethod->status = $status;
            $shippingmethod->save();
            $shiping_method_id = $shippingmethod->id;

            foreach ($state_ids as $state_id) {
                $shipping_state = new ShippingState;
                $shipping_state->state_id = $state_id;
                $shipping_state->method_id = $shiping_method_id;
                $shipping_state->save();
            }
        }
        return redirect('admin/shipping-methods');
    }

    public function create()
    {
        $states = ShippingState::all();
        return view('admin/create-shipping-method', compact('states'));
    }

    public function destroy($id)
    {
        $shippingmethod = ShippingMethod::find($id);
        $shippingmethod->delete();
        $shippingstates = ShippingState::where('method_id', $id)->get();
        foreach ($shippingstates as $shippingstate) {
            $shippingstate->delete();
        }
        return redirect('admin/shipping-methods');
    }
}

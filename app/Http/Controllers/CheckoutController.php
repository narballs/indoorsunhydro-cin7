<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\ApiOrder;
use App\Models\ApiOrderItem;
use App\Models\Contact;
use App\Models\State;
use App\Models\PaymentMethod;
use Illuminate\Support\Facades\Auth;
use App\User;
use Illuminate\Support\Facades\Session;

class CheckoutController extends Controller
{
    public function index()
    {
        $user_id = auth()->id();
        $contact = Contact::where('user_id', $user_id)->first();
        if ($contact) {
            $isApproved = $contact->contact_id;
        }
        if (Auth::check() && !empty($isApproved)) {
            $states = State::all();

            $payment_methods = PaymentMethod::with('options')->get();
            $user_address = Contact::where('user_id', $user_id)->first();
            return view('checkout/index2', compact('user_address', 'states', 'payment_methods'));
        } else if (Auth::check() && empty($isApproved)) {
            Session::flash('message', "Your account is being reviewed you can't proceed to checkout, however you can make carts");
            return redirect('/cart/');
        } else {
            Session::flash('message', "You need to login or register to complete checkout");
            return view('user-registration-second');
        }
    }
    public function thankyou($id)
    {
        $order = ApiOrder::where('id', $id)->with('user.contact', 'apiOrderItem.product.options')->first();
        $createdDate = $order->created_at;
        $formatedDate = $createdDate->format('F  j, Y h:i:s A');
        $orderitems = ApiOrderItem::where('order_id', $id)->with('product')->get();
        $count = $orderitems->count();
        $best_products = Product::where('status', '!=', 'Inactive')->orderBy('views', 'DESC')->limit(4)->get();
        $user_id = Auth::id();
        $contact = Contact::where('user_id', $user_id)->first();
        $pricing = $contact->priceColumn;
        return view('checkout/order-received', compact('order', 'orderitems', 'formatedDate', 'count', 'best_products', 'pricing'));
    }
}
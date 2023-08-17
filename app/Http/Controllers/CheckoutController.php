<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\ApiOrder;
use App\Models\ApiOrderItem;
use App\Models\Contact;
use App\Models\Cart;
use App\Models\State;
use App\Models\PaymentMethod;
use App\Models\UsState;
use App\Models\TaxClass;
use Illuminate\Support\Facades\Auth;
use App\User;
use Illuminate\Support\Facades\Session;
use App\Helpers\MailHelper;
use Stripe\Event;
use Stripe\StripeObject;
use Stripe\Webhook;

class CheckoutController extends Controller
{
    public function index(Request $request)
    {
        $user_id = auth()->id();
        $selected_company = Session::get('company');
        if (!$selected_company) {
            Session::flash('message', "Please select a company for which you want to make an order for");
            return redirect('/cart/');
        }
        $contact = Contact::where('user_id', $user_id)
            ->where('status', 1)
            ->where('company', $selected_company)
            ->with('states')
            ->with('cities')
            ->first();
        $cart_items = session()->get('cart');
        $cart_total = 0;
        foreach ($cart_items as $cart_item) {
            $row_price = $cart_item['quantity'] * $cart_item['price'];
            $cart_total = $row_price + $cart_total;
        }

        if ($contact) {
            $isApproved = $contact->contact_id;
        }
        if (Auth::check() && (!empty($contact->contact_id) || !empty($contact->secondary_id)) && $contact->status == 1) {
            // $tax_class = TaxClass::where('is_default', 1)->first();
            $states = UsState::all();
            $payment_methods = PaymentMethod::with('options')->get();
            $contact_id = session()->get('contact_id');
            $user_address = null;
            if ($contact->secondary_id) {
                $parent_id = Contact::where('secondary_id', $contact->secondary_id)->first();
                $user_address = Contact::where('user_id', $user_id)->where('secondary_id', $parent_id->secondary_id)->first();
            } else {
                $user_address = Contact::where('user_id', $user_id)->where('contact_id', $contact_id)->orWhere('contact_id' , $contact->contact_id)->first();
            }
            $tax_class = TaxClass::where('name', $user_address->tax_class)->first();
            $tax_class_none = TaxClass::where('name', 'none')->first();
            return view('checkout/index2', compact(
                'user_address',
                'states',
                'payment_methods',
                'tax_class',
                'contact_id',
                'tax_class_none'
            ));
        } else {
            return redirect()->back()->with('message', 'Your account is disabled. You can not proceed with checkout. Please contact us.');
        }
    }


    public function thankyou(Request $request , $id)
    {

        $stripe = new \Stripe\StripeClient(config('services.stripe.secret'));
        $checkout_session = $stripe->checkout->sessions->retrieve(
            $request->session_id,
            []
        );
        if (!empty($checkout_session)) {
            $get_order = ApiOrder::where('id', $id)->first();
            if ($checkout_session->payment_status == 'paid') {
                $get_order->stage = 'paid';
                $get_order->save();
            } else {
                $get_order->stage =  $checkout_session->payment_status;
                $get_order->save();
            }
        }
        $order = ApiOrder::where('id', $id)
            ->with(
                'user.contact',
                'apiOrderItem.product.options',
                'texClasses'
            )->first();
        $order_contact = Contact::where('contact_id', $order->memberId)->first();
        $createdDate = $order->created_at;
        $formatedDate = $createdDate->format('F  j, Y h:i:s A');
        $orderitems = ApiOrderItem::where('order_id', $id)->with('product')->get();
        $count = $orderitems->count();
        $best_products = Product::where('status', '!=', 'Inactive')->orderBy('views', 'DESC')->limit(4)->get();
        $user_id = Auth::id();
        Cart::where('user_id', $user_id)->where('is_active', 1)->delete();

        Session::forget('cart');
        Session::forget('cart_hash');

        $contact = Contact::where('user_id', $user_id)->first();

        $pricing = $contact->priceColumn;
        return view(
            'checkout/order-received',
            compact(
                'order',
                'orderitems',
                'order_contact',
                'formatedDate',
                'count',
                'best_products',
                'pricing'
            )
        );
    }
    public function webhook(Request $request) {
        $stripe = new \Stripe\StripeClient(config('services.stripe.secret'));
        $event = $stripe->events->retrieve(
            'evt_1Nfh9kGNTgOo1VJWYONFRSqw',
            []
        );
        $order_id = $event->data->object->metadata->order_id;
        dd($order_id);
        return response()->json($event);
    }
}

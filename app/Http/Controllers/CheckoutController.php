<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\OrderComment;
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
use Carbon\Carbon;
use Illuminate\Support\Facades\Session;
use App\Helpers\MailHelper;
use Stripe\Event;
use Stripe\StripeObject;
use Stripe\Webhook;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Helpers\SettingHelper;
use App\Helpers\UserHelper;
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
        $payload = $request->getContent();
        $stripeSignature = $request->header('Stripe-Signature');
        $webhookSecret = config('services.stripe.webhook_secret');
        
        // try {
        //     $event = Webhook::constructEvent($payload, $signature, config('services.stripe.webhook_secret'));
        //     // dd($event);
        //     // Handle the event based on its type
        //     switch ($event->type) {
        //         case 'checkout.session.completed':
        //             // Handle checkout session completed event
        //             break;
        //         case 'payment_intent.succeeded':
        //             // Handle payment intent succeeded event
        //             break;
        //         // Add more cases for other event types
        //     }

        //     return response()->json(['status' => 'success'], Response::HTTP_OK);
        // } catch (\Exception $e) {
        //     Log::error($e->getMessage());
        //     return response()->json(['status' => 'error', 'message' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        // }
        try {
            $event = Webhook::constructEvent($payload, $stripeSignature, $webhookSecret);
        } catch (\Stripe\Exception\SignatureVerificationException $e) {
            Log::error($e->getMessage());
            return response()->json(['error' => 'Invalid webhook signature'], 400);
        }

        switch ($event->type) {
            case 'charge.succeeded':
                $stripe = new \Stripe\StripeClient(config('services.stripe.secret'));
                $payment_succeded = $stripe->events->retrieve(
                    $event->id,
                    []
                );
                $dateCreated = Carbon::now();
                $createdDate = Carbon::now();
                $session_contact_id = Session::get('contact_id');
                $active_contact_id = null;
                $is_primary = null;
                if (!empty($session_contact_id)) {
                    $contact = Contact::where('contact_id', $session_contact_id)->first();
                    if ($contact) {
                        $active_contact_id = $contact->contact_id;
                    } else {
                        $contact = Contact::where('secondary_id', $session_contact_id)->first();
                        $active_contact_id = $contact->parent_id;
                    }
                }
                if($active_contact_id) {
                    $is_primary = Contact::where('contact_id', $session_contact_id)->first();
                }
                $order_id = $payment_succeded->data->object->metadata->order_id;


                $currentOrder = ApiOrder::where('id', $order_id)->first();
                if ($payment_succeded->data->object->paid == true) {
                    $currentOrder->stage = 'paid';
                    $currentOrder->save();
                } else {
                    $currentOrder->stage =  $payment_succeded->data->object->paid;
                    $currentOrder->save();
                }

                $order_comment = new OrderComment;
                $order_comment->order_id = $order_id;
                $order_comment->comment = 'Order Placed through Stripe';
                $order_comment->save();


               
                $order_items = ApiOrderItem::with('order.texClasses', 'product.options')
                ->where('order_id', $order_id)
                ->get();
                $contact = Contact::where('user_id', auth()->id())->first();
                $user_email = Auth::user();
                $count = $order_items->count();
                $best_products = Product::where('status', '!=', 'Inactive')->orderBy('views', 'DESC')->limit(4)->get();
                $addresses = [
                    'billing_address' => [
                        'firstName' => $contact->firstName,
                        'lastName' => $contact->lastName,
                        'address1' => $contact->address1,
                        'address2' => $contact->address2,
                        'city' => $contact->city,
                        'state' => $contact->state,
                        'zip' => $contact->postCode,
                        'mobile' => $contact->mobile,
                        'phone' => $contact->phone,
                    ],
                    'shipping_address' => [
                        'postalAddress1' => $contact->postalAddress1,
                        'postalAddress2' => $contact->postalAddress2,
                        'phone' => $contact->postalCity,
                        'postalCity' => $contact->postalState,
                        'postalState' => $contact->postalPostCode,
                        'postalPostCode' => $contact->postalPostCode
                    ],
                    'best_product' => $best_products,
                    'user_email' =>   $user_email,
                    'currentOrder' => $currentOrder,
                    'count' => $count,
                    'order_id' => $order_id,
                ];

                $name = $contact->firstName;
                $email =  $contact->email;
                $reference  =  $currentOrder->reference;
                $template = 'emails.admin-order-received';
                $admin_users = DB::table('model_has_roles')->where('role_id', 1)->pluck('model_id');

                $admin_users = $admin_users->toArray();

                $users_with_role_admin = User::select("email")
                    ->whereIn('id', $admin_users)
                    ->get();

                $data = [
                    'name' =>  $name,
                    'email' => $email,
                    'subject' => 'New order received',
                    'reference' => $reference,
                    'order_items' => $order_items,
                    'dateCreated' => $dateCreated,
                    'addresses' => $addresses,
                    'best_product' => $best_products,
                    'currentOrder' => $currentOrder,
                    'count' => $count,
                    'from' => SettingHelper::getSetting('noreply_email_address')
                ];

                if (!empty($users_with_role_admin)) {
                    foreach ($users_with_role_admin as $role_admin) {
                        $subject = 'New order received';
                        $adminTemplate = 'emails.admin-order-received';
                        $data['email'] = $role_admin->email;
                        MailHelper::sendMailNotification('emails.admin-order-received', $data);
                    }
                }
                $parent_email = Contact::where('contact_id', $active_contact_id)->first();
        
                $data['subject'] = 'Your order has been received';
                $data['email'] = $email;
                MailHelper::sendMailNotification('emails.admin-order-received', $data);
        
        
                $email_sent_to_users = [];
                $user = User::where('id',  Auth::id())->first();
                $all_ids = UserHelper::getAllMemberIds($user);
                $all_members = Contact::whereIn('id', $all_ids)->get();
                foreach ($all_members as $member) {
                    $member_user = User::find($member->user_id);
                    if (!empty($member_user) && $member_user->hasRole(['Order Approver'])) {
                        if (isset($email_sent_to_users[$member_user->id])) {
                            continue;
                        }
        
                        $email_sent_to_users[$member_user->id] = $member_user;
                        $data['name'] = $member_user->firstName;
                        $data['subject'] = 'New order awaiting approval';
                        $data['email'] = $member_user->email;
                        MailHelper::sendMailNotification('emails.user-order-received', $data);
                    }
                }
                // Handle payment success event
                break;
            case 'invoice.payment_failed':
                // Handle payment failure event
            break;
            // Add more cases for other event types you want to handle
        }
        
        return response()->json(['status' => 'success']);
    }

    public function event() {
        $user = User::where('id',  Auth::id())->first();
        $all_ids = UserHelper::getAllMemberIds($user);
        $all_members = Contact::whereIn('id', $all_ids)->get();
        foreach ($all_members as $member) {

            $member_user = User::find($member->user_id);
            dd($member_user);
        }
    }
}

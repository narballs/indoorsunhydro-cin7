<?php
namespace App\Http\Controllers;

use App\Mail\NewsletterMail;
use App\Models\NewsletterSubscriberTemplate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\NewsletterSubscription;
use App\Models\NewsletterTemplate;
use App\Models\SalePaymentOrderItem;
use App\Models\SalePayments;
use App\Models\SubscriberEmailList;
use App\Models\SubscriberList;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Twilio\Rest\Events\V1\SubscriptionList;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\HeadingRowImport;

class SalePaymentsController extends Controller
{
    function __construct()
    {
        $this->middleware(['role:Sale Payments']);
    }


    // sales payments


    

    public function sale_payments(Request $request) {
        $search = $request->get('search_by_name_email');
        $payment_method = $request->get('payment_method');
        $date_from = $request->get('date_from');
        $date_to = $request->get('date_to');


        // Format the dates to match the format stored in the database (ISO 8601)
        $modify_date_from = !empty($date_from) ? Carbon::parse($date_from)->toIso8601String() : null;
        $modify_date_to = !empty($date_to) ? Carbon::parse($date_to)->toIso8601String() : null;
        
        $sale_payments_query  = SalePayments::orderBy('created_at' , 'Desc');

        if (!empty($search)) {
            $sale_payments_query = $sale_payments_query->where('customer_first_name', 'LIKE', '%' . $search . '%')
                ->orWhere('customer_last_name', 'like', '%' . $search . '%')
                ->orWhere('email', 'like', '%' . $search . '%');
        }

        if (!empty($payment_method)) {
            $sale_payments_query = $sale_payments_query->where('method', $payment_method);
        }

        if (!empty($date_from) && empty($date_to)) {
            $sale_payments_query = $sale_payments_query->whereDate('paymentDate', '=', $modify_date_from);
        }

        if (!empty($date_to) && empty($date_from)) {
            $sale_payments_query = $sale_payments_query->whereDate('paymentDate', '=', $modify_date_to);
        }

        if (!empty($date_from) && !empty($date_to)) {
            $sale_payments_query = $sale_payments_query->whereBetween(DB::raw('DATE(paymentDate)'), [$modify_date_from, $modify_date_to]);
        }


        $sale_payments = $sale_payments_query->paginate(10)->withQueryString();
        return view('newsletter_layout/sale-payments/index', compact('sale_payments' , 'search' , 'payment_method' , 'date_from' , 'date_to'));
    }

    public function sale_payments_show($order_id) {
        $sale_payment = SalePayments::where('orderId', $order_id)->first();
        $api_orders = SalePaymentOrderItem::where('orderId',$order_id)->get();
        if (count($api_orders) > 0) {
            return view('newsletter_layout/sale-payments/show', compact('sale_payment', 'api_orders' ));
        } else {
            return redirect()->back()->with('error', 'Order Detail not found !');
        }
    }
}
<?php
namespace App\Http\Controllers;

use App\Exports\PayoutBalanceExport;
use App\Mail\NewsletterMail;
use App\Models\NewsletterSubscriberTemplate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\NewsletterSubscription;
use App\Models\NewsletterTemplate;
use App\Models\Payout;
use App\Models\PayoutBalance;
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

class PayoutController extends Controller
{
    function __construct()
    {
        $this->middleware(['role:Payouts']);
    }


    // sales payments


    

    public function payouts(Request $request)
    {
        $payouts_query = Payout::with('payoutBalances')->orderBy('arrive_date', 'desc');

        // Search by payout ID or destination name
        if ($request->has('search') && !empty($request->search)) {
            $payouts_query->where(function ($query) use ($request) {
                $query->where('amount', 'like', '%' . $request->search . '%');
            });
        }

        // Date filtering
        if ($request->has('last_14_days')) {
            $payouts_query->where('arrive_date', '>=', now()->subDays(14));
        } elseif ($request->has('this_month')) {
            $payouts_query->whereYear('arrive_date', now()->year)
                        ->whereMonth('arrive_date', now()->month);
        } elseif ($request->has('last_month')) {
            $payouts_query->whereYear('arrive_date', now()->subMonth()->year)
                        ->whereMonth('arrive_date', now()->subMonth()->month);
        } elseif ($request->has('all_time')) {
            // No filter needed, show all records
        }

        // Paginate results
        $payouts = $payouts_query->get();

        return view('newsletter_layout.payouts.index', compact('payouts'));
    }



    public function payouts_details($id) {
        $payout_balances = PayoutBalance::where('payout_id', $id)->get(); 
        return view('newsletter_layout.payouts.details', compact('payout_balances' , 'id'));
    }


    public function transactions_export(Request $request,  $id) {
        $payout = Payout::findOrFail($id);

        $hide_radar = $request->boolean('hide_radar', false);
        $hide_chargebacks = $request->boolean('hide_Chargeback', false);

        // Set file name dynamically
        $fileName = "Payout_Transactions_{$id}.xlsx";

        // Return the Excel file
        return Excel::download(new PayoutBalanceExport($id, $hide_radar, $hide_chargebacks), $fileName);


    }
}
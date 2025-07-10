<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SalesReport;
use Stripe\Stripe;
use Stripe\Charge;
use Carbon\Carbon;
use App\Exports\SalesReportExport;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;

class SalesReportController extends Controller
{
    
    public function index(Request $request)
    {
        $query = SalesReport::query();

        // Optional: Apply filters
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('from') && $request->filled('to')) {
            $query->whereBetween('transaction_date', [$request->from, $request->to]);
        }

        // Sort by transaction date (most recent first)
        $reports = $query->orderBy('transaction_date', 'desc')->paginate(25);

        // Totals (filtered)
        $filteredTotalAmount = (clone $query)->sum('amount');
        $filteredPartialRefundAmount = (clone $query)->sum('partial_refund_amount');

        // Totals (overall — no filter)
        $overallTotalAmount = SalesReport::sum('amount');
        $overallPartialRefundAmount = SalesReport::sum('partial_refund_amount');


        return view('admin.sales_report.index', compact(
            'reports',
            'filteredTotalAmount',
            'filteredPartialRefundAmount',
            'overallTotalAmount',
            'overallPartialRefundAmount'
        ));
    }


    public function importStripeTransactions()
    {
        \Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));

        $startingAfter = null;
        $hasMore = true;

        while ($hasMore) {
            $params = [
                'limit' => 100,
                'expand' => ['data.refunds', 'data.dispute'],
            ];

            if ($startingAfter) {
                $params['starting_after'] = $startingAfter;
            }

            $charges = \Stripe\Charge::all($params);
            $chargesData = $charges->data;

            if (count($chargesData) === 0) {
                break;
            }

            foreach ($chargesData as $charge) {
                $status = $charge->status;
                $refundDate = null;
                $partialRefundReason = null;
                $partialRefundAmount = 0;
                $hasFullRefund = false;

                // ✅ Handle partial refunds (skip full refunds)
                if (
                    $charge->refunded &&
                    isset($charge->refunds->data) &&
                    count($charge->refunds->data) > 0
                ) {
                    foreach ($charge->refunds->data as $refund) {
                        if ($refund->amount < $charge->amount) {
                            $partialRefundReason = $refund->reason ?? 'Partial refund';
                            $refundDate = \Carbon\Carbon::createFromTimestamp($refund->created);
                            $partialRefundAmount += $refund->amount / 100;
                            $status = 'partially_refunded';
                        } elseif ($refund->amount == $charge->amount) {
                            $hasFullRefund = true;
                        }
                    }
                }

                // ❌ Skip fully refunded charges
                if ($hasFullRefund) {
                    continue;
                }

                // ✅ Handle disputes
                if ($charge->dispute && isset($charge->dispute->status)) {
                    if ($charge->dispute->status === 'lost') {
                        $status = 'dispute_lost';
                    }
                }

                // ✅ Handle partial payments
                if (isset($charge->metadata->expected_amount)) {
                    $expected = (float) $charge->metadata->expected_amount;
                    $actual = $charge->amount / 100;

                    if ($actual < $expected) {
                        $status = 'partial_paid';
                    }
                }

                // ✅ Skip if already imported
                if (SalesReport::where('stripe_id', $charge->id)->exists()) {
                    continue;
                }

                // ✅ Save to database
                SalesReport::create([
                    'order_id' => $charge->metadata->order_id ?? null,
                    'stripe_id' => $charge->id,
                    'status' => $status,
                    'amount' => $charge->amount / 100,
                    'partial_refund_amount' => $partialRefundAmount > 0 ? $partialRefundAmount : null,
                    'currency' => $charge->currency,
                    'customer_email' => $charge->billing_details->email ?? null,
                    'partial_refund_reason' => $partialRefundReason,
                    'refund_date' => $refundDate,
                    'payment_method' => $charge->payment_method_details->type ?? null,
                    'transaction_date' => \Carbon\Carbon::createFromTimestamp($charge->created),
                ]);
            }

            $startingAfter = end($chargesData)->id;
            $hasMore = $charges->has_more;
        }

        return back()->with('success', 'All Stripe transactions imported successfully.');
    }


    // public function export(Request $request, $type)
    // {
    //     $filename = 'sales_report_' . now()->format('Ymd_His');

    //     // Build query with filters
    //     $query = SalesReport::query();

    //     if ($request->filled('status')) {
    //         $query->where('status', $request->status);
    //     }

    //     if ($request->filled('from') && $request->filled('to')) {
    //         $query->whereBetween('transaction_date', [$request->from, $request->to]);
    //     }

    //     $filteredData = $query->get();

    //     // Export to CSV or Excel
    //     if (in_array($type, ['csv', 'xlsx'])) {
    //         return Excel::download(new SalesReportExport($filteredData), "$filename.$type");
    //     }

    //     // Export to PDF
    //     if ($type === 'pdf') {
    //         $pdf = Pdf::loadView('admin.sales_report.pdf', ['data' => $filteredData])
    //             ->setPaper('A4', 'landscape');
    //         return $pdf->download("$filename.pdf");
    //     }

    //     return back();
    // }


    public function export(Request $request, $type)
    {
        $filename = 'sales_report_' . now()->format('Ymd_His');

        // Build query with filters
        $query = SalesReport::query();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('from') && $request->filled('to')) {
            $query->whereBetween('transaction_date', [$request->from, $request->to]);
        }

        $filteredData = $query->get();

        // ✅ Totals based on filters
        $totalAmount = $filteredData->sum('amount');
        $totalPartialRefund = $filteredData->sum('partial_refund_amount');

        // Export to CSV or Excel
        if (in_array($type, ['csv', 'xlsx'])) {
            return Excel::download(
                new SalesReportExport($filteredData, $totalAmount, $totalPartialRefund),
                "$filename.$type"
            );
        }

        // Export to PDF
        if ($type === 'pdf') {
            $pdf = Pdf::loadView('admin.sales_report.pdf', [
                    'data' => $filteredData,
                    'totalAmount' => $totalAmount,
                    'totalPartialRefund' => $totalPartialRefund
                ])
                ->setPaper('A4', 'landscape');

            return $pdf->download("$filename.pdf");
        }

        return back();
    }




}

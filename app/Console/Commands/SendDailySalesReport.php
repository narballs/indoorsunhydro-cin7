<?php

namespace App\Console\Commands;

use App\Models\SalesReport;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;
use App\Models\SalesReportSetting;
use App\Models\SpecificAdminNotification;

class SendDailySalesReport extends Command
{
    protected $signature = 'sales:send-daily-report';
    protected $description = 'Send daily sales report email for previous day';

    public function handle()
    {
        // Get email recipients from your settings table
        // $settings = SalesReportSetting::first();
        // if (!$settings || empty($settings->emails)) {
        //     $this->error('No email recipients found in settings.');
        //     return;
        // }

        

        // Decode emails and extract valid email addresses from the 'value' key
        // $emailsArray = json_decode($settings->emails, true);
        // $emails = collect($emailsArray)
        //     ->pluck('value')
        //     ->filter(function ($email) {
        //         return filter_var($email, FILTER_VALIDATE_EMAIL);
        //     })
        //     ->all();

        $emails = SpecificAdminNotification::where('receive_accounting_reports', true)
            ->pluck('email')
            ->filter(function ($email) {
                return filter_var($email, FILTER_VALIDATE_EMAIL);
            })
            ->unique()
            ->values()
            ->all();

        // Step 2: Ensure we have at least one valid email
        if (empty($emails)) {
            $this->error('No valid email recipients found after filtering.');
            return;
        }

        // dd($emails); // Debugging line to check emails

        // Make sure there is at least one valid email
        if (empty($emails)) {
            $this->error('No valid email recipients found after filtering.');
            return;
        }

        // Get previous day's date range
        $start = Carbon::yesterday()->startOfDay();
        $end = Carbon::yesterday()->endOfDay();

        // Fetch transactions for previous day
        $sales = SalesReport::whereBetween('transaction_date', [$start, $end])->get();

        // Do not send email if there are no sales
        if ($sales->isEmpty()) {
            $this->info('No sales for the previous day. No email sent.');
            return;
        }

        // Generate CSV file
        $csvFileName = 'sales_report_' . $start->format('Ymd') . '.csv';
        $csvPath = storage_path('app/' . $csvFileName);

        $handle = fopen($csvPath, 'w');
        if ($handle === false) {
            $this->error('Could not create CSV file.');
            return;
        }

        // CSV header
        fputcsv($handle, [
            'Order ID',
            'Stripe ID',
            'Amount',
            'Partially Refund Amount',
            'Customer Email',
            'Status',
            'Refund Date',
            'Payment Method',
            'Transaction Date'
        ]);

        foreach ($sales as $sale) {
            fputcsv($handle, [
                $sale->order_id,
                $sale->stripe_id,
                $sale->amount !== null ? '$' . number_format($sale->amount, 2) : '-',
                $sale->partially_refund_amount !== null ? '$' . number_format($sale->partially_refund_amount, 2) : '-',
                $sale->customer_email ?? '',
                ucfirst(str_replace('_', ' ', $sale->status)),
                $sale->refund_date ? Carbon::parse($sale->refund_date)->format('Y-m-d H:i:s') : '',
                $sale->payment_method ?? '',
                $sale->transaction_date ? Carbon::parse($sale->transaction_date)->format('Y-m-d H:i:s') : '',
            ]);
        }
        fclose($handle);

        // Send email with CSV attached to ALL recipients as TO
        Mail::raw('Please find the attached daily sales report as a CSV file.', function ($message) use ($emails, $start, $csvPath, $csvFileName) {
            $message->from('noreply@indoorsunhydro.com', 'Indoorsun Hydro')
                ->to($emails) // Pass array directly!
                ->subject('Daily Sales Report for ' . $start->format('Y-m-d'))
                ->attach($csvPath, [
                    'as' => $csvFileName,
                    'mime' => 'text/csv',
                ]);
        });

        // Optionally, delete the CSV file after sending
        unlink($csvPath);

        $this->info('Sales report sent successfully!');
    }
}

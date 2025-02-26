<?php

namespace App\Console\Commands;

use App\Models\Payout;
use App\Models\PayoutBalance;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class Payouts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sync:payouts';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync payouts from Stripe to the database';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */

     public function handle()
    {
         try {
             $stripe = new \Stripe\StripeClient(config('services.stripe.secret'));
     
             // Paginate through all payouts
             $hasMore = true;
             $startingAfter = null;
     
             while ($hasMore) {
                 // Build query parameters dynamically (remove starting_after if null)
                 $params = ['limit' => 100];
                 if ($startingAfter) {
                     $params['starting_after'] = $startingAfter;
                 }
     
                 $payoutsResponse = $stripe->payouts->all($params);
     
                 foreach ($payoutsResponse->data as $payout) {
                     try {
                         $payout = $stripe->payouts->retrieve($payout->id);
     
                         // Check if the payout already exists
                         $payment = Payout::firstOrCreate(
                             ['payout_id' => $payout->id],
                             [
                                 'amount' => $payout->amount / 100, // Convert from cents
                                 'status' => $payout->status,
                                 'type' => $payout->type,
                                 'method' => $payout->method,
                                 'source_type' => $payout->source_type,
                                 'currency' => $payout->currency,
                                 'destination_name' => $payout->destination_name ?? null,
                                 'payout_created' => date('Y-m-d H:i:s', $payout->created),
                                 'arrive_date' => date('Y-m-d H:i:s', $payout->arrival_date),
                             ]
                         );
     
                         // Paginate through balance transactions
                         $btHasMore = true;
                         $btStartingAfter = null;
     
                         while ($btHasMore) {
                             $btParams = [
                                 'payout' => $payout->id,
                                 'limit' => 100
                             ];
                             if ($btStartingAfter) {
                                 $btParams['starting_after'] = $btStartingAfter;
                             }
     
                             $balanceTransactions = $stripe->balanceTransactions->all($btParams);
     
                             foreach ($balanceTransactions->data as $bt) {
                                 try {
                                     if ($bt->type === 'payout') {
                                         continue;
                                     }
     
                                     $type = $bt->type;
                                     $id = $bt->id;
                                     $created = date('Y-m-d H:i:s', $bt->created);
                                     $description = $bt->description ?? '';
                                     $amount = $bt->amount / 100; // Convert from cents
                                     $currency = $bt->currency;
                                     $fees = $bt->fee / 100;
                                     $net = $bt->net / 100;
                                     $convertedAmount = $amount;
                                     $convertedCurrency = $currency;
     
                                     // Initialize customer and order details
                                     $customerId = '';
                                     $customerEmail = '';
                                     $customerName = '';
                                     $orderId = '';
     
                                     if ($bt->source && $type === 'charge') {
                                         try {
                                             $charge = $stripe->charges->retrieve($bt->source);
                                             if (!empty($charge->customer)) {
                                                 $customer = $stripe->customers->retrieve($charge->customer);
                                                 $customerEmail = $customer->email ?? '';
                                                 $customerName = $customer->name ?? '';
                                             }
                                             $orderId = $charge->metadata->order_id ?? '';
                                         } catch (\Exception $e) {
                                             Log::error("Error retrieving charge details for transaction {$bt->id}: " . $e->getMessage());
                                         }
                                     }
     
                                     // Check if the balance transaction already exists
                                     PayoutBalance::firstOrCreate(
                                         ['payout_balance_id' => $bt->id],
                                         [
                                             'payout_id' => $payment->id,
                                             'order_id' => $orderId,
                                             'customer_name' => $customerName,
                                             'customer_email' => $customerEmail,
                                             'currency' => $currency,
                                             'type' => $type,
                                             'description' => $description,
                                             'amount' => $amount,
                                             'converted_amount' => $convertedAmount,
                                             'fees' => $fees,
                                             'net' => $net,
                                             'charge_created' => $created,
                                         ]
                                     );
                                 } catch (\Exception $e) {
                                     Log::error("Error processing balance transaction {$bt->id}: " . $e->getMessage());
                                 }
                             }
     
                             // Check if there are more balance transactions to fetch
                             $btHasMore = $balanceTransactions->has_more;
                             if ($btHasMore) {
                                 $btStartingAfter = end($balanceTransactions->data)->id;
                             }
                         }
     
                     } catch (\Exception $e) {
                         Log::error("Error processing payout {$payout->id}: " . $e->getMessage());
                     }
                 }
     
                 // Check if there are more payouts to fetch
                 $hasMore = $payoutsResponse->has_more;
                 if ($hasMore) {
                     $startingAfter = end($payoutsResponse->data)->id;
                 }
             }
     
             $this->info('Payouts synced successfully!');
     
         } catch (\Exception $e) {
             Log::error("Error connecting to Stripe: " . $e->getMessage());
         }
    }
     


}

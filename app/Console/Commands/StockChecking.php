<?php

namespace App\Console\Commands;

use App\Helpers\SettingHelper;
use App\Helpers\UtilHelper;
use App\Models\AdminSetting;
use App\Models\ApiErrorLog;
use App\Models\ApiSyncLog;
use App\Models\InventoryLocation;
use App\Models\Product;
use App\Models\ProductOption;
use App\Models\ProductStock;
use Carbon\Carbon;
use Illuminate\Console\Command;

class StockChecking extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'stock:checking';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Stock Checking';

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
        $admin_setting = AdminSetting::where('option_name', 'stock_checking')->first();
        if (empty($admin_setting) || strtolower($admin_setting->option_value) != 'yes') {
            $this->error('Stock Api settings is off');
            return false;
        }

        $cin7_auth_username = SettingHelper::getSetting('cin7_auth_username');
        $cin7_auth_password = SettingHelper::getSetting('cin7_auth_password');
        $inactive_inventory_locations = InventoryLocation::where('status', 0)->pluck('cin7_branch_id')->toArray();
        $skip_branches = $inactive_inventory_locations;
        $current_date = Carbon::now()->setTimezone('UTC')->format('Y-m-d H:i:s');
        $stock_sync_log = ApiSyncLog::where('end_point', 'Stock End Point')->first();
        if (empty($stock_sync_log)) {
            $stock_sync_log = new ApiSyncLog();
            $stock_sync_log->end_point = 'Stock End Point';
            $stock_sync_log->description = 'Stock Updated';
            $stock_sync_log->record_count = 0;
            $stock_sync_log->last_synced = $current_date;
            $stock_sync_log->save();
        }

        $last_product_synced_date = $stock_sync_log->last_synced;

        $product_stock_sync_raw_date = Carbon::parse($last_product_synced_date);
        $product_stock_sync_date = $product_stock_sync_raw_date->format('Y-m-d');
        $product_stock_sync_time = $product_stock_sync_raw_date->format('H:i:s');
        $api_formatted_product_stock_sync_date = $product_stock_sync_date . 'T' . $product_stock_sync_time . 'Z';

        $client2 = new \GuzzleHttp\Client();

        // Find total stock pages
        $total_stock_pages = 200;
        $total_record_count = 0;
        $stock_api_url = 'https://api.cin7.com/api/v1/Stock?where=modifiedDate>='. $api_formatted_product_stock_sync_date . '&rows=250';

        // for ($i = 1; $i <= $total_stock_pages; $i++) {
            $this->info('Processing page#');
            try {

                $res = $client2->request(
                    'GET', 
                    $stock_api_url, 
                    [
                        'auth' => [
                            $cin7_auth_username,
                            $cin7_auth_password
                        ]
                        
                    ]
                );

                UtilHelper::saveDailyApiLog('auto_update_stock');


                $api_product_stock = $res->getBody()->getContents();
            
                $api_product_stock = json_decode($api_product_stock);
                $record_count = count($api_product_stock);
                $total_record_count += $record_count; 
                $this->info('Record Count per page #--------------------------' .$record_count);


                $this->info('Record Count => ' . $record_count);
                
                // if ($record_count < 1 || empty($record_count)) {
                //     $this->info('----------------break-----------------');
                //     break;
                // }
            }
            catch (\Exception $e) {
                $msg = $e->getMessage();
                $errorlog = new ApiErrorLog();
                $errorlog->payload = $e->getMessage();
                $errorlog->exception = $e->getCode();
                $errorlog->save();
            }
            $stock_available = 0;
            $stock_on_hand = 0;
            $stock_available_product_option = 0;
            $stock_on_hand_product_option = 0;
            if ($api_product_stock) {
                // foreach ($api_product_stock as $product_stock) {
                //     $product_id = $product_stock->productId;
                //     $option_id = $product_stock->productOptionId;
                //     $product = Product::where('product_id', $product_id)->first();
                //     if (!empty($product)) {
                //         $stock_updated = UtilHelper::updateProductStock($product, $option_id);
                //         if ($stock_updated) {
                //             $this->info('Stock Updated for product#' . $product_id);
                //         }
                //     }
                // }

                foreach ($api_product_stock as $product_option_stock) {
                    if (!in_array($product_option_stock->branchId, $skip_branches)) {
                        $product_stock = ProductStock::where('product_id' ,  $product_option_stock->productId)
                        ->where('branch_id' , $product_option_stock->branchId)
                        ->where('option_id' , $product_option_stock->productOptionId)
                        ->first();
                        $stock_available += $product_option_stock->available;
                        if (!empty($product_stock)) {
                            $product_stock->available_stock = $stock_available;
                            $product_stock->save();
                            $this->info('Stock Updated for product option#' . $product_stock->option_id);
                        } else {
                            $product_stock = ProductStock::create([
                                'available_stock' => $stock_available,
                                'branch_id' => $product_option_stock->branchId,
                                'product_id' => $product_option_stock->productId,
                                'branch_name' => $product_option_stock->branchName,
                                'option_id' => $product_option_stock->productOptionId
                            ]);
                            $this->info('Stock Created for product option#' . $product_stock->option_id);
                        }
                    }
                }

                foreach ($api_product_stock as $product_option_stock) {
                    $single_product_option = ProductOption::with('price')->where('option_id',$product_option_stock->productOptionId)->first();
                    if (!in_array($product_option_stock->branchId, $skip_branches)) {
                        $stock_available_product_option += $product_option_stock->available;
                        $stock_on_hand_product_option += $product_option_stock->stockOnHand;
                        if (!empty($single_product_option)) {
                            $single_product_option->stockAvailable = $stock_available_product_option;
                            $single_product_option->stockOnHand = $stock_on_hand_product_option;
                            $single_product_option->save();
                            $this->info('Stock Updated for product option#' . $single_product_option->option_id);
                        }
                    }
                }
            }

            
        // }

        $stock_sync_log->last_synced = $current_date;
        $stock_sync_log->record_count = $total_record_count;
        $stock_sync_log->save();

        $this->info('Total Record Count#' . $total_record_count);
        $this->info('------------------------------');
        $this->info('-------------Finished------------------');

    }
}

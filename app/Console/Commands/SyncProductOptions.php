<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use GuzzleHttp\Client;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductOption;
use App\Models\Brand;
use App\Models\Pricingnew;
use Illuminate\Support\Str;
use App\Models\ApiErrorLog;
use App\Models\ApiSyncLog;
use App\Models\AdminSetting;

use Carbon\Carbon;

use App\Helpers\UtilHelper;
use App\Helpers\SettingHelper;

class SyncProductOptions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'Sync:ProductOptions';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
        
        $admin_setting = AdminSetting::where('option_name', 'sync_product_options')->first();
        if (empty($admin_setting) || $admin_setting->option_value != 'Yes') {
            $this->error('Product Options setting is off');
            return false;
        }

        $current_date = Carbon::now()->setTimezone('UTC')->format('Y-m-d H:i:s');
        $product_option_sync_log = ApiSyncLog::where('end_point', 'https://api.cin7.com/api/v1/ProductOptions')->first();
        if (empty($product_option_sync_log)) {
            $product_option_sync_log = new ApiSyncLog();
            $product_option_sync_log->end_point = 'https://api.cin7.com/api/v1/ProductOptions';
            $product_option_sync_log->description = 'Products Options Sync';
            $product_option_sync_log->record_count = 0;
            $product_option_sync_log->last_synced = $current_date;
            $product_option_sync_log->save();
        }

        $last_product_option_synced_date = $product_option_sync_log->last_synced;

        $product_sync_raw_date = Carbon::parse($last_product_option_synced_date);
        $product_options_sync_date = $product_sync_raw_date->format('Y-m-d');
        $product_options_sync_time = $product_sync_raw_date->format('H:i:s');
        $api_formatted_product_options_sync_date = $product_options_sync_date . 'T' . $product_options_sync_time . 'Z';

        $client2 = new \GuzzleHttp\Client();
        $total_record_count = 0;

        $client = new \GuzzleHttp\Client();

        $cin7_auth_username = SettingHelper::getSetting('cin7_auth_username');
        $cin7_auth_password = SettingHelper::getSetting('cin7_auth_password');

        $retail_price_column = SettingHelper::getSetting('retail_price_column');


        $client2 = new \GuzzleHttp\Client();

        // Find total category pages
        $total_products_pages = 200;

        $product_options_api_url = 'https://api.cin7.com/api/v1/ProductOptions?where=ModifiedDate>='. $api_formatted_product_options_sync_date . '&rows=250';

        for ($i = 1; $i <= $total_products_pages; $i++) {
            $this->info('Processing page#' . $i);
            try {

                $res = $client2->request(
                    'GET', 
                    $product_options_api_url . '&page=' . $i, 
                    [
                        'auth' => [
                            $cin7_auth_username,
                            $cin7_auth_password
                        ]
                        
                    ]
                );

                UtilHelper::saveDailyApiLog('sync_product_options');
                $api_product_options = $res->getBody()->getContents();
                $api_product_options = json_decode($api_product_options);
                $record_count = count($api_product_options);
                $total_record_count += $record_count; 
                $this->info('Record Count per page #--------------------------' .$record_count);


                $this->info('Record Count => ' . $record_count);
                
                if ($record_count < 1 || empty($record_count)) {
                    $this->info('----------------break-----------------');
                    break;
                }
            }
            catch (\Exception $e) {
                $msg = $e->getMessage();
                $errorlog = new ApiErrorLog();
                $errorlog->payload = $e->getMessage();
                $errorlog->exception = $e->getCode();
                $errorlog->save();
            }
            $retail_price_column = SettingHelper::getSetting('retail_price_column');
            foreach ($api_product_options as $product_option) {
                $this->info('Processing option_id ' . $product_option->id . ' => ' . $product_option->modifiedDate . ' => ' . $product_option->code);
                // $this->info('Processing product prices ' . $product_option->priceColumns);
                if ($product_option) {
                    $retail_price = isset($product_option->priceColumns->$retail_price_column) ? $product_option->priceColumns->$retail_price_column : 0;
                    $update_product_options = ProductOption::with('price')->where('option_id',$product_option->id)->first();

                    if (!empty($update_product_options)) {
                        $update_product_options->option1 = $product_option->option1;
                        $update_product_options->option_id = $product_option->id;
                        $update_product_options->product_id = $product_option->productId;
                        $update_product_options->code = $product_option->code;
                        $update_product_options->productOptionSizeCode = $product_option->productOptionSizeCode;
                        $update_product_options->supplierCode = $product_option->supplierCode;
                        $update_product_options->option1 = $product_option->option1;
                        $update_product_options->option2 = $product_option->option2;
                        $update_product_options->option3 = $product_option->option3;
                        $update_product_options->optionWeight = $product_option->optionWeight;
                        $update_product_options->size = $product_option->size;
                        $update_product_options->retailPrice = $retail_price;
                        $update_product_options->wholesalePrice = $product_option->wholesalePrice;
                        $update_product_options->vipPrice = $product_option->vipPrice;
                        $update_product_options->specialPrice = $product_option->specialPrice;
                        $update_product_options->specialsStartDate = $product_option->specialsStartDate;
                        $update_product_options->stockAvailable = $product_option->stockAvailable;
                        $update_product_options->stockOnHand = $product_option->stockOnHand;
                        $update_product_options->specialDays = $product_option->specialDays;
                        $update_product_options->image =  !empty($product_option->image) ? $product_option->image->link: '';
                        $update_product_options->save();

                        $price_columns = $product_option->priceColumns;
                        $column_keys = array_keys(get_object_vars($price_columns));
                        $table_columns = $this->getPriceColumns();

                        $priceColumn = Pricingnew::where('option_id', $update_product_options->option_id)->first();
                        if (empty($priceColumn)) {

                            $prices_array = [
                                'option_id' => $product_option->id,
                            ];

                            if (!empty($column_keys)) {
                                foreach ($column_keys as $column_key) {
                                    if (isset($table_columns[$column_key])) {
                                        $prices_array[$column_key] = $price_columns->$column_key;
                                    }
                                }
                            }
                            $pricingnew = new Pricingnew($prices_array);
                            $pricingnew->save();
                        }
                        else {
                            if (!empty($column_keys)) {
                                foreach ($column_keys as $column_key) {
                                    if (isset($table_columns[$column_key])) {
                                        $priceColumn->$column_key = $product_option->priceColumns->$column_key;
                                    }
                                }
                            }
                            
                            $priceColumn->save();
                        }
                    }
                    else {
                        $retail_price = isset($product_option->priceColumns->$retail_price_column) ? $product_option->priceColumns->$retail_price_column : 0;
                        $product_option_table = new ProductOption([
                            'option1' => $product_option->option1,
                            'option_id' => $product_option->id,
                            'product_id' => $product_option->productId,
                            'code' => $product_option->code,
                            'productOptionSizeCode' =>  $product_option->productOptionSizeCode,
                            'supplierCode' =>  $product_option->supplierCode,
                            'option1' =>  $product_option->option1,
                            'option2' =>  $product_option->option2,
                            'option3' =>  $product_option->option3,
                            'optionWeight' =>  $product_option->optionWeight,
                            'size' =>  $product_option->size,
                            'retailPrice' =>  $retail_price,
                            'wholesalePrice' =>  $product_option->wholesalePrice,
                            'vipPrice' =>  $product_option->vipPrice,
                            'specialPrice' =>  $product_option->specialPrice,
                            'specialsStartDate' =>  $product_option->specialsStartDate,
                            'stockAvailable' =>  $product_option->stockAvailable,
                            'stockOnHand' =>  $product_option->stockOnHand,
                            'specialDays' =>  $product_option->specialDays,
                            'image' =>  !empty($product_option->image) ? $product_option->image->link: ''
                        ]);
                        $product_option_table->save();

                        $price_columns = $product_option->priceColumns;
                        $column_keys = array_keys(get_object_vars($price_columns));
                        $table_columns = $this->getPriceColumns();

                        $prices_array = [
                            'option_id' => $product_option->id,
                        ];

                        if (!empty($column_keys)) {
                            foreach ($column_keys as $column_key) {
                                if (isset($table_columns[$column_key])) {
                                    $prices_array[$column_key] = $price_columns->$column_key;
                                }
                            }
                        }

                        $priceColumn = new Pricingnew($prices_array);
                        $priceColumn->save();
                    }
                }
            }
            
        }

        $product_option_sync_log->last_synced = $current_date;
        $product_option_sync_log->record_count = $total_record_count;
        $product_option_sync_log->save();

        $this->info('Total Record Count#' . $total_record_count);
        $this->info('------------------------------');
        $this->info('-------------Finished------------------');
    }

    private function getPriceColumns() {
        return [
            'retailUSD' => true,
            'terraInternUSD' => true,
            'sacramentoUSD' => true,
            'wholesaleUSD' => true,
            'oklahomaUSD' => true,
            'calaverasUSD' => true,
            'tier0USD' => true,
            'tier1USD' => true,
            'tier2USD' => true,
            'tier3USD' => true,
            'commercialOKUSD' => true,
            'costUSD' => true,
            'specialPrice' => true,
            'disP1USD' => true,
            'disP2USD' => true,
            'comccusd' => true,
            'com1USD' => true,
            'msrpusd' => true
        ];
    }
}

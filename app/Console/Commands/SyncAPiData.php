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



class SyncAPiData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'Sync:ApiData';

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
        $admin_setting = AdminSetting::where('option_name', 'sync_api_data')->first();
        if (empty($admin_setting) || $admin_setting->option_value != 'Yes') {
            $this->error('Api sync data setting is off');
            return false;
        }

        
        $current_date = Carbon::now()->setTimezone('UTC')->format('Y-m-d H:i:s');
        $product_sync_log = ApiSyncLog::where('end_point', 'https://api.cin7.com/api/v1/Products')->first();
        if (empty($product_sync_log)) {
            $product_sync_log = new ApiSyncLog();
            $product_sync_log->end_point = 'https://api.cin7.com/api/v1/Products';
            $product_sync_log->desription = 'Products Sync';
            $product_sync_log->record_count = 0;
            $product_sync_log->last_synced = $current_date;
            $product_sync_log->save();
        }

        $last_product_synced_date = $product_sync_log->last_synced;

        $product_sync_raw_date = Carbon::parse($last_product_synced_date);
        $product_sync_date = $product_sync_raw_date->format('Y-m-d');
        $product_sync_time = $product_sync_raw_date->format('H:i:s');
        $api_formatted_product_sync_date = $product_sync_date . 'T' . $product_sync_time . 'Z';

        // get category sync date
        $category_sync_log = ApiSyncLog::where('end_point', 'categories')->first();
        if (empty($category_sync_log)) {
            $category_sync_log = new ApiSyncLog();
            $category_sync_log->end_point = 'categories';
            $category_sync_log->desription = 'Categories Sync';
            $category_sync_log->record_count = 0;
            $category_sync_log->last_synced = $current_date;
            $category_sync_log->save();
        }

        $last_category_synced_date = $category_sync_log->last_synced;

        $category_sync_raw_date = Carbon::parse($last_category_synced_date);
        $category_sync_date = $category_sync_raw_date->format('Y-m-d');
        $category_sync_time = $category_sync_raw_date->format('H:i:s');
        $api_formatted_category_sync_date = $category_sync_date . 'T' . $category_sync_time . 'Z';
        

        $client2 = new \GuzzleHttp\Client();
        $total_record_count = 0;

        $client = new \GuzzleHttp\Client();

        $cin7_auth_username = SettingHelper::getSetting('cin7_auth_username');
        $cin7_auth_password = SettingHelper::getSetting('cin7_auth_password');


        

        // Find total category pages
        $total_category_pages = 9;

        for ($i = 1; $i <= $total_category_pages; $i++) {
            $this->error('Categories: Processing page#' . $i);
            
            $res = $client->request(
                'GET', 
                'https://api.cin7.com/api/v1/ProductCategories?rows=250&page=' . $i,
                [
                    'auth' => [
                        $cin7_auth_username,
                        $cin7_auth_password
                    ]                    
                ]
            );
            


            $api_categories = $res->getBody()->getContents();
            $api_categories = json_decode($api_categories);

            $record_count = count($api_categories);
            $this->info('Categories: Record Count per page #--------------------------' . $record_count);
            
            if ($record_count < 1 || empty($record_count)) {
                $this->info('----------------break-----------------');
                break;
            }

            foreach ($api_categories as $api_category) {
                $category = Category::where('category_id', $api_category->id)->first();
                if (!empty($category)) {
                    $this->info('---------------------------------------');
                    $this->info('Processing Category ' . $api_category->name . ', ID: ' . $api_category->id);
                    $this->info('---------------------------------------');

                    $category->name = $api_category->name;
                    $category->slug = Str::slug($api_category->name);
                    $category->is_active = ($api_category->isActive == 1 || $api_category->isActive == '1') ? 1 : 0;
                    $category->parent_id = $api_category->parentId;
                    $category->save();
                }
                else {
                    $category = new Category([
                        'id' => $api_category->id,
                        'name' => $api_category->name,
                        'slug' => Str::slug($api_category->name),
                        'category_id' => $api_category->id,
                        'parent_id' => $api_category->parentId,
                        'is_active' => ($api_category->isActive == 1 || $api_category->isActive == '1') ? 1 : 0,
                        'sort' => $api_category->sort
                    ]);
                    $category->save();
                }
            }
        }

        


            $client2 = new \GuzzleHttp\Client();

            // Find total category pages
            $total_products_pages = 150;

            for ($i = 1; $i <= $total_products_pages; $i++) {
                $this->info('Processing page#' . $i);
                try {

                    $res = $client2->request(
                        'GET', 
                        'https://api.cin7.com/api/v1/Products?where=modifieddate>='. $api_formatted_product_sync_date . '&page=' . $i . '&rows=250', 
                        [
                            'auth' => [
                                $cin7_auth_username,
                                $cin7_auth_password
                            ]
                         
                        ]
                    );

                    UtilHelper::saveDailyApiLog('sync_products');


                    $api_products = $res->getBody()->getContents();
              
                    $api_products = json_decode($api_products);
                    $record_count = count($api_products);
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

          
                $brands = [];
                foreach ($api_products as $api_product) {
                    $this->info($api_product->id);

                    $brands[] = $api_product->brand;
                    $this->info('---------------------------------------');
                    $this->info('Processing Products ' . $api_product->name . ' => ' . $api_product->modifiedDate);
                    $this->info('---------------------------------------');
                    $product = Product::where('product_id', $api_product->id)->with('options.price')->first();
    
                    if ($product) {
                        if ($api_product->categoryIdArray) {
                            $category_id = $api_product->categoryIdArray[0];    
                        }
                        else {
                            $category_id = 0;
                        }

                        $product->name =  $api_product->name;
                        $product->slug = Str::slug($api_product->name);
                        $product->status =  $api_product->status;
                        $product->description =  $api_product->description;
                        $product->category_id =  $category_id;
                        $product->images =  !empty($api_product->images[0]) ? $api_product->images[0]->link: '';
                        $product->code =  $api_product->productOptions[0]->code;
                        $product->retail_price =  $api_product->productOptions[0]->priceColumns->sacramentoUSD;
                        $product->stockAvailable =  $api_product->productOptions[0]->stockAvailable;
                        
                        if (isset($api_product->brand)) {
                            $brand = Brand::where('name', $api_product->brand)->first();
                            if (empty($brand)) {
                                $brand = new Brand([
                                    'name' => $api_product->brand
                                ]);
                                $brand->save();
                                $product->brand_id = $brand->id;
                            }
                            else {
                                $product->brand_id = $brand->id;
                            }
                        }
                        
                        $product->save();
                    if ($api_product->productOptions) {

                        foreach ($api_product->productOptions as $api_productOption) {
                            $product_option = ProductOption::with('price')->where('option_id',$api_productOption->id)->first();

                            if ($product_option) {
                                $product_option->option1 = $api_productOption->option1;
                                $product_option->option_id = $api_productOption->id;
                                $product_option->product_id = $api_productOption->productId;
                                $product_option->code = $api_productOption->code;
                                $product_option->productOptionSizeCode = $api_productOption->productOptionSizeCode;
                                $product_option->supplierCode = $api_productOption->supplierCode;
                                $product_option->option1 = $api_productOption->option1;
                                $product_option->option2 = $api_productOption->option2;
                                $product_option->option3 = $api_productOption->option3;
                                $product_option->optionWeight = $api_productOption->optionWeight;
                                $product_option->size = $api_productOption->size;
                                $product_option->retailPrice = $api_productOption->priceColumns->sacramentoUSD;
                                $product_option->wholesalePrice = $api_productOption->wholesalePrice;
                                $product_option->vipPrice = $api_productOption->vipPrice;
                                $product_option->specialPrice = $api_productOption->specialPrice;
                                $product_option->specialsStartDate = $api_productOption->specialsStartDate;
                                $product_option->stockAvailable = $api_productOption->stockAvailable;
                                $product_option->stockOnHand = $api_productOption->stockOnHand;
                                $product_option->specialDays = $api_productOption->specialDays;
                                $product_option->image =  !empty($api_productOption->image) ? $api_productOption->image->link: '';
                                $product_option->save();

                                $price_columns = $api_productOption->priceColumns;
                                $column_keys = array_keys(get_object_vars($price_columns));
                                $table_columns = $this->getPriceColumns();

                                $priceColumn = Pricingnew::where('option_id', $product_option->option_id)->first();
                                if (empty($priceColumn)) {

                                    $prices_array = [
                                        'option_id' => $api_productOption->id,
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
                                                $priceColumn->$column_key = $api_productOption->priceColumns->$column_key;
                                            }
                                        }
                                    }
                                    
                                    $priceColumn->save();
                                }
                            }
                            else {

                                $retail_price_column = SettingHelper::getSetting('retail_price_column');
                                $retail_price = isset($api_productOption->priceColumns->$retail_price_column) ? $api_productOption->priceColumns->$retail_price_column : 0;

                                $product_option = new ProductOption([
                                    'option1' => $api_productOption->option1,
                                    'option_id' => $api_productOption->id,
                                    'product_id' => $api_productOption->productId,
                                    'code' => $api_productOption->code,
                                    'productOptionSizeCode' =>  $api_productOption->productOptionSizeCode,
                                    'supplierCode' =>  $api_productOption->supplierCode,
                                    'option1' =>  $api_productOption->option1,
                                    'option2' =>  $api_productOption->option2,
                                    'option3' =>  $api_productOption->option3,
                                    'optionWeight' =>  $api_productOption->optionWeight,
                                    'size' =>  $api_productOption->size,
                                    'retailPrice' =>  $retail_price,
                                    'wholesalePrice' =>  $api_productOption->wholesalePrice,
                                    'vipPrice' =>  $api_productOption->vipPrice,
                                    'specialPrice' =>  $api_productOption->specialPrice,
                                    'specialsStartDate' =>  $api_productOption->specialsStartDate,
                                    'stockAvailable' =>  $api_productOption->stockAvailable,
                                    'stockOnHand' =>  $api_productOption->stockOnHand,
                                    'specialDays' =>  $api_productOption->specialDays,
                                    'image' =>  !empty($api_productOption->image) ? $api_productOption->image->link: ''
                                ]);
                                $product_option->save();

                                $price_columns = $api_productOption->priceColumns;
                                $column_keys = array_keys(get_object_vars($price_columns));
                                $table_columns = $this->getPriceColumns();

                                $prices_array = [
                                    'option_id' => $api_productOption->id,
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
                else {
                    if ($api_product->categoryIdArray) {
                        $category_id = $api_product->categoryIdArray[0];    
                    }
                    else {
                        $category_id = 0;
                    }
                    if (!empty($product->brand)) {
                        $product->brand =  $api_product->brand;
                        $brand = Brand::where('name', $api_product->brand)->first();
                        $brand_id = $brand->id;
                    }
                    else {
                        $brand_id = '';
                    }



                    $product = new Product([
                        'product_id' => $api_product->id,
                        'name' => $api_product->name,
                        'slug' => Str::slug($api_product->name),
                        'status' => $api_product->status,
                        'description' => $api_product->description,
                        'category_id' => $category_id,
                        'images' => !empty($api_product->images[0]) ? $api_product->images[0]->link: '',
                        'code' => $api_product->productOptions[0]->code,
                        'retail_price' => $api_product->productOptions[0]->priceColumns->sacramentoUSD,
                        'stockAvailable' => $api_product->productOptions[0]->stockAvailable,
                        'brand' => $api_product->brand,
                        'brand_id' => $brand_id
                    ]);
                    $product->save();
                    if ($api_product->productOptions) {
                        foreach ($api_product->productOptions as $api_productOption) {
                            $productOption = new ProductOption([
                                'option_id' =>  $api_productOption->id,
                                'product_id' =>  $api_productOption->productId,
                                'status' =>  $api_productOption->status,
                                'code' =>  $api_productOption->code,
                                'productOptionSizeCode' =>  $api_productOption->productOptionSizeCode,
                                'supplierCode' =>  $api_productOption->supplierCode,
                                'option1' =>  $api_productOption->option1,
                                'option2' =>  $api_productOption->option2,
                                'option3' =>  $api_productOption->option3,
                                'optionWeight' =>  $api_productOption->optionWeight,
                                'size' =>  $api_productOption->size,
                                'retailPrice' =>  $api_productOption->retailPrice,
                                'wholesalePrice' =>  $api_productOption->wholesalePrice,
                                'vipPrice' =>  $api_productOption->vipPrice,
                                'specialPrice' =>  $api_productOption->specialPrice,
                                'specialsStartDate' =>  $api_productOption->specialsStartDate,
                                'stockAvailable' =>  $api_productOption->stockAvailable,
                                'stockOnHand' =>  $api_productOption->stockOnHand,
                                'specialDays' =>  $api_productOption->specialDays,
                                'image' =>  !empty($api_productOption->image) ? $api_productOption->image->link: ''
                            ]);
                            $productOption->save();
                        }
                    }
                }
            }
            
        }

        $product_sync_log->last_synced = $current_date;
        $product_sync_log->record_count = $total_record_count;
        $product_sync_log->save();

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


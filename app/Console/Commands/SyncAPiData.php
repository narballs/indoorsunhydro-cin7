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
use Carbon\Carbon;

use App\Helpers\UtilHelper;



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

        $current_date = Carbon::now()->setTimezone('UTC')->format('Y-m-d H:i:s');
        $sync_log = ApiSyncLog::where('end_point', 'https://api.cin7.com/api/v1/Products')->first();
        if (empty($sync_log)) {
            $sync_log = new ApiSyncLog();
            $sync_log->end_point = 'https://api.cin7.com/api/v1/Products';
            $sync_log->desription = 'Products Sync';
            $sync_log->record_count = 0;
            $sync_log->last_synced = $current_date;
            $sync_log->save();
        }

        $last_synced_date = $sync_log->last_synced;
        
        
        $this->info('Last updated time#--------------------------' . $last_synced_date);
        $this->info('Current time#--------------------------' . $current_date);

        $rawDate = Carbon::parse($last_synced_date);
        
        $getdate = $rawDate->format('Y-m-d');
        $getTime = $rawDate->format('H:i:s');


        $formattedDateSting = $getdate . 'T' . $getTime . 'Z';
        $client2 = new \GuzzleHttp\Client();
        $total_record_count = 0;

        $client = new \GuzzleHttp\Client();


        // Find total category pages
        $total_category_pages = 9;

        for ($i = 1; $i <= $total_category_pages; $i++) {
            $this->info('Processing page#' . $i);
            // try {
                $res = $client->request(
                    'GET', 
                    'https://api.cin7.com/api/v1/ProductCategories?page'.$i, 
                    [
                         'auth' => [
                                'IndoorSunHydroUS',
                                'faada8a7a5ef4f90abaabb63e078b5c1'
                            //env('API_USER'),
                            //env('API_PASSWORD')
                        ]
                     
                    ]
                );
            //}
            // catch (\Exception $e) {
                // $msg = $e->getMessage();
                // $errorlog = new ApiErrorLog();
                // $errorlog->payload = $e->getMessage();
                // $errorlog->exception = $e->getCode();
                // $errorlog->save();

            //}

            $api_categories = $res->getBody()->getContents();
            //dd($api_categories);
            $api_categories = json_decode($api_categories);

            $this->info('Found ' . count($api_categories) . ' from API');

            foreach($api_categories as $api_category) {
                $this->info($api_category->id);
                $category = Category::where('category_id', $api_category->id)->first();
                if (!empty($category)) {
                    $this->info('---------------------------------------');
                    $this->info('Processing Category ' . $api_category->name);
                    $this->info('---------------------------------------');
                        //old one 
                        // $category_data = [
                        //     'id' => $api_category->id,
                        //     'name' => $api_category->name,
                        //     'category_id' => $api_category->id,
                        //     'parent_id' => $api_category->parentId,
                        //     'is_active' => $api_category->isActive,
                        //     'sort' => $api_category->sort,
                        // ];

                        // $category = Category::firstOrCreate(
                        //     ['id' => $api_category->id],
                        //     $category_data
                        // );

                    $category->name = $api_category->name;
                    $category->slug = Str::slug($api_category->name);
                    $category->is_active = $api_category->isActive;
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
                        'is_active' => $api_category->isActive,
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
                        'https://api.cin7.com/api/v1/Products?where=modifieddate>='. $formattedDateSting . '&page=' . $i . '&rows=250', 
                        [
                            'auth' => [
                               'IndoorSunHydroUS',
                                'faada8a7a5ef4f90abaabb63e078b5c1'
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
                        if ($product->brand) {
                            $brand = Brand::where('name', $product->brand)->first();
                            if (empty($brand)) {
                                $brand = new Brand([
                                    'name' => $product->brand
                                ]);
                                $brand->save();
                            }
                            else {
                                $brand->name = $product->brand;
                                $brand->save();
                            }
                        }
                        if ($product->brand) {
                            $product->brand =  $api_product->brand;
                            $brand = Brand::where('name', $api_product->brand)->first();
                            $product->brand_id = $brand->id;
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
                                $product_option->retailPrice =$api_productOption->priceColumns->sacramentoUSD;
                                $product_option->wholesalePrice = $api_productOption->wholesalePrice;
                                $product_option->vipPrice = $api_productOption->vipPrice;
                                $product_option->specialPrice = $api_productOption->specialPrice;
                                $product_option->specialsStartDate = $api_productOption->specialsStartDate;
                                $product_option->stockAvailable = $api_productOption->stockAvailable;
                                $product_option->stockOnHand = $api_productOption->stockOnHand;
                                $product_option->specialDays = $api_productOption->specialDays;
                                $product_option->image =  !empty($api_productOption->image) ? $api_productOption->image->link: '';
                                $product_option->save();
                                $priceColumn = Pricingnew::where('option_id', $product_option->option_id)->first();
                                if (empty($priceColumn)) {
                                    $priceColumn = new Pricingnew([
                                        'option_id' => $api_productOption->id,
                                        'retailUSD' => $api_productOption->priceColumns->retailUSD,
                                        'terraInternUSD' => $api_productOption->priceColumns->terraInternUSD,
                                        'sacramentoUSD' => $api_productOption->priceColumns->sacramentoUSD,
                                        'wholesaleUSD' => $api_productOption->priceColumns->wholesaleUSD,
                                        'oklahomaUSD' => $api_productOption->priceColumns->oklahomaUSD,
                                        'tier1USD' => $api_productOption->priceColumns->tier1USD,
                                        'calaverasUSD' => $api_productOption->priceColumns->calaverasUSD,
                                        'tier2USD' => $api_productOption->priceColumns->tier2USD,
                                        'tier3USD' => $api_productOption->priceColumns->tier3USD,
                                        'commercialOKUSD' => $api_productOption->priceColumns->commercialOKUSD,
                                        'costUSD' => $api_productOption->priceColumns->costUSD,
                                        'specialPrice' => $api_productOption->priceColumns->specialPrice
                                    ]);
                                    $priceColumn->save();
                                }
                                else {
                                    $priceColumn->retailUSD = $api_productOption->priceColumns->retailUSD;
                                    $priceColumn->wholesaleUSD = $api_productOption->priceColumns->wholesaleUSD;
                                    $priceColumn->terraInternUSD = $api_productOption->priceColumns->terraInternUSD;
                                    $priceColumn->sacramentoUSD = $api_productOption->priceColumns->sacramentoUSD;
                                    $priceColumn->calaverasUSD = $api_productOption->priceColumns->calaverasUSD;
                                    $priceColumn->tier1USD = $api_productOption->priceColumns->tier1USD;
                                    $priceColumn->tier2USD = $api_productOption->priceColumns->tier2USD;
                                    $priceColumn->tier3USD = $api_productOption->priceColumns->tier3USD;
                                    $priceColumn->commercialOKUSD = $api_productOption->priceColumns->commercialOKUSD;
                                    $priceColumn->costUSD = $api_productOption->priceColumns->costUSD;
                                    $priceColumn->specialPrice = $api_productOption->priceColumns->specialPrice;
                                    $priceColumn->save();

                                }
                            }
                            else {
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
                                    'retailPrice' =>  $api_productOption->priceColumns->sacramentoUSD,
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
                                $priceColumn = new Pricingnew([
                                    'option_id' => $api_productOption->id,
                                    'retailUSD' => $api_productOption->priceColumns->retailUSD,
                                    'wholesaleUSD' => $api_productOption->priceColumns->wholesaleUSD,
                                    'terraInternUSD' => $api_productOption->priceColumns->terraInternUSD,
                                    'sacramentoUSD' => $api_productOption->priceColumns->sacramentoUSD,
                                    'oklahomaUSD' => $api_productOption->priceColumns->oklahomaUSD,
                                    'tier1USD' => $api_productOption->priceColumns->tier1USD,
                                    'calaverasUSD' => $api_productOption->priceColumns->calaverasUSD,
                                    'tier2USD' => $api_productOption->priceColumns->tier2USD,
                                    'tier3USD' => $api_productOption->priceColumns->tier3USD,
                                    'commercialOKUSD' => $api_productOption->priceColumns->commercialOKUSD,
                                    'costUSD' => $api_productOption->priceColumns->costUSD,
                                    'specialPrice' => $api_productOption->priceColumns->specialPrice
                                ]);
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

        $sync_log->last_synced = $current_date;
        $sync_log->record_count = $total_record_count;
        $sync_log->save();

        $this->info('Total Record Count#' . $total_record_count);
        $this->info('------------------------------');
        $this->info('-------------Finished------------------');
    }
}


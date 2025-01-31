<?php

namespace App\Console\Commands;

use App\Models\AdminSetting;
use App\Models\Category;
use App\Models\Pricingnew;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\ProductOption;
use Illuminate\Console\Command;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image as ImageFacade;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Support\Facades\Log;

class DownloadAndSaveImage extends Command
{
    protected $signature = 'download:image';

    protected $description = 'Download an image from a URL and save it to the database';

    public function handle()
    {
        
        $all_product_images = ProductImage::pluck('product_id')->toArray();
        $price_column = null;
        $default_price_column = AdminSetting::where('option_name', 'default_price_column')->first();
        if (!empty($default_price_column)) {
            $price_column = $default_price_column->option_value;
        }
        else {
            $price_column = 'sacramentoUSD';
        }

        $product_categories = Category::where('is_active', 1)->pluck('category_id')->toArray();
        $all_products_ids = Product::whereIn('category_id' , $product_categories)
        ->pluck('product_id')->toArray();
        $product_options_ids = ProductOption::whereIn('product_id' , $all_products_ids)->pluck('option_id')->toArray();
        $product_pricing_option_ids = Pricingnew::whereIn('option_id' , $product_options_ids)
        ->where($price_column , '>' , 0)
        ->pluck('option_id')
        ->toArray();
        $products_ids = ProductOption::whereIn('option_id' , $product_pricing_option_ids)
        ->where('status', '!=', 'Disabled')
        ->pluck('product_id')->toArray();
        $all_products_query = Product::with('options','options.defaultPrice')->whereIn('product_id' , $products_ids)
        ->where('status' , '!=' , 'Inactive')
        ->whereNotIn('id', $all_product_images);
        $all_products = $all_products_query->get();

        $this->info('Downloading and saving images...'  . count($all_products));

        $productImages = [];
        if (count($all_products) > 0) {
            foreach ($all_products as $product) {
                if (count($product->options) > 0) {
                    foreach ($product->options as $option) {
                        
                        $product_images = ProductImage::where('product_id', $product->id)->first();
                        if (empty($product_images)) {
                            if (!empty($product->images)) {
                                try {
                                    $client = new Client();
                                    $response = $client->get($product->images);
                                    
                                    $imageData = $response->getBody()->getContents();
                                    $image = ImageFacade::make($imageData);
                                    // Get width and height
                                    $width = $image->getWidth();
                                    $height = $image->getHeight();
                                    if (($width > 250 || $width < 250) ||  ($height > 250 || $height < 250)) {
                                        // Get the desired width and height
                                        $newWidth = 250; // Replace with your desired width
                                        $newHeight = 250; // Replace with your desired height
                                        // Resize the image

                                        $image->resize($newWidth, $newHeight);
                                        $publicPath = public_path('theme/products/images/');
                                        if (!file_exists($publicPath)) {
                                            mkdir($publicPath, 0777, true);
                                        }
                                        $imageName = time() . '_' . uniqid() . '.png';
                                        $image->save($publicPath . $imageName);

                                    }
                                    else {
                                        $sourcePath = public_path('theme/products/images/');
                                        if (!file_exists($sourcePath)) {
                                            mkdir($sourcePath, 0777, true);
                                        }
                                        $imageName = time() . '_' . uniqid() . '.png';
                                        $imagePath = $sourcePath . $imageName;
                                        file_put_contents($imagePath, $imageData);
                                    }

                                    $productImage = new ProductImage();
                                    $productImage->product_id = $product->id;
                                    $productImage->image = $imageName;
                                    $productImage->save();
                                } catch (\Exception $e) {
                                    
                                    Log::info('no image found for product id: ' . $product->id);
                                }
                            } else {
                                Log::info('no image found for product id: ' . $product->id);
                            } 
                            
                        } else {
                            Log::info('image already exists for product id: ' . $product->id);
                        }  
                        // else {
                        //     if (!empty($product->images)) {
                        //         try {
                        //             $client = new Client();
                        //             $response = $client->get($product->images);
                                    
                        //             $imageData = $response->getBody()->getContents();
                        //             $image = ImageFacade::make($imageData);
                        //             // Get width and height
                        //             $width = $image->getWidth();
                        //             $height = $image->getHeight();
                        //             if (($width > 250 || $width < 250) ||  ($height > 250 || $height < 250)) {
                        //                 // Get the desired width and height
                        //                 $newWidth = 250; // Replace with your desired width
                        //                 $newHeight = 250; // Replace with your desired height
                        //                 // Resize the image
                        //                 $image->resize($newWidth, $newHeight);
                        //                 $publicPath = public_path('theme/products/images/');
                        //                 if (!file_exists($publicPath)) {
                        //                     mkdir($publicPath, 0777, true);
                        //                 }
                        //                 $imageName = time() . '_' . uniqid() . '.png';
                        //                 $image->save($publicPath . $imageName);

                        //             }
                        //             else {
                        //                 $publicPath = public_path('theme/products/images/');
                        //                 if (!file_exists($publicPath)) {
                        //                     mkdir($publicPath, 0777, true);
                        //                 }
                        //                 $imageName = time() . '_' . uniqid() . '.png';
                        //                 $imagePath = $publicPath . $imageName;
                        //                 file_put_contents($imagePath, $imageData);
                        //             }

                        //             $productImages = new ProductImage();
                        //             $productImages->product_id = $product->id;
                        //             $productImages->image = $imageName;
                        //             $productImages->save();
                        //         } catch (\Exception $e) {
                                    
                        //             Log::info('no image found for product id: ' . $product->id);
                        //         }
                               
                        //     } 
                            
                        // }
                    }
                }
            }
        }
        $this->info('Image downloaded and saved successfully.');
    }
}

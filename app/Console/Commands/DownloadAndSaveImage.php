<?php

namespace App\Console\Commands;

use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Console\Command;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image as ImageFacade;

class DownloadAndSaveImage extends Command
{
    protected $signature = 'download:image';

    protected $description = 'Download an image from a URL and save it to the database';

    public function handle()
    {
        $all_products = Product::with('options','options.defaultPrice', 'product_brand', 'categories' , 'product_views','apiorderItem', 'product_stock')
        ->with(['product_views','apiorderItem' , 'options' => function ($q) {
            $q->where('status', '!=', 'Disabled');
            
        }])
        ->whereHas('options.defaultPrice', function ($q) {
            $q->where('retailUSD', '!=', 0);
        })
        ->whereHas('categories' , function ($q) {
            $q->where('is_active', 1);
        })
        ->where('status' , '!=' , 'Inactive')
        ->where('barcode' , '!=' , '')
        ->get();
        if (count($all_products) > 0) {
            foreach ($all_products as $product) {
                if (count($product->options) > 0) {
                    foreach ($product->options as $option) {
                        $product_images = ProductImage::where('product_id', $product->id)->first();
                        if (!empty($product_images)) {

                            if (!empty($product->images)) {
                                $client = new Client();
                                $response = $client->get($product->images);
                                $imageData = $response->getBody()->getContents();
                                if ($response->getStatusCode() == 200) {
                                    $publicPath = public_path('/theme/products/images/');
                                    if (!file_exists($publicPath)) {
                                        mkdir($publicPath, 0777, true);
                                    }
                                    $imageName = time() . '_' . uniqid() . '.png';
                                    $imagePath = $publicPath . $imageName;
                                    file_put_contents($imagePath, $imageData);

                                    $product_images->image = $imageName;
                                    $product_images->save();
                                } else {
                                    $sourcePath = public_path('theme/img/image_not_available.png');
                                    $imageName = time() . '_' . uniqid() . '.' . pathinfo($sourcePath, PATHINFO_EXTENSION);
                                    $destinationPath = public_path('/theme/products/images/');
                                    // Copy the file to the new location
                                    copy($sourcePath, $destinationPath . $imageName);
                                    $product_images->image = $imageName;
                                    $product_images->save();
                                }
                            } else {
                                $sourcePath = public_path('theme/img/image_not_available.png');
                                $imageName = time() . '_' . uniqid() . '.' . pathinfo($sourcePath, PATHINFO_EXTENSION);
                                $destinationPath = public_path('/theme/products/images/');
                                // Copy the file to the new location
                                copy($sourcePath, $destinationPath . $imageName);
                                $product_images->image = $imageName;
                                $product_images->save();
                            }
                        } else {
                            if (!empty($product->images)) {
                                $client = new Client();
                                $response = $client->get($product->images);
                                $imageData = $response->getBody()->getContents();
                                if ($response->getStatusCode() == 200) {
                                    $publicPath = public_path('/theme/products/images/');
                                    if (!file_exists($publicPath)) {
                                        mkdir($publicPath, 0777, true);
                                    }
                                    $imageName = time() . '_' . uniqid() . '.png';
                                    $imagePath = $publicPath . $imageName;
                                    file_put_contents($imagePath, $imageData);

                                    $productImages = new ProductImage();
                                    $productImages->product_id = $product->id;
                                    $productImages->image = $imageName;
                                    $productImages->save();
                                }
                                else {
                                    $sourcePath = public_path('theme/img/image_not_available.png');
                                    $imageName = time() . '_' . uniqid() . '.' . pathinfo($sourcePath, PATHINFO_EXTENSION);
                                    $destinationPath = public_path('/theme/products/images/');
                                    // Copy the file to the new location
                                    copy($sourcePath, $destinationPath . $imageName);
                                    $productImages = new ProductImage();
                                    $productImages->product_id = $product->id;
                                    $productImages->image = $imageName;
                                    $productImages->save();
                                }
                                
                                
                            } else {
                                $sourcePath = public_path('theme/img/image_not_available.png');
                                $imageName = time() . '_' . uniqid() . '.' . pathinfo($sourcePath, PATHINFO_EXTENSION);
                                $destinationPath = public_path('/theme/products/images/');
                                // Copy the file to the new location
                                copy($sourcePath, $destinationPath . $imageName);
                                $product_images = new ProductImage();
                                $product_images->product_id = $product->id;
                                $product_images->image = $imageName;
                                $product_images->save();
                            }
                        }
                    }
                }
            }
        }

        $this->info('Image downloaded and saved successfully.');
    }
}

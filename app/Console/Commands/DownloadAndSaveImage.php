<?php

namespace App\Console\Commands;

use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Console\Command;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image as ImageFacade;
use GuzzleHttp\Exception\RequestException;

class DownloadAndSaveImage extends Command
{
    protected $signature = 'download:image';

    protected $description = 'Download an image from a URL and save it to the database';

    public function handle()
    {
        $all_products = Product::with('options','options.defaultPrice', 'product_brand', 'categories' , 'product_views','apiorderItem','product_image' , 'product_stock')
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
        $productImages = [];
        if (count($all_products) > 0) {
            foreach ($all_products as $product) {
                if (count($product->options) > 0) {
                    foreach ($product->options as $option) {
                        
                        $product_images = ProductImage::where('product_id', $product->id)->first();
                        if (!empty($product_images)) {
                            if (!empty($product->images)) {
                                try {
                                    $client = new Client();
                                    $response = $client->get($product->images);
                                    
                                    $imageData = $response->getBody()->getContents();
                                    $image = ImageFacade::make($imageData);
                                    // Get width and height
                                    $width = $image->getWidth();
                                    $height = $image->getHeight();
                                    if ($width > 250 || $height > 250) {
                                        // Get the desired width and height
                                        $newWidth = 250; // Replace with your desired width
                                        $newHeight = 250; // Replace with your desired height
                                        // Resize the image
                                        $image->resize($newWidth, $newHeight);
                                        $sourcePath = public_path('theme/img/image_not_available.png');
                                        if (!file_exists($sourcePath)) {
                                            mkdir($sourcePath, 0777, true);
                                        }
                                        $imageName = time() . '_' . uniqid() . '.png';
                                        $image->save($sourcePath . $imageName);

                                    }
                                    else {
                                        $sourcePath = public_path('theme/img/image_not_available.png');
                                        if (!file_exists($sourcePath)) {
                                            mkdir($sourcePath, 0777, true);
                                        }
                                        $imageName = time() . '_' . uniqid() . '.png';
                                        $imagePath = $sourcePath . $imageName;
                                        file_put_contents($imagePath, $imageData);
                                    }

                                    $product_images->image = $imageName;
                                    $product_images->save();
                                } catch (RequestException $e) {
                                    // Handle the case when a request exception occurs
                                    if ($e->hasResponse()) {
                                        $sourcePath = public_path('theme/img/image_not_available.png');
                                        $imageName = time() . '_' . uniqid() . '.' . pathinfo($sourcePath, PATHINFO_EXTENSION);
                                        $destinationPath = public_path('theme/products/images/');
                                        // Copy the file to the new location
                                        
                                        // Ensure the destination directory exists
                                        if (!file_exists($destinationPath)) {
                                            mkdir($destinationPath, 0777, true);
                                        }

                                        // Copy the file to the new location
                                        copy($sourcePath, $destinationPath . $imageName);
                                        $product_images->image = $imageName;
                                        $product_images->save();
                                    } else {
                                        $sourcePath = public_path('theme/img/image_not_available.png');
                                        $imageName = time() . '_' . uniqid() . '.' . pathinfo($sourcePath, PATHINFO_EXTENSION);
                                        $destinationPath = public_path('theme/products/images/');
                                        // Copy the file to the new location
                                       
                                        // Ensure the destination directory exists
                                        if (!file_exists($destinationPath)) {
                                            mkdir($destinationPath, 0777, true);
                                        }

                                        // Copy the file to the new location
                                        copy($sourcePath, $destinationPath . $imageName);
                                        $product_images->image = $imageName;
                                        $product_images->save();
                                    }
                                }
                            } else {
                                $sourcePath = public_path('theme/img/image_not_available.png');
                                $imageName = time() . '_' . uniqid() . '.' . pathinfo($sourcePath, PATHINFO_EXTENSION);
                                $destinationPath = public_path('theme/products/images/');
                                
                                // Ensure the destination directory exists
                                if (!file_exists($destinationPath)) {
                                    mkdir($destinationPath, 0777, true);
                                }

                                // Copy the file to the new location
                                copy($sourcePath, $destinationPath . $imageName);
                                $product_images->image = $imageName;
                                $product_images->save();
                            }
                        } else {
                            if (!empty($product->images)) {
                                try {
                                    $client = new Client();
                                    $response = $client->get($product->images);
                                    
                                    $imageData = $response->getBody()->getContents();
                                    $image = ImageFacade::make($imageData);
                                    // Get width and height
                                    $width = $image->getWidth();
                                    $height = $image->getHeight();
                                    if ($width > 250 || $height > 250) {
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
                                        $publicPath = public_path('theme/products/images/');
                                        if (!file_exists($publicPath)) {
                                            mkdir($publicPath, 0777, true);
                                        }
                                        $imageName = time() . '_' . uniqid() . '.png';
                                        $imagePath = $publicPath . $imageName;
                                        file_put_contents($imagePath, $imageData);
                                    }

                                    $productImages = new ProductImage();
                                    $productImages->product_id = $product->id;
                                    $productImages->image = $imageName;
                                    $productImages->save();
                                } catch (RequestException $e) {
                                    // Handle the case when a request exception occurs
                                    if ($e->hasResponse()) {
                                        $sourcePath = public_path('theme/img/image_not_available.png');
                                        $imageName = time() . '_' . uniqid() . '.' . pathinfo($sourcePath, PATHINFO_EXTENSION);
                                        $destinationPath = public_path('theme/products/images/');
                                        
                                        // Ensure the destination directory exists
                                        if (!file_exists($destinationPath)) {
                                            mkdir($destinationPath, 0777, true);
                                        }

                                        // Copy the file to the new location
                                        copy($sourcePath, $destinationPath . $imageName);
                                        $productImages = new ProductImage();
                                        $productImages->product_id = $product->id;
                                        $productImages->image = $imageName;
                                        $productImages->save();
                                    } else {
                                        $sourcePath = public_path('theme/img/image_not_available.png');
                                        $imageName = time() . '_' . uniqid() . '.' . pathinfo($sourcePath, PATHINFO_EXTENSION);
                                        $destinationPath = public_path('theme/products/images/');
                                        
                                        // Ensure the destination directory exists
                                        if (!file_exists($destinationPath)) {
                                            mkdir($destinationPath, 0777, true);
                                        }

                                        // Copy the file to the new location
                                        copy($sourcePath, $destinationPath . $imageName);
                                        $productImages = new ProductImage();
                                        $productImages->product_id = $product->id;
                                        $productImages->image = $imageName;
                                        $productImages->save();
                                    }
                                }
                               
                            } else {
                                $sourcePath = public_path('theme/img/image_not_available.png');
                                $imageName = time() . '_' . uniqid() . '.' . pathinfo($sourcePath, PATHINFO_EXTENSION);
                                $destinationPath = public_path('theme/products/images/');
                                
                                // Ensure the destination directory exists
                                if (!file_exists($destinationPath)) {
                                    mkdir($destinationPath, 0777, true);
                                }

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

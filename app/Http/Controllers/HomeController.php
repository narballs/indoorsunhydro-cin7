<?php

namespace App\Http\Controllers;

use App\Helpers\UserHelper;
use App\Models\AdminSetting;
use App\Models\ApiOrderItem;
use App\Models\Category;
use App\Models\Page;
use App\Models\Product;
use App\Models\ProductView;
use App\Models\BuyList;
use App\Models\NewsletterSubscription;
use App\Models\ProductBuyList;
use Session;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use App\Models\GoogleReview;



class HomeController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        
       // session::forget('cart_hash');
       //  session::forget('companies');
       //  session::forget('cart');
       //  $cart = session::get('cart');
       
       //   exit;
       $cart_items = UserHelper::switch_price_tier($request);
        $contact_id = null;
        $contact_id = session()->get('contact_id');
        $categories = Category::orderBy('name', 'ASC')
            ->with('products')->where('is_active', 1)
            ->get();
        $product_views = null;
        $best_selling_products = null;
        $product_views_chunks = null;
        $user_id = Auth::id();
        $user_buy_list_options = [];
        $lists = '';
        $pages = Page::where('status', 1)->get();
        if (auth()->user()) {
            $product_views = ProductView::with('product.options', 'product.options.defaultPrice','product.brand', 'product.options.products','product.categories' ,'product.apiorderItem')
            ->whereHas('product' , function($query) {
                $query->where('status' , '!=' , 'Inactive');
            })
            ->select('product_id' , DB::raw('count(*) as entry_count'))
            ->whereNotNull('user_id')
            ->where('user_id' , $user_id)
            ->orderBy('created_at' , 'DESC')
            ->take(10)
            ->groupBy('product_id')
            ->get();
            
        } else {
            $product_views = null;
        }
        $best_selling_products = ApiOrderItem::with('product.options', 'product.options.defaultPrice','product.brand', 'product.options.products','product.categories' ,'product.apiorderItem')
            ->whereHas('product' , function($query) {
                $query->where('status' , '!=' , 'Inactive');
            })
            ->select('product_id' , DB::raw('count(*) as entry_count'))
            ->orderBy('created_at' , 'DESC')
            ->groupBy('product_id')
            ->take(24)
            ->get();
        $user_list = BuyList::where('user_id', $user_id)
            ->where('contact_id', $contact_id)
            ->first();
        if (!empty($user_list)) {
            $user_buy_list_options = ProductBuyList::where('list_id', $user_list->id)->pluck('option_id', 'option_id')->toArray();
        }

        $lists = BuyList::where('user_id', $user_id)
            ->where('contact_id', $contact_id)
            ->with('list_products')
            ->get();


        $notify_user_about_product_stock = AdminSetting::where('option_name', 'notify_user_about_product_stock')->first();
        $products_to_hide = BuyList::with('list_products')->where('title' , 'Products_to_hide')->first();
        
        if (!empty($products_to_hide)) {
            $products_to_hide = $products_to_hide->list_products->pluck('option_id')->toArray();
        }
        $reviews = GoogleReview::orderBy('rating', 'DESC')->where('rating','!=','null')->where('rating', '>', 4)->get();
        $averageRating = $this->calculateAverageRating($reviews);
        return view('index', compact('categories','cart_items','averageRating', 'product_views','best_selling_products','lists','user_buy_list_options' , 'contact_id' , 'notify_user_about_product_stock' , 'products_to_hide'));
    }

    public function show_page($slug) {
        $page = Page::where('slug' , $slug)->first();
        $pages = Page::where('status', 1)->get();
        return view('partials.show_page', compact('page' , 'pages'));
    }


    public function subscribe_newsletter(Request $request) {
        $request->validate([
            'email' => 'required|email'
        ]);

        $flag_newsletter = NewsletterSubscription::where('email' , $request->email)->first();
        if($flag_newsletter) {
            return redirect()->back()->with('error' , 'You are already subscribed to our newsletter');
        }
        $email = $request->email;
        $newsletter = new NewsletterSubscription();
        $newsletter->email = $email;
        $newsletter->save();
        return redirect()->back()->with('success' , 'You have been subscribed to our newsletter');
    }

    // public function fetchReviews()
    // {
        
    //     $apiKey = config('services.google_places.api_key'); // Store the API key in the config file
    //     $placeId = config('services.google_places.place_id'); // Store the place ID in the config file

    //     $url = "https://maps.googleapis.com/maps/api/place/details/json?place_id={$placeId}&fields=reviews&key={$apiKey}";
    //     $response = Http::get($url);
    //     $body = $response->json();
    //     if ($response->successful()) {
    //         $body = $response->json();
        
    //         // Check if reviews exist in the response
    //         if (isset($body['result']['reviews']) && !empty($body['result']['reviews'])) {
    //             $reviews = $body['result']['reviews'];
    //             if (!empty($reviews)) {
    //                 foreach ($reviews as $review) {
    //                     // Generate a unique identifier using author's name and review time
    //                     $reviewId = md5($review['author_name'] . $review['time']); // Use MD5 or another hashing function
            
    //                     // Update or create the review in the database
    //                     GoogleReview::updateOrCreate(
    //                         [
    //                             'google_review_id' => $reviewId, // Use generated ID as unique identifier
    //                         ],
    //                         [
    //                             'author_name' => $review['author_name'],
    //                             'author_url' => $review['author_url'] ?? null,
    //                             'language' => $review['language'] ?? null,
    //                             'profile_photo_url' => $review['profile_photo_url'] ?? null,
    //                             'rating' => $review['rating'],
    //                             'relative_time_description' => $review['relative_time_description'] ?? null,
    //                             'text' => $review['text'],
    //                             'review_time' => \Carbon\Carbon::createFromTimestamp($review['time']),
    //                             'place_id' => $placeId,
    //                             'translated' => false, // Update this based on your translation logic
    //                         ]
    //                     );
    //                 }
            
    //                 // Return the reviews to the view
    //                 return view('google_reviews', compact('reviews')); // Pass reviews to the view
    //             } else {
    //                 // If no reviews found
    //                 return response()->json(['message' => 'No reviews found'], 404);
    //             }
    //         }
        
    //         // If no reviews found
    //         return response()->json(['message' => 'No reviews found'], 404);
        
    //     } else {
    //         // Return error status and message if the API request was not successful
    //         return response()->json([
    //             'status' => $response->status(),
    //             'message' => 'Failed to retrieve reviews',
    //         ], $response->status());
    //     }
    // }


    public function fetchReviews($nextPageToken = null)
    {
        $apiKey = config('services.google_places.api_key'); // Store the API key in the config file
        $placeId = config('services.google_places.place_id'); // Store the place ID in the config file

        // Base URL with required parameters
        $url = "https://maps.googleapis.com/maps/api/place/details/json?place_id={$placeId}&fields=reviews&key={$apiKey}";
        if ($nextPageToken) {
            $url .= "&pagetoken={$nextPageToken}";
        }

        // Send the request
        $response = Http::get($url);
        $body = $response->json();
        // Check for a successful response and the presence of reviews
        if ($response->successful() && isset($body['result']['reviews'])) {
            $reviews = $body['result']['reviews'];

            // Check if reviews are present
            if (!empty($reviews)) {
                // Loop through the reviews and update or create in the database
                foreach ($reviews as $review) {
                    // Generate a unique identifier using author's name and review time
                    $reviewId = md5($review['author_name'] . $review['time']); // Use MD5 or another hashing function

                    // Update or create the review in the database
                    GoogleReview::updateOrCreate(
                        [
                            'google_review_id' => $reviewId, // Use generated ID as unique identifier
                        ],
                        [
                            'author_name' => $review['author_name'],
                            'author_url' => $review['author_url'] ?? null,
                            'language' => $review['language'] ?? null,
                            'profile_photo_url' => $review['profile_photo_url'] ?? null,
                            'rating' => $review['rating'],
                            'relative_time_description' => $review['relative_time_description'] ?? null,
                            'text' => $review['text'],
                            'review_time' => \Carbon\Carbon::createFromTimestamp($review['time']),
                            'place_id' => $placeId,
                            'translated' => false, // Update this based on your translation logic
                        ]
                    );
                }

                // Check if there's a next_page_token for more reviews
                if (isset($body['next_page_token'])) {
                    // Google API requires a short delay (2-3 seconds) before calling the next page
                    sleep(2); // Delay for API token processing
                    return $this->fetchReviews($body['next_page_token']); // Recursively fetch more reviews
                }

                // Return success message after fetching all pages
                return response()->json(['message' => 'All reviews fetched and saved successfully'], 200);
            } else {
                // No reviews found
                return response()->json(['message' => 'No reviews found'], 404);
            }
        }

        // Handle cases where no reviews or error in the request
        return response()->json([
            'status' => $response->status(),
            'message' => $response->successful() ? 'No reviews found' : 'Failed to retrieve reviews',
        ], $response->status());
    }

    public function get_google_reviews()
    {
        $reviews = GoogleReview::orderBy('rating', 'DESC')->where('rating','!=','null')->where('rating', '>', 4)->get();
        return response()->json($reviews);
    }

    private function calculateAverageRating($reviews)
    {
        if ($reviews->isEmpty()) {
            return 'No Reviews'; // Return a message if there are no reviews
        }
        
        $totalRating = $reviews->sum('rating'); // Sum all the ratings
        $total = round($totalRating / $reviews->count(), 1); // Calculate and return the average, rounded to one decimal place
        return number_format($total, 1);
    }

    
}

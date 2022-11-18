<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ContactUsController;
use App\Http\Controllers\Admin\TestController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\OrderManagementController;
use App\Http\Controllers\Admin\ShippingMethodController;
use App\Http\Controllers\Admin\ContactController;
use App\Http\Controllers\ChangePasswordController;
use App\Http\Controllers\Admin\CustomerSearchController;
use App\Http\Controllers\Admin\AdminProductController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });
Route::get('/',[ HomeController::class,
    'index'
])->name('index');
// Route::resource('/abc', ProductController::class)->only([
//     'abc'
// ]);
Route::get('send-mail', function () {
   
    $details = [
        'title' => 'Mail from waqas',
        'body' => 'This is for testing email using smtp'
    ];
   
    \Mail::to('wqszeeshan@gmail.com')->send(new \App\Mail\Subscribe($details));
   
    dd("Email is Sent.");
});
//Route::get('/category/{id}', 'CategoryController@showCategory');
Route::get('/products/{id}/{slug}', [ProductController::class, 'showProductByCategory']);
Route::get('/products/', [ProductController::class, 'showAllProducts']);
Route::get('/product-detail/{id}/{option_id}/{slug}', [ProductController::class, 'showProductDetail']);
Route::get('/user/', [UserController::class, 'userRegistration'])->name('user');
Route::post('/login/',[UserController::class, 'process_login'])->name('login');
Route::post('/user-contact/',[UserController::class, 'save_contact'])->name('save_contact');
Route::post('/update-contact/',[UserController::class, 'update_contact'])->name('update_contact');
Route::get('/my-account/',[UserController::class, 'my_account'])->name('my_account');
Route::get('/user-addresses/',[UserController::class, 'user_addresses'])->name('user_addresses');
Route::get('/user-order-detail/{id}',[UserController::class, 'user_order_detail'])->name('user-order-detail');
Route::post('/register/basic/create', [UserController::class,'process_signup'])->name('register');
Route::post('/logout',[UserController::class, 'logout'])->name('logout');

Route::get('/product-brand/{name}', [ProductController::class, 'showProductByBrands']);
Route::post('add-to-cart', [ProductController::class, 'addToCart'])->name('add.to.cart');
Route::get('/remove/{id}', [ProductController::class, 'removeProductByCategory']);
Route::get('cart', [ProductController::class, 'cart'])->name('cart');
Route::post('update-cart', [ProductController::class, 'updateCart'])->name('update.cart');
Route::get('checkout', [CheckoutController::class, 'index'])->name('checkout');
Route::post('order', [OrderController::class, 'store'])->name('order');
Route::get('/thankyou/{id}', [CheckoutController::class, 'thankyou'])->name('thankyou');
Route::post('order-status-update', [OrderController::class, 'updateStatus'])->name('order.status.update');
Route::post('change-password', [ChangePasswordController::class, 'store'])->name('change.password');
Route::get('/contact-us/', [ContactUsController::class, 'index']);
Route::post('/contact-us-store/', [ContactUsController::class, 'store'])->name('contact.us.store');
//Route::post('/',[UserController::class, 'logout'])->name('logout');


Route::group(['middleware' => ['admin']], function () {
   Route::get('admin/dashboard', [DashboardController::class, 'index'])->name('admin.view');
   Route::get('admin/orders', [OrderManagementController::class, 'index'])->name('admin.orders');
   Route::get('admin/order/create', [OrderManagementController::class, 'create'])->name('admin.order.create');
   Route::get('/admin/order-detail/{id}', [OrderManagementController::class, 'show'])->name('admin.order.detail');
   Route::post('admin/order-comments', [OrderManagementController::class, 'addComments'])->name('admin.order.comments');
   Route::post('admin/order-status', [OrderManagementController::class, 'updateStatus'])->name('update.order.status');
   Route::get('admin/shipping-methods', [ShippingMethodController::class, 'index'])->name('admin.shipping-methods');
   Route::get('admin/shipping-method/{id}', [ShippingMethodController::class, 'edit'])->name('admin.shipping-method');
   Route::post('admin/shipping-method', [ShippingMethodController::class, 'store'])->name('admin.shipping-method.store');
   Route::get('admin/shipping-methods/create', [ShippingMethodController::class, 'create'])->name('admin.shipping-methods.create');
   Route::get('admin/shipping-method/delete/{id}', [ShippingMethodController::class, 'destroy'])->name('admin.shipping-method.delete');
   Route::get('admin/contacts', [ContactController::class, 'supplier'])->name('admin.contacts');
   Route::get('admin/customers', [ContactController::class, 'customer'])->name('admin.customer');
   Route::get('admin/customer/create', [ContactController::class, 'customer_create'])->name('admin.customer.create');
   Route::post('admin/customer/store', [ContactController::class, 'customer_store'])->name('admin.customer.store');
   Route::get('admin/customer-detail/{id}', [ContactController::class, 'show_customer'])->name('admin.customer.detail');
   Route::get('admin/api-order-details/{id}', [OrderManagementController::class, 'show_api_order'])->name('admin.api.order.details');
   Route::post('admin/order-full-fill', [OrderManagementController::class, 'order_full_fill'])->name('admin.order.full.fill');
   Route::post('admin/customer-activate', [ContactController::class, 'activate_customer'])->name('admin.customer.activate');
   Route::post('admin/update-pricing-column', [ContactController::class, 'update_pricing_column'])->name('admin.update.pricing.column');
   Route::get('admin/customersearch', [CustomerSearchController::class, 'customerSearch'])->name('admin.customer.search');
   Route::resource('admin/products', AdminProductController::class);
});
Route::get('product/search', [ProductController::class, 'productSearch'])->name('product_search');


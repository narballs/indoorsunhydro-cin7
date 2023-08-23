<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ContactUsController;
use App\Http\Controllers\CreateCartController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\OrderManagementController;
use App\Http\Controllers\Admin\ShippingMethodController;
use App\Http\Controllers\Admin\ContactController;
use App\Http\Controllers\Admin\AdminSettingsController;
use App\Http\Controllers\Admin\AdminCommandsController;
use App\Http\Controllers\ChangePasswordController;
use App\Http\Controllers\Admin\CustomerSearchController;
use App\Http\Controllers\Admin\AdminProductController;
use App\Http\Controllers\Users\RoleController;
use App\Http\Controllers\Admin\AdminBuyListController;
use App\Http\Controllers\Admin\AdminShareListController;
use App\Http\Controllers\Admin\LogsController;
use App\Http\Controllers\Admin\DailyApiLogController;
use App\Http\Controllers\Admin\TaxClassController;
use App\Http\Controllers\Admin\OperationalZipCodeController;
use App\Models\TaxClass;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use App\Models\User;

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


Route::get('/', [HomeController::class, 'index'])->name('index');



Route::get('send-password/fornt-end/{id}', [UserController::class, 'send_password_fornt_end'])->name('users.send_password_fornt');

Route::get('send-mail', function () {
    $details = [
        'title' => 'Mail from waqas',
        'body' => 'This is for testing email using smtp',
        'name' => 'jjjj'
    ];
    Mail::to('naris@letswebnow.com')->send(new \App\Mail\Subscribe($details));
    dd("Email is Sent.");
});

Route::get('/products/{id}/{slug}', [ProductController::class, 'showProductByCategory']);
Route::get('/products/', [ProductController::class, 'showAllProducts']);
Route::get('/product-detail/{id}/{option_id}/{slug}', [ProductController::class, 'showProductDetail']);
Route::group(['middleware' => ['alreadyloggedin']], function () {
    Route::get('/user/', [UserController::class, 'userRegistration'])->name('user');
});
Route::post('api/fetch-cities', [UserController::class, 'fetchCity']);
Route::post('/login/', [UserController::class, 'process_login'])->name('login');
Route::post('/user-contact/', [UserController::class, 'save_contact'])->name('save_contact');
Route::post('/update-contact/', [UserController::class, 'update_contact'])->name('update_contact');
Route::get('/my-account/', [UserController::class, 'my_account'])->name('my_account');
Route::get('/my-qoutes/', [UserController::class, 'my_qoutes'])->name('my_qoutes');
Route::get('/my-qoutes-details/{id}', [UserController::class, 'my_qoutes_details'])->name('my_qoutes_details');
Route::get('/my-qoute-edit/{id}', [UserController::class, 'my_qoute_edit'])->name('my_qoute_edit');
Route::get('/my-account-user-addresses/', [UserController::class, 'address_user_my_account'])->name('user_addresses_my_account');
Route::get('/user-order-detail/{id}', [UserController::class, 'user_order_detail'])->name('user-order-detail');
Route::post('/check/email', [UserController::class, 'checkEmail'])->name('check_email');
Route::post('/check/address', [UserController::class, 'checkAddress'])->name('check_address');
Route::post('/register/basic/create', [UserController::class, 'process_signup'])->name('register');
Route::post('/switch-company/', [UserController::class, 'switch_company'])->name('switch-company');
Route::post('/switch-company-select/', [UserController::class, 'switch_company_select'])->name('switch-company-select');
Route::post('/register/basic/invitation', [UserController::class, 'invitation_signup'])->name('invitation.signup');
Route::post('/logout', [UserController::class, 'logout'])->name('logout');
Route::get('/lost-password', [UserController::class, 'lost_password'])->name('lost.password');
Route::post('/recover-password', [UserController::class, 'recover_password'])->name('recover.password');

Route::get('/product-brand/{name}', [ProductController::class, 'showProductByBrands']);
Route::post('add-to-cart/', [ProductController::class, 'addToCart'])->name('add.to.cart');
Route::get('/remove/{id}', [ProductController::class, 'removeProductByCategory']);
Route::get('/cart', [ProductController::class, 'cart'])->name('cart');
Route::post('update-cart', [ProductController::class, 'updateCart'])->name('update.cart');
Route::get('checkout', [CheckoutController::class, 'index'])->name('checkout');
Route::post('order', [OrderController::class, 'store'])->name('order');
Route::get('/thankyou/{id}', [CheckoutController::class, 'thankyou'])->name('thankyou');
Route::post('order-status-update', [OrderController::class, 'updateStatus'])->name('order.status.update');
Route::post('change-password', [ChangePasswordController::class, 'store'])->name('change.password');
Route::get('/contact-us/', [ContactUsController::class, 'index']);
Route::post('/contact-us-store/', [ContactUsController::class, 'store'])->name('contact.us.store');
Route::get('/create-cart/{id}', [CreateCartController::class, 'create_cart'])->name('create.cart');
Route::post('/add-to-wish-list/', [ProductController::class, 'addToWishList']);
Route::get('/get-wish-lists/', [ProductController::class, 'getWishLists']);
Route::get('/get-lists-names/', [ProductController::class, 'getListNames']);
Route::post('/create-list/', [ProductController::class, 'createList']);
Route::post('/delete/favorite/product', [ProductController::class, 'delete_favorite_product']);
Route::get('/child/categories/{parent_id}', [ProductController::class, 'get_child_categories']);
Route::group(['prefix' => 'my-account/'], function () {
    Route::get('my-favorites', [UserController::class, 'myFavorites'])->name('my_favorites');
    Route::get('my-orders', [UserController::class, 'myOrders'])->name('myOrders');
    Route::get('my-order-detail/{id}', [UserController::class, 'order_detail'])->name('order_detail');
    Route::get('address/', [UserController::class, 'address'])->name('my_account_address');
    Route::get('account-profile/', [UserController::class, 'account_profile'])->name('account_profile');
    Route::post('account-profile/update', [UserController::class, 'account_profile_update'])->name('account_profile_update');
    Route::get('additional-users', [UserController::class, 'additional_users'])->name('additional_users');
});

Route::group(['middleware' => ['auth']], function () {
    Route::resource('admin/roles', RoleController::class);
    Route::resource('admin/tax_classes', TaxClassController::class);
    Route::resource('admin/users', UserController::class);
    Route::get('admin/dashboard', [DashboardController::class, 'index'])->name('admin.view');
    Route::get('admin/orders', [OrderManagementController::class, 'index'])->name('admin.orders');
    Route::delete('admin/orders/all/delete', [OrderManagementController::class, 'deleteAllOrders']);
    Route::post('admin/orders/multi-full-fill', [OrderManagementController::class, 'multiOrderFullFill']);
    Route::get('admin/order/create', [OrderManagementController::class, 'create'])->name('admin.order.create');
    Route::get('/admin/order-detail/{id}', [OrderManagementController::class, 'show'])->name('admin.order.detail');
    Route::post('admin/order-comments', [OrderManagementController::class, 'addComments'])->name('admin.order.comments');
    Route::delete('admin/order/delete', [OrderManagementController::class, 'destroy'])->name('admin.order.delete');
    Route::post('admin/order-status', [OrderManagementController::class, 'updateStatus'])->name('update.order.status');
    Route::get('admin/shipping-methods', [ShippingMethodController::class, 'index'])->name('admin.shipping-methods');
    Route::get('admin/shipping-method/{id}', [ShippingMethodController::class, 'edit'])->name('admin.shipping-method');
    Route::post('admin/shipping-method', [ShippingMethodController::class, 'store'])->name('admin.shipping-method.store');
    Route::get('admin/shipping-methods/create', [ShippingMethodController::class, 'create'])->name('admin.shipping-methods.create');
    Route::get('admin/shipping-method/delete/{id}', [ShippingMethodController::class, 'destroy'])->name('admin.shipping-method.delete');
    Route::get('admin/contacts', [ContactController::class, 'supplier'])->name('admin.contacts');
    Route::get('admin/customers', [ContactController::class, 'customer'])->name('admin.customer');

    Route::get('admin/commands/import_contacts', [AdminCommandsController::class, 'import_contacts'])->name('admin.commands.import_contacts');


    Route::get('admin/customer/create', [ContactController::class, 'customer_create'])->name('admin.customer.create');
    Route::post('admin/customer/store', [ContactController::class, 'customer_store'])->name('admin.customer.store');
    Route::get('admin/customer-detail/{id}', [ContactController::class, 'show_customer'])->name('admin.customer.detail');
    Route::get('admin/customer-delete/{id}', [ContactController::class, 'customer_delete'])->name('admin.customer.delete');
    Route::get('admin/customer-edit/{id}', [ContactController::class, 'customer_edit'])->name('admin.customer.edit');
    Route::post('admin/customer-update/', [ContactController::class, 'customer_update'])->name('admin.customer.update');
    Route::get('admin/api-order-details/{id}', [OrderManagementController::class, 'show_api_order'])->name('admin.api.order.details');
    Route::post('admin/order-full-fill', [OrderManagementController::class, 'order_full_fill'])->name('admin.order.full.fill');
    Route::post('admin/multiple/cancle/orders', [OrderManagementController::class, 'multiple_cancle_orders']);
    Route::post('admin/check-status', [OrderManagementController::class, 'check_order_status'])->name('admin.check.order.status');
    Route::post('admin/multi/check-status', [OrderManagementController::class, 'mutli_check_order_status']);
    Route::post('admin/order-cancel', [OrderManagementController::class, 'cancelOrder']);
    Route::post('admin/customer-activate', [ContactController::class, 'activate_customer'])->name('admin.customer.activate');
    Route::post('admin/update-pricing-column', [ContactController::class, 'update_pricing_column'])->name('admin.update.pricing.column');
    Route::get('admin/customersearch', [CustomerSearchController::class, 'customerSearch'])->name('admin.customer.search');
    Route::resource('admin/products', AdminProductController::class);
    Route::resource('admin/buy-list', AdminBuyListController::class);
    Route::post('admin/add-to-list', [AdminBuyListController::class, 'addToList']);
    Route::post('admin/generate-list', [AdminBuyListController::class, 'genrateList']);
    Route::post('admin/share-list', [AdminShareListController::class, 'shareList']);
    Route::get('admin/admin-users', [UserController::class, 'adminUsers']);
    Route::get('admin/get-parent', [ContactController::class, 'getParent']);
    Route::post('admin/assign-parent-child', [ContactController::class, 'assingParentChild']);
    Route::get('admin/user-switch/{id}/{contactId}', [UserController::class, 'switch_user'])->name('users.switch');
    Route::get('admin/send-password/{id}', [UserController::class, 'send_password'])->name('users.send_password');
    Route::get('admin/go-back', [UserController::class, 'switch_user_back'])->name('users.switch_user_back');
    Route::get('admin/api-sync-logs', [LogsController::class, 'index']);

    Route::get('admin/daily_api_logs', [DailyApiLogController::class, 'index']);



    Route::get('admin/logout', function () {
        Auth::logout();
        Session::forget('logged_in_as_another_user');
        return redirect()->route('user');
    });
});
Route::post('/stripe/webhook', [OrderController::class, 'webhook']);
Route::get('product/search', [ProductController::class, 'productSearch'])->name('product_search');
Route::post('admin/send-invitation-email', [ContactController::class, 'send_invitation_email'])->name('admin.send_invitation_email');
Route::post('create/secondary/user', [UserController::class, 'create_secondary_user']);
Route::delete('secondary/user/delete', [UserController::class, 'delete_secondary_user'])->name('secondary_user.delete');
Route::post('/reset-password', [UserController::class, 'reset_password'])->name('reset_password');

Route::get('/home', [HomeController::class, 'index'])->name('home');
Route::group(['middleware' => ['auth']], function () {
});
Route::get('/products/{id}/{slug}', [ProductController::class, 'showProductByCategory']);
Route::get('/products/', [ProductController::class, 'showAllProducts']);
Route::get('/product-detail/{id}/{option_id}/{slug}', [ProductController::class, 'showProductDetail']);
Route::get('/user/', [UserController::class, 'userRegistration'])->name('user');
Route::post('api/fetch-cities', [UserController::class, 'fetchCity']);
Route::post('/login/', [UserController::class, 'process_login'])->name('login');
Route::post('/user-contact/', [UserController::class, 'save_contact'])->name('save_contact');
Route::post('/update-contact/', [UserController::class, 'update_contact'])->name('update_contact');
Route::get('/my-account/', [UserController::class, 'my_account'])->name('my_account');
Route::get('select-companies-to-order', [UserController::class, 'choose_company']);
Route::get('/my-qoutes/', [UserController::class, 'my_qoutes'])->name('my_qoutes');
Route::get('/my-qoutes-details/{id}', [UserController::class, 'my_qoutes_details'])->name('my_qoutes_details');
Route::get('/my-qoute-edit/{id}', [UserController::class, 'my_qoute_edit'])->name('my_qoute_edit');
Route::get('/user-addresses/', [UserController::class, 'user_addresses'])->name('user_addresses');
Route::get('/user-order-detail/{id}', [UserController::class, 'user_order_detail'])->name('user-order-detail');
Route::post('/register/basic/create', [UserController::class, 'process_signup'])->name('register');
Route::post('/switch-company/', [UserController::class, 'switch_company'])->name('switch-company');
Route::post('/register/basic/invitation', [UserController::class, 'invitation_signup'])->name('invitation.signup');
Route::post('/logout', [UserController::class, 'logout'])->name('logout');

Route::get('/product-brand/{name}', [ProductController::class, 'showProductByBrands']);
Route::post('add-to-cart/', [ProductController::class, 'addToCart'])->name('add.to.cart');
Route::get('/remove/{id}', [ProductController::class, 'removeProductByCategory']);
Route::get('cart', [ProductController::class, 'cart'])->name('cart');
Route::post('update-cart', [ProductController::class, 'updateCart'])->name('update.cart');
Route::get('checkout', [CheckoutController::class, 'index'])->name('checkout');
Route::post('order', [OrderController::class, 'store'])->name('order');
Route::get('/thankyou/{id}', [CheckoutController::class, 'thankyou'])->name('thankyou');
Route::post('order-status-update', [OrderController::class, 'updateStatus'])->name('order.status.update');
//Route::post('change-password', [ChangePasswordController::class, 'store'])->name('change.password');
Route::get('/contact-us/', [ContactUsController::class, 'index']);
Route::post('/contact-us-store/', [ContactUsController::class, 'store'])->name('contact.us.store');
Route::get('/create-cart/{id}', [CreateCartController::class, 'create_cart'])->name('create.cart');
Route::post('/add-to-wish-list/', [ProductController::class, 'addToWishList']);
Route::get('/get-wish-lists/', [ProductController::class, 'getWishLists']);
Route::get('/get-lists-names/', [ProductController::class, 'getListNames']);
Route::post('/create-list/', [ProductController::class, 'createList']);
Route::post('/multi-favorites-to-cart/', [ProductController::class, 'multi_favorites_to_cart']);
Route::get('/order/items/{id}', [ProductController::class, 'order_items']);
Route::post('/buy/order/items', [ProductController::class, 'buy_again_order_items']);

Route::group(['middleware' => ['auth']], function () {
    Route::resource('admin/roles', RoleController::class);
    Route::resource('admin/users', UserController::class);
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
    Route::get('admin/customer-delete/{id}', [ContactController::class, 'customer_delete'])->name('admin.customer.delete');
    Route::get('admin/customer-edit/{id}', [ContactController::class, 'customer_edit'])->name('admin.customer.edit');
    Route::post('admin/customer-update/', [ContactController::class, 'customer_update'])->name('admin.customer.update');
    Route::get('admin/api-order-details/{id}', [OrderManagementController::class, 'show_api_order'])->name('admin.api.order.details');
    Route::post('admin/order-full-fill', [OrderManagementController::class, 'order_full_fill'])->name('admin.order.full.fill');
    Route::post('admin/order-cancel', [OrderManagementController::class, 'cancelOrder']);
    Route::post('admin/customer-activate', [ContactController::class, 'activate_customer'])->name('admin.customer.activate');
    Route::post('admin/update-pricing-column', [ContactController::class, 'update_pricing_column'])->name('admin.update.pricing.column');
    Route::get('admin/customersearch', [CustomerSearchController::class, 'customerSearch'])->name('admin.customer.search');
    Route::resource('admin/products', AdminProductController::class);
    Route::resource('admin/buy-list', AdminBuyListController::class);
    Route::post('admin/add-to-list', [AdminBuyListController::class, 'addToList']);
    Route::post('admin/generate-list', [AdminBuyListController::class, 'genrateList']);
    Route::post('admin/share-list', [AdminShareListController::class, 'shareList']);
    Route::post('admin/auto-full-fill', [AdminSettingsController::class, 'autoFullfill']);
    Route::get('admin/admin-users', [UserController::class, 'adminUsers']);
    Route::get('admin/get-parent', [ContactController::class, 'getParent']);
    Route::get('admin/get-parent', [ContactController::class, 'getParent']);
    Route::post('admin/refresh-contact', [ContactController::class, 'refreshContact']);
    Route::post('admin/disable-secondary', [ContactController::class, 'disableSecondary']);
    Route::get('admin/user-switch/{id}', [UserController::class, 'switch_user'])->name('users.switch');
    Route::get('admin/send-password/{id}', [UserController::class, 'send_password'])->name('users.send_password');
    Route::get('admin/go-back', [UserController::class, 'switch_user_back'])->name('users.switch_user_back');

    //crud for admin settings
    Route::prefix('admin')->group(function () {

        Route::get('settings/index', [AdminSettingsController::class, 'index'])->name('admin.settings.index');
        // Route::get('settings/create', [AdminSettingsController::class, 'create'])->name('admin.settings.create');
        Route::post('settings/store', [AdminSettingsController::class, 'store'])->name('admin.settings.store');
        Route::get('settings/edit/{id}', [AdminSettingsController::class, 'edit'])->name('admin.settings.edit');
        Route::post('settings/update/{id}', [AdminSettingsController::class, 'update'])->name('admin.settings.update');
        Route::post('settings/delete/{id}', [AdminSettingsController::class, 'delete'])->name('admin.settings.delete');
        Route::post('/order/delete-item', [OrderController::class, 'delete_order_item'])->name('delete_order_item');
        Route::post('/order/item/delete', [OrderController::class, 'delete_order'])->name('delete_order');
        Route::post('/order/update', [OrderController::class, 'update_order'])->name('update_order');
        Route::post('/order/add-product', [OrderController::class, 'addProduct'])->name('add_product');
        Route::get('/order/search-product', [OrderController::class, 'searchProduct'])->name('search_product');
    });

    Route::get('admin/logout', function () {
        Session::forget('contact_id');
        Session::forget('company');
        Session::forget('companies');
        Session::forget('cart');
        Session::forget('logged_in_as_another_user');
        Session::flush();

        Auth::logout();
        return redirect()->route('user');
    });
});
Route::get('product/search', [ProductController::class, 'productSearch'])->name('product_search');
Route::post('admin/send-invitation-email', [ContactController::class, 'send_invitation_email'])->name('admin.send_invitation_email');
Route::post('create/secondary/user', [UserController::class, 'create_secondary_user']);
Route::post('user-order-approve', [UserController::class, 'user_order_approve']);
Route::post('/verify-order/', [UserController::class, 'verify_order']);
Route::post('/send-order-approval-email/', [UserController::class, 'send_order_approval_email']);

Route::delete('secondary/user/delete', [UserController::class, 'delete_secondary_user'])->name('secondary_user.delete');
Route::post('/reset-password', [UserController::class, 'reset_password'])->name('reset_password');

Route::get('/home', [HomeController::class, 'index'])->name('home');
Route::get('/customer/invitation/{hash}', [ContactController::class, 'customer_invitation']);
Route::group(['middleware' => ['auth']], function () {
});


Route::get('/index', [UserController::class, 'index_email_view']);
Route::get('/event', [CheckoutController::class, 'event']);

Route::resource('admin/operational-zip-codes', OperationalZipCodeController::class);
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\LandingPageController;
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
use App\Http\Controllers\AdminInventoryLocationController;
use App\Http\Controllers\Admin\WholesaleApplicationController;
use App\Http\Controllers\Admin\DiscountController;
use App\Http\Controllers\Admin\PagesController;
use App\Http\Controllers\GoogleContentController;
use App\Http\Controllers\EmailListController;
use App\Http\Controllers\CampaignController;
use App\Http\Controllers\ExportController;
use App\Http\Controllers\ProductStockNotificationController;
use App\Http\Controllers\GetProductDimensionController;
use App\Http\Controllers\NewsletterController;
use App\Http\Controllers\NewsletterTemplateController;
use App\Http\Controllers\PayoutController;
use App\Http\Controllers\SalePaymentsController;
use App\Http\Controllers\ShippingQuoteSettingController;
use App\Http\Controllers\SalesReportController;
use App\Http\Controllers\SmsController;
use App\Models\SalePayments;
use App\Models\TaxClass;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use App\Models\User;

use Google\Service\Dfareporting\OrderContact;
use Carbon\Carbon;

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
Route::get('/export', [ExportController::class, 'export'])->name('export');
Route::get('/list_products', [GoogleContentController::class, 'list_products'])->name('list_products');
Route::get('/insertProductsbatch', [GoogleContentController::class, 'insertProductsbatch'])->name('insertProductsbatch');
Route::get('/google/authorize', [GoogleContentController::class, 'authorizeGoogle'])->name('google.authorize');
Route::get('/auth/google/callback', [GoogleContentController::class, 'handleCallback'])->name('google.callback');
Route::get('/handleCallbackRetrieve', [GoogleContentController::class, 'handleCallbackRetrieve'])->name('handleCallbackRetrieve');
Route::get('/google/insert-products', [GoogleContentController::class, 'insertProducts'])->name('google.insertProducts');
Route::resource('landing-page', LandingPageController::class);
Route::group(['middleware' => ['isRestricted']], function () {
    Route::get('/', [HomeController::class, 'index'])->name('index');
});
Route::get('/wholesale/account/create', [UserController::class, 'create_wholesale_account'])->name('create_wholesale_account');
Route::get('/wholesale/account/thankyou/{id}', [UserController::class, 'wholesaleuser_thankyou'])->name('wholesaleuser_thankyou');
Route::get('/wholesale/account/edit/{id}', [UserController::class, 'edit_wholesale_account'])->name('edit_wholesale_account');
Route::post('/wholesale/account/check/email', [UserController::class, 'wholesale_user_check_email'])->name('wholesale_user_check_email');
Route::post('/wholesale/generate/pdf/{id}', [UserController::class, 'wholesale_application_generate_pdf'])->name('wholesale_application_generate_pdf');



Route::post('/wholesale/account/update', [UserController::class, 'update_wholesale_account'])->name('update_wholesale_account');
Route::post('wholesale/account/store', [UserController::class, 'store_wholesale_account'])->name('store_wholesale_account');
Route::post('/save-for-now', [UserController::class, 'save_for_now'])->name('save_for_now');
Route::post('/save-email-for-now', [UserController::class, 'save_email_for_now'])->name('save_email_for_now');
Route::post('/validate-email', [UserController::class, 'validate_email'])->name('validate_email');
Route::post('/get-user-by-email', [UserController::class, 'show_previous_data_by_email'])->name('show_previous_data_by_email');



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

//  ----------  added middleware for capture previous url before login -------------
 
Route::middleware(['web', 'capture.previous.url'])->group(function () {
    Route::get('/products/{id}/{slug}', [ProductController::class, 'showProductByCategory']);
    Route::get('/products/', [ProductController::class, 'showAllProducts']);
    Route::get('/product-detail/{id}/{option_id}/{slug}', [ProductController::class, 'showProductDetail']);
    Route::get('/product-brand/{name}', [ProductController::class, 'showProductByBrands']);
    Route::get('/contact-us/', [ContactUsController::class, 'index']);
    Route::get('/page/{slug}', [HomeController::class, 'show_page']);
    Route::get('admin/page/blog/detail/{slug}', [PagesController::class, 'blog_detail'])->name('blog_detail');
});

//  ----------  end middleware for capture previous url before login -------------

Route::group(['middleware' => ['alreadyloggedin']], function () {
    Route::get('/user/', [UserController::class, 'userRegistration'])->name('user');
});

Route::post('/login/', [UserController::class, 'process_login'])->name('login');
Route::get('/my-account/', [UserController::class, 'my_account'])->name('my_account');
Route::post('/user-contact/', [UserController::class, 'save_contact'])->name('save_contact');
Route::post('/update-contact/', [UserController::class, 'update_contact'])->name('update_contact');


//buy again products with paginate jquery 
Route::get('/my-account/buy-again-products', [UserController::class, 'buy_again_products'])->name('buy_again_products');

// save landing page details
Route::post('landing-page/personal/details', [LandingPageController::class, 'landing_page_personal_details'])->name('landing_page_personal_details');
Route::post('landing-page/company/details', [LandingPageController::class, 'landing_page_company_details'])->name('landing_page_company_details');
Route::post('landing-page/address/details', [LandingPageController::class, 'landing_page_address_details'])->name('landing_page_address_details');

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
Route::get('/emptyCart', [ProductController::class, 'emptyCart'])->name('emptyCart');
Route::get('/cart', [ProductController::class, 'cart'])->name('cart');
Route::post('update-cart', [ProductController::class, 'updateCart'])->name('update.cart');
Route::post('update-product-cart', [ProductController::class, 'update_product_cart'])->name('update_product_cart');
Route::get('checkout', [CheckoutController::class, 'index'])->name('checkout');
// select default address
Route::post('select-default-shipping-address', [CheckoutController::class, 'select_default_shipping_address'])->name('select_default_shipping_address');

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
Route::get('wholesale-application/view/{id}', [UserController::class, 'view_wholesale_account'])->name('view_wholesale_account');
Route::group(['prefix' => 'my-account/'], function () {
    Route::get('my-favorites', [UserController::class, 'myFavorites'])->name('my_favorites');
    Route::get('my-orders', [UserController::class, 'myOrders'])->name('myOrders');
    Route::get('my-order-detail/{id}', [UserController::class, 'order_detail'])->name('order_detail');
    Route::get('address/', [UserController::class, 'address'])->name('my_account_address');
    Route::get('account-profile/', [UserController::class, 'account_profile'])->name('account_profile');
    Route::post('account-profile/update', [UserController::class, 'account_profile_update'])->name('account_profile_update');
    Route::get('additional-users', [UserController::class, 'additional_users'])->name('additional_users');
    Route::post('address/default', [UserController::class, 'make_address_default'])->name('make_address_default');
    Route::post('/allow-access', [UserController::class, 'allow_access'])->name('allow_access');
});

Route::group(['middleware' => ['auth']], function () {
    Route::get('/thankyou/creating/account', [UserController::class, 'thankyou_for_creating_account'])->name('thankyou_for_creating_account');
    Route::resource('admin/pages', PagesController::class);
    Route::post('/editor/image_upload', [PagesController::class, 'image_upload'])->name('image_upload');
    // faqs page section
    Route::get('admin/page/faqs', [PagesController::class, 'faqs'])->name('faqs.index');
    Route::get('admin/page/faqs/create', [PagesController::class, 'create_faq'])->name('faqs.create');
    Route::post('admin/page/faqs/store', [PagesController::class, 'store_faq'])->name('faqs.store');
    Route::get('admin/page/faqs/edit/{id}', [PagesController::class, 'edit_faq'])->name('faqs.edit');
    Route::post('admin/page/faqs/update/{id}', [PagesController::class, 'update_faq'])->name('faqs.update');
    Route::post('admin/page/faqs/delete/{id}', [PagesController::class, 'delete_faq'])->name('faqs.delete');

    // blog page section
    Route::get('admin/page/blogs', [PagesController::class, 'blogs'])->name('blogs.index');
    Route::get('admin/page/blogs/create', [PagesController::class, 'create_blog'])->name('blogs.create');
    Route::post('admin/page/blogs/store', [PagesController::class, 'store_blog'])->name('blogs.store');
    Route::get('admin/page/blogs/edit/{id}', [PagesController::class, 'edit_blog'])->name('blogs.edit');
    Route::post('admin/page/blogs/update/{id}', [PagesController::class, 'update_blog'])->name('blogs.update');
    Route::post('admin/page/blogs/delete/{id}', [PagesController::class, 'delete_blog'])->name('blogs.delete');

    // ai questions  section
    Route::get('admin/ai/questions', [AdminSettingsController::class, 'ai_questions'])->name('ai_questions');
    Route::get('admin/ai/questions/create', [AdminSettingsController::class, 'create_ai_question'])->name('create_ai_question');
    Route::post('admin/ai/question/store', [AdminSettingsController::class, 'store_ai_question'])->name('store_ai_question');
    Route::get('admin/ai/question/edit/{id}', [AdminSettingsController::class, 'edit_ai_question'])->name('edit_ai_question');
    Route::post('admin/ai/question/update/{id}', [AdminSettingsController::class, 'update_ai_question'])->name('update_ai_question');
    Route::post('admin/ai/question/delete/{id}', [AdminSettingsController::class, 'delete_ai_question'])->name('delete_ai_question');
    

    Route::resource('admin/discounts', DiscountController::class);
    Route::post('admin/discounts/duplicate', [DiscountController::class, 'discounts_duplicate'])->name('discounts_duplicate');
    Route::get('admin/redeemed-discount-users', [DiscountController::class, 'redeemed_discount_users'])->name('redeemed_discount_users');
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
    Route::get('admin/commands/update-product-prices', [AdminCommandsController::class, 'update_product_prices'])->name('update_product_prices');
    Route::get('admin/empty-failed-jobs', [AdminSettingsController::class, 'empty_failed_jobs'])->name('empty_failed_jobs');
    Route::get('admin/commands/import_specific_contact', [AdminCommandsController::class, 'import_specific_contact'])->name('import_specific_contact');


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
    Route::post('/admin/buy-list/update/{id}', [AdminBuyListController::class, 'update_buy_list']);
    Route::post('admin/generate-list', [AdminBuyListController::class, 'genrateList']);
    Route::post('admin/share-list', [AdminShareListController::class, 'shareList']);
    Route::get('admin/admin-users', [UserController::class, 'adminUsers']);
    Route::get('admin/get-parent', [ContactController::class, 'getParent']);
    Route::post('admin/assign-parent-child', [ContactController::class, 'assingParentChild']);
    Route::get('admin/user-switch/{id}/{contactId}/{admin_id}', [UserController::class, 'switch_user']);
    Route::get('admin/send-password/{id}', [UserController::class, 'send_password'])->name('users.send_password');
    Route::get('admin/go-back', [UserController::class, 'switch_user_back'])->name('users.switch_user_back');
    Route::get('admin/api-sync-logs', [LogsController::class, 'index']);

    Route::get('admin/daily_api_logs', [DailyApiLogController::class, 'index']);
    Route::get('/update-all-products', [DailyApiLogController::class, 'update_all_products'])->name('update-all-products');
    
    Route::post('admin/orders/create/label', [OrderController::class, 'create_label']);
    Route::get('admin/order/label/download/{filename}', [OrderController::class, 'download_label'])->name('download_label');
    Route::post('admin/customer/update-order-status', [OrderController::class, 'update_order_status'])->name('update_order_status');
    Route::post('admin/order/update-order-status', [OrderController::class, 'update_order_status_by_admin'])->name('update_order_status_by_admin');
    Route::post('admin/update_user_job', [ContactController::class, 'update_user_job'])->name('update_user_job');
    Route::post('admin/send-wholesale-order-to-shipstation', [OrderManagementController::class, 'send_wholesale_order_to_shipstation'])->name('send_wholesale_order_to_shipstation');
    Route::post('admin/send-buy-list-order-to-shipstation', [OrderManagementController::class, 'send_buy_list_order_to_shipstation'])->name('send_buy_list_order_to_shipstation');
    Route::post('admin/send-po-box-wholesale-order-to-shipstation', [OrderManagementController::class, 'send_po_box_wholesale_order_to_shipstation'])->name('send_po_box_wholesale_order_to_shipstation');
    Route::post('admin/send-confirmation-email', [OrderManagementController::class, 'send_confirmation_email'])->name('send_confirmation_email');


    // send orer to shipstation
    Route::post('admin/send-order-to-shipstation', [OrderManagementController::class, 'send_order_to_shipstation'])->name('send_order_to_shipstation');

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
Route::get('/products/{id}/{option_id}/{slug}/get-similar-products', [ProductController::class, 'getSimilarProducts']);
Route::get('/user/', [UserController::class, 'userRegistration'])->name('user');
Route::post('api/fetch-cities', [UserController::class, 'fetchCity']);
// Route::post('/login/', [UserController::class, 'process_login'])->name('login');
// Route::post('/user-contact/', [UserController::class, 'save_contact'])->name('save_contact');
// Route::post('/update-contact/', [UserController::class, 'update_contact'])->name('update_contact');

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
Route::get('cart', [ProductController::class, 'cart'])->name('cart');

// Route::get('/product-brand/{name}', [ProductController::class, 'showProductByBrands']);
// Route::post('add-to-cart/', [ProductController::class, 'addToCart'])->name('add.to.cart');
// Route::get('/remove/{id}', [ProductController::class, 'removeProductByCategory']);
// Route::post('update-cart', [ProductController::class, 'updateCart'])->name('update.cart');
// Route::get('checkout', [CheckoutController::class, 'index'])->name('checkout');
// Route::post('order', [OrderController::class, 'store'])->name('order');
// Route::get('/thankyou/{id}', [CheckoutController::class, 'thankyou'])->name('thankyou');
// Route::post('order-status-update', [OrderController::class, 'updateStatus'])->name('order.status.update');
//Route::post('change-password', [ChangePasswordController::class, 'store'])->name('change.password');
// Route::get('/contact-us/', [ContactUsController::class, 'index']);
// Route::post('/contact-us-store/', [ContactUsController::class, 'store'])->name('contact.us.store');
// Route::get('/create-cart/{id}', [CreateCartController::class, 'create_cart'])->name('create.cart');
// Route::post('/add-to-wish-list/', [ProductController::class, 'addToWishList']);
// Route::get('/get-wish-lists/', [ProductController::class, 'getWishLists']);
// Route::get('/get-lists-names/', [ProductController::class, 'getListNames']);
// Route::post('/create-list/', [ProductController::class, 'createList']);
Route::post('/multi-favorites-to-cart/', [ProductController::class, 'multi_favorites_to_cart']);
Route::get('/order/items/{id}', [ProductController::class, 'order_items']);
Route::post('/buy/order/items', [ProductController::class, 'buy_again_order_items']);
Route::get('/products/buy-again', [ProductController::class, 'buy_again']);
Route::post('/see-similar-products', [ProductController::class, 'see_similar_products']);

Route::group(['middleware' => ['auth']], function () {
    Route::resource('admin/roles', RoleController::class);
    Route::resource('admin/users', UserController::class);
    // Route::resource('admin/roles', RoleController::class);
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
    Route::post('admin/auto-create-label', [AdminSettingsController::class, 'autoCreateLabel']);
    Route::get('admin/admin-users', [UserController::class, 'adminUsers']);
    Route::get('admin/get-parent', [ContactController::class, 'getParent']);
    Route::get('admin/get-parent', [ContactController::class, 'getParent']);
    Route::post('admin/refresh-contact', [ContactController::class, 'refreshContact']);
    Route::post('admin/disable-secondary', [ContactController::class, 'disableSecondary']);
    Route::get('admin/send-password/{id}', [UserController::class, 'send_password'])->name('users.send_password');
    Route::get('admin/go-back', [UserController::class, 'switch_user_back'])->name('users.switch_user_back');
    Route::get('/site', [UserController::class, 'switch_admin'])->name('switch_admin');
    Route::resource('admin/inventory-locations', AdminInventoryLocationController::class);
    Route::resource('admin/wholesale-applications', WholesaleApplicationController::class);
    Route::post('/admin/wholesale-application/approve', [WholesaleApplicationController::class, 'wholesale_application_approve'])->name('wholesale_application_approve');
    Route::get('/admin/recycle-bin', [AdminSettingsController::class, 'recycle_bin'])->name('recycle_bin');
    Route::post('/admin/restore/contact/{id}', [AdminSettingsController::class, 'restore_contact'])->name('restore_contact');
    Route::post('/admin/delete/contact/permanent/{id}', [AdminSettingsController::class, 'delete_contact_permanently'])->name('delete_contact_permanently');
    Route::get('/admin/contact-logs', [AdminSettingsController::class, 'contact_logs'])->name('contact_logs');

    // enable /disable shipping price 
    Route::post('admin/enable-shipping-price', [ContactController::class, 'enableShippingPrice']);
    Route::post('admin/disable-shipping-price', [ContactController::class, 'disableShippingPrice']);
    Route::post('admin/update/product/price', [AdminProductController::class, 'update_product_price'])->name('update_product_price');


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
        Route::get('/shipping-quotes', [AdminSettingsController::class, 'shipping_quotes'])->name('shipping_quotes');
        Route::post('/update-shipping-quotes', [AdminSettingsController::class, 'update_shipping_quotes'])->name('update_shipping_quotes');

        Route::get('/shipping-quotes/settings/index', [ShippingQuoteSettingController::class, 'index'])->name('shipping_quotes.settings.index');
        Route::get('/shipping-quotes/settings/create', [ShippingQuoteSettingController::class, 'create'])->name('shipping_quotes.settings.create');
        Route::post('/shipping-quotes/settings/store', [ShippingQuoteSettingController::class, 'store'])->name('shipping_quotes.settings.store');
        Route::get('/shipping-quotes/settings/edit/{id}', [ShippingQuoteSettingController::class, 'edit'])->name('shipping_quotes.settings.edit');
        Route::post('/shipping-quotes/settings/update/{id}', [ShippingQuoteSettingController::class, 'update'])->name('shipping_quotes.settings.update');
        Route::post('/shipping-quotes/settings/delete/{id}', [ShippingQuoteSettingController::class, 'delete'])->name('shipping_quotes.settings.delete');

        // auto label setting 

        Route::get('/label-settings', [AdminSettingsController::class, 'show_label_settings'])->name('show_label_settings');
        Route::post('/update-label-settings', [AdminSettingsController::class, 'update_label_settings'])->name('update_label_settings');
        //cin7 keys settings
        Route::get('/cin7-api-keys-settings', [AdminSettingsController::class, 'cin7_api_keys_settings'])->name('cin7_api_keys_settings');

        Route::post('/cin7-api-keys-settings/stop-api', [AdminSettingsController::class, 'stop_cin7_api'])->name('stop_cin7_api');
        
        // udpate key thresold
        Route::post('/cin7-api-keys-settings/update-threshold', [AdminSettingsController::class, 'update_cin7_api_threshold'])->name('update_cin7_api_threshold');
        Route::get('/reset-cin7-api-keys', [AdminCommandsController::class, 'reset_cin7_api_keys'])->name('reset_cin7_api_keys');
        
        Route::post('/mark/order/shipped', [OrderController::class, 'mark_order_shipped'])->name('mark_order_shipped');
        
        Route::get('/get-shipstation-api-logs', [AdminSettingsController::class, 'get_shipstation_api_logs'])->name('get_shipstation_api_logs');
        Route::get('/get-cin7-payment-logs', [AdminSettingsController::class, 'get_cin7_payment_logs'])->name('get_cin7_payment_logs');

        Route::get('/payouts', [OrderController::class, 'payouts'])->name('payouts');
        Route::get('/payout/details/{id}', [OrderController::class, 'payouts_details'])->name('admin.payouts.details');
        Route::get('/transactions_export/{id}', [OrderController::class, 'transactions_export'])->name('admin.transactions_export');
        Route::get('/images-requests', [AdminSettingsController::class, 'images_requests'])->name('admin.images_requests');
        Route::get('/images/requests/approve/{id}', [AdminSettingsController::class, 'images_requests_approve'])->name('images_requests_approve');
        // Route::post('/payout-details', [OrderController::class, 'payout_details'])->name('payout_details');


        // stock report settings

        Route::get('/stock-report-settings', [AdminSettingsController::class, 'admin_stock_report_settings'])->name('admin_stock_report_settings');
        Route::post('/update-stock-report-settings', [AdminSettingsController::class, 'admin_update_stock_report_settings'])->name('admin_update_stock_report_settings');
        Route::get('/send-stock-summary-emails', [AdminCommandsController::class, 'send_stock_summary_emails'])->name('send_stock_summary_emails');

        
        Route::get('/sales-report', [SalesReportController::class, 'index'])->name('sales-report.index');
        Route::post('/sales-report/import', [SalesReportController::class, 'importStripeTransactions'])->name('sales-report.import');
        Route::get('/sales-report/export/{type}', [SalesReportController::class, 'export'])->name('sales-report.export');


        
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
Route::get('/google-reviews', [HomeController::class, 'fetchReviews'])->name('fetchReviews');
Route::get('/get_google_reviews', [HomeController::class, 'get_google_reviews'])->name('get_google_reviews');
Route::get('/customer/invitation/{hash}', [ContactController::class, 'customer_invitation']);
Route::group(['middleware' => ['auth']], function () {
});




Route::get('/index', [UserController::class, 'index_email_view']);
Route::get('/event', [CheckoutController::class, 'event']);

Route::resource('admin/operational-zip-codes', OperationalZipCodeController::class);
Route::post('/order/mark/paid', [OrderController::class, 'mark_order_paid']);
Route::post('admin/search/customer', [AdminSettingsController::class, 'search_customer']);
Route::get('/page/{slug}', [HomeController::class, 'show_page']);
Route::post('page/blogs/search', [PagesController::class, 'blog_search'])->name('blog_search');
Route::get('admin/page/blog/detail/{slug}', [PagesController::class, 'blog_detail'])->name('blog_detail');

Route::post('product-stock/notification', [ProductStockNotificationController::class, 'notify_user_about_product_stock'])->name('notify_user_about_product_stock');
Route::get('admin/notify-users', [AdminSettingsController::class, 'notify_users'])->name('notify_users');
Route::get('admin/notify-users/delete/{id}', [AdminSettingsController::class, 'delete_product_stock_notification_user'])->name('delete_product_stock_notification_user');
Route::post('admin/product-stock-notification', [AdminSettingsController::class, 'product_stock_notification'])->name('product_stock_notification');
Route::get('admin/all-admins', [AdminSettingsController::class, 'all_admins'])->name('all_admins');
Route::post('admin/send-email-to-specific-admins', [AdminSettingsController::class, 'send_email_to_specific_admin'])->name('send_email_to_specific_admin');
Route::get('check-existing-email', [CheckoutController::class, 'check_existing_email'])->name('check_existing_email');
Route::post('authenticate-user', [CheckoutController::class, 'authenticate_user'])->name('authenticate_user');
Route::post('apply-discount-code', [CheckoutController::class, 'apply_discount_code'])->name('apply_discount_code');
Route::get('get-product-dimension', [GetProductDimensionController::class, 'get_product_dimension'])->name('get_product_dimension');
Route::post('admin/search/aletrnative/products', [ProductStockNotificationController::class, 'search_alternate_products'])->name('search_alternate_products');
Route::post('admin/send/alternative/notification', [ProductStockNotificationController::class, 'add_alternative_product'])->name('add_alternative_product');
Route::post('admin/alternative/products/history', [ProductStockNotificationController::class, 'alternate_products_history'])->name('alternate_products_history');
Route::post('admin/notify/user/product/history', [ProductStockNotificationController::class, 'notify_users_from_alternate_history'])->name('notify_users_from_alternate_history');
Route::get('/order/cin7-payment/{order_reference}', [OrderController::class, 'cin7_payments'])->name('cin7_payments');
Route::get('/cin7/payment/success/{orderId}', [OrderController::class, 'cin7_payments_success'])->name('cin7_payments_success');

// newsletter module 
Route::get('/newsletter/dashboard', [NewsletterController::class, 'newsletter_dashboard'])->name('newsletter_dashboard');
Route::get('/newsletter/subscribers', [NewsletterController::class, 'newsletter_subscriptions'])->name('newsletter_subscriptions');
Route::post('/subscribe/newsletter', [HomeController::class, 'subscribe_newsletter'])->name('subscribe_newsletter');



// Newsletter Template routes
Route::get('/newsletter-templates', [NewsletterTemplateController::class, 'index'])->name('newsletter-templates.index');
Route::get('/newsletter-templates/create', [NewsletterTemplateController::class, 'create'])->name('newsletter-templates.create');
Route::get('/newsletter-templates/detail/{id}', [NewsletterTemplateController::class, 'newsletter_templates_detail'])->name('newsletter_templates_detail');
Route::post('/newsletter-templates/delete/{id}', [NewsletterTemplateController::class, 'delete_newsletter_template'])->name('delete_newsletter_template');
Route::post('/newsletter-templates/duplicate/{id}', [NewsletterTemplateController::class, 'duplicate_newsletter_template'])->name('duplicate_newsletter_template');
Route::get('/newsletter-templates/edit/{id}', [NewsletterTemplateController::class, 'edit_newsletter_template'])->name('edit_newsletter_template');
Route::post('/newsletter-templates/update/{id}', [NewsletterTemplateController::class, 'update_newsletter_template'])->name('update_newsletter_template');
Route::post('/newsletter-templates', [NewsletterTemplateController::class, 'store'])->name('newsletter-templates.store');
Route::post('/newsletter-templates/upload/image', [NewsletterTemplateController::class, 'upload_newsletterImage'])->name('upload_newsletterImage');


//sales payments
Route::get('/sale/payments', [SalePaymentsController::class, 'sale_payments'])->name('sale_payments');
Route::get('/sale/payments/show/{Id}', [SalePaymentsController::class, 'sale_payments_show'])->name('sale-payments.show');



// payouts 

Route::get('/payouts', [PayoutController::class, 'payouts'])->name('payouts');
Route::get('/payout/details/{id}', [PayoutController::class, 'payouts_details'])->name('payouts.details');
Route::get('/transactions_export/{id}', [PayoutController::class, 'transactions_export'])->name('transactions_export');


// Assign templates to users
Route::get('/assign/template', [NewsletterController::class, 'showAssignForm'])->name('assign_template_form');
Route::post('/assign', [NewsletterController::class, 'assignTemplates'])->name('assign.templates');
Route::get('/assign-templates-view', [NewsletterController::class, 'view_assigned_templates'])->name('view_assigned_templates');
Route::get('/edit-assigned-template/{id}', [NewsletterController::class, 'edit_assigned_template'])->name('edit_assigned_template');
Route::post('/edit-assigned-template/{id}', [NewsletterController::class, 'update_assigned_template'])->name('update_assigned_template');
Route::post('/delete-assigned-template/{id}', [NewsletterController::class, 'delete_assigned_template'])->name('delete_assigned_template');
Route::post('/send-newsletter/{id}', [NewsletterController::class, 'send_newspaper'])->name('send_newspaper');
Route::get('/all-contacts', [NewsletterController::class, 'all_contacts'])->name('all_contacts');

// subscribers list
Route::get('subscribers/list/index', [NewsletterController::class, 'subscribers_list'])->name('subscribers_list');
Route::get('subscribers/list/create', [NewsletterController::class, 'subscribers_list_create'])->name('subscribers_list_create');
Route::post('subscribers/list/store', [NewsletterController::class, 'subscribers_list_store'])->name('subscribers_list_store');
Route::get('subscribers/list/edit/{id}', [NewsletterController::class, 'subscribers_list_edit'])->name('subscribers_list_edit');
Route::post('subscribers/list/update/{id}', [NewsletterController::class, 'subscribers_list_update'])->name('subscribers_list_update');
Route::post('subscribers/list/delete/{id}', [NewsletterController::class, 'subscribers_list_delete'])->name('subscribers_list_delete');
Route::get('/list/show/users/{id}', [NewsletterController::class, 'subscribers_list_show_users'])->name('subscribers_list_show_users');


// save users to list

Route::post('save-users-to-list', [NewsletterController::class, 'save_users_to_list'])->name('save_users_to_list');
Route::post('user/list/delete/{id}', [NewsletterController::class, 'delete_user_from_list'])->name('delete_user_from_list');
Route::post('/import-subscribers', [NewsletterController::class, 'importSubscribers'])->name('subscribers.import');
Route::post('/bulk/upload', [NewsletterController::class, 'bulk_upload'])->name('subscribers_bulk_upload');

// delete selected emails 

Route::post('delete-selected-emails', [NewsletterController::class, 'delete_selected_emails'])->name('delete_selected_emails');

// add new user to list 
Route::post('/list/subscribers/add', [NewsletterController::class, 'add_subscriber_to_list'])->name('add_subscriber_to_list');
Route::post('/bulk/upload/users/list', [NewsletterController::class, 'bulk_upload_to_list'])->name('bulk_upload_to_list');
Route::post('/import/users/list', [NewsletterController::class, 'importUsersToList'])->name('import_users_to_list');


// submit bulk products request 

Route::post('/bulk/products/request', [ProductController::class, 'bulk_products_request'])->name('bulk_products_request');
Route::get('/scrape/product/image/{id}', [ProductController::class, 'scrape_product_image'])->name('scrape_product_image');
Route::post('/add-to-catalog', [ProductController::class, 'addToCatalog'])->name('addToCatalog');


// create sms list

Route::get('/sms/list/index', [SmsController::class, 'sms_list'])->name('sms_list');
Route::get('/sms/list/create', [SmsController::class, 'sms_list_create'])->name('sms_list_create');
Route::post('/sms/list/store', [SmsController::class, 'sms_list_store'])->name('sms_list_store');
Route::get('/sms/list/edit/{id}', [SmsController::class, 'sms_list_edit'])->name('sms_list_edit');
Route::post('/sms/list/update/{id}', [SmsController::class, 'sms_list_update'])->name('sms_list_update');
Route::post('/sms/list/delete/{id}', [SmsController::class, 'sms_list_delete'])->name('sms_list_delete');


//add mobile number to list
Route::post('/list/numbers/add', [SmsController::class, 'add_mobile_numbers_to_list'])->name('add_mobile_numbers_to_list');

// add sms template
Route::get('/sms/templates', [SmsController::class, 'list_sms_templates'])->name('list_sms_templates');
Route::get('/sms/templates/create', [SmsController::class, 'create_sms_templates'])->name('create_sms_templates');
Route::get('/sms/templates/edit/{id}', [SmsController::class, 'edit_sms_templates'])->name('edit_sms_templates');
Route::post('/sms/templates/update/{id}', [SmsController::class, 'update_sms_templates'])->name('update_sms_templates');
Route::post('/sms/templates/store', [SmsController::class, 'store_sms_templates'])->name('store_sms_templates');
Route::post('/sms/templates/delete/{id}', [SmsController::class, 'delete_sms_templates'])->name('delete_sms_templates');
Route::get('/sms/templates/detail/{id}', [SmsController::class, 'sms_detail'])->name('sms_detail');
Route::post('/sms/templates/duplicate/{id}', [SmsController::class, 'sms_template_duplicate'])->name('sms_template_duplicate');

Route::post('/sms-template/upload/image', [SmsController::class, 'upload_sms_templateImage'])->name('upload_sms_templateImage');


//adding numbers to list
Route::post('add_mobile_numbers_to_list', [SmsController::class, 'add_mobile_numbers_to_list'])->name('add_mobile_numbers_to_list');
Route::post('number/list/delete/{id}', [SmsController::class, 'delete_number_from_list'])->name('delete_number_from_list');
Route::get('/list/show/numbers/{id}', [SmsController::class, 'show_numbers_from_list'])->name('show_numbers_from_list');




Route::post('bulk-upload-numbers-to-list', [SmsController::class, 'bulk_upload_numbers_to_list'])->name('bulk_upload_numbers_to_list');
Route::post('import-numbers-to-list', [SmsController::class, 'import_numbers_to_list'])->name('import_numbers_to_list');

Route::post('/send-sms/{id}', [SmsController::class, 'send_sms'])->name('send_sms');

// thankyou page
Route::get('/thank-you', [ContactUsController::class, 'thankyou_page'])->name('thankyou_page');
Route::get('/ai-answer', [ProductController::class, 'ai_answer'])->name('ai_answer');
Route::post('/add-new-address', [UserController::class, 'add_new_address'])->name('add_new_address');
Route::post('/notifyOutOfStock', [ProductController::class, 'notifyOutOfStock'])->name('notifyOutOfStock');
Route::post('/updateItemQuantitytoOriginal', [ProductController::class, 'updateItemQuantitytoOriginal'])->name('updateItemQuantitytoOriginal');
Route::post('/removeOutOfStock', [ProductController::class, 'removeOutOfStock'])->name('removeOutOfStock');
Route::get('/PackingSlip', [ProductController::class, 'PackingSlip'])->name('PackingSlip');
Route::get('/retriveProducts', [ProductController::class, 'retriveProducts'])->name('retriveProducts');
Route::post('/order/reminder/store', [CheckoutController::class, 'store_order_reminder'])->name('store_order_reminder');
Route::get('/re-order/{id}', [CheckoutController::class, 're_order'])->name('re_order');





// get filter products from pythone api

// Route::get('/filter-products', [ProductController::class, 'filter_products'])->name('filter_products');
// Route::post('/send-data-flask', [ProductController::class, 'sendDataToFlask']);




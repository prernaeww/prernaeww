<?php

use Illuminate\Support\Facades\Route;

// use App\Http\Controllers\Admin\UserController;
// use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\NotificationController;
use App\Http\Controllers\Admin\SendMailController;
use App\Http\Controllers\Admin\CMSController;
use Laravel\Socialite\Facades\Socialite;


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

Route::get('home', 'Website\HomeController@index')->name('home');
Route::get('appleredirect', 'Website\HomeController@appleredirect')->name('appleredirect');
Route::post('applecallback', 'AppleSocialController@handleCallback')->name('applecallback');


Route::post('store_list', 'Website\HomeController@store_list')->name('store_list');
Route::get('deals', 'Website\HomeController@deals')->name('deals');
Route::get('store-detail/{id}', 'Website\HomeController@store_detail')->name('store_detail');
Route::post('search_product', 'Website\HomeController@search_product')->name('search_product');
Route::post('add_remove_fav_store', 'Website\FavoriteStoreController@add_remove_fav_store')->name('add_remove_fav_store');
Route::get('products/{id}/{category}', 'Website\HomeController@product_view_all')->name('product_view_all');
Route::get('facebook-redirect', 'Website\HomeController@facebook_login')->name('facebook-redirect');
Route::get('google-login/{id}', 'Website\HomeController@google_login')->name('google-login');
Route::get('password/reset/{id}', 'CommonController@password_reset')->name('password/reset');
Route::post('password/update', 'CommonController@password_update')->name('password/update');
Route::get('phone/add/{id}', 'Website\AccountController@add_phone_number')->name('phone/add');


Route::get('auth/redirect', function () {
    return Socialite::driver('google')->redirect();
});
Route::get('auth/callback', 'Website\HomeController@google_login')->name('auth/callback');

Route::post('add_remove_fav_product', 'Website\FavoriteController@add_remove_fav_product')->name('add_remove_fav_product');
Route::get('signup', 'Website\HomeController@signup')->name('signup');
Route::post('register_customer', 'Website\HomeController@register_customer')->name('register_customer');
Route::get('send_otp', 'Website\HomeController@send_otp')->name('send_otp');
Route::post('send_otp_mobile', 'Website\HomeController@send_otp_mobile')->name('send_otp_mobile');
Route::get('product/{id}/{id1}', 'Website\HomeController@product')->name('product');

Route::post('add-to-cart', 'Website\CartController@add_to_cart');
Route::post('notification', 'Website\AccountController@notification')->name('notification');

Route::group(['namespace' => 'Website', 'middleware' => ['customer']], function () {
    Route::get('account', 'AccountController@index')->name('account');

    Route::post('update', 'AccountController@update')->name('update');
    Route::post('notification_toggle', 'AccountController@notification_toggle')->name('notification_toggle');

    Route::get('orders/{type}', 'OrderController@index')->name('orders');
    Route::get('order/detail/{id}', 'OrderController@detail')->name('order_details');
    Route::get('order/here/{id}', 'OrderController@i_am_here');
    Route::resource('address', AddressController::class);
    Route::any('add-address', 'AddressController@add')->name('add_address');
    Route::post('delete_address', 'AddressController@delete')->name('delete_address');
    Route::get('cart', 'CartController@index')->name('cart');
    Route::get('checkout', 'CartController@checkout')->name('checkout');
    Route::post('place_order', 'CartController@place_order')->name('place_order');
    Route::post('cart_delete/{id}', 'CartController@destroy');
    Route::post('qty_update', 'CartController@qty_update')->name('qty_update');
    Route::get('cart_verify', 'CartController@cart_verify')->name('cart_verify');

    Route::get('favorite', 'FavoriteController@index')->name('favorite');
});

Route::get('education-outreach', 'Api\DocumentController@education_outreach');
Route::get('contact-us', 'Api\DocumentController@contact_us')->name('contact-us');
Route::get('about-us', 'Website\AboutUsController@index')->name('about-us');
Route::get('internet-based-ads', 'Api\DocumentController@internet_based_ads');
Route::get('privacy-notice', 'Api\DocumentController@privacy_notice');
Route::get('term-of-service', 'Api\DocumentController@term_of_service');

Route::post('add-channel-id', 'CommonController@add_channel_id');
Route::get('account-activation/{id}', 'CommonController@account_activation');
Route::any('create/password/{id}/{token}', 'CommonController@craete_password')->name('create.password');
Route::get('auth-account-activation/{id}', 'CommonController@auth_account_activation');

Route::post('forgot/password', 'CommonController@forgot_password')->name('forgot_password');
Route::get('store-forgot-password', 'CommonController@store_forgot_password')->name('store.forgot');
Route::get('board-forgot-password', 'CommonController@board_forgot_password')->name('board.forgot');
Route::get('customer-forgot-password', 'CommonController@customer_forgot_password')->name('customer.forgot');
Route::post('send_email_otp', 'CommonController@send_email_otp')->name('send_email_otp');

Route::get('/', 'Website\HomeController@index');
Route::get('auto_reject_order', 'CronController@auto_reject_order')->name('auto_reject_order');
Route::get('order_failed', 'CronController@order_failed')->name('order_failed');
Route::get('both_reject', 'CronController@both_reject')->name('both_reject');
Route::get('order_expire', 'CronController@order_expire')->name('order_expire');
Route::get('diet_station', 'CronController@diet_station');
Route::get('invoice/pdf/{id}', 'InvoiceController@pdf');
Route::get('reminder', 'CronController@reminder');
Route::get('test_booky', 'CronController@test_booky');
Route::get('generate_token/{id}', 'CommonController@generate_token')->name('generate_token');
Route::post('make_payment', 'CommonController@make_payment')->name('make_payment');
Route::get('transaction_fail/{id}', 'CommonController@transaction_fail')->name('transaction_fail');
Route::get('transaction_success/{id}/{id1}', 'CommonController@transaction_success')->name('transaction_success');
// Route::get('transaction_success/{id}', 'CommonController@transaction_success')->name('transaction_success');
Route::get('refund/{id}/{id1}', 'CommonController@refund')->name('refund');
Route::get('payment/card', 'CommonController@save_card')->name('save_card');
Route::get('card-list', 'CommonController@save_card_list')->name('save_card_list');
Route::post('card-delete', 'CommonController@save_card_delete')->name('save_card_delete');
Route::post('proceed_to_payment', 'CommonController@proceed_to_payment')->name('proceed_to_payment');




Route::get('add-channel-id', 'CommonController@add_channel_id');

Route::group(['namespace' => 'Admin', 'prefix' => 'admin', 'as' => 'admin.', 'middleware' => ['auth', 'admin']], function () {

    Route::get('profile', 'ProfileController@view')->name('profile');
    Route::post('profile-update/{id}', 'ProfileController@update')->name('profile.update');

    Route::post('change-password/{id}', 'ProfileController@changePassword')->name('change.password');

    Route::get('dashboard', 'DashboardController@index')->name('dashboard');
    Route::get('run_command', 'DashboardController@run_command')->name('run_command');
    Route::get('users', 'UserController@index')->name('users.index');
    Route::get('users/show/{id}', 'UserController@show')->name('user.show');
    Route::get('users/edit/{id}', 'UserController@edit')->name('user.edit');
    Route::put('users/update/{id}', 'UserController@update')->name('user.update');
    Route::get('users/destroy/{id}', 'UserController@edit')->name('user.destroy');
    Route::post('active_deactive', 'UserController@active_deactive')->name('active_deactive');
    Route::post('active_deactive_store_board', 'UserController@active_deactive_store_board')->name('active_deactive_store_board');

    // Route::post('active_deactive_school','SchoolController@active_deactive_school')->name('active_deactive_school');

    // Route::post('active_deactive_category','CategoryController@active_deactive_category')->name('active_deactive_category');

    //  Route::post('active_deactive_family','FamilyController@active_deactive_family')->name('active_deactive_family');

    Route::resource('board', BoardController::class);
    Route::resource('store', StoreController::class);
    Route::resource('category', CategoryController::class);
    Route::resource('measurement', MeasurementController::class);
    Route::resource('school', SchoolController::class);
    Route::resource('issue', IssueController::class);
    Route::resource('grade', GradeController::class);
    Route::resource('banner', BannerController::class);
    Route::resource('orders', OrderController::class);
    Route::resource('family', FamilyController::class);
    Route::resource('product', ProductController::class);
    Route::resource('inventory', InventoryController::class);
    Route::post('data-active-store', 'InventoryController@get_store_data');

    Route::post('product/addstore/{id}', 'ProductController@addstore')->name('product.addstore');
    Route::get('remove_product_store/{id}', 'ProductController@remove_product_store')->name('remove_product_store');
    Route::post('save_product_store', 'ProductController@save_product_store')->name('save_product_store');
    // Route::post('active_deactive_product','ProductController@active_deactive_product')->name('active_deactive_product');
    Route::post('get_image', 'ProductController@get_image')->name('get_image');

    Route::post('order_notification_customer', 'OrderController@order_notification_customer')->name('order_notification_customer');

    Route::get('reported/issue', 'IssueController@reported_issue_index')->name('reported.issue');
    Route::get('bulk/notification', [NotificationController::class, 'index'])->name('bulk.index');
    Route::post('bulk/notification', [NotificationController::class, 'store'])->name('bulk.store');

    Route::get('system/config', 'SystemConfigController@index')->name('system.config');
    Route::post('system/config/submit', 'SystemConfigController@update')->name('system.config.submit');
    Route::post('system/config/backup', 'SystemConfigController@update')->name('system.config.backup');
    Route::post('system/config/download', 'SystemConfigController@update')->name('system.config.download');
    Route::post('system/config/delete_backup', 'SystemConfigController@update')->name('system.config.delete_backup');

    Route::get('app/config', 'SystemConfigController@app')->name('app.config');
    Route::post('app/config/submit', 'SystemConfigController@submit_app')->name('app.config.submit');

    Route::get('report/all_report', 'ReportController@index')->name('report.all');

    Route::get('order/all_orders', 'OrderController@all_orders')->name('order.all');

    Route::get('order/create_order', 'OrderController@create_order')->name('order.create');

    Route::post('order/get_user', 'OrderController@get_user')->name('order.get_user');
    Route::post('order/get_meal', 'OrderController@get_meal')->name('order.get_meal');
    Route::post('order/get_meal_details', 'OrderController@get_meal_details')->name('order.get_meal_details');
    Route::post('order/book_meal', 'OrderController@book_meal')->name('order.book_meal');

    Route::get('order/refund', 'OrderController@refund')->name('order.refund');
    Route::get('order/all_orders/view/{id}', 'OrderController@all_orders_view')->name('order.all.view');
    Route::post('refund', 'OrderController@refund_test')->name('refund');

    Route::any('send-mail-store', [SendMailController::class, 'store'])->name('mail.store');
    Route::any('send-mail-board', [SendMailController::class, 'board'])->name('mail.board');
    Route::any('send-mail-customer', [SendMailController::class, 'customer'])->name('mail.customer');

    Route::any('terms-conditions', [CMSController::class, 'terms_conditions'])->name('terms_conditions');
    Route::any('privacy-policy', [CMSController::class, 'privacy_policy'])->name('privacy_policy');
    Route::any('interest-bases-ads', [CMSController::class, 'interest_bases_ads'])->name('interest_bases_ads');
    Route::any('education-out-reach', [CMSController::class, 'education_out_reach'])->name('education_out_reach');
});
Route::group(['namespace' => 'Board', 'prefix' => 'board', 'as' => 'board.', 'middleware' => ['auth', 'board']], function () {

    Route::get('profile', 'ProfileController@view')->name('profile');
    Route::post('profile-update/{id}', 'ProfileController@update')->name('profile.update');

    Route::post('change-password/{id}', 'ProfileController@changePassword')->name('change.password');

    Route::get('dashboard', 'DashboardController@index')->name('dashboard');
    Route::post('active_deactive_category', 'CategoryController@active_deactive_category')->name('active_deactive_category');
    Route::resource('category', CategoryController::class);
    Route::resource('store', StoreController::class);
    Route::resource('banner', BannerController::class);
    Route::resource('product', ProductController::class);
    Route::resource('orders', OrderController::class);
    Route::resource('meal', MealController::class);
    Route::post('order_notification_customer', 'OrderController@order_notification_customer')->name('order_notification_customer');

    Route::get('report/all_report', 'ReportController@all_report')->name('report.all');

    Route::get('order/all', 'OrderController@all')->name('order.all');
    Route::get('order/all/view/{id}', 'OrderController@all_orders_view')->name('order.all.view');

    Route::get('order/inprocess', 'OrderController@inprocess')->name('order.inprocess');
    Route::get('order/inprocess/view/{id}', 'OrderController@inprocess_view')->name('order.inprocess.view');
    Route::get('order/inprocess/edit/{id}', 'OrderController@inprocess_edit')->name('order.inprocess.edit');
    Route::post('order/inprocess/item/update', 'OrderController@item_update')->name('order.item.update');
    Route::post('order/inprocess/date/update', 'OrderController@date_update')->name('order.date.update');

    Route::post('active_deactive_product', 'ProductController@active_deactive_product')->name('active_deactive_product');
    Route::post('active_deactive_meal', 'MealController@active_deactive_meal')->name('active_deactive_meal');

    Route::post('pending_order', 'OrderController@pending_order')->name('pending_order');

    Route::get('order/completed', 'OrderController@completed')->name('order.completed');
    Route::get('order/completed/view/{id}', 'OrderController@completed_view')->name('order.completed.view');

    Route::get('order/fail', 'OrderController@fail')->name('order.fail');
    Route::get('order/fail/view/{id}', 'OrderController@fail_view')->name('order.fail.view');
});

Route::group(['namespace' => 'Store', 'prefix' => 'store', 'as' => 'store.', 'middleware' => ['auth', 'store']], function () {

    Route::get('profile', 'ProfileController@view')->name('profile');
    Route::post('profile-update/{id}', 'ProfileController@update')->name('profile.update');

    Route::post('change-password/{id}', 'ProfileController@changePassword')->name('change.password');

    Route::get('dashboard', 'DashboardController@index')->name('dashboard');
    Route::post('active_deactive_category', 'CategoryController@active_deactive_category')->name('active_deactive_category');
    Route::resource('category', CategoryController::class);
    Route::resource('product', ProductController::class);
    Route::resource('orders', OrderController::class);
    Route::resource('meal', MealController::class);

    Route::post('order_notification_customer', 'OrderController@order_notification_customer')->name('order_notification_customer');

    Route::get('report/all_report', 'ReportController@all_report')->name('report.all');

    Route::get('order/all', 'OrderController@all')->name('order.all');
    Route::get('order/all/view/{id}', 'OrderController@all_orders_view')->name('order.all.view');

    Route::get('order/inprocess', 'OrderController@inprocess')->name('order.inprocess');
    Route::get('order/inprocess/view/{id}', 'OrderController@inprocess_view')->name('order.inprocess.view');
    Route::get('order/inprocess/edit/{id}', 'OrderController@inprocess_edit')->name('order.inprocess.edit');
    Route::post('order/inprocess/item/update', 'OrderController@item_update')->name('order.item.update');
    Route::post('order/inprocess/date/update', 'OrderController@date_update')->name('order.date.update');

    Route::post('order/accept', 'OrderController@accept')->name('order.accept');
    Route::post('order/reject', 'OrderController@reject')->name('order.reject');
    Route::post('order/ready-to-pickup', 'OrderController@readytopickup')->name('order.readytopickup');
    Route::post('order/delivered', 'OrderController@delivered')->name('order.delivered');

    Route::post('active_deactive_product', 'ProductController@active_deactive_product')->name('active_deactive_product');
    Route::post('active_deactive_meal', 'MealController@active_deactive_meal')->name('active_deactive_meal');

    Route::post('pending_order', 'OrderController@pending_order')->name('pending_order');

    Route::get('order/completed', 'OrderController@completed')->name('order.completed');
    Route::get('order/completed/view/{id}', 'OrderController@completed_view')->name('order.completed.view');

    Route::get('order/fail', 'OrderController@fail')->name('order.fail');
    Route::get('order/fail/view/{id}', 'OrderController@fail_view')->name('order.fail.view');
});

Route::get('test_generate_token/{id}', 'CommonController@test_generate_token')->name('test_generate_token');
Route::any('test_make_payment', 'CommonController@test_make_payment')->name('test_make_payment');

require __DIR__ . '/auth.php';
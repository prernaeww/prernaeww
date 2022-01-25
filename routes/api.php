<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\LoginController;
use App\Http\Controllers\Api\ProfileController;
use App\Http\Controllers\Api\MealController;
use App\Http\Controllers\Api\ContactUsController;
use App\Http\Controllers\Api\FavoriteStoreController;
use App\Http\Controllers\Api\StoreController;
use App\Http\Controllers\Api\FavoriteProductController;
use App\Http\Controllers\Api\AddressController;
use App\Http\Controllers\Api\CartController;
use App\Http\Controllers\Api\CardController;
use App\Http\Controllers\Api\OrderController;
// use App\Http\Controllers\Api\TestingController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*

|--------------------------------------------------------------------------

| API Routes

|--------------------------------------------------------------------------

|

| Here is where you can register API routes for your application. These

| routes are loaded by the RouteServiceProvider within a group which

| is assigned the "api" middleware group. Enjoy building your API!

|

 */

Route::get('/init/{type?}/{app_version?}', [AuthController::class, 'init'])->middleware('localization');

Route::post('/login', [AuthController::class, 'login'])->middleware('localization');

Route::post('/register', [AuthController::class, 'register'])->middleware('localization');

Route::post('/change_password_otp', [ProfileController::class, 'change_password_otp'])->middleware('localization');

Route::post('/forgot_password', [AuthController::class, 'forgot_password'])->middleware('localization');

Route::post('social-login', [LoginController::class, 'socialLogin'])->middleware('localization');

Route::get('apple-details/{id}', [LoginController::class, 'appleDetails'])->middleware('localization');

Route::get('/school_list', [AuthController::class, 'school_list'])->middleware('localization');

Route::post('/test_upload', [AuthController::class, 'test_upload']);

Route::post('/check_email', [AuthController::class, 'check_email']);

Route::post('/contact_us', [ContactUsController::class, 'index']);


Route::post('payment', 'Api\PaymentController@payment');

Route::get('payment/success', 'Api\PaymentController@success');

Route::get('payment/fail', 'Api\PaymentController@fail');

Route::get('payment/success_test', 'Api\PaymentController@success_test');



Route::middleware('auth:api')->get('/user', function (Request $request) {

    return $request->user();

});



Route::group(['middleware' => ['auth:api','localization']], function () {

    Route::get('/user', function (Request $request) {

        return $request->user();

    });

    Route::post('/childrens', [ProfileController::class, 'children_list']);

    Route::post('/change_password', [ProfileController::class, 'change_password']);   
    

    Route::post('/profile_view_edit', [ProfileController::class, 'profile_view_edit']);

    Route::post('/delete_children', [ProfileController::class, 'delete_children']);

    

    Route::post('/home', [MealController::class, 'home']);

    Route::post('/get_all_meals', [MealController::class, 'get_all_meals']);

    Route::post('/get_meal_detail', [MealController::class, 'get_meal_detail']);

    Route::post('/get_category_wise_products', [MealController::class, 'get_category_wise_products']);

    Route::post('/get_school_details', [MealController::class, 'get_school_details']);

    Route::post('/date_wise_meal_plan', [MealController::class, 'date_wise_meal_plan']);

    Route::post('/checkout_screen_details', [MealController::class, 'checkout_screen_details']);

    Route::post('/book_meal', [MealController::class, 'book_meal']);

    Route::post('/get_order_details', [MealController::class, 'get_order_details']);

    Route::post('/get_current_order_list', [MealController::class, 'get_current_order_list']);

    Route::post('/get_past_order_list', [MealController::class, 'get_past_order_list']);

    Route::get('/issue_list', [MealController::class, 'issue_list']);

    Route::post('/report_issue', [MealController::class, 'report_issue']);

    Route::post('/get_day_wise_order', [MealController::class, 'get_day_wise_order']);

    Route::post('/get_invoices', [MealController::class, 'get_invoices']);

    Route::post('/favourite_unfavourite_product', [MealController::class, 'favourite_unfavourite_product']);

    Route::post('/blacklist_unblacklist_product', [MealController::class, 'blacklist_unblacklist_product']);

    Route::post('/list_of_favourite', [MealController::class, 'list_of_favourite']);

    Route::post('/list_of_blacklist', [MealController::class, 'list_of_blacklist']);

    Route::post('/search', [MealController::class, 'search']);

    Route::post('/get_all_products', [MealController::class, 'get_all_products']);

    Route::post('/change_order_items', [MealController::class, 'change_order_items']);

    Route::post('/change_order_date', [MealController::class, 'change_order_date']);

    // Route::post('/contact_us', [ContactUsController::class, 'index']);

    Route::post('/notifications', [ProfileController::class, 'notifications']);

    Route::post('/notifications_status_change', [ProfileController::class, 'notifications_status_change']); 

    Route::post('/notifications/delete', [ProfileController::class, 'notifications_delete']);

    Route::get('/logout', [ProfileController::class, 'logout']);

    Route::prefix('favorite_store')->group(function () {
        Route::post('/', [FavoriteStoreController::class, 'index']);
        Route::post('add', [FavoriteStoreController::class, 'add']);
        Route::post('remove', [FavoriteStoreController::class, 'remove']);
    });
    
    Route::prefix('favorite_product')->group(function () {
        Route::get('/', [FavoriteProductController::class, 'index']);
        Route::post('add', [FavoriteProductController::class, 'add']);
        Route::post('remove', [FavoriteProductController::class, 'remove']);
        Route::post('product_view_all', [FavoriteProductController::class, 'product_view_all']);
    });
        
    Route::prefix('address')->group(function () {
        Route::get('/', [AddressController::class, 'index']);
        Route::post('add', [AddressController::class, 'add']);
        Route::post('edit/{id}', [AddressController::class, 'edit']);
        Route::get('delete/{id}', [AddressController::class, 'delete']);
    });

    Route::prefix('card')->group(function () {
        Route::get('/', [CardController::class, 'index']);
        Route::get('delete/{id}', [CardController::class, 'delete']);
    });

    Route::prefix('cart')->group(function () {
        Route::get('/', [CartController::class, 'index']);
        Route::post('add', [CartController::class, 'add']);
        Route::post('edit', [CartController::class, 'qty_update']);
        Route::post('remove_product', [CartController::class, 'remove_product']);
        Route::get('clear', [CartController::class, 'clear']);
        Route::get('verify', [CartController::class, 'verify']);
    });

    Route::prefix('order')->group(function () {
        Route::post('/', [OrderController::class, 'index']);
        Route::post('history', [OrderController::class, 'history']);
        Route::post('detail', [OrderController::class, 'detail']);
        Route::post('reached', [OrderController::class, 'reached']);
        Route::post('update_order_status', [OrderController::class, 'update_order_status']);
    });

    Route::get('/pay_by_card/{id}', [OrderController::class, 'pay_by_card']);

});


Route::prefix('store')->group(function () {

    Route::post('store_list', 'Api\StoreController@store_list');

    Route::post('details', 'Api\StoreController@details');

    // Route::post('find_store', [StoreController::class, 'find_store']);

    // Route::any('search_by_zipcode', [StoreController::class, 'search_by_zipcode']);

    Route::post('product', 'Api\StoreController@product'); // Home API
    Route::post('product_view_all', 'Api\StoreController@product_view_all');

    Route::post('deals', [StoreController::class, 'deals']);
    Route::post('deals_old', [StoreController::class, 'deals_old']);
    Route::post('search', [StoreController::class, 'search']);
});



Route::post('/send_otp', [AuthController::class, 'send_otp']);

Route::get('send_push_notification/{id}', 'Api\TestingController@push_notification');
Route::post('test_update_order_status', 'Api\TestingController@test_update_order_status');
Route::get('send_web_push/{id}', 'Api\TestingController@send_web_push');

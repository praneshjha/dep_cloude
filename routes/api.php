<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApiApp\ProductController;

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

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

// Route::post('/token', 'TokenController@generate');
// Route::group(['namespace'=>'ApiApp'], function () {
//     Route::post('v1/login', 'LoginController@login');
//     Route::post('v1/register', 'RegisterController@register');
//     Route::get('v1/countries', 'RegisterController@getCountry');
  
//     Route::group(['middleware' => 'auth:api'], function() {
//         Route::get('v1/logout', 'LoginController@logout');
//         //Route::get('user', 'AuthController@user');
//     });
// });

Route::post('/token', 'TokenController@generate');
Route::get('/generate_token/{email}', 'ApiApp\ChatController@generateToken');
Route::post('register', 'ApiApp\RegisterController@register');
Route::post('login', 'ApiApp\RegisterController@login');
     
Route::middleware('auth:api')->group(function () {
    Route::get('logout', 'ApiApp\RegisterController@logout');
    Route::post('change_password', 'ApiApp\RegisterController@changePassword');
    Route::post('profile_image_update', 'ApiApp\RegisterController@profileImageUpdate');
    Route::post('password/forgot-password', 'ApiApp\RegisterController@forgotPassword');
    Route::post('password/reset', 'ApiApp\RegisterController@passwordReset');
    Route::get('profile', 'ApiApp\RegisterController@profile');
    Route::get('permissions', 'ApiApp\RegisterController@permissions');
    Route::resource('products', ProductController::class);
    Route::post('all-departures', 'ApiApp\DepartureController@allDeparture');
    Route::get('departures-details/{id}', 'ApiApp\DepartureController@departureDetails');
    Route::post('my-departures', 'ApiApp\DepartureController@myDeparture');
    Route::post('my-bookings', 'ApiApp\DepartureController@myBooking');
    Route::post('destination-delete', 'ApiApp\DepartureController@deleteDestination');
    Route::post('/departure/book/seat', 'ApiApp\DepartureController@bookSeat')->name('departure_book');
    Route::post('/departures-booking-history','ApiApp\DepartureController@AllDepartureBookingHistory')->name('all_departure_booking_history');
    Route::post('/book-departure-show','ApiApp\DepartureController@bookDepartureShow')->name('book-departure-show');
    
    //Dashboard Related Routes
    Route::get('/dashboard/favourite','ApiApp\DashboardController@favourite_supplier')->name('favourite_supplier');
    Route::post('/dashboard/common-search-list','ApiApp\DashboardController@fetchData')->name('searchbar');
    Route::post('/dashboard/search_result','ApiApp\DashboardController@searchList')->name('search_result_unfiltered');
    Route::post('/dashboard/favourite_redirect','ApiApp\DashboardController@fav_Sup_Redirect')->name('fav_Sup_redirect');
    //Holding Related Routes

    Route::post('/departure-hold-seat', 'ApiApp\DepartureController@holdSeatStore');
    Route::post('/all-departures-hold-history', 'ApiApp\DepartureController@AllDepartureHoldHistory');
    Route::post('/my-hold-history', 'ApiApp\DepartureController@myHoldDeparture');
    Route::post('/departure-booking-history', 'ApiApp\DepartureController@departureBookingHistory');
    Route::post('/booking-history-details', 'ApiApp\DepartureController@bookingHistoryDetails');
    Route::post('/booking-cancel', 'ApiApp\DepartureController@bookingCancle');
    Route::post('/booking-price-update', 'ApiApp\DepartureController@bookingPriceUpdate');

    Route::post('/departure-hold-history', 'ApiApp\DepartureController@departureHoldHistory');
    Route::post('/hold-history-details', 'ApiApp\DepartureController@holdHistoryDetails');
    Route::post('/booking-confirm', 'ApiApp\DepartureController@departureConfirm');
    Route::post('/force-release', 'ApiApp\DepartureController@forceRelease');
    Route::post('/departure-publish', 'ApiApp\DepartureController@departurePublish');
    Route::post('/common-search-list', 'ApiApp\AutoSearchController@fetchData');

    Route::post('/departure-from', 'ApiApp\AutoSearchController@DepartureFrom');
    Route::post('/departure-to', 'ApiApp\AutoSearchController@DepartureTo');
    Route::post('/publishers-list', 'ApiApp\AutoSearchController@publisherList');
    
    //All booking filter apis
    Route::post('/all-book-departure-list', 'ApiApp\AutoSearchController@allDepartureList');
    Route::post('/all-book-publisher-list', 'ApiApp\AutoSearchController@allDeparturePublishers');

    // my booking filter apis

    Route::post('/my-booked-departure-list', 'ApiApp\AutoSearchController@myBookedDepartureList');
    Route::post('/my-booked-publisher-list', 'ApiApp\AutoSearchController@myBookedDeparturePublishers');
    //Public Profiles
    Route::post('/suplier-buyer-profile', 'ApiApp\DepartureController@SupplierBuyerProfile');

    //Route::get('/check_chat_channel/{id}', 'MessageController@check_chat_channel')->name('check_chat_channel');
    Route::get('/chat_user_list', 'ApiApp\MessageController@chat_room')->name('chat_user_list');
    Route::get('/chat_room_app/{id}', 'ApiApp\MessageController@chat_room_details')->name('chat_room_details_app');
    //Filter publishers
});

Route::group(['namespace'=>'API'],function(){
    Route::post('/get_dc_departures_to_dook','DeparturesPullController@getDCDepartureDook');
    Route::post('/get_dc_sync_departures_to_dook','DeparturesPullController@getDCSyncDepartureDook');
    //Route::post('/get_agent_departures','DepartureAgentPullController@countryRelatedDepartureForAgent');
    Route::post('/get_departure_seats','DepartureSeatsController@seatsIndexForAgentDook');
    Route::post('/get_departure_seats_for_admin','DepartureSeatsController@seatsIndexForAgentListingAdmin');
    Route::get('/departure_country_lists','DepartureCountryPullController@countryList');    
    Route::get('/get_pull_status','DeparturesPullController@remainingChangeDeparture');
    
});
   Route::post('/webhook_cloud','WebhookController@webhook_cloud')->name('webhook_cloud');
   Route::get('/get_webhook','WebhookController@get_webhook')->name('get_webhook');
   Route::post('/webhook_cloud_pre','WebhookController@webhook_cloud_pre')->name('webhook_cloud_pre');
   Route::get('/get_current_user_chat_channels','WebhookController@get_current_user_chat_channels')->name('get_current_user_chat_channels');
    //Free chat Id send

   Route::get('/get_channel_id/{id}','ApiApp\ChatController@getChatId');
<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});
Route::get('/test-mail', 'PricingModuleController@testingMail');

Route::get('/about-us', function () {
    return view('aboutus');
});
Route::get('/mail', function () {
    return view('mail.welcome');
});
Route::get('/how-it-works', function () {
    return view('how_it_works');
});
Route::get('/pricing', function () {
    return view('pricing');
});
Route::get('/demo', function () {
    return view('demo');
});
Route::get('/contact-us', function () {
    return view('contact_us');
});
Route::get('/thankyou', function () {
    return view('demo_thankyou');
});
Route::get('comapny_small','ProfileController@abcLowerCase')->name('lk');
Route::get('get-users-list','DemoContactController@getUserData');
Route::post('/demo-store', 'DemoContactController@demo')->name('demo_store');
Route::post('/contact-store', 'DemoContactController@contactUs')->name('contact_store');
// Auth::routes();
// Route::get('/newtext', function () {
//     return view('departure.text');
// });
Route::post('/public-id','ProfileController@PublicId')->name('public_id_unique');
//Route::get('/profile/{id}/{public}', 'ProfileController@publicProfile')->name('public_profile');
//Route::get('/supplier-profile/{id}/{public}', 'ProfileController@SupplierProfile')->name('supplier_profile');
Route::get('/profile/{public}', 'ProfileController@SupplierProfile')->name('supplier_profile');

	//Route::get('/home', 'HomeController@index')->name('home');
Route::get('/start_from_destination', 'Departure\DepartureController@startFromDestination')->name('start_from');
Route::get('/search-country', 'Departure\DepartureController@SearchCountry')->name('start_from');
	Route::post('/unique-Email-Check', 'Departure\DepartureController@uniqueEmailCheck')->name('email_id_unique_check');
	Auth::routes(['verify' => true]);
	Auth::routes();

	Route::group(['middleware' => 'auth'], function () {
		Route::get('/thank-you', function () {
			return view('layouts.thank_you');
		});
    Route::get('/home', 'HomeController@index')->name('home');
	//Route::get('/', 'HomeController@index')->name('home');
	
	//Dashboard//MK
	Route::get('/dashboard', 'Dashboard\DashboardController@searchResults')->name('dashboard');
	Route::post('/dashboard/fetch2','Dashboard\DashboardController@fetchData')->name('fetch_search');
	Route::get('/dashboard/ajax_view','Dashboard\DashboardController@packageTypeData')->name('ajax_view');
	Route::get('/dashboard/favourite','Dashboard\DashboardController@favouriteTypeData')->name('favouriteSup');
	Route::get('/dashboard/fav_package','Dashboard\DashboardController@favouritePkgData')->name('favouritePkgData');

	//Dashboard//MK ends

	//Departure Routes
	Route::get('/my-departures/card-view', 'Departure\DepartureController@departureIndex')->name('departure');
	Route::get('/my-departures/table-view', 'Departure\DepartureController@departureIndex')->name('departure-table');
	Route::get('/departure-basic-detail-create/{departure_type_id}', 'Departure\DepartureController@departureCreate')->name('departure_create');
	Route::get('/departure-basic-details-edit/{id}', 'Departure\DepartureController@departureEdit')->name('departure_edit');
	Route::post('/departure/basic-detail/store/{departure_type_id}', 'Departure\DepartureController@departureStore')->name('departure_store');
	Route::post('/departure/basic-detail/update/{id}', 'Departure\DepartureController@departureUpdate')->name('departure_update');
	Route::post('/departure-disable/{id}', 'Departure\DepartureController@departureDisable')->name('departure_disable');
	Route::post('/departure-company-publish/{id}', 'Departure\DepartureController@departureCompanyPublish')->name('departure_company_publish');
	Route::post('/departure-confirm/{id}', 'Departure\DepartureController@DepartureConfirm')->name('departure_confirm');
	Route::get('/my-departures-details/{id}', 'Departure\DepartureController@details')->name('departure_details');
    Route::get('/departures/card-view', 'Departure\DepartureController@allDeparture')->name('all_departure1');
    Route::get('/departures/table-view', 'Departure\DepartureController@allDeparture')->name('all_departure-table');

    Route::post('/departure-unpublish/{id}', 'Departure\DepartureController@departureUnpublish')->name('departure_unpublish');

    Route::get('/departure-series/{id}', 'Departure\SeriesController@seriesIndex')->name('departure_series');

    //book & hold
    Route::post('/departure/hold/update', 'Departure\DepartureController@holdDurationUpdate')->name('departure_holdduration');
	Route::post('/departure/book/seat', 'Departure\DepartureController@bookSeat')->name('departure_book');
	Route::get('/my-bookings', 'Departure\DepartureController@myBooked')->name('my_booking');
	Route::get('/my-holdings', 'Departure\DepartureController@myHolded')->name('my_holding');
	Route::post('/hold/departure/release/{id}', 'Departure\DepartureController@release');

	Route::get('/forcehold/departure/release/{id}', 'Departure\DepartureController@ForceRelease');

	Route::get('/departures-details/{id}', 'Departure\DepartureController@AllDepartureDetails')->name('all_departure_details');
	//by raj
	Route::post('/hold-departure-release/{id}', 'Departure\DepartureController@releaseHoldMyBooking');

	Route::get('/booking/history','Departure\DepartureController@BookingHistory')->name('booking_history');
	Route::get('/user-booking/{id}','Departure\DepartureController@UserBooking')->name('user_booking');
    Route::get('/user-holding/{id}','Departure\DepartureController@UserHolding')->name('user_holding');
	//buyer and supplier
	Route::get('/buyers','Departure\DepartureController@UserList')->name('user_list');
    Route::post('/user-type-change/{id}', 'Departure\DepartureController@UserTypeChange')->name('user_update');
	Route::get('/supliers','Departure\DepartureController@SupplierList')->name('suplier_list');
	Route::post('/user-status-change/{id}', 'Departure\DepartureController@UserStatusChange')->name('user_status');
	Route::get('/buyer-users/{id}','Departure\DepartureController@BuyerUserList')->name('buyer_user_list');
	Route::get('/suplier-users/{id}','Departure\DepartureController@SupplierUserList')->name('suplier_user_list');

	Route::get('/hold/history','Departure\DepartureController@HoldHistory')->name('hold_history');
    //departure approved
	Route::post('/departure-approve/{id}', 'Departure\DepartureController@departureApprove')->name('departure_approve');
	//Start From and To  Airlins Routes
	
	Route::get('/departure_airline', 'Departure\DepartureController@departureAirline')->name('departure_airline');
	Route::get('/departure_airline_return', 'Departure\DepartureController@departureAirline')->name('departure_airline_return');

	//departure search by destination 
    Route::get('/departure_destination_search', 'Departure\DepartureController@DepartureDestinationSearch');
    // departure approved
	Route::get('/pending-departures', 'Departure\DepartureController@ApprovedDeparture')->name('unapproved_departure');
	// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
	// Ashish
	Route::post('/get_columns', 'Departure\DepartureController@getColumns')->name('get_columns');
	//Hotel
    Route::get('/departure/hotel-details/{id}', 'Departure\HotelController@CreateHotel')->name('hotel_create');
    Route::post('/departure/hotel/store/{id}', 'Departure\HotelController@HotelStore')->name('hotel_store');
    Route::post('/departure/hotel/update/{id}', 'Departure\HotelController@HotelUpdate')->name('hotel_update');
    Route::post('/departure/hotel/delete/{id}', 'Departure\HotelController@HotelDelete')->name('hotel_delete');
	//Flight
	Route::get('/departure/flight-details/{id}', 'Departure\FlightController@CreateFlight')->name('flight_create');
	Route::post('/departure/flight/store/{id}', 'Departure\FlightController@FlightStore')->name('flight_store');
	Route::post('/departure/flight/update/{id}', 'Departure\FlightController@FlightUpdate')->name('flight_update');
	Route::post('/departure/flight/delete-o/{id}','Departure\FlightController@FlightOriginDelete')->name('flight_origin_delete'); //MK
	Route::post('/departure/flight/delete-r/{id}','Departure\FlightController@FlightReturnDelete')->name('flight_return_delete'); //MK
	Route::get('get-airline-ajax', 'Departure\FlightController@getAirlineAjax');

	//inclusion
	Route::get('/departure/inclusion/{id}', 'Departure\InclusionController@inclusionIndex')->name('inclusion');
	Route::post('/departure/inclusion/store/{id}', 'Departure\InclusionController@storeInclusion')->name('inclusion_store');
	Route::post('/departure/inclusion/update/{id}', 'Departure\InclusionController@updateInclusion')->name('inclusion_update');
	Route::get('/inclusion-icon-ajax','Departure\InclusionController@InclusionIcon');	
	
	//inactive departure
	Route::get('/departures-inacitve','Departure\DepartureController@inactiveDeparture')->name('inactive_depature');
	
	//role && user route
    Route::get('/roles','RoleController@index')->name('role');
	Route::post('/departure/role/save','RoleController@create')->name('role_create');
	Route::post('/departure/role-edit','RoleController@RoleEdit')->name('role_edit');
	Route::get('/users','RoleController@user')->name('user');
	Route::post('/departure/user/save','RoleController@UserCreate')->name('user_create');
	Route::post('/departure/user/update','RoleController@UserUpdate')->name('user_update');
	Route::post('/departure/user/disable/{id}','RoleController@disable')->name('user_disable');
	Route::post('/user-delete/{id}','RoleController@Userdelete')->name('user_delete');
	Route::post('/role-delete/{id}','RoleController@Roledelete')->name('role_delete');
	Route::post('/departure/user/role/permission','RoleController@PermissionAllow')->name('role_permission');
	Route::post('/departure/user/role/permission','RoleController@PermissionAllow')->name('role_permission');

	//Ititberary PDF
	Route::get('/pdf-itinerary-create/{id}', 'Departure\ItineraryPdfController@pdfItinerayCreate')->name('pdf_itinerary');
	Route::post('/pdf-itinerary/store/{id}', 'Departure\ItineraryPdfController@pdfItinerayStore')->name('pdf_itinerary_store');
	Route::post('/pdf-itinerary/update/{id}', 'Departure\ItineraryPdfController@pdfItinerayUpdate')->name('pdf_itinerary_update');

	//Pricing
	Route::get('/departure/pricing/{id}', 'Departure\PricingController@PricingIndex')->name('pricing_create');
	Route::post('/departure/pricing/store/{id}', 'Departure\PricingController@StorePricing')->name('pricing_store');
    Route::post('/departure/pricing/updating/{id}','Departure\PricingController@UpdatePricing')->name('pricing_updating');
    Route::post('/pricing-change-all/{id}','Departure\PricingController@changePricingAll')->name('change_pricing_all');

    Route::get('/select_currency', 'Departure\PricingController@SelectCurrency')->name('currency');
	
	//Terms of Payment
	Route::get('/terms-payment-create/{id}', 'Departure\TermsPayementController@TermsPaymentCreate')->name('terms_payment_create');
	Route::post('/terms/payment/store/{id}', 'Departure\TermsPayementController@TermsPayemntStore')->name('terms_payment_store');
	//Route::get('/terms-payment-edit/{id}','Departure\TermsPayementController@TermsPaymentEdit')->name('terms_payment_edit');
	Route::post('/terms/payment/update/{id}', 'Departure\TermsPayementController@TermsPaymentUpdate')->name('terms_payment_update');
	Route::get('/terms-payment-delete/{id}/{dep}', 'Departure\TermsPayementController@PaymentScheduleDelete')->name('terms_payment_delete');
	
    //terms
	Route::get('/departure/terms/{id}','Departure\TermsPayementController@TermsIndex')->name('terms_create');
	Route::post('/departure/terms/store/{id}','Departure\TermsPayementController@TermsStore')->name('terms_store');
	
    //currency converion
	Route::get('/currency-conversion', 'Departure\TermsPayementController@currencyConverion')->name('currency_converion');
	Route::post('/currency/converion/update', 'Departure\TermsPayementController@currencyConverionUpdate')->name('currency_converion_update');
	/////////// ++++++++++++++++++++++++++++++++++++++++++++++++++++++////////////
	Route::get('/departure/itinerary-create/{id}', 'Departure\ItineraryController@itineraryIndex')->name('itinerary_create');
	Route::post('/departure/itinerary-store/{id}', 'Departure\ItineraryController@itineraryStore')->name('itinerary_store');
	Route::post('/departure/itinerary-update/{id}', 'Departure\ItineraryController@itineraryUpdate')->name('itinerary_update');
	Route::get('get-itinerary-destination-pois-ajax', 'Departure\ItineraryController@getDestinationPoiAjax');

	Route::post('/itinerary-delete/{id}', 'Departure\ItineraryController@itinerayDelete')->name('itinerary_delete');

	Route::get('/agent-itinerary', 'AgentItineraryController@agentItinerayIndex')->name('agent_itinerary_index');
	Route::get('/agent-itinerary/create', 'AgentItineraryController@agentItinerayCreate')->name('agent_itinerary');
	Route::post('/agent-itinerary/store', 'AgentItineraryController@agentItinerayStore')->name('agent_itinerary_store');
	Route::get('/agent-itinerary/edit/{id}', 'AgentItineraryController@agentItinerayEdit')->name('agent_itinerary_edit');
	Route::post('/agent-itinerary/update/{id}', 'AgentItineraryController@agentItinerayUpdate')->name('agent_itinerary_update');
	Route::post('/agent-itinerary-disable/{id}', 'AgentItineraryController@agentItineraryDestroy')->name('agent_iti_disable');

	//Pricing Module Routes
	Route::get('/get_pricing_ajax', 'Departure\DepartureController@getPricingAjax')->name('get_pricing_ajax');
    Route::post('/departure/price_update', 'PricingModuleController@updatePriceModal')->name('price_update');
	Route::post('/departure/default_price/{id}', 'Departure\PricingController@updateDefaultPrice')->name('default_price');
	//Book section
	Route::get('/book/details','BookController@index')->name('book');
	Route::post('/book/seat','BookController@store')->name('store');
	Route::post('/book/hold','BookController@hold')->name('hold');
    //Booking cancel
    Route::post('/booking-cancel/{id}','BookController@BookingCancle')->name('Booking_cancel');
	Route::post('/booking-cancel-buyer/{id}','BookController@BookingCancleSup')->name('booking_cancel_buyer');	
    Route::post('/booking-price-update','BookController@BookingPriceUpdate')->name('booking_price_update');
    //departure Booking History
	Route::get('/departure-booking-history/{id}','Departure\DepartureController@DepartureBookingHistory')->name('departure_booking_history');
    Route::get('/departure-hold-history/{id}','Departure\DepartureController@DepartureholdHistory')->name('departure_hold_history');

	Route::get('/departures-booking-history','Departure\DepartureController@AllDepartureBookingHistory')->name('all_departure_booking_history');
	Route::get('/departures-booking-history-details/{id}','Departure\DepartureController@BookingHistoryDetails')->name('departure_booking_history_details');
	Route::get('/departures-hold-history-details/{id}','Departure\DepartureController@HoldHistoryDetails')->name('departure_hold_history_details');

	//departure hold history
	Route::get('/all-departures-hold-history','Departure\DepartureController@AllDepartureHoldHistory')->name('all_departure_hold_history');
    Route::get('/departures-hold-history-details/{id}','Departure\DepartureController@HoldHistoryDetails')->name('departure_hold_history_details');

	//filter departure
	Route::get('/departure_from', 'Departure\DepartureController@DepartureFrom')->name('start_from');
	Route::get('/departure_to', 'Departure\DepartureController@DepartureTo')->name('start_to');
	Route::get('/destination-delete/{id}', 'Departure\DepartureController@destinationDelete')->name('destination_delete');
	
	//user profile
	Route::get('/company-profile','ProfileController@UserProfile')->name('profile');
	Route::get('/buyer-profile/{id}','ProfileController@BuyerProfile')->name('buyer_profile');
	Route::get('/company-profile-edit','ProfileController@UserEdit')->name('edit_profile');
	Route::post('/my/profile/update','ProfileController@UserProfileUpdate')->name('update_prifile');
	Route::get('/change-password','ProfileController@ChangePassword')->name('change_password');
	Route::post('/my/profille/update','ProfileController@PasswordUpdate')->name('passwrod_update');

	Route::get('/destination-pois-ajax', 'Departure\DepartureController@pointofInterestGet');

	Route::post('/copy-departure', 'Departure\DepartureController@copyDeparture')->name('departure_copy');
	Route::post('/series-departure', 'Departure\SeriesController@seriesDeparture')->name('series_departure');
	
	//Cancelation schedule
	Route::get('/cancelation-schedule/{id}','Departure\CancelationController@index')->name('cancelation_create');
	Route::post('/cancelation-schedule-store/{id}','Departure\CancelationController@store')->name('cancelation_store');	
	Route::post('/cancelation-schedule-update/{id}','Departure\CancelationController@update')->name('cancelation_update');	
	Route::get('/cancelation-schedule-delete/{id}/{dep}','Departure\CancelationController@delete')->name('cancelation_delete');	
	Route::post('/pricing_disable_enable/{id}','Departure\PricingController@enableDisable')->name('penable_disable');
	Route::get('/terms-master','Departure\TermsPayementController@TermsMasterIndex')->name('term_master');
	Route::post('/terms-master-store','Departure\TermsPayementController@TermsMasterStore')->name('term_master_store');
	Route::post('/terms-master-update','Departure\TermsPayementController@TermsMasterUpdate')->name('term_master_update');	

	Route::get('/messages/{id}', 'MessageController@index')->name('messages.index');
	Route::get('/messages/{id}/{ids}', 'MessageController@chat')->name('messages.chat');

    Route::get('/check_chat_channel/{id}', 'MessageController@check_chat_channel')->name('check_chat_channel');
    Route::get('/chat_room', 'MessageController@chat_room')->name('chat_room');
    Route::get('/chat_room/{id}', 'MessageController@chat_room_details')->name('chat_room_details');
	//Filter publishers
	Route::get('/publishers-list', 'Departure\DepartureController@publisherListSearch');
	
	Route::get('/tags-list-search', 'Departure\DepartureController@tagsListSearch');

	// New Paginate All Departure Route
// New Paginate All Departure Route

	Route::get('/all-departures/', 'AllDeparturePaginateAjaxController@allDeparturePaginate')->name('all_departure');
	Route::get('/all_dep_ajx', 'AllDeparturePaginateAjaxController@allDeparture_ajax')->name('all_dep_ajx');

	Route::post('/get_booking_modal', 'AllDeparturePaginateAjaxController@get_booking_modal')->name('get_booking_modal');

	Route::post('/get_hold_modal', 'AllDeparturePaginateAjaxController@get_hold_modal')->name('get_hold_modal');

	// Route::get('/all-departure-table', 'AllDeparturePaginateAjaxController@allDeparturePaginate')->name('all_departure_table_p');
	Route::post('/autocomplete/fetch2', 'AllDeparturePaginateAjaxController@fetchData')->name('autocomplete.fetch2');

	//Auto Search
	Route::post('/autocomplete/fetch', 'AutoSearchController@fetchData')->name('autocomplete.fetch');

	Route::patch('/fcm-token-update', 'NotificationController@updateFCMToken')->name('fcmUpdate');

	Route::post('/store-notification', 'NotificationController@storeNotification')->name('store_notification');

	Route::post('/notification-status-change/{id}', 'NotificationController@changeStatusNotification')->name('notification_status_change');
	Route::get('/notifications','NotificationController@getNotification')->name('get_notification');

	// Pull it Ajax Route
	Route::post('/pullIt-country-data','PullItController@getCountryData')->name('pullIt_country_data');
	Route::post('/pullIt-destination-data','PullItController@getDestinationData')->name('pullIt_destination_data');
	Route::post('/pullIt-poi-data','PullItController@getPoiData')->name('pullIt_poi_data');

	//Mailer Send

	Route::get('/mailers','MailerController@indexMailer')->name('index_mailer');
	Route::post('/mailer-store','MailerController@htmlUpload')->name('mailer_store');
	Route::post('/mailer-delete/{id}','MailerController@mailerDelete')->name('mailer_delete');

	//Country Route
	Route::get('/countries','CountryListController@countryList')->name('countries');
	Route::post('/country_update','CountryListController@metatagUpdate')->name('country_update');

	
	
});
Route::get('/baku-team','MarqueeController@bakuTeam')->name('baku_team');
	Route::get('/almaty-team','MarqueeController@almatyTeam')->name('almaty_team');
	Route::get('/turkey-team','MarqueeController@turkeyTeam')->name('turkey_team');
//app mail verify
Route::get('verify_email/token/{token_user}/email/{mail}', 'ApiApp\RegisterController@verifyEmail');

//Route::get('/home', 'HomeController@index')->name('home');
//Route::get('/book','BookController@index')->name('booking_section');
 
   	Route::post('/get_cht_cloud_set','WebhookController@checkForNewChat')->name('checkForNewChat');
   	//make country urls in single click for public dep
   	Route::get('/country_urls', 'Departure\DepartureController@country_slug');
   	//Public Departure L:ist Routes
   	Route::get('/public_destinations', 'PublicDepartureController@departurePublicDestinations')->name('public_destinations');
	Route::get('/all_dep_ajx_public', 'PublicDepartureController@allDeparture_ajax')->name('all_dep_ajx_public');
	Route::post('/autocomplete/fetch3', 'PublicDepartureController@fetchData')->name('autocomplete.fetch3');
	Route::post('/autocomplete/fetch3', 'PublicDepartureController@fetchData')->name('autocomplete.fetch3');

	Route::get('/destination_country_wise_public', 'PublicDepartureController@CountryWiseDestnationsPublic')->name('destination_country_wise_public');

	Route::get('/{country}', 'PublicDepartureController@countryDepartureUrlPaginate')->name('country_group_tours');

	
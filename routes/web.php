<?php

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

//Auth::routes();
	Route::get('/account/login', 'Auth\LoginController@showLoginForm')->name('login');
	Route::post('/account/login', 'Auth\LoginController@authenticate')->name('login');
	Route::post('/account/logout', 'Auth\LoginController@logout')->name('logout');
	Route::post('/account/password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail')->name('password.email');
	Route::get('/account/password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm')->name('password.request');
	Route::post('/account/password/reset', 'Auth\ResetPasswordController@reset');
	Route::get('/account/password/reset/{token}', 'Auth\ResetPasswordController@showResetForm')->name('password.reset');
	Route::get('/account/register', 'Auth\RegisterController@showRegistrationForm')->name('register');
	Route::post('/account/register', 'Auth\RegisterController@register');



// ADMIN
	Route::get('/padmin/query', 'AdminController@query')->name('admin-query');
	Route::post('/padmin/query', 'AdminController@runQuery')->name('run-query');
	Route::get('/padmin/users', 'AdminController@users')->name('admin-users');
	Route::get('/padmin/suspend/{id}', 'AdminController@suspendUser')->name('admin-suspend-user');
	Route::get('/padmin/unsuspend/{id}', 'AdminController@unsuspendUser')->name('admin-unsuspend-user');
	Route::get('/padmin/listings', 'AdminController@listings')->name('admin-listings');
	Route::get('/padmin/listings/verify/{id}', 'AdminController@verifyListing')->name('verify-listing');
	Route::get('/padmin/listings/unverify/{id}', 'AdminController@unverifyListing')->name('unverify-listing');
	Route::get('/padmin/listings/activate/{id}', 'AdminController@activateListing')->name('activate-listing');
	Route::get('/padmin/listings/deactivate/{id}', 'AdminController@deactivateListing')->name('deactivate-listing');
	Route::get('/padmin/listings/suspend/{id}', 'AdminController@suspendListing')->name('suspend-listing');
	Route::get('/padmin/listings/unsuspend/{id}', 'AdminController@unsuspendListing')->name('unsuspend-listing');




// DASHBOARD
	// Account
	Route::get('/account', 'HomeController@index')->name('home');
	Route::post('/account', 'HomeController@updateProfile')->name('update-profile');
	Route::post('/account/profile', 'HomeController@updateProfilePhoto')->name('profile-photo');
	Route::get('/account/onboard', 'HomeController@onboardHost')->name('onboard');
	Route::get('/account/payment-dashboard', 'HomeController@paymentDashboard')->name('payment-dashboard');

    // Listings
    Route::get('/account/listings', 'ListingController@index')->name('view-own-listings');
    Route::get('/account/listings/manage/{id}', 'ListingController@manage')->name('manage-listing');

    Route::get('/account/listings/availability/{id}', 'ListingController@availability')->name('listing-availability');
    Route::post('/account/listings/add-exception/', 'ListingController@addException')->name('add-exception');
    Route::post('/account/listings/edit-exception/', 'ListingController@editException')->name('edit-exception');
    Route::post('/account/listings/remove-exception/', 'ListingController@removeException')->name('remove-exception');

	Route::post('/account/listings/publish', 'ListingController@publishListing')->name('publish-listing');
	Route::post('/account/listings/unpublish', 'ListingController@unpublishListing')->name('unpublish-listing');

	Route::get('/account/listings/add', 'ListingController@addListing')->name('add-listing');
	Route::post('/account/listings/add', 'ListingController@createListing')->name('create-listing');

	Route::get('/account/listings/page/1/{listing}', 'ListingController@editListing')->name('edit-listing-p1');
	Route::post('/account/listings/page/1', 'ListingController@storePage1')->name('store-listing-p1');

	Route::get('/account/listings/page/2/{listing}', 'ListingController@editPage2')->name('edit-listing-p2');
	Route::post('/account/listings/page/2', 'ListingController@storePage2')->name('store-listing-p2');

	Route::get('/account/listings/page/3/{listing}', 'ListingController@editPage3')->name('edit-listing-p3');
	Route::post('/account/listings/page/3', 'ListingController@storePage3')->name('store-listing-p3');

	Route::get('/account/listings/page/4/{listing}', 'ListingController@editPage4')->name('edit-listing-p4');
    Route::post('/account/listings/page/4', 'ListingController@storePage4')->name('store-listing-p4');

    Route::get('/account/listings/page/5/{listing}', 'ListingController@editPage5')->name('edit-listing-p5');
    Route::post('/account/listings/page/5', 'ListingController@storePage5')->name('store-listing-p5');

    Route::get('/account/listings/page/6/{listing}', 'ListingController@editPage6')->name('edit-listing-p6');
    Route::post('/account/listings/page/6', 'ListingController@storePage6')->name('store-listing-p6');
	Route::post('/account/listings/make-primary-image', 'ListingController@makePrimaryImage')->name('make-primary-image');
	Route::post('/account/listings/remove-image', 'ListingController@removeImage')->name('remove-listing-image');

	Route::get('/account/listings/page/7/{listing}', 'ListingController@editPage7')->name('edit-listing-p7');
	Route::post('/account/listings/page/7', 'ListingController@storePage7')->name('store-listing-p7');

	Route::get('/account/listings/reservations/{id}', 'ListingController@manageReservations')->name('manage-reservations');

	// Favorite Listing
	Route::post('/account/listing/favorite', 'HomeController@favorite')->name('favorite-listing');
	Route::get('/account/favorites', 'HomeController@favorites')->name('favorited-listings');

	// Trips
	Route::get('/account/upcoming-trips', 'HomeController@upcomingTrips')->name('upcoming-trips');
	Route::get('/account/booking/{id}', 'HomeController@viewBooking')->name('view-booking');
	Route::get('/account/past-trips', 'HomeController@pastTrips')->name('past-trips');

	// Messages
	Route::get('/messages/send/{to}', 'MessageController@writeMessage')->name('write');
	Route::post('/messages/send', 'MessageController@sendMessage')->name('send-message');
	Route::get('/messages', 'MessageController@index')->name('messages');
	Route::post('/messages/reply', 'MessageController@reply')->name('reply');


// Searching

//search is rate limited to 60 attempts per minute
Route::group(['middleware' => 'throttle'], function () {
  Route::get('/getupclose/', 'SearchController@search')->name('search');
});

// View Listing
Route::get('/getupclose/listing/{id}', 'BookingController@listing')->name('view-listing');

// Booking
Route::post('/getupclose/listing/book', 'BookingController@start')->name('start-booking');
Route::get('/getupclose/listing/policies/{id}', 'BookingController@policy')->name('listing-policies');
Route::post('/getupclose/listing/policies/confirm', 'BookingController@policyConfirm')->name('confirm-booking');
Route::get('/getupclose/booking/pay/{id}', 'BookingController@pay')->name('pay-booking');
Route::post('/getupclose/booking/pay/', 'BookingController@finishPay')->name('finish-payment');
Route::post('/account/booking/cancel/', 'BookingController@cancelBooking')->name('cancel-booking');

// Ratings & Reviews
Route::get('/account/review/listing/{id}', 'ReviewController@reviewListing')->name('review-listing');
Route::post('/account/review/listing', 'ReviewController@submitReviewListing')->name('submit-review-listing');
Route::get('/account/review/user/{id}', 'ReviewController@reviewUser')->name('review-user');
Route::post('/account/review/user', 'ReviewController@submitReviewUser')->name('submit-review-user');


// Templates
Route::get('/ptemp/search', function(){
	\Debugbar::disable();
	return view('ptemp.search');
});

Route::get('/ptemp/hlogin', function(){
	\Debugbar::disable();
	return view('ptemp.hlogin');
});

Route::get('/ptemp/csrf', function(){
	\Debugbar::disable();
	return view('ptemp.csrf');
});


Route::get('/ptemp/featured', 'BookingController@featuredListings');


Route::get('account/test', function(){
	return view('test.calendar');
});

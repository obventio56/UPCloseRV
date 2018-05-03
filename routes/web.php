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

Route::get('/', function () {
    return view('frontpage');
});

Auth::routes();
Route::post('login', 'Auth\LoginController@authenticate')->name('login');

// Account/Dashboard
Route::get('/home', 'HomeController@index')->name('home');
Route::post('/home', 'HomeController@updateProfile')->name('update-profile');
Route::post('/home-profile', 'HomeController@updateProfilePhoto')->name('profile-photo');
Route::get('/onboard', 'HomeController@onboardHost')->name('onboard');
Route::get('/payment-dashboard', 'HomeController@paymentDashboard')->name('payment-dashboard');

// Admin
Route::get('/admin/query', 'AdminController@query')->name('admin-query');
Route::post('/admin/query', 'AdminController@runQuery')->name('run-query');
Route::get('/admin/users', 'AdminController@users')->name('admin-users');
Route::get('/admin/suspend/{id}', 'AdminController@suspendUser')->name('admin-suspend-user');
Route::get('/admin/unsuspend/{id}', 'AdminController@unsuspendUser')->name('admin-unsuspend-user');

// Messages
Route::get('/messages/send/{to}', 'MessageController@writeMessage')->name('write');
Route::post('/messages/send', 'MessageController@sendMessage')->name('send-message');
Route::get('/messages', 'MessageController@index')->name('messages');
Route::post('/messages/reply', 'MessageController@reply')->name('reply');


// DASHBOARD
    // Listings
    Route::get('/dashboard/listings', 'ListingController@index')->name('view-own-listings');
    Route::get('/dashboard/listings/manage/{id}', 'ListingController@manage')->name('manage-listing');

    Route::get('/dashboard/listings/availability/{id}', 'ListingController@availability')->name('listing-availability');
    Route::post('/dashboard/listings/add-exception/', 'ListingController@addException')->name('add-exception');
    Route::post('/dashboard/listings/edit-exception/', 'ListingController@editException')->name('edit-exception');
    Route::post('/dashboard/listings/remove-exception/', 'ListingController@removeException')->name('remove-exception');

	Route::get('/dashboard/listings/add', 'ListingController@addListing')->name('add-listing');
	Route::post('/dashboard/listings/add', 'ListingController@createListing')->name('create-listing');

	Route::get('/dashboard/listings/page/1/{id}', 'ListingController@editListing')->name('edit-listing-p1');
	Route::post('/dashboard/listings/page/1', 'ListingController@storePage1')->name('store-listing-p1');

	Route::get('/dashboard/listings/page/2/{id}', 'ListingController@editPage2')->name('edit-listing-p2');
	Route::post('/dashboard/listings/page/2', 'ListingController@storePage2')->name('store-listing-p2');

	Route::get('/dashboard/listings/page/3/{id}', 'ListingController@editPage3')->name('edit-listing-p3');
	Route::post('/dashboard/listings/page/3', 'ListingController@storePage3')->name('store-listing-p3');

	Route::get('/dashboard/listings/page/4/{id}', 'ListingController@editPage4')->name('edit-listing-p4');
    Route::post('/dashboard/listings/page/4', 'ListingController@storePage4')->name('store-listing-p4');

    Route::get('/dashboard/listings/page/5/{id}', 'ListingController@editPage5')->name('edit-listing-p5');
    Route::post('/dashboard/listings/page/5', 'ListingController@storePage5')->name('store-listing-p5');

    Route::get('/dashboard/listings/page/6/{id}', 'ListingController@editPage6')->name('edit-listing-p6');
    Route::post('/dashboard/listings/page/6', 'ListingController@storePage6')->name('store-listing-p6');
	Route::post('/dashboard/listings/make-primary-image', 'ListingController@makePrimaryImage')->name('make-primary-image');
	Route::post('/dashboard/listings/remove-image', 'ListingController@removeImage')->name('remove-listing-image');

Route::get('/dashboard/listings/page/7/{id}', 'ListingController@editPage7')->name('edit-listing-p7');

	// Favorite Listing
	Route::post('/dashboard/listing/favorite', 'HomeController@favorite')->name('favorite-listing');
	Route::get('/dashboard/favorites', 'HomeController@favorites')->name('favorited-listings');


// Searching
Route::get('/getupclose/', 'SearchController@search')->name('search');

// View Listing
Route::get('/getupclose/listing/{id}', 'SearchController@listing')->name('view-listing');

// Booking
Route::post('/getupclose/listing/book', 'BookingController@start')->name('start-booking');
Route::get('/getupclose/listing/policies/{id}', 'BookingController@policy')->name('listing-policies');
Route::get('/getupclose/booking/pay/', 'BookingController@pay')->name('pay-booking');
Route::post('/getupclose/booking/pay/', 'BookingController@finishPay')->name('finish-payment');

// Testing
Route::get('/test/calendar', function(){
    return view('test.calendar');
});

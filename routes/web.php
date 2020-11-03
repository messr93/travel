<?php

use App\Models\Notification;
use App\Models\Offer;
use App\User;
use Illuminate\Support\Facades\Route;

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


Route::get('testo', function(){
    return view('vue_mce');
});


Route::get('listener/{id}', function ($id) {

    return view('offer_listner', ['id' => $id]);
});


Route::get('/', 'Frontend\WebsiteController@index');

Route::group(['prefix' => '{oldLang}', 'middleware' => 'langLocaleMonitor'], function(){                             /// original route
    //Route::get('/', 'Frontend\WebsiteController@index');

    Route::get('/home', 'HomeController@index')->name('home');
    Auth::routes();

    ################################################### Starts User offers Routes ###########################################
    Route::get('home/offers/all', 'Frontend\OfferController@index')->name('userOffers.all');
    Route::post('home/offers/all', 'Frontend\OfferController@allData')->name('userOffers.allData');

    Route::get('home/offers/create', 'Frontend\OfferController@create')->name('userOffers.create');
    Route::post('home/offer/insert', 'Frontend\OfferController@insert')->name('userOffers.insert');

    Route::get('home/offer/{offer}/edit', 'Frontend\OfferController@edit')->name('userOffers.edit')->middleware('can:updateOffer,offer');
    Route::post('home/offer/{offer}/update', 'Frontend\OfferController@update')->name('userOffers.update')->middleware('can:updateOffer,offer');

    Route::post('home/offers/delete', 'Frontend\OfferController@delete')->name('userOffers.delete');

    Route::post('home/offer/changeStatus', 'Frontend\OfferController@changeStatus')->name('userOffers.changeStatus');
    ################################################### End User Offers Routes ##############################################

    ################################################### End Notification Routes ##############################################

    Route::post('notification/markAsRead', 'Frontend\NotificationController@markAsRead')
        ->name('notifyMarkAsRead');

    ################################################### End Notification Routes ##############################################

    ################################################## Begin Profile Routes ###################################################

    Route::get('home/edit-profile-info', 'Frontend\ProfileController@editProfileInfo')->name('editProfileInfo');
    Route::post('update-profile_info', 'Frontend\ProfileController@updateProfileInfo')->name('updateProfileInfo');

    Route::get('home/edit-profile-email', 'Frontend\ProfileController@editProfileEmail')->name('editProfileEmail');
    Route::post('home/update-profile_email', 'Frontend\ProfileController@updateProfileEmail')->name('updateProfileEmail');

    Route::get('home/edit-profile-password', 'Frontend\ProfileController@editProfilePassword')->name('editProfilePassword');
    Route::post('home/update-profile_password', 'Frontend\ProfileController@updateProfilePassword')->name('updateProfilePassword');

    ################################################## End Profile Routes ###################################################



});

##############################################################################################################################

Route::group(['middleware' => 'enforceLang'], function(){                                            /// repeated route
    //Route::get('/', 'Frontend\WebsiteController@index');

    Route::get('/home', 'HomeController@index')->name('home');
    Auth::routes();

    ################################################### Starts User offers Routes ###########################################
    Route::get('home/offers/all', 'Frontend\OfferController@index')->name('userOffers.all');
    Route::post('home/offers/all', 'Frontend\OfferController@allData')->name('userOffers.allData');

    Route::get('home/offers/create', 'Frontend\OfferController@create')->name('userOffers.create');
    Route::post('home/offer/insert', 'Frontend\OfferController@insert')->name('userOffers.insert');

    Route::get('home/offer/{offer}/edit', 'Frontend\OfferController@edit')->name('userOffers.edit')->middleware('can:updateOffer,offer');
    Route::post('home/offer/{offer}/update', 'Frontend\OfferController@update')->name('userOffers.update')->middleware('can:updateOffer,offer');

    Route::post('home/offers/delete', 'Frontend\OfferController@delete')->name('userOffers.delete');

    Route::post('home/offer/changeStatus', 'Frontend\OfferController@changeStatus')->name('userOffers.changeStatus');

    ################################################### End User Offers Routes ##############################################

    ################################################### End Notification Routes ##############################################

    Route::post('notification/markAsRead', 'Frontend\NotificationController@markAsRead')
        ->name('notifyMarkAsRead');

    ################################################### End Notification Routes ##############################################

    ################################################## Begin Profile Routes ###################################################
    Route::get('home/edit-profile-info', 'Frontend\ProfileController@editProfileInfo')->name('editProfileInfo');
    Route::post('home/update-profile_info', 'Frontend\ProfileController@updateProfileInfo')->name('updateProfileInfo');

    Route::get('home/edit-profile-email', 'Frontend\ProfileController@editProfileEmail')->name('editProfileEmail');
    Route::post('home/update-profile_email', 'Frontend\ProfileController@updateProfileEmail')->name('updateProfileEmail');

    Route::get('home/edit-profile-password', 'Frontend\ProfileController@editProfilePassword')->name('editProfilePassword');
    Route::post('home/update-profile_password', 'Frontend\ProfileController@updateProfilePassword')->name('updateProfilePassword');

    ################################################## End Profile Routes ###################################################

});

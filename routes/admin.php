<?php

use Illuminate\Support\Facades\Route;



Route::group(['prefix' => '{oldLang}', 'middleware' => 'langLocaleMonitor'], function(){                             /// original route
    Route::group(['prefix' => 'admin'], function(){

        Route::get('/dashboard', 'DashboardController@index')->name('admin.dashboard');
        Route::post('regions', 'RegionController@getRegions')->name('getRegions');

################################################# Begin admin auth Routes #################################################
        Route::get('login', 'Auth\AdminAuthController@showLogin')->name('admin.showLoginForm');
        Route::post('login/post', 'Auth\AdminAuthController@login')->name('admin.login');
        Route::get('logout', 'Auth\AdminAuthController@logout')->name('admin.logout');

        Route::get('forget_password', 'Auth\AdminAuthController@showForgetForm')->name('admin.showForgetForm');
        Route::post('forget_password', 'Auth\AdminAuthController@sendForgetToken')->name('admin.sendResetToken');

        Route::get('reset/password/{token}', 'Auth\AdminAuthController@showResetForm')->name('admin.showResetForm');
        Route::post('reset/password', 'Auth\AdminAuthController@updatePassword')->name('admin.updatePassword');


################################################# End admin auth Routes #################################################


################################################# Begin Admin Routes #################################################

        Route::get('admins/all', function(){
            return view('backend.admins.index');
        })->name('admins.all')->middleware('auth:admin');
        Route::get('admins/create', function(){
            return view('backend.admins.create');
        })->name('admins.create')->middleware('auth:admin');

################################################# End Admin Routes #################################################

################################################# Begin Trips Routes #################################################

        Route::get('trips/all' , 'TripController@index')->name('trips.all');

        Route::get('trips/create' , 'TripController@create')->name('trips.create');
        Route::post('trips/post', 'TripController@insert')->name('trips.insert');


################################################# End Trips Routes #################################################

############################################## Begin Offers Routes #################################################

        Route::get('offers/all' , 'OfferController@index')->name('offers.all');
        Route::post('offers/all' , 'OfferController@allData')->name('offers.allData');

        Route::get('offers/create' , 'OfferController@create')->name('offers.create');
        Route::post('offers/post', 'OfferController@insert')->name('offers.insert');

        Route::get('offer/{id}/edit', 'OfferController@edit')->name('offers.edit');
        Route::post('offer/{id}/update', 'OfferController@update')->name('offers.update');

        Route::post('offer/delete', 'OfferController@delete')->name('offers.delete');

        Route::post('offer/changeStatus', 'OfferController@changeStatus')->name('offers.changeStatus');


################################################# End Offers Routes #################################################

############################################### Begin Programs Routes #################################################

        Route::get('programs/all' , 'ProgramController@index')->name('programs.all');
        Route::post('programs/all' , 'ProgramController@allData')->name('programs.allData');

        Route::get('programs/create' , 'ProgramController@create')->name('programs.create');
        Route::post('programs/post', 'ProgramController@insert')->name('programs.insert');

        Route::get('programs/{id}/edit', 'ProgramController@edit')->name('programs.edit');
        Route::post('programs/{id}/update', 'ProgramController@update')->name('programs.update');

        Route::post('programs/delete', 'ProgramController@delete')->name('programs.delete');

        Route::post('programs/changeStatus', 'ProgramController@changeStatus')->name('programs.changeStatus');


################################################# End Offers Routes #################################################

    });
});




Route::group(['prefix' => 'admin', 'middleware' => 'enforceLang'], function(){                                            /// repeated route

    Route::get('/dashboard', 'DashboardController@index')->name('admin.dashboard');
    Route::post('regions', 'RegionController@getRegions')->name('getRegions');

################################################# Begin admin auth Routes #################################################
    Route::get('login', 'Auth\AdminAuthController@showLogin')->name('admin.showLoginForm');
    Route::post('login/post', 'Auth\AdminAuthController@login')->name('admin.login');
    Route::get('logout', 'Auth\AdminAuthController@logout')->name('admin.logout');

    Route::get('forget_password', 'Auth\AdminAuthController@showForgetForm')->name('admin.showForgetForm');
    Route::post('forget_password', 'Auth\AdminAuthController@sendForgetToken')->name('admin.sendResetToken');

    Route::get('reset/password/{token}', 'Auth\AdminAuthController@showResetForm')->name('admin.showResetForm');
    Route::post('reset/password', 'Auth\AdminAuthController@updatePassword')->name('admin.updatePassword');


################################################# End admin auth Routes #################################################


################################################# Begin Admin Routes #################################################

    Route::get('admins/all', function(){
        return view('backend.admins.index');
    })->name('admins.all')->middleware('auth:admin');
    Route::get('admins/create', function(){
        return view('backend.admins.create');
    })->name('admins.create')->middleware('auth:admin');

################################################# End Admin Routes #################################################

################################################# Begin Trips Routes #################################################

    Route::get('trips/all' , 'TripController@index')->name('trips.all');

    Route::get('trips/create' , 'TripController@create')->name('trips.create');
    Route::post('trips/post', 'TripController@insert')->name('trips.insert');


################################################# End Trips Routes #################################################


############################################## Begin Offers Routes #################################################

    Route::get('offers/all' , 'OfferController@index')->name('offers.all');
    Route::post('offers/all' , 'OfferController@allData')->name('offers.allData');

    Route::get('offers/create' , 'OfferController@create')->name('offers.create');
    Route::post('offers/post', 'OfferController@insert')->name('offers.insert');

    Route::get('offer/{id}/edit', 'OfferController@edit')->name('offers.edit');
    Route::post('offer/{id}/update', 'OfferController@update')->name('offers.update');

    Route::post('offer/delete', 'OfferController@delete')->name('offers.delete');

    Route::post('offer/changeStatus', 'OfferController@changeStatus')->name('offers.changeStatus');


################################################# End Offers Routes #################################################

############################################### Begin Programs Routes #################################################

    Route::get('programs/all' , 'ProgramController@index')->name('programs.all');
    Route::post('programs/all' , 'ProgramController@allData')->name('programs.allData');

    Route::get('programs/create' , 'ProgramController@create')->name('programs.create');
    Route::post('programs/post', 'ProgramController@insert')->name('programs.insert');

    Route::get('programs/{id}/edit', 'ProgramController@edit')->name('programs.edit');
    Route::post('programs/{id}/update', 'ProgramController@update')->name('programs.update');

    Route::post('programs/delete', 'ProgramController@delete')->name('programs.delete');

    Route::post('programs/changeStatus', 'ProgramController@changeStatus')->name('programs.changeStatus');


################################################# End Offers Routes #################################################
});

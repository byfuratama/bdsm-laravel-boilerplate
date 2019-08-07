<?php

//Route untuk authentikasi user 
Route::group([
    'prefix' => 'auth'
], function ($router) {
    Route::post('login', 'AuthController@login');
    Route::group(['middleware' => ['jwt.verify']], function() { 
        Route::post('logout', 'AuthController@logout');
        Route::post('refresh', 'AuthController@refresh');
        Route::post('payload', 'AuthController@payload');
    });
});

//Tambah route dibawah sini

//taruh Route di luar sini untuk yang tidak perlu authentikasi
//Route::get('data', 'DataController@data');

Route::group(['middleware' => ['jwt.verify']], function() { 

    //taruh Route di dalam sini untuk yang perlu authentikasi
    //Route::<Method>('<NamaURL>', '<NamaController>@<NamaFunction>');
    Route::get('test', 'TestController@index');
    Route::post('test', 'TestController@store');
    Route::get('test/{id}', 'TestController@show');
    Route::put('test/{id}', 'TestController@update');
    Route::delete('test/{id}', 'TestController@destroy');
    
    Route::get('test/detail', 'TestController@withDetail');


});
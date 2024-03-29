<?php

//Route untuk authentikasi user 
Route::group([
    'prefix' => 'auth'
], function ($router) {
    Route::post('login', 'AuthController@login');
    Route::group(['middleware' => ['jwt.verify']], function() { 
        Route::post('logout', 'AuthController@logout');
        Route::post('refresh', 'AuthController@refresh');
        Route::post('me', 'AuthController@me');
        Route::post('payload', 'AuthController@payload');
    
        Route::post('update-info', 'AuthController@updateInfo');
        Route::post('update-password', 'AuthController@updatePassword');
    });
});

//Tambah route dibawah sini

//taruh Route di luar sini untuk yang tidak perlu authentikasi
Route::get('data', function() {
    echo "asdf";
});

Route::get('testprint', 'ReceiptController@print_struk_test');

//Buat beberapa route:group untuk hak akses yang berbeda2 pisahkan dengan | jika ada 2 atau lebih hak akses yang dapat menggunakannya
//Route::group(['middleware' => ['jwt.verify:<HAK_AKSES>']], function ()) {....

Route::group(['middleware' => ['jwt.verify:admin']], function() {

    Route::get('user/data', 'UserController@index');
    Route::post('user/data', 'UserController@store');
    Route::get('user/data/{id}', 'UserController@show');
    Route::put('user/data/{id}', 'UserController@update');
    Route::delete('user/data/{id}', 'UserController@destroy');

});

Route::group(['middleware' => ['jwt.verify']], function() { 

    Route::get('supplier/data', 'SupplierController@index');
    Route::get('supplier/aktif', 'SupplierController@indexAktif');
    Route::post('supplier/data', 'SupplierController@store');
    Route::get('supplier/data/{id}', 'SupplierController@show');
    Route::put('supplier/data/{id}', 'SupplierController@update');
    Route::delete('supplier/data/{id}', 'SupplierController@destroy');

    Route::get('item/data', 'ItemController@index');
    Route::post('item/data', 'ItemController@store');
    Route::get('item/data/{id}', 'ItemController@show');
    Route::put('item/data/{id}', 'ItemController@update');
    Route::delete('item/data/{id}', 'ItemController@destroy');
    Route::get('item/detail', 'ItemController@indexWithDetail');
    Route::get('item/detail/{id}', 'ItemController@showWithDetail');

    Route::get('item/aktif', 'ItemController@indexAktif');
    Route::get('item/all', 'ItemController@indexAll');

    Route::get('unit/data', 'UnitController@index');
    Route::post('unit/data', 'UnitController@store');
    Route::get('unit/data/{id}', 'UnitController@show');
    Route::put('unit/data/{id}', 'UnitController@update');
    Route::delete('unit/data/{id}', 'UnitController@destroy');

    Route::get('category/data', 'CategoryController@index');
    Route::post('category/data', 'CategoryController@store');
    Route::get('category/data/{id}', 'CategoryController@show');
    Route::put('category/data/{id}', 'CategoryController@update');
    Route::delete('category/data/{id}', 'CategoryController@destroy');

    Route::get('sales/data', 'SaleController@index');
    Route::post('sales/data', 'SaleController@store');
    Route::get('sales/data/{id}', 'SaleController@show');
    Route::put('sales/data/{id}', 'SaleController@update');
    Route::delete('sales/data/{id}', 'SaleController@destroy');
    Route::get('sales/num', 'SaleController@noOrder');
    Route::get('sales-detail/data/{id}', 'SaleDetailController@byIDParent');

    Route::get('purchases/data', 'PurchaseController@index');
    Route::post('purchases/data', 'PurchaseController@store');
    Route::get('purchases/data/{id}', 'PurchaseController@show');
    Route::put('purchases/data/{id}', 'PurchaseController@update');
    Route::delete('purchases/data/{id}', 'PurchaseController@destroy');
    Route::get('purchases/num', 'PurchaseController@noOrder');
    Route::get('purchases-detail/data/{id}', 'PurchaseDetailController@byIDParent');
    
    Route::get('report/uang','ReportController@uang');
    Route::get('report/item','ReportController@item');
    Route::get('report/sales','ReportController@sales');

    Route::get('report/uang/excel','ReportController@uangExcel');
    Route::get('report/item/excel','ReportController@itemExcel');
    Route::get('report/sales/excel','ReportController@salesExcel');

    Route::post('receipt-print', 'ReceiptController@print_struk');

});
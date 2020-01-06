<?php

Route::middleware(['auth:api'])
    ->namespace('ConfrariaWeb\User\Controllers')
    ->name('api.')
    ->prefix('api')
    ->group(function () {

Route::name('users.')
    ->prefix('users')
    ->group(function () {
        Route::get('datatable', 'UserController@datatable')->name('datatable');
        Route::get('select2', 'UserController@select2')->name('select2');
    });

});
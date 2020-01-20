<?php

Route::middleware(['auth:api'])
    ->name('api.')
    ->prefix('api')
    ->group(function () {

        Route::name('users.')
            ->prefix('users')
            ->namespace('ConfrariaWeb\User\Controllers')
            ->group(function () {

                Route::get('datatable', 'UserController@datatable')->name('datatable');
                Route::get('select2', 'UserController@select2')->name('select2');

                Route::name('status.')
                    ->prefix('status')
                    ->group(function () {

                        Route::get('datatable', 'UserStatusController@datatable')->name('datatable');
                        Route::get('select2', 'UserStatusController@select2')->name('select2');

                    });

            });

    });

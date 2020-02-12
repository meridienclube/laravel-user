<?php

Route::prefix('admin')
    ->name('admin.')
    ->middleware(['web', 'auth'])
    ->namespace('ConfrariaWeb\User\Controllers')
    ->group(function () {

        Route::prefix('users')
            ->name('users.')
            ->group(function () {

                Route::name('statuses.')
                    ->prefix('statuses')
                    ->group(function () {
                    Route::get('trashed', 'UserStatusController@trashed')->name('trashed');
                });
                Route::resource('statuses', 'UserStatusController');

            });

        Route::resource('users', 'UserController');

    });


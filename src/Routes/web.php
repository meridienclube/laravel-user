<?php

Route::prefix('admin')
    ->name('admin.')
    ->middleware(['web', 'auth'])
    ->namespace('ConfrariaWeb\User\Controllers')
    ->group(function () {

        Route::resource('users', 'UserController');

    });

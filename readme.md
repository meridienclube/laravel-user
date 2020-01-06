# Package User Laravel
Complete user pack for laravel.

## Install
From console use the following command:
```php
composer require confrariaweb/laravel-user
```

Publish the files needed for the package.
```php
php artisan vendor:publish --tag=cw_user --force
```

Then create the tables with artisan migrate:
```php
php artisan migrate
```

In the App\User class include the trait "ConfrariaWeb\User\Traits\UserTrait" in the following way:
```php
<?php
namespace App;

use ConfrariaWeb\User\Traits\UserTrait;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;
    use UserTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
}
```

##Check package
To verify that the package is installed correctly use the following artisan command:
```php
php artisan user:check-package
```

## License
The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

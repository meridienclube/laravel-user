<?php

namespace ConfrariaWeb\User\Models;

use Illuminate\Database\Eloquent\Model;
use ConfrariaWeb\Historic\Traits\HistoricTrait;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserStatus extends Model
{

    protected $table = 'user_statuses';

    protected $fillable = [
        'name', 'slug', 'order', 'closure'
    ];

    public function users()
    {
        return $this->hasMany('App\User', 'status_id');
    }

}

<?php

namespace ConfrariaWeb\User\Models;

use Illuminate\Database\Eloquent\Model;
use ConfrariaWeb\Historic\Traits\HistoricTrait;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserStep extends Model
{

    use HistoricTrait;
    use SoftDeletes;

    protected $fillable = [
        'name',
    ];

    protected $table = 'user_steps';

    public function users()
    {
        return $this->belongsToMany('App\User', 'step_user');
    }

}

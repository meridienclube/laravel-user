<?php

namespace MeridienClube\Meridien\Scopes;

use Illuminate\Database\Eloquent\Scope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

class UserContactScope implements Scope
{
    /**
     * Apply the scope to a given Eloquent query builder.
     *
     * @param \Illuminate\Database\Eloquent\Builder $builder
     * @param \Illuminate\Database\Eloquent\Model $model
     * @return void
     */
    public function apply(Builder $builder, Model $model)
    {
        /*
        if (!app()->runningInConsole()) {
            $builder->addSelect(DB::raw('(GROUP_CONCAT(DISTINCT UserContactScopeContactUser.content)) AS column_contacts'))
                ->leftJoin('user_contacts AS UserContactScopeContactUser', 'users.id', '=', 'UserContactScopeContactUser.user_id');
        }
        */
    }
}

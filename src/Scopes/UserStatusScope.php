<?php
namespace MeridienClube\Meridien\Scopes;

use MeridienClube\Meridien\Status;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class UserStatusScope implements Scope
{
    /**
     * Apply the scope to a given Eloquent query builder.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $builder
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @return void
     */
    public function apply(Builder $builder, Model $model)
    {
        if(!app()->runningInConsole())
        {
            /*
            $builder->addSelect([
                'status_name' => Status::select('statuses.name')->whereColumn('statuses.id', 'users.status_id')->limit(1)
            ]);
            */
        }
    }
}

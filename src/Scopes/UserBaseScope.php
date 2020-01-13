<?php

namespace ConfrariaWeb\User\Scopes;

use Illuminate\Database\Eloquent\Scope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

class UserBaseScope implements Scope
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
        if (!app()->runningInConsole()) {
            $onlyMyBase = auth()->user()->roles()->where('settings->only_my_base', 1)->exists();
            $builder->when(($onlyMyBase && !auth()->user()->isAdmin()), function ($query) {
                return $query
                    ->leftJoin('bases AS basesUserBaseScope', 'users.id', '=', 'basesUserBaseScope.base_id')
                    ->whereNotIn('users.id',
                        DB::table('bases')
                            ->select('bases.base_id')
                            ->distinct()
                            ->pluck('base_id')
                    )
                    ->orWhere('basesUserBaseScope.user_id', auth()->id());
            });
        }
    }
}

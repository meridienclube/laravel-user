<?php

namespace ConfrariaWeb\User\Scopes;

use Illuminate\Database\Eloquent\Scope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

class UserDatatableScope implements Scope
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
            $builder

                //->addSelect(DB::raw('(GROUP_CONCAT(DISTINCT UserStepScopeSteps.name)) AS datatable_steps'))
                //->addSelect(DB::raw('(GROUP_CONCAT(DISTINCT UserRolesScopeRoles.display_name)) AS datatable_roles'))

                //->leftJoin('step_user AS UserStepScopeStepUser', 'users.id', '=', 'UserStepScopeStepUser.user_id')
                //->leftJoin('steps AS UserStepScopeSteps', 'UserStepScopeStepUser.step_id', '=', 'UserStepScopeSteps.id')

                //->leftJoin('role_user AS UserRolesScopeRoleUser', 'users.id', '=', 'UserRolesScopeRoleUser.user_id')
                //->leftJoin('roles AS UserRolesScopeRoles', 'UserRolesScopeRoleUser.role_id', '=', 'UserRolesScopeRoles.id')
            ;
        }
*/

    }
}

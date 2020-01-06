<?PHP

namespace ConfrariaWeb\User\Repositories;

use App\User;
use ConfrariaWeb\User\Contracts\UserContract;
use ConfrariaWeb\Vendor\Traits\RepositoryTrait;
use Illuminate\Database\Eloquent\Builder;

class UserRepository implements UserContract
{
    use RepositoryTrait;

    function __construct(User $user)
    {
        $this->obj = $user;
    }

    public function orderBy($order = 'id', $by = 'asc')
    {
        $this->obj = $this->obj
            ->when(('roles' == $order), function ($query) use ($order, $by) {
                return $query
                    ->leftJoin('entrust_role_user AS orderByRoleUser', 'users.id', '=', 'orderByRoleUser.user_id')
                    ->leftJoin('entrust_roles AS orderByRoles', 'orderByRoleUser.role_id', '=', 'orderByRoles.id')
                    //->groupBy('orderByRoles.display_name')
                    ->orderBy('orderByRoles.display_name', $by);
            })
            ->when(('steps' == $order), function ($query) use ($order, $by) {
                return $query
                    ->leftJoin('step_user AS orderByStepUser', 'users.id', '=', 'orderByStepUser.user_id')
                    ->leftJoin('steps AS orderBySteps', 'orderByStepUser.step_id', '=', 'orderBySteps.id')
                    //->groupBy('orderBySteps.name')
                    ->orderBy('orderBySteps.name', $by);
            })
            ->when(in_array($order, ['name', 'email', 'cpf_cnpj']), function ($query) use ($order, $by) {
                return $query->orderBy($order, $by);
            });
        return $this;
    }

    public function base(int $id, $take = 10)
    {
        $user = $this->obj->find($id);
        return $user->base()->paginate($take);
    }

    public function destroyContact(int $user_id, int $contact_id)
    {
        $user = $this->obj->find($user_id);
        return $user->contacts()->find($contact_id)->delete();
    }

    public function orWhere(array $data = [])
    {

        if (isset($data['roles']) && is_array($data['roles'])) {
            foreach ($data['roles'] as $role_k => $role_v) {
                if (!isset($role_v)) continue;
                $this->obj = $this->obj->orWhereHas('roles', function (Builder $query) use ($role_v) {
                    $query->where('display_name', 'like', '%' . $role_v . '%');
                    $query->orWhere('name', 'like', '%' . $role_v . '%');
                });
            }
        }

        if (isset($data['contacts']) && is_array($data['contacts'])) {
            foreach ($data['contacts'] as $contact_k => $contact_v) {
                if (!isset($contact_v)) continue;
                $this->obj = $this->obj->orWhereHas('contacts', function (Builder $query) use ($contact_v) {
                    $query->where('content', 'like', '%' . $contact_v . '%');
                });
            }
        }

        if (isset($data['optionsValues']) && is_array($data['optionsValues'])) {
            foreach ($data['optionsValues'] as $option_k => $option_v) {
                if (!isset($option_v)) continue;
                $this->obj = $this->obj->orWhereHas('optionsValues', function (Builder $query) use ($option_v) {
                    $query->where('content', 'like', '%' . $option_v . '%');
                });
            }
        }

        $this->obj = $this->obj->groupBy('users.id');

        return $this;
    }

    public function where(array $data, $take = null, $skip = false, $select = false)
    {
        if (isset($data['statuses']) && is_array($data['statuses'])) {
            $this->obj = $this->obj->whereIn('status_id', $data['statuses']);
        }

        if (isset($data['employees']) && is_array($data['employees'])) {
            $this->obj = $this->obj->join('bases AS whereEmployeesBase', function ($join) use ($data) {
                $join->on('users.id', '=', 'whereEmployeesBase.base_id')
                    ->whereIn('whereEmployeesBase.user_id', $data['employees']);
            });
        }

        if (isset($data['cpf_cnpj'])) {
            $this->obj = $this->obj->where('cpf_cnpj', 'like', '%' . $data['cpf_cnpj'] . '%');
        }

        if (isset($data['name'])) {
            $this->obj = $this->obj->where('users.name', 'like', '%' . $data['name'] . '%');
        }

        if (isset($data['id'])) {
            $this->obj = $this->obj->where('users.id', $data['id']);
        }

        if (isset($data['options'])) {
            $empty = true;
            foreach ($data['options'] as $opt) {
                if (isset($opt) && !empty($opt)) {
                    $empty = false;
                    break;
                }
            }
            if (!$empty) {
                $this->obj = $this->obj->whereHas('options', function (Builder $query) use ($data) {
                    foreach ($data['options'] as $option_k => $option_v) {
                        if (isset($option_v)) {
                            $query->where('content', 'like', $option_v);
                        }
                    }
                });
            }
        }

        if (isset($data['steps'])) {
            $this->obj = $this->obj->whereHas('steps', function (Builder $query) use ($data) {
                $query->whereIn('steps.id', $data['steps']);
            });
        }
        if (isset($data['roles'])) {
            $this->obj = $this->obj->whereHas('roles', function (Builder $query) use ($data) {
                $query->whereIn('roles.id', $data['roles']);
            });
        }

        if (isset($data['customer_role'])) {
            $this->obj = $this->obj->whereHas('roles', function (Builder $query) use ($data) {
                $query->where('roles.settings->customer_role', $data['customer_role']);
            });
        }

        $this->obj = $this->obj->groupBy('users.id');

        return $this;
    }


    public function associates()
    {
        return $this->obj->onlyAssociates()->get();
    }

    public function employees()
    {
        return $this->obj->employees()->get();
    }

    protected function syncWithoutDetaching($obj, $data)
    {
        if (isset($data['steps'])) {
            $obj->steps()->syncWithoutDetaching($data['steps']);
        }

        if (isset($data['indicator'])) {
            $obj->indicator()->syncWithoutDetaching($data['indicator']);
        }

        if (isset($data['roles'])) {
            $obj->roles()->syncWithoutDetaching($data['roles']);
        }

        if (isset($data['for_base'])) {
            $users_for_base = is_array($data['for_base']) ? $data['for_base'] : [$data['for_base']];
            foreach ($users_for_base as $user_for_base) {
                $userFindForBase = $this->obj->find($user_for_base);
                if (isset($obj->id)) {
                    $userFindForBase->base()->syncWithoutDetaching($obj->id);
                }
            }
        }
    }

    protected function attach($obj, $data)
    {
        if (isset($data['for_base'])) {
            $users_for_base = is_array($data['for_base']) ? $data['for_base'] : [$data['for_base']];
            foreach ($users_for_base as $user_for_base) {
                $userFindForBase = $this->obj->find($user_for_base);
                if (isset($obj->id)) {
                    $userFindForBase->base()->attach($obj->id);
                }
            }
        }

        if (isset($data['steps'])) {
            $obj->steps()->attach($data['steps']);
        }

        if (isset($data['roles'])) {
            $obj->roles()->attach($data['roles']);
        }

        if (isset($data['base'])) {
            $obj->baseOwner()->attach($data['base']);
        }

        /*
        if (isset($data['historic']) && is_array($data['historic']) && !empty($data['historic'])) {
            $obj->historics()->create($data['historic']);
        }

        if (isset($data['historics']) && is_array($data['historics']) && !empty($data['historics'])) {
            $obj->historics()->createMany($data['historics']);
        }
        */

        if (isset($data['files'])) {
            $obj->files()->createMany($data['files']);
        }
    }

    protected function syncs($obj, $data)
    {

        if (isset($data['steps'])) {
            $obj->steps()->sync($data['steps']);
        }
        /*
                if (isset($data['contacts'])) {
                    $this->SyncAllBelongsTo($obj, 'contacts', $data['contacts']);
                }
        */
        if (isset($data['indicator'])) {
            $obj->indicator()->sync($data['indicator']);
        }

        if (isset($data['base'])) {
            $obj->baseOwner()->sync($data['base']);
        }

        if (isset($data['roles'])) {
            $obj->roles()->sync($data['roles']);
        }

        if (isset($data['employees'])) {
            $obj->employees()->sync($data['employees']);
        }

        if (isset($data['associates'])) {
            $obj->associates()->sync($data['associates']);
        }

        if (isset($data['options'])) {
            $obj->optionsValues()->sync($data['options']);
        }

        if (isset($data['optionsValues'])) {
            $obj->optionsValues()->sync($data['optionsValues']);
        }

        if (isset($data['integrations'])) {
            $obj->integrations()->attach($data['integrations']);
        }

    }

    protected function SyncAllBelongsTo($obj, $relation, $data)
    {
        $dataChecked = array_filter(array_map(function ($item) {
            if ($item['value']) {
                return $item;
            }
        }, $data));
        $obj->{$relation}()->delete();
        $obj->{$relation}()->createMany($dataChecked);

    }
    /*
        public function orderBy($sql, $data = NULL)
        {
            $collunsuser = [
                'name',
                'email',
                'created_at',
                'updated_at'
            ];
            $by = data_get($data, 'orderby', 'name');
            $order = data_get($data, 'order', 'ASC');

            return $sql
                ->when(in_array($by, $collunsuser), function ($query) use ($by, $order) {
                    return $query->orderBy('users.' . $by, $order);
                })
                ->when(($by == 'roles'), function ($query) use ($order) {
                    return $query->whereHas('roles', function($query) {
                        $query->whereNotNull('roles.display_name')
                            ->orderBy('roles.display_name','desc');
                    })->orderBy('roles.display_name','desc');
                });
        }
    */
}

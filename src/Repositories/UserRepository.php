<?PHP

namespace ConfrariaWeb\User\Repositories;

use App\User;
use ConfrariaWeb\User\Contracts\UserContract;
use ConfrariaWeb\Vendor\Traits\RepositoryTrait;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Config;

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
                    ->leftJoin(Config::get('cw_entrust.role_user_table') . ' AS orderByRoleUser', 'users.id', '=', 'orderByRoleUser.user_id')
                    ->leftJoin(Config::get('cw_entrust.roles_table') . ' AS orderByRoles', 'orderByRoleUser.role_id', '=', 'orderByRoles.id');
                    //->groupBy('orderByRoles.display_name')
                    //->orderBy('orderByRoles.display_name', $by);
            })
            ->when(in_array($order, ['name', 'email', 'id']), function ($query) use ($order, $by) {
                return $query->orderBy($order, $by);
            });
        return $this;
    }

    public function orWhere(array $data = [])
    {
        $this->obj = $this->obj->addSelect('users.*');

        if (isset($data['email'])) {
            $this->obj = $this->obj->orWhere('users.email', 'like', '%' . $data['email'] . '%');
        }

        if (isset($data['roles']) && is_array($data['roles'])) {
            foreach ($data['roles'] as $role_k => $role_v) {
                if (!isset($role_v)) continue;
                $this->obj = $this->obj->orWhereHas('roles', function (Builder $query) use ($role_v) {
                    $query->where('display_name', 'like', '%' . $role_v . '%');
                    $query->orWhere('name', 'like', '%' . $role_v . '%');
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
        $this->obj = $this->obj->addSelect('users.*');

        if (isset($data['statuses']) && is_array($data['statuses'])) {
            $this->obj = $this->obj->whereIn('status_id', $data['statuses']);
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

        if (isset($data['roles'])) {
            $this->obj = $this->obj->whereHas('roles', function (Builder $query) use ($data) {
                $query->whereIn('roles.id', $data['roles']);
            });
        }

        $this->obj = $this->obj->groupBy('users.id');

        return $this;
    }

    protected function syncWithoutDetaching($obj, $data)
    {
        if (isset($data['roles'])) {
            $obj->roles()->syncWithoutDetaching($data['roles']);
        }
    }

    protected function attach($obj, $data)
    {

        if (isset($data['steps'])) {
            $obj->steps()->attach($data['steps']);
        }

        if (isset($data['roles'])) {
            $obj->roles()->attach($data['roles']);
        }

        if (isset($data['files'])) {
            $obj->files()->createMany($data['files']);
        }
    }

    protected function syncs($obj, $data)
    {

        if (isset($data['roles'])) {
            $obj->roles()->sync($data['roles']);
        }

        if (isset($data['options'])) {
            $obj->optionsValues()->sync($data['options']);
        }

        if (isset($data['optionsValues'])) {
            $obj->optionsValues()->sync($data['optionsValues']);
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

}

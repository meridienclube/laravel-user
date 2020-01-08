<?php

namespace ConfrariaWeb\User\Traits;

use Illuminate\Support\Facades\Config;
use Staudenmeir\EloquentHasManyDeep\HasRelationships;

trait UserTrait
{

    use HasRelationships;

    public function rolePermissions()
    {
        return $this->hasManyDeep(
            Config::get('cw_entrust.permission'),
            ['entrust_role_user', Config::get('cw_entrust.role'), Config::get('cw_entrust.permission_role_table')]
        );
    }

    public function roles()
    {
        return $this->belongsToMany(Config::get('cw_entrust.role'), Config::get('cw_entrust.role_user_table'), Config::get('cw_entrust.user_foreign_key'));
    }

    public function hasRole($role)
    {
        return $this->roles->contains('name', $role);
    }

    public function hasPermission($permission)
    {
        return ($this->roles->contains('name', 'admin') || $this->rolePermissions->contains('name', $permission));
    }

    public function format()
    {
        return collect([
            'id' => $this->id,
            'name' => $this->name,
            'last_task' => ($this->tasks()->count() > 0) ?
                \Carbon\Carbon::parse($this->tasks()->orderBy('id', 'desc')->first()['datetime'])->toDateString() :
                '',
            'belongs_to' => [
                'status' => $this->status->name
            ],
            'implode' => [
                'contacts' => $this->contacts->implode('content', ', '),
                'roles' => $this->roles->implode('display_name', ', '),
                'steps' => $this->steps->implode('name', ', ')
            ]
        ]);
    }

}
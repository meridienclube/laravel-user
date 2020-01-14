<?php

namespace ConfrariaWeb\User\Traits;

use ConfrariaWeb\File\Traits\FileTrait;
use ConfrariaWeb\Option\Traits\OptionTrait;
use Illuminate\Support\Facades\Config;
use Staudenmeir\EloquentHasManyDeep\HasRelationships;

trait UserTrait
{

    use OptionTrait;
    use HasRelationships;
    use FileTrait;

    public function rolePermissions()
    {
        return $this->hasManyDeep(
            Config::get('cw_entrust.permission'),
            [Config::get('cw_entrust.role_user_table'), Config::get('cw_entrust.role'), Config::get('cw_entrust.permission_role_table')]
        );
    }

    public function roleTasksStatuses()
    {
        return $this->hasManyDeep(
            'ConfrariaWeb\Task\Models\TaskStatus',
            [Config::get('cw_entrust.role_user_table'), Config::get('cw_entrust.role'), 'role_status_task']
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

    public function tasks()
    {
        return $this->belongsToMany('ConfrariaWeb\Task\Models\Task', 'task_user', 'user_id');
    }

    public function contacts()
    {
        return $this->morphMany('ConfrariaWeb\Contact\Models\Contact', 'contactable');
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

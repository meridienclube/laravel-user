<?php

namespace ConfrariaWeb\User\Traits;

trait UserTrait
{

    public function scopeCustomers()
    {
        return $this->whereHas('roles', function ($q) {
            $q->where('settings->customer_role', 1);
        });
    }

    public function scopeEmployees()
    {
        return $this->whereHas('roles', function ($q) {
            $q->where('settings->employee_role', 1);
        });
    }

    public function scopeOnlyAssociates()
    {
        return $this->whereHas('roles', function ($q) {
            $q->where('name', config('erp.associated_role'));
        });
    }

    public function scopeOnlyEmployees()
    {
        return $this->whereHas('roles', function ($q) {
            $q->where('settings->employee_role', 1);
        });
    }

    public function rolePermissions()
    {
        return $this->hasManyDeep(
            'App\Permission',
            ['role_user', 'App\Role', 'permission_role']
        );
    }

    public function roleSteps()
    {
        return $this->hasManyDeep(
            'App\Step',
            ['role_user', 'App\Role', 'role_step'],
            ['user_id']
        );
    }

    public function roleStepWhenCreatingUser()
    {
        return $this->hasManyDeep(
            'App\Step',
            ['role_user', 'App\Role', 'role_step_when_creating_user'],
            ['user_id']
        );
    }

    public function roleStatuses()
    {
        return $this->hasManyDeep(
            'App\Status',
            ['role_user', 'App\Role', 'role_status_user']
        );
    }

    public function roleTasksStatuses()
    {
        return $this->hasManyDeep(
            'App\Status',
            ['role_user', 'App\Role', 'role_status_task']
        );
    }

    public function roleOptions()
    {
        return $this->hasManyDeep(
            'App\Option',
            ['role_user', 'App\Role', 'option_role'],
            ['user_id']
        )->distinct();
    }

    public function indications()
    {
        return $this->belongsToMany('App\UserAuth', 'user_indications', 'user_id', 'indicated_id');
    }

    public function indicator()
    {
        return $this->belongsToMany('App\UserAuth', 'user_indications', 'indicated_id', 'user_id');
    }

    public function dashboards()
    {
        return $this->hasMany('App\Dashboard');
    }


    public function status()
    {
        return $this->belongsTo('App\Status');
    }

    public function steps()
    {
        return $this->belongsToMany('App\Step', 'step_user', 'user_id');
    }

    public function tasks()
    {
        return $this->belongsToMany('App\Task', 'task_user', 'user_id');
    }

    public function roles()
    {
        return $this->belongsToMany('App\Role', 'role_user', 'user_id');
    }

    function avatar()
    {
        return ($this->files()->count() > 0) ? $this->files()->orderBy('created_at', 'desc')->first()->url : $this->get_gravatar();
    }

    public function hasRole($role)
    {
        return $this->roles->contains('name', $role);
    }

    public function hasPermission($permission)
    {
        return ($this->roles->contains('name', 'admin') || $this->rolePermissions->contains('name', $permission));
    }

    public function isAdmin()
    {
        return $this->roles->contains('name', 'admin');
    }

    public function base()
    {
        return $this->belongsToMany(
            'App\UserAuth',
            'bases',
            'user_id',
            'base_id'
        );
    }

    public function baseOwner()
    {
        return $this->belongsToMany(
            'App\UserAuth',
            'bases',
            'base_id',
            'user_id'
        );
    }

    public function routeNotificationForMail($notification)
    {
        return $this->email;
    }

    public function routeNotificationForNexmo($notification)
    {
        //return $this->phone;
        return '5551994024451';
    }

    public function routeNotificationForTelegram()
    {
        //return $this->telegram_user_id;
        //return '860618393';
        //https://api.telegram.org/bot967072185:AAFMC0uJVplTtjpago5I4uTyYjb9jZdP8oU/getUpdates
        return '860618393';
    }

    public function routeNotificationForSlack($notification)
    {
        return 'https://hooks.slack.com/services/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';
    }

    public function setEmailAttribute($value)
    {
        if (empty($value)) {
            $this->attributes['email'] = NULL;
        } else {
            $this->attributes['email'] = $value;
        }
    }

    function get_gravatar($email = null, $s = 80, $d = 'mp', $r = 'g', $img = false, $atts = array())
    {
        $url = 'https://www.gravatar.com/avatar/';
        $url .= md5(strtolower(trim(isset($email) ? $email : $this->email)));
        $url .= "?s=$s&d=$d&r=$r";
        if ($img) {
            $url = '<img src="' . $url . '"';
            foreach ($atts as $key => $val)
                $url .= ' ' . $key . '="' . $val . '"';
            $url .= ' />';
        }
        return $url;
    }

    public function format()
    {
        return collect($this->format_array());
    }

    public function format_array()
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

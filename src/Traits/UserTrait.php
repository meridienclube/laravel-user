<?php

namespace ConfrariaWeb\User\Traits;

use ConfrariaWeb\File\Traits\FileTrait;
use ConfrariaWeb\Historic\Traits\HistoricTrait;
use ConfrariaWeb\Option\Traits\OptionTrait;
use Illuminate\Support\Facades\Config;
use Staudenmeir\EloquentHasManyDeep\HasRelationships;

trait UserTrait
{
    use FileTrait;
    use HasRelationships;
    use HistoricTrait;
    use OptionTrait;

    public function isAdmin()
    {
        return $this->roles->contains('name', 'admin');
    }

    public function roles()
    {
        return $this->belongsToMany(Config::get('cw_entrust.role'), Config::get('cw_entrust.role_user_table'), Config::get('cw_entrust.user_foreign_key'));
    }

    public function permissions()
    {
        return $this->hasManyDeep(
            Config::get('cw_entrust.permission'),
            [Config::get('cw_entrust.role_user_table'), Config::get('cw_entrust.role'), Config::get('cw_entrust.permission_role_table')]
        );
    }

    public function hasRole($role)
    {
        return $this->roles->contains('name', $role);
    }

    public function hasPermission($permission)
    {
        return ($this->roles->contains('name', 'admin') || $this->permissions->contains('name', $permission));
    }

    public function status()
    {
        return $this->belongsTo('ConfrariaWeb\User\Models\UserStatus', 'status_id');
    }

    function avatar()
    {
        return ($this->files()->count() > 0) ? $this->files()->orderBy('created_at', 'desc')->first()->url : $this->get_gravatar();
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

}

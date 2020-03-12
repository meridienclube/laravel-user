<?php

namespace ConfrariaWeb\User\Traits;

use Carbon\Carbon;
use ConfrariaWeb\Contact\Traits\ContactTrait;
use ConfrariaWeb\File\Traits\FileTrait;
use ConfrariaWeb\Historic\Traits\HistoricTrait;
use ConfrariaWeb\Integration\Traits\IntegrationTrait;
use ConfrariaWeb\Location\Traits\LocationTrait;
use ConfrariaWeb\Option\Traits\OptionTrait;
use ConfrariaWeb\Task\Models\Task;
use ConfrariaWeb\Task\Traits\TaskTrait;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Staudenmeir\EloquentHasManyDeep\HasRelationships;

trait UserTrait
{
    use LocationTrait;
    use ContactTrait;
    use FileTrait;
    use HasRelationships;
    use HistoricTrait;
    use IntegrationTrait;
    use OptionTrait;
    use TaskTrait;

    public function options()
    {
        return $this->hasManyDeep(
            'ConfrariaWeb\Option\Models\Option',
            [
                Config::get('cw_entrust.role_user_table'),
                'ConfrariaWeb\Entrust\Models\Role',
                'option_role'
            ], // Intermediate models and tables, beginning at the far parent (User).
            [
                'user_id', // Foreign key on the "role_user" table.
                'id',      // Foreign key on the "roles" table (local key).
                'role_id'  // Foreign key on the "options" table.
            ],
            [
                'id',      // Local key on the "users" table.
                'role_id', // Local key on the "role_user" table (foreign key).
                'id'       // Local key on the "roles" table.
            ]
        )->distinct();
    }

    public function isAdmin()
    {
        return $this->roles->contains('name', 'admin');
    }

    /*
     * Etapas em que o usuario se encontra no CRM
     * Metodo utilizado somente quando conter
     * o pacote "confrariaweb/laravel-crm".
     */
    public function steps()
    {
        return $this->belongsToMany('ConfrariaWeb\Crm\Models\Step', 'crm_step_user');
    }

    /*
     * Etapas vinculadas aos perfis do usuario
     * Metodo utilizado somente quando conter
     * o pacote "confrariaweb/laravel-crm".
     */
    public function roleSteps()
    {
        return $this->hasManyDeep(
            'ConfrariaWeb\Crm\Models\Step',
            [Config::get('cw_entrust.role_user_table'), Config::get('cw_entrust.role'), 'crm_role_step']
        )->distinct();
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

    /**
     * Lista todos os indicados do usuário
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function indications()
    {
        return $this->belongsToMany('App\User', 'user_indications', 'user_id', 'indicated_id');
    }

    /**
     * Lista o usuario indicador, lista quem indicou
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function indicator()
    {
        return $this->belongsToMany('App\User', 'user_indications', 'indicated_id', 'user_id');
    }

    /**
     * Metodo utilizado em conjunto com o pacote de dashboard "confrariaweb/laravel-dashboard"
     * @return mixed
     */
    public function dashboards()
    {
        return $this->hasMany('ConfrariaWeb\Dashboard\Models\Dashboard');
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

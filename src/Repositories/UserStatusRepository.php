<?PHP

namespace ConfrariaWeb\User\Repositories;

use ConfrariaWeb\User\Contracts\UserStatusContract;
use ConfrariaWeb\User\Models\UserStatus;
use ConfrariaWeb\Vendor\Traits\RepositoryTrait;

class UserStatusRepository implements UserStatusContract
{

    use RepositoryTrait;

    function __construct(UserStatus $user_status)
    {
        $this->obj = $user_status;
    }

    public function where(array $data, $take = null, $skip = false, $select = false)
    {
        if (isset($data['id'])) {
            $this->obj = $this->obj->where('id', $data['id']);
        }

        if (isset($data['name'])) {
            $this->obj = $this->obj->where('name', 'like', '%' . $data['name'] . '%');
        }

        if (isset($data['slug'])) {
            $this->obj = $this->obj->where('slug', 'like', '%' . $data['slug'] . '%');
        }

        if (isset($data['order'])) {
            $this->obj = $this->obj->where('order', $data['order']);
        }

        if (isset($data['closure'])) {
            $this->obj = $this->obj->where('closure', $data['closure']);
        }

        return $this;
    }

}

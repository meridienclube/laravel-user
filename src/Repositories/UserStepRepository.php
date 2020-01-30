<?PHP

namespace ConfrariaWeb\User\Repositories;

use ConfrariaWeb\User\Contracts\UserStepContract;
use ConfrariaWeb\User\Models\UserStep;
use ConfrariaWeb\Vendor\Traits\RepositoryTrait;

class UserStepRepository implements UserStepContract
{

    use RepositoryTrait;

    function __construct(UserStep $user_step)
    {
        $this->obj = $user_step;
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

        return $this;
    }

}

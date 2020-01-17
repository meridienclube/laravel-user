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

}

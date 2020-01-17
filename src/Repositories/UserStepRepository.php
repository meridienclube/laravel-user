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

}

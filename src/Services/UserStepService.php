<?php

namespace ConfrariaWeb\User\Services;

use ConfrariaWeb\User\Contracts\UserStepContract;
use ConfrariaWeb\Vendor\Traits\ServiceTrait;

class UserStepService
{
    use ServiceTrait;

    public function __construct(UserStepContract $user_step)
    {
        $this->obj = $user_step;
    }

}

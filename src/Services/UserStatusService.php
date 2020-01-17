<?php

namespace ConfrariaWeb\User\Services;

use ConfrariaWeb\User\Contracts\UserStatusContract;
use ConfrariaWeb\Vendor\Traits\ServiceTrait;

class UserStatusService
{
    use ServiceTrait;

    public function __construct(UserStatusContract $user_status)
    {
        $this->obj = $user_status;
    }

}

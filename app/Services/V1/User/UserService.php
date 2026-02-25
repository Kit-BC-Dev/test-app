<?php

namespace App\Services\V1\User;

use App\Repositories\V1\User\UserRepository;
use App\Services\BaseService;

class UserService extends BaseService
{
    public function __construct(UserRepository $repository)
    {
        parent::__construct($repository);
    }

}
<?php

namespace App\Services\V1\User;

use App\Services\BaseService;
use App\Repositories\V1\User\UserInformationRepository;


class UserInformationService extends BaseService
{
    public function __construct(UserInformationRepository $repository)
    {
        parent::__construct($repository);
    }
}
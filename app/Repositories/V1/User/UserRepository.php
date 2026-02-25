<?php

namespace App\Repositories\V1\User;

use App\Models\User;
use App\Repositories\BaseRepository;
class UserRepository extends BaseRepository
{
    public function __construct(User $model)
    {
        parent::__construct($model);
    }
}
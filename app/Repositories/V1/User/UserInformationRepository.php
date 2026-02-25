<?php 

namespace App\Repositories\V1\User;

use App\Repositories\BaseRepository;
use App\Models\UserInformation;

class UserInformationRepository extends BaseRepository
{
    public function __construct(UserInformation $model)
    {
        parent::__construct($model);
    }
}
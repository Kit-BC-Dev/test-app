<?php

namespace App\Pipelines\V1\User;

use Closure;
use App\Services\V1\User\UserInformationService;
class SaveUserInformation
{
    public function __construct(protected UserInformationService $userInformationService)
    {}

    public function handle(array $data, Closure $next)
    {
        $this->userInformationService->create(
            [
                'user_id' => $data['user']->id,
                'first_name' => $data['first_name'],
                'last_name' => $data['last_name'],
                'phone_number' => $data['phone_number'],
                'birth_day' => $data['birth_day'],

            ]
        );
        return $next($data);
    }
}
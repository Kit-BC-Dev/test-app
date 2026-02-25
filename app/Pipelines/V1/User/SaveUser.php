<?php

namespace App\Pipelines\V1\User;

use Closure;
use App\Services\V1\User\UserService;

class SaveUser
{
    public function __construct(private UserService $userService)
    {
    }

    public function handle(array $data, Closure $next)
    {
        $data['user'] = $this->userService->create([
            'email' => $data['email'],
            'password' => $data['password'],
        ]);
        return $next($data);
    }
}
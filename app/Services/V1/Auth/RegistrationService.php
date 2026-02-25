<?php

namespace App\Services\V1\Auth;

use Illuminate\Pipeline\Pipeline;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\JsonResponse;
use App\Pipelines\V1\User\{
    SaveUser,
    SaveUserInformation,
};
use App\Pipelines\V1\Address\SaveAddress;

class RegistrationService
{
    public function __construct(
        protected Pipeline $pipeline,
    ){}
    public function register(array $data): JsonResponse
    {
        $result = DB::transaction(function () use ($data) {
            return $this->pipeline->send($data)
                ->through([
                    SaveUser::class,
                    SaveUserInformation::class,
                    SaveAddress::class,
                ])
                ->thenReturn();
        });

        return response()->json([
            'message' => 'Registration successful'
        ], 201);
    }
}
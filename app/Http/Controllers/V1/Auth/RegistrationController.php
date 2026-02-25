<?php

namespace App\Http\Controllers\V1\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\Auth\RegistrationRequest;
use Illuminate\Http\Request;
use App\Services\V1\Auth\RegistrationService;

class RegistrationController extends Controller
{
    public function __construct(protected RegistrationService $registrationService)
    {
    }
    public function __invoke(RegistrationRequest $request)
    {
        return $this->registrationService->register($request->all());
    }
}

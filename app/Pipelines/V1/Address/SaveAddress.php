<?php

namespace App\Pipelines\V1\Address;

use Closure;
use App\Services\V1\Address\AddressService;
class SaveAddress
{
    public function __construct(
        protected AddressService $addressService
    ){}


    public function handle($data, Closure $next)
    {
        $this->addressService->create([
            'user_id' => $data['user']->id,
            'street' => $data['street'],
            'city' => $data['city'],
            'state' => $data['state'],
            'country' => $data['country'],
            'zip_code' => $data['zip_code'],
        ]);
        return $next($data);
    }
}
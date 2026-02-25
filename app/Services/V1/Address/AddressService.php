<?php

namespace App\Services\V1\Address;

use App\Services\BaseService;
use App\Repositories\V1\Address\AddressRepository;

class AddressService extends BaseService
{
    public function __construct(AddressRepository $addressRepository)
    {
        $this->repository = $addressRepository;
    }
}
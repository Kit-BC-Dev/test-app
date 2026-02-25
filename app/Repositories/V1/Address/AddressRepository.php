<?php

namespace App\Repositories\V1\Address;

use App\Repositories\BaseRepository;
use App\Models\Address;
class AddressRepository extends BaseRepository
{
    public function __construct(Address $address)
    {
        parent::__construct($address);
    }
}
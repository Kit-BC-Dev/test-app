<?php

namespace App\Interfaces;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
interface BasicCRUDInterface
{
    public function index(): Collection|null;
    public function create(array $data): Model;
    public function show(int $id): Model|null;
    public function update(array $data, int $id): Model|null;
    public function delete(int $id): bool;
}
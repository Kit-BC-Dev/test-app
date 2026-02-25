<?php

namespace App\Services;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Illuminate\Database\Eloquent\Model;

abstract class BaseService
{
    public function __construct(protected $repository)
    {
        $this->repository = $repository;
    }

    public function index(): Collection
    {
        return $this->repository->index();
    }

    public function create(array $data): Model
    {
        return $this->repository->create($data);
    }

    public function findByField(string $field, mixed $value): Model|null
    {
        return $this->repository->findByField($field, $value);
        
    }

    public function update(array $data, int $id): Model|null
    {
        return $this->repository->update($data, $id);

    }

    public function delete(int $id): bool
    {
        return $this->repository->delete($id);
    
    }
}
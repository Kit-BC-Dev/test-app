<?php

namespace App\Repositories;

use App\Interfaces\BasicCRUDInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

abstract class BaseRepository implements BasicCRUDInterface
{
    public function __construct(protected Model $model)
    {
        $this->model = $model;
    }

    public function index(): Collection
    {
        return $this->model->get();
    }

    public function create(array $data): Model
    {
        return $this->model->create($data);
    }

    public function findByField(string $field, mixed $value): Model|null
    {
        return $this->model->where($field, $value)->first();
    }

    public function update(array $data, int $id): Model|null
    {
        $model = $this->model->findOrFail($id);
        $model->update($data);
        return $model;
    }

    public function delete(int $id): bool
    {
        $model = $this->model->findOrFail($id);
        return $model->delete($id);
    }
}
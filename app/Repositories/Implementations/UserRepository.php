<?php

namespace App\Repositories\Implementations;

use App\Models\UserModel;
use App\Repositories\Interfaces\UserRepositoryInterface;

class UserRepository implements UserRepositoryInterface
{
    protected $model;

    public function __construct()
    {
        $this->model = new UserModel();
    }

    public function getAll(): array
    {
        return $this->model->orderBy('id', 'DESC')->findAll();
    }

    public function search(?string $keyword): array
    {
        return $this->model
            ->searchUsers($keyword)
            ->orderBy('id', 'DESC')
            ->findAll();
    }

    public function find(int $id): ?array
    {
        return $this->model->find($id);
    }

    public function findByEmail(string $email): ?array
    {
        return $this->model->where('email', $email)->first();
    }

    public function create(array $data): int
    {
        $this->model->insert($data);
        return $this->model->getInsertID();
    }

    public function updateUser(int $id, array $data): bool
    {
        return $this->model->update($id, $data);
    }

    public function deleteUser(int $id): bool
    {
        return (bool) $this->model->delete($id);
    }

    public function findByRole(string $role): ?array
    {
        return $this->model->where('role', $role)->first();
    }
}

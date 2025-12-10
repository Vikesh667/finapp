<?php

namespace App\Repositories\Interfaces;

interface UserRepositoryInterface
{
    public function getAll(): array;

    public function search(?string $keyword): array;

    public function find(int $id): ?array;

    public function findByEmail(string $email): ?array;

    public function create(array $data): int;

    public function updateUser(int $id, array $data): bool;

    public function deleteUser(int $id): bool;
    
    public function findByRole(string $role): ?array;

}

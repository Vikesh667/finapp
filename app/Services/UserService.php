<?php

namespace App\Services;

use App\Repositories\Interfaces\UserRepositoryInterface;

class UserService
{
    protected $repo;

    public function __construct(UserRepositoryInterface $repo)
    {
        $this->repo = $repo;
    }

    public function listUser(): array
    {
        return $this->repo->getAll();
    }

    public function searchUser(?string $keyword): array
    {
        return $this->repo->search($keyword);
    }

    public function getUser(int $id): ?array
    {
        return $this->repo->find($id);
    }

    public function getPaginatedUsers(int $page, ?string $search, int $limit = 10): array
    {
        $offset = ($page - 1) * $limit;

        $filteredUsers = $search
            ? $this->repo->search($search)
            : $this->repo->getAll();

        $totalFiltered = count($filteredUsers);

        $users = array_slice($filteredUsers, $offset, $limit);

        $total = count($this->repo->getAll());

        return [
            'users'        => $users,
            'current_page' => $page,
            'per_page'     => $limit,
            'total'        => $total,
            'filtered'     => $totalFiltered,
            'total_pages'  => ceil($totalFiltered / $limit)
        ];
    }

    public function createUser(array $data): int
    {
        if ($this->repo->findByEmail($data['email'])) {
            throw new \RuntimeException('Email already exists.');
        }

        if (!empty($data['password'])) {
            $data['password'] = password_hash($data['password'], PASSWORD_BCRYPT);
        }

        return $this->repo->create($data);
    }

    public function updateUser(int $id, array $data): bool
    {
        // Ensure user exists
        $existingUser = $this->repo->find($id);

        if (!$existingUser) {
            throw new \RuntimeException("User not found.");
        }

        // --- UNIQUE EMAIL CHECK ---
        if (!empty($data['email'])) {
            $existing = $this->repo->findByEmail($data['email']);

            if ($existing && $existing['id'] != $id) {
                throw new \RuntimeException("Email already exists.");
            }
        }

        // Handle password
        if (empty($data['password'])) {
            unset($data['password']);
        } else {
            $data['password'] = password_hash($data['password'], PASSWORD_BCRYPT);
        }

        return $this->repo->updateUser($id, $data);
    }


    public function deleteUserWithTransfer(int $id): bool
    {
        // 1. Check if user exists
        $user = $this->repo->find($id);
        if (!$user) {
            throw new \RuntimeException("User not found.");
        }

        // 2. Find admin user
        $admin = $this->repo->findByRole('admin');
        if (!$admin) {
            throw new \RuntimeException("Admin not found.");
        }

        // 3. Transfer customers to admin
        $customerRepo = service('customerRepository');
        $customerRepo->transferCustomers($id, $admin['id']);

        // 4. Delete the user
        return $this->repo->deleteUser($id);
    }
}

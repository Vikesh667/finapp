<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table            = 'users';
    protected $primaryKey       = 'id';
    protected $returnType       = 'array';

    protected $allowedFields = [
        'name',
        'email',
        'phone',
        'address',
        'country',
        'state',
        'city',
        'password',
        'role',
        'profile_image',
        'force_logout'
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';   // ✅ Soft delete support (optional)

    protected $skipValidation = false;

    protected $validationRules = [
        'name'     => 'required|alpha_space|min_length[3]|max_length[255]',
        'email'    => 'required|valid_email|is_unique[users.email,id,{id}]',
        'phone'    => 'permit_empty|numeric|min_length[10]|max_length[15]',
        'address'  => 'permit_empty|max_length[500]',
        'country'  => 'permit_empty|max_length[100]',
        'state'    => 'permit_empty|max_length[100]',
        'city'     => 'permit_empty|max_length[100]',
        'password' => 'permit_empty|min_length[8]|max_length[255]|regex_match[/^(?=.*[0-9])(?=.*[a-z])(?=.*[A-Z]).*$/]'
    ];

    protected $validationMessages = [
        'name' => [
            'required'     => 'Name is required.',
            'alpha_space'  => 'Name can only contain letters and spaces.',
            'min_length'   => 'Name must be at least 3 characters.',
        ],
        'email' => [
            'required'     => 'Email is required.',
            'valid_email'  => 'Enter a valid email.',
            'is_unique'    => 'This email is already registered.'
        ],
        'phone' => [
            'numeric'      => 'Phone number must be numeric.',
            'min_length'   => 'Phone must be at least 10 digits.',
            'max_length'   => 'Phone cannot exceed 15 digits.'
        ],
        'password' => [
            'regex_match'  => 'Password must include uppercase, lowercase, and a number.'
        ]
    ];


    public function saveUser(array $data, ?int $userId = null)
    {
        $userData = [
            'name'     => trim($data['name'] ?? ''),
            'email'    => strtolower(trim($data['email'] ?? '')),
            'phone'    => trim($data['phone'] ?? ''),
            'address'  => trim($data['address'] ?? ''),
            'state'    => trim($data['state'] ?? ''),
            'country'  => trim($data['country'] ?? ''),
            'city'     => trim($data['city'] ?? ''),
            'role'     => $data['role'] ?? 'user',
        ];

        if (!empty($data['password'])) {
            $userData['password'] = password_hash($data['password'], PASSWORD_BCRYPT);
        }

        // ✅ Update or Insert
        return $userId
            ? $this->update($userId, $userData)
            : $this->insert($userData);
    }


    public function searchUsers(?string $keyword)
    {
        $builder = $this;

        if (!empty($keyword)) {
            $builder = $builder
                ->groupStart()
                    ->like('name', $keyword)
                    ->orLike('email', $keyword)
                    ->orLike('phone', $keyword)
                ->groupEnd();
        }

        return $builder;
    }
}

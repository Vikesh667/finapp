<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table            = 'users';
    protected $primaryKey       = 'id';
    protected $returnType       = 'array';

    protected $allowedFields = [
        'name', 'email', 'phone', 'address', 'country', 'state', 'city',
        'password', 'role', 'profile_image', 'force_logout'
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    protected $useSoftDeletes = true;

    protected $skipValidation = false;

  protected $validationRules = [
    'name'     => 'required|alpha_space|min_length[3]|max_length[255]',
    'email'    => 'required|valid_email',
    'phone'    => 'permit_empty|numeric|min_length[10]|max_length[15]',
    'address'  => 'permit_empty|string|max_length[500]',
    'country'  => 'permit_empty|string|max_length[100]',
    'state'    => 'permit_empty|string|max_length[100]',
    'city'     => 'permit_empty|string|max_length[100]',
    'password' => 'permit_empty|min_length[8]|max_length[255]|regex_match[/^(?=.*[0-9])(?=.*[a-z])(?=.*[A-Z]).*$/]',
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
            'is_unique'    => 'This email is already registered.',
        ],
    ];

    public function searchUsers(?string $keyword)
    {
        if (empty($keyword)) {
            return $this;
        }

        return $this->groupStart()
            ->like('name', $keyword)
            ->orLike('email', $keyword)
            ->orLike('phone', $keyword)
            ->groupEnd();
    }
}

<?php

namespace App\Models;

use CodeIgniter\Model;

class CustomerModel extends Model
{
    protected $table = 'customers';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'user_id',
        'client_id',
        'created_by',
        'name',
        'email',
        'phone',
        'shop_name',
        'device_type',
        'city',
        'state',
        'country',
        'address'
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    protected $validationRules = [
        'name'     => 'required|alpha_space|min_length[3]|max_length[255]',
        'email'    => 'required|valid_email|is_unique[customers.email,id,{id}]',
        'phone'    => 'permit_empty|numeric|min_length[10]|max_length[15]',
    ];

    protected $validationMessages = [
        'name' => [
            'required' => 'The name field is required.',
            'alpha_space' => 'The name can only contain letters and spaces.',
        ],
        'email' => [
            'required' => 'Email is required.',
            'valid_email' => 'Please provide a valid email address.',
        ],
        'phone' => [
            'numeric' => 'Phone number can only contain digits.',
            'min_length' => 'Phone number must be at least 10 digits.',
            'max_length' => 'Phone number cannot exceed 15 digits.',
        ],
    ];

    protected $skipValidation = false;

    // 🔍 Filter customer list by role, user, and client
    public function getFilterdCustomer($role, $userId, $filterClientId = null)
    {
        $builder = $this->select('
        customers.*,
        clients.company_name AS client_name,
        users.name AS created_by_name
    ')
            ->join('clients', 'clients.id = customers.client_id', 'left')
            ->join('users', 'users.id = customers.created_by', 'left')
            ->orderBy('customers.created_at', 'DESC')
            ->distinct();

        //  Admin sees all customers
        if ($role === 'admin') {
            if ($filterClientId) {
                $builder->where('customers.client_id', $filterClientId);
            }
            return $builder;
        }

        //  User sees only customers assigned or created by them
        if ($role === 'user') {
            $builder->where('customers.user_id', $userId);
        }

        //  Optional filter by client
        if ($filterClientId) {
            $builder->where('customers.client_id', $filterClientId);
        }

        return $builder;
    }


    //  Create or Update Customer
    public function saveCustomer(array $data, $userId, $clientId, $loggedUserId, $customerId = null)
    {
        // Safety validation (avoid null inserts)
        if (empty($userId)) {
            throw new \Exception("user_id cannot be empty");
        }

        if (empty($clientId)) {
            throw new \Exception("client_id cannot be empty");
        }

        $customerData = [
            'user_id'     => (int) $userId,
            'client_id'   => (int) $clientId,
            'created_by'  => (int) $loggedUserId,

            'name'        => trim($data['name']),
            'phone'       => trim($data['phone']),
            'shop_name'   => trim($data['shop_name']),
            'email'       => strtolower(trim($data['email'])),
            'device_type' => trim($data['device_type']),
            'city'        => trim($data['city']),
            'state'       => trim($data['state']),
            'country'     => trim($data['country']),
            'address'     => trim($data['address']),
        ];

        // update OR insert
        return $customerId
            ? $this->update($customerId, $customerData)
            : $this->insert($customerData);
    }
}

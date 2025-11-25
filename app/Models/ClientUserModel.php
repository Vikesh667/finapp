<?php

namespace App\Models;

use CodeIgniter\Model;

class ClientUserModel extends Model
{
    protected $table = 'client_users';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'client_id',
        'user_id',
        'assigned_by',
        'created_at',
        'updated_at'
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // ✅ Check if user assigned
    public function isUserAssigned($clientId, $userId)
    {
        return $this->where('client_id', $clientId)
                    ->where('user_id', $userId)
                    ->countAllResults() > 0;
    }
}

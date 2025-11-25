<?php
namespace App\Models;

use CodeIgniter\Model;

class CustomerReassignHistoryModel extends Model
{
    protected $table = 'customer_reassign_history';   // ✔ correct table name
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'customer_id',
        'client_id',
        'old_user_id',
        'new_user_id',
        'reassigned_by',
        'created_at'
    ];

    protected $useTimestamps = false; // we will manually insert created_at

    /**
     * 🧾 Get all reassignment history logs
     */
    public function getAllHistory()
    {
        return $this->select("
                customer_reassign_history.*, 
                old_user.name AS old_user_name,
                new_user.name AS new_user_name,
                reassigned_by_user.name AS reassigned_by_name,
                customers.name AS customer_name,
                clients.company_name AS client_name
            ")
            ->join('users AS old_user', 'old_user.id = customer_reassign_history.old_user_id', 'left')
            ->join('users AS new_user', 'new_user.id = customer_reassign_history.new_user_id', 'left')
            ->join('users AS reassigned_by_user', 'reassigned_by_user.id = customer_reassign_history.reassigned_by', 'left')
            ->join('customers', 'customers.id = customer_reassign_history.customer_id', 'left')
            ->join('clients', 'clients.id = customer_reassign_history.client_id', 'left')
            ->orderBy('customer_reassign_history.created_at', 'DESC')
            ->findAll();
    }

    /**
     * 🧾 Get history for a specific customer
     */
    public function getHistoryByCustomer($customerId)
    {
        return $this->select("
                customer_reassign_history.*, 
                old_user.name AS old_user_name,
                new_user.name AS new_user_name,
                reassigned_by_user.name AS reassigned_by_name
            ")
            ->join('users AS old_user', 'old_user.id = customer_reassign_history.old_user_id', 'left')
            ->join('users AS new_user', 'new_user.id = customer_reassign_history.new_user_id', 'left')
            ->join('users AS reassigned_by_user', 'reassigned_by_user.id = customer_reassign_history.reassigned_by', 'left')
            ->where('customer_reassign_history.customer_id', $customerId)
            ->orderBy('customer_reassign_history.created_at', 'DESC')
            ->findAll();
    }
}

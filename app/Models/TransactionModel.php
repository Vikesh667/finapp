<?php

namespace App\Models;

use CodeIgniter\Model;

class TransactionModel extends Model
{
    protected $table = 'transactions';
    protected $primaryKey = 'id';
    protected $allowedFields = ['user_id', 'client_id', 'customer_id', 'company_id', 'code', 'rate', 'extra_code', 'igst', 'cgst', 'sgst', 'total_amount', 'grand_total', 'paid_amount', 'remaining_amount', 'total_code', 'created_By', 'recipt_no', 'gst_number', 'gst_applied', 'hsn_code', 'remark'];
    protected $useTimestamps = true;

    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';


    protected $validationRules = [
        'code' => 'required|decimal|greater_than_equal_to[1]',
        'total_amount' => 'required|decimal|greater_than_equal_to[1]',
        'paid_amount' => 'required|decimal|greater_than_equal_to[0]',
        'remaining_amount' => 'required|decimal|greater_than_equal_to[0]',
        'total_code' => 'required|decimal|greater_than_equal_to[0]',
    ];



    public function getTransaction($role, $userId, $filters = [])
{
    $builder = $this->select('transactions.*, customers.name as transfor_by')
        ->join('customers', 'customers.id = transactions.customer_id', 'left')
        ->orderBy('transactions.created_at', 'DESC');

    // 1️⃣ Role filter
    if ($role === 'user') {
        $builder->where('transactions.user_id', $userId);
    }

    // 🔍 2️⃣ Keyword / Search filter (MISSING BEFORE)
    if (!empty($filters['keyword'])) {
        $builder->groupStart();
            $builder->like('customers.name', $filters['keyword']);
            $builder->orLike('transactions.remark', $filters['keyword']);
            $builder->orLike('transactions.code', $filters['keyword']);
            $builder->orLike('transactions.total_amount', $filters['keyword']);
            $builder->orLike('transactions.paid_amount', $filters['keyword']);
            $builder->orLike('transactions.remaining_amount', $filters['keyword']);
        $builder->groupEnd();
    }

    // 3️⃣ Client filter
    if (!empty($filters['client_id'])) {
        $builder->where('transactions.client_id', $filters['client_id']);
    }

    // 4️⃣ Customer filter
    if (!empty($filters['customer_id'])) {
        $builder->where('transactions.customer_id', $filters['customer_id']);
    }

    // 5️⃣ Status filter
    if (!empty($filters['status'])) {
        if ($filters['status'] === 'paid') {
            $builder->where('transactions.remaining_amount', 0);
        } elseif ($filters['status'] === 'pending') {
            $builder->where('transactions.remaining_amount >', 0);
        }
    }

    // 6️⃣ Date filter
    if (!empty($filters['date_filter'])) {
        switch ($filters['date_filter']) {
            case 'today':
                $builder->where('DATE(transactions.created_at)', date('Y-m-d'));
                break;

            case 'yesterday':
                $builder->where('DATE(transactions.created_at)', date('Y-m-d', strtotime('-1 day')));
                break;

            case 'this_week':
                $builder->where('YEARWEEK(transactions.created_at, 1)', date('oW'));
                break;

            case 'last_week':
                $builder->where('YEARWEEK(transactions.created_at)', date('YW', strtotime('-1 week')));
                break;

            case 'this_month':
                $builder->where('MONTH(transactions.created_at)', date('m'))
                        ->where('YEAR(transactions.created_at)', date('Y'));
                break;

            case 'last_month':
                $builder->where('MONTH(transactions.created_at)', date('m', strtotime('-1 month')))
                        ->where('YEAR(transactions.created_at)', date('Y', strtotime('-1 month')));
                break;

            case 'custom':
                if (!empty($filters['from_date'])) {
                    $builder->where('DATE(transactions.created_at) >=', $filters['from_date']);
                }
                if (!empty($filters['to_date'])) {
                    $builder->where('DATE(transactions.created_at) <=', $filters['to_date']);
                }
                break;
        }
    }

    return $builder;
}



    public function getTransactionDetails($customerId)
    {
        return $this->where('customer_id', $customerId)->orderBy('created_at', 'DESC')->findAll();
    }

    public function getMonthlyRevenu()
    {
        return $this->select("DATE_FORMAT(created_at,'%Y-%m') as month ,sum(total_amount) as total")
            ->groupBy("DATE_FORMAT(created_at,'%Y-%m')")
            ->orderBy("month", "ASC")
            ->findAll();
    }
}

<?php

namespace App\Models;

use CodeIgniter\Model;

class TransactionModel extends Model {
    protected $table='transactions';
    protected $primaryKey='id';
    protected $allowedFields=['user_id','client_id','customer_id','code','rate','extra_code','total_amount','paid_amount','remaining_amount','total_code','created_By','recipt_no','gst_number','gst_applied','remark'];
    protected $useTimestamps=true;
    
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

    // Role based filter
    if ($role === 'user') {
        $builder->where('transactions.user_id', $userId);
    }

  
    if (!empty($filters['client_id'])) {
        $builder->where('transactions.client_id', $filters['client_id']);
    }

    if (!empty($filters['customer_id'])) {
        $builder->where('transactions.customer_id', $filters['customer_id']);
    }

    if (!empty($filters['status'])) {
        if ($filters['status'] == 'paid') {
            $builder->where('transactions.remaining_amount', 0);
        } elseif ($filters['status'] == 'pending') {
            $builder->where('transactions.remaining_amount >', 0);
        }
    }

    if (!empty($filters['min_amount'])) {
        $builder->where('transactions.total_amount >=', $filters['min_amount']);
    }

    if (!empty($filters['max_amount'])) {
        $builder->where('transactions.total_amount <=', $filters['max_amount']);
    }

  
    if (!empty($filters['from_date'])) {
        $builder->where('DATE(transactions.created_at) >=', $filters['from_date']);
    }

    if (!empty($filters['to_date'])) {
        $builder->where('DATE(transactions.created_at) <=', $filters['to_date']);
    }


    if (!empty($filters['license_key'])) {
        $builder->like('transactions.code', $filters['license_key']);
    }


    if (!empty($filters['keyword'])) {
        $builder->groupStart()
            ->like('customers.name', $filters['keyword'])
            ->orLike('transactions.total_amount', $filters['keyword'])
            ->orLike('transactions.paid_amount', $filters['keyword'])
            ->orLike('transactions.code', $filters['keyword'])
            ->groupEnd();
    }

    return $builder;
}

    public function getTransactionDetails($customerId){
       return $this->where('customer_id',$customerId)->orderBy('created_at','DESC')->findAll();
                      
    }

    public function getMonthlyRevenu(){
      return $this->select("DATE_FORMAT(created_at,'%Y-%m') as month ,sum(total_amount) as total")
                   ->groupBy("DATE_FORMAT(created_at,'%Y-%m')")
                   ->orderBy("month","ASC")
                   ->findAll();
    }
    
}

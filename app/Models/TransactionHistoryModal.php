<?php 
namespace App\Models;
use CodeIgniter\Model;

class TransactionHistoryModal extends Model{
    protected $table='transaction_history';
    protected $primaryKey='id';
    protected $allowedFields=['user_id','client_id','customer_id','transaction_id','amount','before_paid_amount','after_paid_amount','payment_method','created_at','remark'];
    protected $useTimestamps=true;

    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';


  public function getTransactionHistory($role,$userId,$keywords=null)
    {
        $builder=$this->select('transaction_history.*,customers.name as customer_name')
                      ->join('customers','customers.id=transaction_history.customer_id','left')
                      ->orderBy('created_at','DESC');
      if($role==='user'){
        $builder->where('transaction_history.user_id',$userId);
      }
      return $builder;
    }
}
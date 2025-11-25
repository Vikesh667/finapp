<?php 
namespace App\Models;
use CodeIgniter\Model;

class ClientAssignHistoryModel extends Model{
    protected $table='client_assign_history';
    protected $primaryKey = 'id';
    protected $allowedFields = ['client_id','user_id','action','admin_id','created_at'];
    protected $useTimestamps = false;


   public function getFullHistory()
{
    return $this->select("
                client_assign_history.*,
                user_table.name  AS user_name,
                admin_table.name AS admin_name
            ")
            ->join('users AS user_table', 'user_table.id = client_assign_history.user_id', 'left')
            ->join('users AS admin_table', 'admin_table.id = client_assign_history.admin_id', 'left')
            ->orderBy('client_assign_history.id', 'DESC')
            ->findAll();
}

}

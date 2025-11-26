<?php
namespace App\Models;
use CodeIgniter\Model;

class BanksModel extends Model{
    protected $table='banks_details';
    protected $primaryKey = 'id';
    protected $allowedFields = ['bank_name','bank_holder_name','account_no','ifsc_code'];
    protected $useTimestamps = false;
}
<?php
namespace App\Models;
use CodeIgniter\Model;
class HSNCodeModel extends Model{
    protected $table='hsn_code';
    protected $primaryKey = 'id';
    protected $allowedFields = ['code','created_at'];
    protected $useTimestamps = false;
}
<?php 
namespace App\Models;

use CodeIgniter\Model;

class LoginHistoryModel extends Model
{
    protected $table = 'login_history';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'user_id',
        'login_time',
        'ip_address',
        'user_agent',
        'platform',
        'location',
        'logout_time',
        'status'
    ];
}

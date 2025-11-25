<?php

namespace App\Models;
use CodeIgniter\Model;

class CompanyInfoModel extends Model
{
    protected $table = 'company_info';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'company_name',
        'address',
        'city',
        'state',
        'country',
        'gst_number',
        'logo',
        
    ];
}

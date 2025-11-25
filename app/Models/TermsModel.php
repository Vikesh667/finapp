<?php

namespace App\Models;

use CodeIgniter\Model;

class TermsModel extends Model
{
    protected $table = 'terms_conditions';
    protected $primaryKey = 'id';
    protected $allowedFields = ['content', 'updated_at'];
}

<?php

namespace App\Models;

use CodeIgniter\Model;

class CommanModel extends Model
{
    public function getcounter()
    {
        $transactionModel = new TransactionModel();
        return  $transactionModel->selectSum('total_amount')->get()->getRow()->total_amount ?? 0;
    }
    public function revenueChart()
    {
        $transactionModel = new TransactionModel();
        return $transactionModel->getMonthlyRevenu();
      
    }
}

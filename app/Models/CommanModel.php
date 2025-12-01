<?php

namespace App\Models;

use CodeIgniter\Model;

class CommanModel extends Model
{
    public function getcounter()
    {
        $transactionModel = new TransactionModel();
        $role = session()->get('role');
        $userId = session()->get('user_id');
        $totalAmount = ($role === 'admin') ? $transactionModel->selectSum('total_amount')->get()->getRow()->total_amount ?? 0
            : $transactionModel->selectSum('total_amount')->where('user_id', $userId)->get()->getRow()->total_amount ?? 0;

        return   $totalAmount;
    }
    public function revenueChart()
    {
        $transactionModel = new TransactionModel();
        return $transactionModel->getMonthlyRevenu();
    }
}

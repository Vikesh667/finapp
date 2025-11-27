<?php

namespace App\Controllers;

use App\Models\ClientModel;
use App\Models\CustomerModel;
use App\Models\TransactionModel;
use App\Models\UserModel;

class Home extends BaseController
{
    public function index(): string
    {
        $user = session()->get();
        $userModel = new UserModel();
        $clientModel = new ClientModel();
        $customerModel = new CustomerModel();
        $transactionModel = new TransactionModel();
        $totalUser = $userModel->countAllResults();
        $totalClient = $clientModel->countAllResults();
        $transactions = $transactionModel->limit(5)->find();
        $totalCustomer = $customerModel->countAllResults();
        $totalTransactions = $transactionModel->countAllResults();

        $totalAmount = $transactionModel->selectSum('total_amount')->get()->getRow()->total_amount ?? 0;

        $totalPaid = $transactionModel->selectSum('paid_amount')->get()->getRow()->paid_amount ?? 0;

        $totalRemaining = $transactionModel->selectSum('remaining_amount')->get()->getRow()->remaining_amount ?? 0;


        $averageTransaction = $transactionModel->selectAvg('total_amount')->get()->getRow()->total_amount ?? 0;

        $averageTransaction = number_format((float)$averageTransaction, 2);

        $highestTransaction = $transactionModel->selectMax('total_amount')->get()->getRow()->total_amount ?? 0;
        $totalCode =  $transactionModel->selectSum('code')->get()->getRow()->code ?? 0;
        $extraCode =  $transactionModel->selectSum('extra_code')->get()->getRow()->extra_code ?? 0;
        $recentTransaction = $transactionModel
            ->select('recipt_no')
            ->orderBy('created_at', 'DESC')
            ->get(1)
            ->getRow();

        $recentTransactionCode = $recentTransaction->recipt_no ?? 'No Transactions Yet';
        // This Month Revenue
        $thisMonthRevenue = $transactionModel
            ->selectSum('total_amount')
            ->where('MONTH(created_at)', date('m'))
            ->where('YEAR(created_at)', date('Y'))
            ->get()->getRow()->total_amount ?? 0;

        // Today Revenue
        $todayRevenue = $transactionModel
            ->selectSum('total_amount')
            ->where('DATE(created_at)', date('Y-m-d'))
            ->get()->getRow()->total_amount ?? 0;

        // Monthly Revenue Chart Data
        $monthly = $transactionModel->select(
            "DATE_FORMAT(created_at, '%Y-%m') AS month, SUM(total_amount) AS total"
        )
            ->groupBy("DATE_FORMAT(created_at, '%Y-%m')")
            ->orderBy("month", "ASC")
            ->findAll();
        // This Month
        $thisMonth = $thisMonthRevenue;

        // Last Month
        $lastMonth = $transactionModel
            ->selectSum('total_amount')
            ->where('MONTH(created_at)', date('m', strtotime('-1 month')))
            ->where('YEAR(created_at)', date('Y', strtotime('-1 month')))
            ->get()->getRow()->total_amount ?? 0;

        // Month % Change
        $monthChange = ($lastMonth > 0)
            ? (($thisMonth - $lastMonth) / $lastMonth) * 100
            : 100;


        // Today
        $today = $todayRevenue;

        // Yesterday
        $yesterday = $transactionModel
            ->selectSum('total_amount')
            ->where('DATE(created_at)', date('Y-m-d', strtotime('-1 day')))
            ->get()->getRow()->total_amount ?? 0;

        // Day % Change
        $dayChange = ($yesterday > 0)
            ? (($today - $yesterday) / $yesterday) * 100
            : 100;



            $totals = $transactionModel
    ->select("
        SUM(CASE WHEN gst_applied = 1 THEN grand_total ELSE 0 END) AS amount_with_gst,
        SUM(CASE WHEN gst_applied = 0 THEN total_amount ELSE 0 END) AS amount_without_gst,
        SUM(
            CASE
                WHEN gst_applied = 1 THEN grand_total
                ELSE total_amount
            END
        ) AS overall_amount
    ", false)
    ->first();
        return view('index', [
            'totalTransactions'   => $totalTransactions,
            'totalAmount'         => $totalAmount,
            'totalPaid'           => $totalPaid,
            'totalRemaining'      => $totalRemaining,
            'averageTransaction'  => $averageTransaction,
            'highestTransaction'  => $highestTransaction,
            'lastTransactionCode' => $recentTransactionCode,
            'totalUser'           => $totalUser,
            'totalClient'         => $totalClient,
            'totalCustomer'       => $totalCustomer,
            'totalCode'           => $totalCode,
            'extraCode'           => $extraCode,
            'transactions'        => $transactions,
            'thisMonthRevenue'    => $thisMonthRevenue,
            'todayRevenue'        => $todayRevenue,
            'monthly'             => $monthly,
            'thisMonth'   => $thisMonth,
            'lastMonth'   => $lastMonth,
            'monthChange' => $monthChange,

            'today'       => $today,
            'yesterday'   => $yesterday,
            'dayChange'   => $dayChange,
            'totals'      => $totals

        ]);
    }

    public function unauthorized()
    {
        return view('unauthorized');
    }
}

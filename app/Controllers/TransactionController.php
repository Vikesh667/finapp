<?php

namespace App\Controllers;

use App\Models\CompanyInfoModel;
use App\Models\CustomerModel;
use App\Models\HSNCodeModel;
use App\Models\TransactionHistoryModal;
use App\Models\TransactionModel;
use App\Models\UserModel;

class TransactionController extends BaseController
{
    public function transaction_app(): string
    {
        return view('app-transactions');
    }
    public function transaction_detail(): string
    {
        return view('app-transaction-detail');
    }
    public function transaction_verify(): string
    {
        return view('app-transaction-verification');
    }

    public function transaction_list()
    {
        $transactionModel = new TransactionModel();
        $userModel = new UserModel();
        $filters = [
            'keyword'      => $this->request->getGet('search'),
            'client_id'    => $this->request->getGet('client_id'),
            'customer_id'  => $this->request->getGet('customer_id'),
            'status'       => $this->request->getGet('status'),
            'date_filter'  => $this->request->getGet('date_filter'), // ⭐ Add this
            'from_date'    => $this->request->getGet('from_date'),
            'to_date'      => $this->request->getGet('to_date'),
        ];


        $userRole = session()->get('role');
        $userId   = session()->get('user_id');

        $builder = $transactionModel->getTransaction($userRole, $userId, $filters);

        $data['transactions'] = $builder->paginate(30, 'transactions');
        $data['pager'] = $builder->pager;

        // For dropdown in filters
        $data['clients'] = model('ClientModel')->findAll();
        $userId = ($userRole === 'admin')  ? "created_by" : session()->get('user_id');
        $data['customers'] = model('CustomerModel')->where('user_id', $userId)->findAll();

        // For form to remember filters
        $data['filters'] = $filters;
        $data['users'] = $userModel->findAll();

        return view('transaction/transaction-list', $data);
    }
    public function transaction_list_json()
    {
        $transactionModel = new TransactionModel();

        $page = $this->request->getGet('page') ?? 1;
        $limit = 30;

        $filters = [
            'keyword'      => $this->request->getGet('keyword'), // ← IMPORTANT
            'client_id'    => $this->request->getGet('client_id'),
            'customer_id'  => $this->request->getGet('customer_id'),
            'status'       => $this->request->getGet('status'),
            'date_filter'  => $this->request->getGet('date_filter'),
            'from_date'    => $this->request->getGet('from_date'),
            'to_date'      => $this->request->getGet('to_date'),
        ];

        $userRole = session()->get('role');
        $userId   = session()->get('user_id');

        $builder = $transactionModel->getTransaction($userRole, $userId, $filters);

        $transactions = $builder->paginate($limit, 'transactions');
        $pager = $builder->pager;

        return $this->response->setJSON([
            'transactions' => $transactions ?? [],
            'current_page' => $pager->getCurrentPage('transactions') ?? 1,
            'per_page'     => $pager->getPerPage('transactions') ?? $limit,
            'total_pages'  => $pager->getPageCount('transactions') ?? 1,
        ]);
    }



    public function create_transaction()
    {
        $transactionModel = new TransactionModel();
        $historyModel     = new \App\Models\TransactionHistoryModal();
        $companyInfoModel = new CompanyInfoModel();
        $customerModel    = new CustomerModel();
        $hsnCodeModel     = new HSNCodeModel();

        $clientId   = (int)$this->request->getPost('client_id');
        $customerId = (int)$this->request->getPost('customer_id');
        $userId     = (int)$this->request->getPost('user_id');
        $paidAmount = (float)$this->request->getPost('paid_amount');
        $baseAmount = (float)$this->request->getPost('total_amount');
        $companyId  = (int)$this->request->getPost('company_id');
        $loggedIn   = session()->get('user_id');
        $hsnId =   $this->request->getPost('hsn_code');
        $hsnCode = $hsnCodeModel
            ->select('hsn_code')
            ->where('id', $hsnId)
            ->get()
            ->getRow('hsn_code');

        $receiptNo  = strtoupper(substr(str_shuffle("0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 10));

        if (!$clientId || !$customerId) {
            return redirect()->back()->with('error', 'Please select a client and customer.');
        }

        // Fetch company and customer
        $company  = $companyInfoModel->find($companyId);
        $customer = $customerModel->find($customerId);

        if (!$company || !$customer) {
            return redirect()->back()->with('error', 'Company or customer not found.');
        }

        // Customer GST number from DB
        $customerGstNumber = $customer['gst_number'] ?? null;

        // Form GST selection: igst / cgst_sgst / none
        $gstType = $this->request->getPost('gst_applied');

        // Apply GST only if customer has GST number AND user selected GST
        if (!empty($customerGstNumber) && $gstType !== 'none') {
            $gstApplied = 1;                // GST enabled
            $gstNumber  = $customerGstNumber;
        } else {
            $gstApplied = 0;                // GST disabled
            $gstType    = 'none';
            $gstNumber  = null;
        }

        // Get state code for IGST / CGST+SGST decision
        $db = \Config\Database::connect();
        $sellerStateCode = $customerStateCode = null;

        if (!empty($company['state'])) {
            $row = $db->query("SELECT state_code FROM states WHERE name = ?", [$company['state']])->getRow();
            $sellerStateCode = $row->state_code ?? null;
        }

        if (!empty($customer['state'])) {
            $row = $db->query("SELECT state_code FROM states WHERE name = ?", [$customer['state']])->getRow();
            $customerStateCode = $row->state_code ?? null;
        }

        // GST Calculation
        $igst = $cgst = $sgst = 0;
        if ($gstApplied) {
            if ($gstType === "igst" || ($sellerStateCode !== $customerStateCode)) {
                $igst = round($baseAmount * 0.18, 2);
            } elseif ($gstType === "cgst_sgst") {
                $cgst = round($baseAmount * 0.09, 2);
                $sgst = round($baseAmount * 0.09, 2);
            }
        }

        // Final amount
        $grandTotal = $baseAmount + $igst + $cgst + $sgst;

        // Save transaction
        $data = [
            'user_id'          => $userId,
            'client_id'        => $clientId,
            'customer_id'      => $customerId,
            'code'             => $this->request->getPost('code'),
            'rate'             => $this->request->getPost('rate'),
            'extra_code'       => $this->request->getPost('extra_code'),
            'total_amount'     => $baseAmount,
            'igst'             => $igst,
            'cgst'             => $cgst,
            'sgst'             => $sgst,
            'grand_total'      => $grandTotal,
            'paid_amount'      => $paidAmount,
            'remaining_amount' => $grandTotal - $paidAmount,
            'total_code'       => $this->request->getPost('total_code'),
            'gst_applied'      => $gstApplied,
            'gst_type'         => $gstType,
            'gst_number'       => $gstNumber,
            'created_by'       => $loggedIn,
            'recipt_no'        => $receiptNo,
            'company_id'       => $companyId,
            'hsn_code'         => $hsnCode,
            'remark'           => $this->request->getPost('remark')
        ];

        if (!$this->validate($transactionModel->getValidationRules())) {
            return redirect()->back()->with('error', $this->validator->getErrors());
        }

        $transactionId = $transactionModel->insert($data);

        // Insert payment history
        if ($paidAmount > 0) {
            $historyModel->insert([
                'user_id'            => $userId,
                'client_id'          => $clientId,
                'customer_id'        => $customerId,
                'transaction_id'     => $transactionId,
                'amount'             => $paidAmount,
                'before_paid_amount' => 0,
                'after_paid_amount'  => $paidAmount,
                'payment_method'     => $this->request->getPost('payment_method'),
                'created_at'         => date("Y-m-d H:i:s"),
                'remark'             => $this->request->getPost('remark')
            ]);
        }
        $session = session();
        if ($session->get('role') === 'user') {
            $adminId = 1; // assuming admin has user ID 1
            $message = "{$session->get('user_name')} created a new transaction for '{$customer['name']}'";
            push_notification($adminId, $message, "New Transaction Created");
        }
        $redirectPath = session()->get('role') === 'admin'
            ? 'admin/transaction-list'
            : 'user/transaction-list';

        return redirect()->to(base_url($redirectPath))
            ->with('success', 'Transaction saved successfully. Invoice is ready.');
    }



    public function getTransaction($id)
    {
        $transactionModel = new TransactionModel();
        $historyModel = new TransactionHistoryModal();

        // Fetch main transaction + customer name
        $transaction = $transactionModel
            ->select('transactions.*, customers.name AS customer_name')
            ->join('customers', 'customers.id = transactions.customer_id', 'left')
            ->where('transactions.id', $id)
            ->first();

        if (!$transaction) {
            return $this->response->setJSON(['error' => 'Transaction not found']);
        }

        // Fetch related transaction history
        $history = $historyModel
            ->where('transaction_id', $id)
            ->findAll();

        // Combine both in one response
        $data = [
            'transaction' => $transaction,
            'history' => $history
        ];

        return $this->response->setJSON($data);
    }


    public function payNow()
    {
        $transactionId = $this->request->getPost('transaction_id');
        $payAmount     = (float) $this->request->getPost('pay_amount');

        $transactionModel = new TransactionModel();
        $historyModel     = new TransactionHistoryModal();

        $transaction = $transactionModel->find($transactionId);

        if (!$transaction) {
            return redirect()->back()->with('error', 'Transaction not found.');
        }

        if ($payAmount <= 0) {
            return redirect()->back()->with('error', 'Invalid payment amount.');
        }

        if ($payAmount > $transaction['remaining_amount']) {
            return redirect()->back()->with('error', 'Pay amount cannot exceed remaining amount.');
        }

        // NEW TOTALS
        $newPaid      = $transaction['paid_amount'] + $payAmount;
        $newRemaining = $transaction['remaining_amount'] - $payAmount;

        // UPDATE TRANSACTION
        $transactionModel->update($transactionId, [
            'paid_amount'      => $newPaid,
            'remaining_amount' => $newRemaining,
            'updated_at'       => date("Y-m-d H:i:s")
        ]);

        // ADD PAYMENT HISTORY
        $historyModel->insert([
            'transaction_id'     => $transactionId,
            'user_id'            => $transaction['user_id'],
            'client_id'          => $transaction['client_id'],
            'customer_id'        => $transaction['customer_id'],
            'amount'             => $payAmount,
            'before_paid_amount' => $transaction['paid_amount'],
            'after_paid_amount'  => $newPaid,
            'payment_method'     => $this->request->getPost('payment_method'),
            'created_at'         => date("Y-m-d H:i:s"),
            'remark'             => $this->request->getPost('remark')
        ]);

        $session = session();
        if ($session->get('role') === 'user') {
            $adminId = 1; // assuming admin has user ID 1
            $customerModel = new CustomerModel();
            $customer = $customerModel->find($transaction['customer_id']);
            $message = "{$session->get('user_name')} made a payment of {$payAmount} for '{$customer['name']}'";
            push_notification($adminId, $message, "Payment Received");
        }

        $redirectPath = $session->get('role') === 'admin'
            ? 'admin/transaction-list'
            : 'user/transaction-list';

        return redirect()->to(base_url($redirectPath))
            ->with('success', 'Payment received successfully.');
    }




    public function delete_transaction($id = null)
    {
        if (!$id) {
            return redirect()->back()->with('error', 'Invalid transaction ID.');
        }

        $transactionModel = new TransactionModel();
        $transaction = $transactionModel->find($id);

        if (!$transaction) {
            return redirect()->back()->with('error', 'Transaction not found.');
        }

        $transactionModel->delete($id);

        $redirectPath = (session()->get('role') === 'admin') ? 'admin/transaction-list' : 'user/transaction-list';
        return redirect()->to(base_url($redirectPath))->with('success', 'Transaction deleted successfully.');
    }



    public function get_transaction_details($customerId)
    {
        $transactionModel = new TransactionModel();
        $transactionDetails = $transactionModel->getTransactionDetails($customerId);
        return view('app-transaction-detail', ['singleTransaction' => $transactionDetails]);
    }



    public function get_payment_history()
    {
        $transactionHistoryModel = new TransactionHistoryModal();
        $userRole = session()->get('role');
        $userId = session()->get('user_id');
        $builder = $transactionHistoryModel->getTransactionHistory($userRole, $userId);
        $data['transactions'] = $builder->paginate(10, 'transactions');
        $data['pager'] = $builder->pager;

        return view('transaction/transaction-history', $data);
    }
}

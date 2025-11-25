<?php

namespace App\Controllers;

use App\Models\TransactionHistoryModal;
use App\Models\TransactionModel;

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

        $filters = [
            'keyword'     => $this->request->getGet('search'),
            'client_id'   => $this->request->getGet('client_id'),
            'customer_id' => $this->request->getGet('customer_id'),
            'status'      => $this->request->getGet('status'),
            'min_amount'  => $this->request->getGet('min_amount'),
            'max_amount'  => $this->request->getGet('max_amount'),
            'from_date'   => $this->request->getGet('from_date'),
            'to_date'     => $this->request->getGet('to_date'),
            'license_key' => $this->request->getGet('license_key'),
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

        return view('transaction/transaction-list', $data);
    }



    public function create_transaction()
    {
        $transactionModel = new TransactionModel();
        $historyModel     = new \App\Models\TransactionHistoryModal();

        $clientId   = (int)$this->request->getPost('client_id');
        $customerId = (int)$this->request->getPost('customer_id');
        $userId     = (int)$this->request->getPost('user_id');
        $paidAmount = (float)$this->request->getPost('paid_amount');
        $receiptNo  = strtoupper(substr(str_shuffle("0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 10));
        $loggedIn   = session()->get('user_id');

        if (!$clientId || !$customerId) {
            return redirect()->back()->with('error', 'Please select a client and customer.');
        }

        $totalAmount  = (float) $this->request->getPost('total_amount');

        // Store GST flag but don't calculate
        $gstApplied = (int)$this->request->getPost('gst_applied');
        $gstNumber  = ($gstApplied) ? $this->request->getPost('gst_number') : null;

        $data = [
            'user_id'          => $userId,
            'client_id'        => $clientId,
            'customer_id'      => $customerId,
            'code'             => $this->request->getPost('code'),
            'rate'             => $this->request->getPost('rate'),
            'extra_code'       => $this->request->getPost('extra_code'),
            'total_amount'     => $totalAmount, // NO GST added here
            'paid_amount'      => $paidAmount,
            'remaining_amount' => $totalAmount - $paidAmount,
            'total_code'       => $this->request->getPost('total_code'),
            'gst_applied'      => $gstApplied,
            'gst_number'       => $gstNumber,
            'created_by'       => $loggedIn,
            'recipt_no'        => $receiptNo,
             'company_id'      => $this->request->getPath('company_id'),  
            'remark'           => $this->request->getPost('remark')
        ];

        if (!$this->validate($transactionModel->getValidationRules())) {
            return redirect()->back()->with('error', $this->validator->getErrors());
        }

        $transactionId = $transactionModel->insert($data);

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

        $redirectPath = session()->get('role') === 'admin'
            ? 'admin/transaction-list'
            : 'user/transaction-list';

        return redirect()->to(base_url($redirectPath))
            ->with('success', 'Transaction saved. Invoice ready for GST generation.');
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
        $gstEnabled    = (int) $this->request->getPost('gst_enabled');

        $transactionModel = new TransactionModel();
        $invoiceModel     = new \App\Models\InvoiceModel();
        $historyModel     = new TransactionHistoryModal();

        $transaction = $transactionModel->find($transactionId);

        if (!$transaction) {
            return redirect()->back()->with('error', 'Transaction not found.');
        }

        if ($payAmount > $transaction['remaining_amount']) {
            return redirect()->back()->with('error', 'Pay amount exceeds remaining amount.');
        }

        // GST Calculation for the payment being made now
        $gstAmount  = $gstEnabled ? round($payAmount * 0.18, 2) : 0;
        $grandTotal = $payAmount + $gstAmount;

        // Update transaction totals
        $newPaid      = $transaction['paid_amount'] + $payAmount;
        $newRemaining = $transaction['remaining_amount'] - $payAmount;

        $transactionModel->update($transactionId, [
            'paid_amount'      => $newPaid,
            'remaining_amount' => $newRemaining,
            'updated_at'       => date("Y-m-d H:i:s")
        ]);


        //--------------------------------------------
        //  CREATE NEW INVOICE FOR THIS PAYMENT
        //--------------------------------------------

        $invoiceModel->insert([
            'transaction_id' => $transactionId,
            'invoice_no'     => 'INV-' . str_pad($transactionId . '-' . time(), 6, '0', STR_PAD_LEFT),
            'client_id'      => $transaction['client_id'],
            'customer_id'    => $transaction['customer_id'],
            'amount'         => $payAmount,
            'gst_amount'     => $gstAmount,
            'grand_total'    => $grandTotal,
            'gst_enabled'    => $gstEnabled,
            'invoice_type'   => ($newRemaining == 0) ? 'Final Invoice' : 'Payment Invoice',
            'status'         => ($newRemaining == 0) ? 'Paid' : 'Partial',
            'created_at'     => date("Y-m-d H:i:s"),
        ]);

        //--------------------------------------------


        // Add payment history
        $historyModel->insert([
            'transaction_id' => $transactionId,
            'user_id'        => $transaction['user_id'],
            'client_id'      => $transaction['client_id'],
            'customer_id'    => $transaction['customer_id'],
            'amount'         => $payAmount,
            'before_paid_amount' => $transaction['paid_amount'],
            'after_paid_amount' => $newPaid,
            'payment_method' => $this->request->getPost('payment_method'),
            'created_at'     => date("Y-m-d H:i:s"),
            'remark'         => $this->request->getPost('remark')
        ]);

        $redirectPath = (session()->get('role') === 'admin') ? 'admin/transaction-list' : 'user/transaction-list';

        return redirect()->to(base_url($redirectPath))->with('success', 'Payment received and invoice generated.');
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

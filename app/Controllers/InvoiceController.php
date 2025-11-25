<?php

namespace App\Controllers;

use App\Models\InvoiceModel;
use App\Models\TransactionModel;
use App\Models\ClientModel;
use App\Models\CustomerModel;

class InvoiceController extends BaseController
{

    /** STEP 1: Preview page showing GST selection + invoice information */
    public function preview($transactionId)
    {
        $tModel       = new \App\Models\TransactionModel();
        $clientModel  = new \App\Models\ClientModel();
        $customerModel = new \App\Models\CustomerModel();
        $compnayInfoModel = new \App\Models\CompanyInfoModel();

        $transaction = $tModel->find($transactionId);

        if (!$transaction) {
            return redirect()->back()->with('error', 'Transaction not found.');
        }

        $db = \Config\Database::connect();

        // ===== Company =====
        $company = $compnayInfoModel->find(2);
        $stateId = $company['state'];
        $query = $db->query('SELECT state_code FROM states WHERE name = ?', [$company['state']]);

        $result = $query->getRow();
        $sellerStateCode = $result ? $result->state_code : null;

        // ===== Customer =====
        $customer = $customerModel->find($transaction['customer_id']);
         
        $customerStateCode = null;
        if ($customer && isset($customer['state'])) {
            $custStateId = $customer['state'];
           $query2 = $db->query('SELECT state_code FROM states WHERE name = ?', [$custStateId]);
            $result2 = $query2->getRow();
            $customerStateCode = $result2 ? $result2->state_code : null;
        }

        // GST Calculation Logic
        $gstApplied  = (int)$transaction['gst_applied'];
        $baseAmount  = (float)$transaction['total_amount'];

        $igst = $cgst = $sgst = 0;

        if ($gstApplied) {
            if ($sellerStateCode != $customerStateCode) {
                $igst = round($baseAmount * 0.18, 2);
            } else {
                $cgst = round($baseAmount * 0.09, 2);
                $sgst = round($baseAmount * 0.09, 2);
            }
        }

        $grandTotal = $baseAmount + $igst + $cgst + $sgst;

        $invoice = [
            'invoice_no'       => $transaction['recipt_no'],
            'date'             => $transaction['created_at'],
            'client'           => $clientModel->find($transaction['client_id']),
            'customer'         => $customer,
            'company'          => $company,
            'base_amount'      => $baseAmount,
            'paid_amount'      => $transaction['paid_amount'],
            'remaining_amount' => $transaction['remaining_amount'],
            'total_code'       => $transaction['total_code'],
            'rate'             => $transaction['rate'],
            'code'             => $transaction['code'],

            'gst_applied'      => $gstApplied,
            'gst_number'       => $transaction['gst_number'],
            'igst'             => $igst,
            'cgst'             => $cgst,
            'sgst'             => $sgst,
            'grand_total'      => $grandTotal,
            'seller_state_code' => $sellerStateCode,
            'customer_state_code' => $customerStateCode
        ];

        return view('transaction/invoice', compact('invoice'));
    }



    /** STEP 2: Save invoice & update transaction after user selects GST */
    public function saveInvoice($transactionId)
    {
        $transactionModel = new TransactionModel();
        $invoiceModel     = new InvoiceModel();

        $transaction = $transactionModel->find($transactionId);

        if (!$transaction) {
            return redirect()->back()->with('error', 'Transaction not found.');
        }

        // Get user selection
        $gstApplied = (int)$this->request->getPost('gst_applied');
        $gstNumber  = ($gstApplied ? $this->request->getPost('gst_number') : null);

        // Calculations
        $baseAmount  = (float)$transaction['total_amount'];
        $gstPercent  = 18;
        $gstAmount   = ($gstApplied === 1 ? ($baseAmount * $gstPercent / 100) : 0);
        $grandTotal  = $baseAmount + $gstAmount;

        // Update transaction table with GST data
        $transactionModel->update($transactionId, [
            'gst_applied' => $gstApplied,
            'gst_number'  => $gstNumber
        ]);

        // Insert invoice
        $invoiceId = $invoiceModel->insert([
            'transaction_id'   => $transactionId,
            'invoice_no'       => $this->generateInvoiceNumber(),
            'client_id'        => $transaction['client_id'],
            'customer_id'      => $transaction['customer_id'],
            'amount'           => $baseAmount,
            'gst_enabled'      => $gstApplied,
            'gst_percentage'   => ($gstApplied ? $gstPercent : 0),
            'gst_amount'       => $gstAmount,
            'grand_total'      => $grandTotal,
            'paid_amount'      => $transaction['paid_amount'],
            'remaining_amount' => $grandTotal - $transaction['paid_amount'],
            'gst_number'       => $gstNumber,
            'status'           => ($grandTotal <= $transaction['paid_amount']) ? 'Paid' : 'Pending',
            'invoice_type'     => "Final Invoice",
            'created_at'       => date("Y-m-d H:i:s"),
        ]);

        return redirect()->to(base_url("invoice/view/$invoiceId"))
            ->with('success', 'Invoice generated successfully.');
    }



    /** STEP 3: Display final invoice */
    public function view($invoiceId)
    {
        $invoiceModel = new InvoiceModel();
        $invoice = $invoiceModel->getInvoiceDetails($invoiceId);

        return view('transaction/invoice_view', compact('invoice'));
    }



    /** Generate unique invoice number */
    private function generateInvoiceNumber()
    {
        return "INV-" . date("Y") . "-" . rand(10000, 99999);
    }
}

<?php

namespace App\Controllers;

use App\Models\BanksModel;
use App\Models\InvoiceModel;
use App\Models\TransactionModel;
use App\Models\ClientModel;
use App\Models\CompanyInfoModel;
use App\Models\CustomerModel;
use App\Models\HSNCodeModel;
use App\Models\TermsModel;

class InvoiceController extends BaseController
{

    /** STEP 1: Preview page showing GST selection + invoice information */
    public function preview($transactionId)
    {
        helper('number');
        $tModel           = new TransactionModel();
        $clientModel      = new ClientModel();
        $customerModel    = new CustomerModel();
        $compnayInfoModel = new CompanyInfoModel();
        $termsModel       = new TermsModel();
        $bankModel        = new BanksModel();
        $hsnCodeModel     = new HSNCodeModel();
        $invoiceModel     = new InvoiceModel();
        $db               = \Config\Database::connect();

        $transaction = $tModel->find($transactionId);

        if (!$transaction) {
            return redirect()->back()->with('error', 'Transaction not found.');
        }

        // Linked data
        $company  = $compnayInfoModel->find($transaction['company_id']);
        $customer = $customerModel->find($transaction['customer_id']);
        $client   = $clientModel->find($transaction['client_id']);
        $banks    = $bankModel->first();
        $terms    = $termsModel->first();

        // Seller State + Code
        $sellerState      = $company['state'] ?? null;
        $sellerStateCode  = null;
        if (!empty($sellerState)) {
            $row = $db->query("SELECT state_code FROM states WHERE name = ?", [$sellerState])->getRow();
            $sellerStateCode = $row->state_code ?? null;
        }

        // Buyer State + Code
        $buyerState      = $customer['state'] ?? null;
        $buyerStateCode  = null;
        if (!empty($buyerState)) {
            $row2 = $db->query("SELECT state_code FROM states WHERE name = ?", [$buyerState])->getRow();
            $buyerStateCode = $row2->state_code ?? null;
        }

        // HSN Code
        $hsnData = null;
        if (!empty($transaction['hsn_code'])) {
            $hsnData = $hsnCodeModel->where('hsn_code', trim($transaction['hsn_code']))->first();
        }

        // Already saved GST values
        $baseAmount  = (float)$transaction['total_amount'];
        $igst        = (float)$transaction['igst'];
        $cgst        = (float)$transaction['cgst'];
        $sgst        = (float)$transaction['sgst'];
        $grandTotal  = (float)$transaction['grand_total'];
        $gstType     = $transaction['gst_type'] ?? 'none';
        $amountWord = numberToWordsIndian($grandTotal);

        // Invoice array → passed to view
        $invoice = [
            'invoice_no'        => $transaction['recipt_no'],
            'date'              => $transaction['created_at'],
            'client'            => $client,
            'customer'          => $customer,
            'company'           => $company,
            'base_amount'       => $baseAmount,
            'paid_amount'       => $transaction['paid_amount'],
            'remaining_amount'  => $transaction['remaining_amount'],
            'total_code'        => $transaction['total_code'],
            'rate'              => $transaction['rate'],
            'code'              => $transaction['code'],
            'remark'            => $transaction['remark'],

            // GST
            'gst_applied'       => $transaction['gst_applied'],
            'gst_type'          => $gstType,
            'gst_number'        => $transaction['gst_number'],
            'igst'              => $igst,
            'cgst'              => $cgst,
            'sgst'              => $sgst,
            'grand_total'       => $grandTotal,

            // States
            'seller_state'      => $sellerState,
            'seller_state_code' => $sellerStateCode,
            'buyer_state'       => $buyerState,
            'customer_state_code'  => $buyerStateCode,

            // HSN
            'hsn_code'          => $hsnData,
            'amount_in_word'    => $amountWord,

            // Bank & Terms
            'banks'             => $banks,
            'terms'             => $terms['content'] ?? "Terms not available."
        ];

        /**
         * Save invoice in invoice table (1 per transaction)
         */
        $invoiceData = [
            'transaction_id'       => $transactionId,
            'invoice_no'           => $transaction['recipt_no'],
            'invoice_date'         => $transaction['created_at'],
            'company_id'           => $transaction['company_id'],
            'customer_id'          => $transaction['customer_id'],
            'client_id'            => $transaction['client_id'],
            'base_amount'          => $baseAmount,
            'paid_amount'          => $transaction['paid_amount'],
            'remaining_amount'     => $transaction['remaining_amount'],
            'total_code'           => $transaction['total_code'],
            'rate'                 => $transaction['rate'],
            'code'                 => $transaction['code'],
            'remark'               => $transaction['remark'],
            'gst_applied'          => $transaction['gst_applied'],
            'gst_type'             => $gstType,
            'gst_number'           => $transaction['gst_number'],
            'igst'                 => $igst,
            'cgst'                 => $cgst,
            'sgst'                 => $sgst,
            'grand_total'          => $grandTotal,
            'bank_id'              => $banks['id'] ?? null,
            'hsn_code'             => $hsnData['hsn_code']      ?? null,
            'hsn_description'      => $hsnData['description']   ?? null,
            'amount_in_word'       => $amountWord,
            'seller_state'      => $sellerState,
            'seller_state_code' => $sellerStateCode,
            'buyer_state'       => $buyerState,
            'customer_state_code'  => $buyerStateCode,
            'terms'                => $terms['content'] ?? "Terms not available."
        ];

        $existing = $invoiceModel->where('transaction_id', $transactionId)->first();
        if ($existing) {
            $invoiceModel->update($existing['id'], $invoiceData);
        } else {
            $invoiceModel->insert($invoiceData);
        }

        return view('transaction/invoice', compact('invoice'));
    }



    public function view($invoiceId)
    {
        $invoiceModel = new InvoiceModel();
        $invoice = $invoiceModel->getInvoiceDetails($invoiceId);

        return view('transaction/invoice_view', compact('invoice'));
    }



}

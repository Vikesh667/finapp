<?php
namespace App\Controllers;

use App\Models\InvoiceModel;

class InvoiceController extends BaseController{
     public function save(){
        $data=json_decode($this->request->getBody(),true);
        $invocieModel=new InvoiceModel();

        $invoiceNumber= strtoupper(substr(str_shuffle("0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 10));
        $invocieModel->insert([
            'transaction_id'=>$data['transaction_id'],
            'customer_id'  =>$data['customer_id'],
            'invoiceNumber'=>$invoiceNumber,
            'base_amount' => $data['base_amount'],
            'gst_percent' => $data['gst_percent'],
            'gst_amount' => $data['gst_amount'],
            'total_amount' => $data['total_amount'],
            'gst_number' => $data['gst_number'],
            'invoice_type' => $data['gst_percent'] > 0 ? 'TAX_INVOICE' : 'RECEIPT'
        ]);
         return $this->response->setJSON(["status" => "success", "invoice_no" => $invoiceNumber]);
     }
     
}
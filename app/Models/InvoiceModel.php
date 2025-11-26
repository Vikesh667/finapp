<?php
namespace App\Models;

use CodeIgniter\Model;

class InvoiceModel extends Model
{
    protected $table      = 'invoices';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'transaction_id',
        'invoice_no',
        'invoice_date',
        'company_id',
        'customer_id',
        'client_id',
        'base_amount',
        'paid_amount',
        'remaining_amount',
        'total_code',
        'rate',
        'code',
        'remark',
        'gst_applied',
        'gst_number',
        'igst',
        'cgst',
        'sgst',
        'grand_total',
        'seller_state_code',
        'customer_state_code',
        'bank_id',
        'hsn_code',
        'hsn_description',
        'amount_in_word',
        'terms'
    ];

    protected $useTimestamps = true;
}

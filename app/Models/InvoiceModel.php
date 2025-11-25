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
        'client_id',
        'customer_id',

        // Amount + GST
        'amount',
        'gst_percentage',
        'gst_amount',
        'grand_total',
        'gst_enabled',
        'gst_number',

        // Payment Details
        'paid_amount',
        'remaining_amount',

        // Invoice Metadata
        'invoice_type',     // Proforma | Payment | Final
        'status',           // Pending | Partial | Paid
        'currency',         // Optional: INR
        'round_off',        // Optional

        'created_at',
        'updated_at'
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    protected $orderBy = 'id DESC';

    public function getInvoiceDetails($invoiceId)
    {
        return $this->select('invoices.*, customers.name AS customer_name, clients.name AS client_name')
            ->join('customers', 'customers.id = invoices.customer_id', 'left')
            ->join('clients', 'clients.id = invoices.client_id', 'left')
            ->where('invoices.id', $invoiceId)
            ->first();
    }
}

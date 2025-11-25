<?php

namespace App\Models;

use CodeIgniter\Model;

class InvoiceModel extends Model
{
    protected $table            = 'invoices';
    protected $primaryKey       = 'id';

    protected $allowedFields = [
        'transaction_id',
        'invoice_no',
        'client_id',
        'customer_id',
        'amount',
        'gst_amount',
        'grand_total',
        'gst_enabled',
        'invoice_type',   // Proforma | Payment Invoice | Final Invoice
        'status',         // Pending | Partial | Paid
        'created_at'
    ];

    protected $useTimestamps = false;  // We manually set created_at
    
    // Optional: Sorting newest invoices first
    protected $orderBy = 'id DESC';

    // Optional helper method to get invoice + client + customer
    public function getInvoiceDetails($invoiceId)
    {
        return $this->select('invoices.*, customers.name AS customer_name, clients.name AS client_name')
            ->join('customers', 'customers.id = invoices.customer_id', 'left')
            ->join('clients', 'clients.id = invoices.client_id', 'left')
            ->where('invoices.id', $invoiceId)
            ->first();
    }
}

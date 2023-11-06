<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RecurringInvoice extends Model
{
    use HasFactory;

    protected $fillable = [
        "customer_number",
        "invoice_date",
        "invoice_title",
        "invoice_start_text",
        "invoice_positions",
        "invoice_payment_condition",
        "invoice_afterword",
        "invoice_total",
        "invoice_subtotal",
        "invoice_discount",
        "invoice_shipping",
        "invoice_schedule",
        "invoice_tax",
        "invoice_status",
        "invoice_active",
        'invoice_due_date_days'
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_number', 'customer_number');
    }
}

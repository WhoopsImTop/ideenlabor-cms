<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Offer extends Model
{
    use HasFactory;

    public function getRouteKeyName()
    {
        return 'invoice_number';
    }

    protected $fillable = [
        "customer_number",
        "invoice_number",
        "invoice_date",
        "invoice_delivery_date",
        "invoice_delivery_start_date",
        "invoice_delivery_end_date",
        "invoice_due_date",
        "invoice_title",
        "invoice_start_text",
        "invoice_positions",
        "invoice_payment_condition",
        "invoice_afterword",
        "invoice_path",
        "invoice_total",
        "invoice_subtotal",
        "invoice_discount",
        "invoice_shipping",
        "invoice_tax",
        "invoice_status"
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_number', 'customer_number');
    }
}

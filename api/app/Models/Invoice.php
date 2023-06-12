<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;

    protected $fillable = [
        "customer_id",
        "customer_number",
        "invoice_number",
        "invoice_date",
        "invoice_delivery_date",
        "invoice_due_date",
        "invoice_title",
        "invoice_start_text",
        "invoice_positions",
        "invoice_payment_condition",
        "invoice_afterword",
        "invoice_path",
        "invoice_total"
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
}

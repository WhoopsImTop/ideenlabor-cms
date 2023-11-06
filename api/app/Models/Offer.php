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

    protected static function boot()
    {
        parent::boot();

        // Generate invoice number before saving the model
        static::creating(function ($invoice) {
            $invoice->invoice_number = static::generateInvoiceNumber();
        });
    }

    /**
     * Generate a unique invoice number.
     *
     * @return string
     */
    protected static function generateInvoiceNumber()
    {
        // Generate a Invoice Number in Format RE-YY-0000
        $invoiceNumber = 'AG-' . date('y') . '-';
        $latestInvoice = static::whereRaw("invoice_number = (select max(`invoice_number`) from offers)")->first();

        if ($latestInvoice) {
            $latestInvoiceNumber = substr($latestInvoice->invoice_number, 6);
            $invoiceNumber .= str_pad($latestInvoiceNumber + 1, 3, 0, STR_PAD_LEFT);
        } else {
            $invoiceNumber .= '001';
        }

        return $invoiceNumber;
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_number', 'customer_number');
    }
}

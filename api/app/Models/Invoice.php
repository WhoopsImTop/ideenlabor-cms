<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Firma;

class Invoice extends Model
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
     * Generate invoice PDF
     * 
     */
    public function generateInvoicePDF()
    {
        $companyData = Firma::find(1);

        //create pdf
        $pdf = Pdf::loadView('index', [
            'invoice' => $this,
            'companyData' => $companyData
        ]);
        //save the pdf to storage invoice/year/month/customer_name/invoice_number.pdf
        //create directory if not exists
        $public_folder_path = 'invoice/' . date('Y') . '/' . date('m') . '/';
        if (!file_exists($public_folder_path)) {
            mkdir($public_folder_path, 0777, true);
        }
        $pdf->save('invoice/' . date('Y') . '/' . date('m') . '/' . $this->invoice_number . '.pdf');

        //add filepath to invoice
        $this->invoice_path = $public_folder_path . $this->invoice_number . '.pdf';

        //save invoice
        $this->save();
    }
    public function autoGenerateInvoicePDF()
    {
        $companyData = Firma::find(1);

        //create pdf
        $pdf = Pdf::loadView('index', [
            'invoice' => $this,
            'companyData' => $companyData
        ]);
        //save the pdf to storage invoice/year/month/customer_name/invoice_number.pdf
        //create directory if not exists
        $public_folder_path = 'public/invoice/' . date('Y') . '/' . date('m') . '/';
        if (!file_exists($public_folder_path)) {
            mkdir($public_folder_path, 0777, true);
        }
        $pdf->save('public/invoice/' . date('Y') . '/' . date('m') . '/' . $this->invoice_number . '.pdf');

        //add filepath to invoice
        $this->invoice_path = $public_folder_path . $this->invoice_number . '.pdf';

        //save invoice
        $this->save();
    }

    /**
     * Generate a unique invoice number.
     *
     * @return string
     */
    protected static function generateInvoiceNumber()
    {
        // Generate a Invoice Number in Format RE-Year-0000
        $invoiceNumber = 'RE-' . date('y') . '-';
        $latestInvoice = static::whereRaw("invoice_number = (select max(`invoice_number`) from invoices)")->first();

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

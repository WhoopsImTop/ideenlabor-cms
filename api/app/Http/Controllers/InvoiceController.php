<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Invoice;
use App\Models\Customer;
use Barryvdh\DomPDF\Facade\Pdf;

class InvoiceController extends Controller
{
    public function index()
    {
        return Invoice::all();
    }

    public function store(Request $request)
    {
        $invoice_number = 'RE' . '-' . substr(date('Y'), 2) . '-' . str_pad(Invoice::whereYear('created_at', date('Y'))->count() + 1, 3, '0', STR_PAD_LEFT);
        $request->merge(['invoice_number' => $invoice_number]);

        //convert invoice_positions to json_string
        $invoice_positions = json_encode($request->invoice_positions);
        $request->merge(['invoice_positions' => $invoice_positions]);

        //create invoice
        $invoice = Invoice::create($request->all());

        //create pdf
        $pdf = Pdf::loadView('index', compact('invoice'));
        //save the pdf to storage invoice/year/month/customer_name/invoice_number.pdf
        $customer = Customer::where('customer_number', $invoice->customer_number)->first();
        //create directory if not exists
        $public_folder_path = 'invoice/' . date('Y') . '/' . date('m') . '/' . $customer->customer_name;
        if (!file_exists($public_folder_path)) {
            mkdir($public_folder_path, 0777, true);
        }
        $pdf->save('invoice/' . date('Y') . '/' . date('m') . '/' . $customer->customer_name . '/' . $invoice->invoice_number . '.pdf');

        //add filepath to invoice
        $invoice->update(['invoice_path' => 'invoice/' . date('Y') . '/' . date('m') . '/' . $customer->customer_name . '/' . $invoice->invoice_number . '.pdf']);

        return response()->json($invoice, 201);
    }
}

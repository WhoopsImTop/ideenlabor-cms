<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Invoice;
use App\Models\Customer;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Http\Resources\InvoiceResource;
use App\Models\Firma;

class InvoiceController extends Controller
{
    public function index()
    {
        return InvoiceResource::collection(Invoice::all());
    }

    public function store(Request $request)
    {
        //convert invoice_positions to json_string
        $invoice_positions = json_encode($request->invoice_positions);
        $request->merge(['invoice_positions' => $invoice_positions]);

        //create invoice
        $invoice = Invoice::create($request->all());
        $customer = Customer::where('customer_number', $invoice->customer_number)->first();

        //relate invoice to customer
        $customer->invoices()->save($invoice);

        //relate customer to invoice
        $invoice->customer()->associate($customer)->save();


        $invoice->generateInvoicePDF();


        return new InvoiceResource($invoice);
    }

    public function show(Invoice $invoice)
    {
        //find invoice by invoice_number
        $invoice = Invoice::where('invoice_number', $invoice->invoice_number)->first();

        return new InvoiceResource($invoice);
    }

    public function update(Request $request, Invoice $invoice)
    {
        //get update_status search query params
        $update_status = $request->query('update_status');
        //convert invoice_positions to json_string
        try {
            if ($update_status == 1) {
                $invoice->update($request->all());

                return new InvoiceResource($invoice);
            } else {
                try {
                    $invoice_positions = json_encode($request->invoice_positions);
                    $request->merge(['invoice_positions' => $invoice_positions]);
                } catch (\Exception $e) {
                    return response()->json(['message' => $e->getMessage()], 500);
                }

                $invoice->update($request->all());

                $invoice->generateInvoicePDF();

                return new InvoiceResource($invoice);
            }
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }
    public function destroy(Invoice $invoice)
    {
        //delete pdf from storage
        if (file_exists($invoice->invoice_path)) {
            unlink($invoice->invoice_path);
        }
        $invoice->delete();

        return response()->json(null, 204);
    }
}

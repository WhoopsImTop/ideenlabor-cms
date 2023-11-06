<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RecurringInvoice;
use App\Models\Customer;
use App\Http\Resources\RecurringInvoiceResource;

class RecurringInvoiceController extends Controller
{
    public function index()
    {
        return RecurringInvoiceResource::collection(RecurringInvoice::all());
    }

    public function store(Request $request)
    {
        //convert invoice_positions to json_string
        $invoice_positions = json_encode($request->invoice_positions);
        $request->merge(['invoice_positions' => $invoice_positions]);

        //create RecurringInvoice
        $recurringInvoice = RecurringInvoice::create($request->all());

        $customer = Customer::where('customer_number', $recurringInvoice->customer_number)->first();

        //relate RecurringInvoice to customer
        $customer->invoices()->save($recurringInvoice);

        //relate customer to RecurringInvoice
        $recurringInvoice->customer()->associate($customer)->save();

        return new RecurringInvoiceResource($recurringInvoice);
    }

    public function show(RecurringInvoice $recurringInvoice)
    {
        //find RecurringInvoice by invoice_number
        $recurringInvoice = RecurringInvoice::where('id', $recurringInvoice->id)->first();

        return new RecurringInvoiceResource($recurringInvoice);
    }

    public function update(Request $request, RecurringInvoice $recurringInvoice)
    {
        //convert invoice_positions to json_string
        $invoice_positions = json_encode($request->invoice_positions);
        $request->merge(['invoice_positions' => $invoice_positions]);


        $recurringInvoice->update($request->all());

        return new RecurringInvoiceResource($recurringInvoice);
    }

    public function destroy(RecurringInvoice $recurringInvoice)
    {
        $recurringInvoice->delete();

        return response()->json(null, 204);
    }
}

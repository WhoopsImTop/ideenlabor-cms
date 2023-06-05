<?php

namespace App\Http\Controllers;

use App\Models\invoice;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreinvoiceRequest;
use App\Http\Requests\UpdateinvoiceRequest;
use App\Http\Resources\invoiceResource;

class InvoiceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return invoiceResource::collection(invoice::all());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreinvoiceRequest $request)
    {
        //create invoice
        $invoice = invoice::create($request->validated());

        //return invoice
        return new invoice($invoice);
    }

    /**
     * Display the specified resource.
     */
    public function show(invoice $invoice)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateinvoiceRequest $request, invoice $invoice)
    {
        //update invoice
        $invoice->update($request->validated());

        //return invoice
        return new invoice($invoice);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(invoice $invoice)
    {
        //delete invoice
        $invoice->delete();

        //return invoice
        return new invoice($invoice);
    }
}

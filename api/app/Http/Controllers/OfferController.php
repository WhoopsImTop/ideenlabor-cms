<?php

namespace App\Http\Controllers;

use App\Models\Offer;
use Illuminate\Http\Request;
use App\Models\Customer;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Http\Resources\OfferResource;
use App\Models\Firma;

class OfferController extends Controller
{
    public function index()
    {
        return OfferResource::collection(Offer::all());
    }

    public function store(Request $request)
    {
        $invoice_number = 'AG' . '-' . substr(date('Y'), 2) . '-' . str_pad(Offer::whereYear('created_at', date('Y'))->count() + 1, 3, '0', STR_PAD_LEFT);
        $request->merge(['invoice_number' => $invoice_number]);

        //convert invoice_positions to json_string
        $invoice_positions = json_encode($request->invoice_positions);
        $request->merge(['invoice_positions' => $invoice_positions]);

        //create invoice
        $offer = Offer::create($request->all());

        $companyData = Firma::find(1);

        //create pdf
        $pdf = Pdf::loadView('offer', compact('offer', 'companyData'));

        //save the pdf to storage invoice/year/month/customer_name/invoice_number.pdf
        $customer = Customer::where('customer_number', $offer->customer_number)->first();
        //create directory if not exists
        $public_folder_path = 'offers/' . date('Y') . '/' . date('m') . '/';
        if (!file_exists($public_folder_path)) {
            mkdir($public_folder_path, 0777, true);
        }
        $pdf->save('offers/' . date('Y') . '/' . date('m') . '/' . $offer->invoice_number . '.pdf');

        //add filepath to invoice
        $offer->update(['invoice_path' => 'offers/' . date('Y') . '/' . date('m') . '/' . $offer->invoice_number . '.pdf']);
        
        $offer->customer()->associate($customer)->save();

        return new OfferResource($offer);
    }

    public function show(Offer $offer)
    {
        //find invoice by invoice_number
        $offer = Offer::where('invoice_number', $offer->invoice_number)->first();

        return new OfferResource($offer);
    }

    public function update(Request $request, Offer $offer)
    {

        //convert invoice_positions to json_string
        $invoice_positions = json_encode($request->invoice_positions);
        $request->merge(['invoice_positions' => $invoice_positions]);


        $offer->update($request->all());

        $companyData = Firma::find(1);

        //update pdf
        $pdf = Pdf::loadView('offer', compact('offer', 'companyData'));
        //create directory if not exists
        $public_folder_path = 'offers/' . date('Y') . '/' . date('m') . '/';

        if (!file_exists($public_folder_path)) {
            mkdir($public_folder_path, 0777, true);
        }

        $pdf->save('offers/' . date('Y') . '/' . date('m') . '/' . $offer->invoice_number . '.pdf');

        //update filepath to invoice
        $offer->update(['invoice_path' => 'offers/' . date('Y') . '/' . date('m') . '/' . $offer->invoice_number . '.pdf']);

        return new OfferResource($offer);
    }

    public function destroy(Offer $offer)
    {
        $offer->delete();

        return response()->json(null, 204);
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Invoice;
use App\Models\Offer;
use App\Models\Customer;
use App\Models\Firma;
use App\Mail\InvoiceMail;

class InvoiceMailController extends Controller {
    public function sendInvoiceEmail(Invoice $invoice, Request $request) {
        $invoice = Invoice::where('invoice_number', $invoice->invoice_number)->first();

        //find customer by relation invoice_number
        $customer = Customer::where('customer_number', $invoice->customer_number)->first();

        $firma = Firma::first();

        $options = [
            'email' => $request->email,
            'bcc' => '25745e47-2f6f-4c40-9f6f-169469c270fe@uploadmail.datev.de',
            'name' => $customer->customer_name,
            'company_logo' => $firma->company_image,
            'message' => $request->message,
            'subject' => $request->subject,
        ];
        
        $invoiceMail = new InvoiceMail($invoice, $options);
        $invoiceMail->build();
        $invoiceMail->sendMail();

        //update invoice mail_sent and mail_send_date and set status to 'offen'
        $invoice->invoice_sent = true;
        $invoice->invoice_send_date = now();
        $invoice->invoice_status = 'offen';
        $invoice->save();

        return response()->json([
            'message' => 'Email erfolgreich versendet!',
        ], 200);
    }
    
    public function sendOfferEmail(Offer $offer, Request $request) {
        $offer = Offer::where('invoice_number', $offer->invoice_number)->first();

        //find customer by relation invoice_number
        $customer = Customer::where('customer_number', $offer->customer_number)->first();

        $firma = Firma::first();

        $options = [
            'email' => $request->email,
            'name' => $customer->customer_name,
            'company_logo' => $firma->company_image,
            'message' => $request->message,
            'subject' => $request->subject,
        ];
        
        $invoiceMail = new InvoiceMail($offer, $options);
        $invoiceMail->build();
        $invoiceMail->sendMail();

        //update invoice mail_sent and mail_send_date and set status to 'offen'
        $offer->invoice_sent = true;
        $offer->invoice_send_date = now();
        $offer->invoice_status = 'offen';
        $offer->save();

        return response()->json([
            'message' => 'Email erfolgreich versendet!',
        ], 200);
    }
}
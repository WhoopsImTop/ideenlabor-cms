<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\RecurringInvoice;
use App\Models\Invoice;
use Carbon\Carbon;
use App\Models\Customer;
use App\Models\Firma;
use App\Mail\InvoiceMail;

class GenerateRecurringInvoices extends Command
{
    protected $signature = 'generate-recurring-invoices';

    protected $description = 'Generate recurring invoices';

    public function handle()
    {
        //get all recurringInvoices
        $recurringInvoices = RecurringInvoice::all();

        //check wich schedule is set for recurring invoice monthlyOn or yearly
        foreach ($recurringInvoices as $recurringInvoice) {
            if ($this->isDue($recurringInvoice->invoice_schedule, $recurringInvoice->invoice_date)) {
                //create invoice
                $invoice = $this->createInvoice($recurringInvoice);

                //send invoice email
                $this->sendInvoiceEmail($invoice);

                //update invoice_date and add one month
                if ($recurringInvoice->invoice_schedule == 'monthlyOn') {
                    $recurringInvoice->invoice_date = Carbon::parse($recurringInvoice->invoice_date)->addMonth();
                } else if ($recurringInvoice->invoice_schedule == 'yearly') {
                    $recurringInvoice->invoice_date = Carbon::parse($recurringInvoice->invoice_date)->addYear();
                }
                //format to yyyy-mm-dd
                $recurringInvoice->invoice_date = $recurringInvoice->invoice_date->format('Y-m-d');
                $recurringInvoice->save();
            } else {
                $this->info('No recurring invoices to generate!');
            }
        }

        $this->info('Recurring invoices generated successfully!');
    }

    private function createInvoice($recurringInvoice)
    {
        //create invoice
        $invoice = new Invoice();

        //calculate carbon now + invoice_due_date_days
        $invoice_due_date = Carbon::now()->addDays($recurringInvoice->invoice_due_date_days);
        $invoice_due_date = $invoice_due_date->format('d.m.Y');

        //update payment_condition to invoice_due_date + invoice_due_date_days
        $new_invoice_payment_condition = 'Zahlbar innerhalb von ' . $recurringInvoice->invoice_due_date_days . ' Tagen nach Rechnungsdatum (Zahlungsziel: ' . $invoice_due_date . ')';

        $invoice->customer_number = $recurringInvoice->customer_number;
        $invoice->invoice_date = now();
        $invoice->invoice_delivery_date = now();
        $invoice->invoice_due_date = $invoice_due_date;
        $invoice->invoice_title = $recurringInvoice->invoice_title;
        $invoice->invoice_start_text = $recurringInvoice->invoice_start_text;
        $invoice->invoice_positions = $recurringInvoice->invoice_positions;
        $invoice->invoice_payment_condition = $new_invoice_payment_condition;
        $invoice->invoice_afterword = $recurringInvoice->invoice_afterword;
        $invoice->invoice_total = $recurringInvoice->invoice_total;
        $invoice->invoice_subtotal = $recurringInvoice->invoice_subtotal;
        $invoice->invoice_discount = $recurringInvoice->invoice_discount;
        $invoice->invoice_shipping = $recurringInvoice->invoice_shipping;
        $invoice->invoice_tax = $recurringInvoice->invoice_tax;
        $invoice->invoice_status = 'offen';

        $invoice->save();

        $invoice->autoGenerateInvoicePDF();

        //find customer by relation invoice_number
        $customer = Customer::where('customer_number', $invoice->customer_number)->first();

        //relate invoice to customer
        $customer->invoices()->save($invoice);

        //relate customer to invoice
        $invoice->customer()->associate($customer)->save();

        return $invoice;
    }

    private function sendInvoiceEmail($invoice)
    {
        //find customer by relation invoice_number
        $customer = Customer::where('customer_number', $invoice->customer_number)->first();

        $firma = Firma::first();

        $message = 'Sehr geehrte Damen und Herren,
        Im Anhang finden Sie die Rechnung ' . $invoice->invoice_number . ' vom ' . $invoice->invoice_date . '
        <br>Bei Fragen stehen wir Ihnen gerne zur Verfügung.
        <br><br>
        Mit freundlichen Grüßen
        <br>
        Christian Bucher';

        $subject = 'Rechnung ' . $invoice->invoice_number;

        $options = [
            'email' => $customer->customer_email,
            'bcc' => ['25745e47-2f6f-4c40-9f6f-169469c270fe@uploadmail.datev.de', 'bucher@khri8.com'],
            'name' => $customer->customer_name,
            'company_logo' => $firma->company_image,
            'message' => $message,
            'subject' => $subject,
        ];

        $invoiceMail = new InvoiceMail($invoice, $options);
        $invoiceMail->build();
        $invoiceMail->sendMail();

        //update invoice mail_sent and mail_send_date and set status to 'offen'
        $invoice->invoice_sent = true;
        $invoice->invoice_send_date = now();
        $invoice->invoice_status = 'offen';
        $invoice->save();

        //write log
        $this->info('Rechnung ' . $invoice->invoice_number . ' wurde erfolgreich versendet');
        return true;
    }


    private function isDue($schedule, $invoice_date)
    {
        if ($schedule === 'monthlyOn') {
            //generiere eine Rechnung, wenn die aktuelle Tageszahl mit der Rechnungstageszahl übereinstimmt und der Rechnungsmonat einen Monat zurückliegt
            $this->info('Rechnung wird generiert');
            $currentDate = Carbon::now();
            // Hier wird das aktuelle Datum im Format "YYYY-MM-DD" erstellt
            $currentDateFormatted = $currentDate->format('Y-m-d');

            return $currentDateFormatted === $invoice_date;
        } elseif ($schedule === 'yearly') {
            // Generiere eine Rechnung, wenn das aktuelle Datum mit dem Rechnungsdatum übereinstimmt
            $currentDate = Carbon::now()->format('Y-m-d');

            return $currentDate === $invoice_date;
        }

        return false; // Wenn der Zeitplan nicht übereinstimmt
    }
}

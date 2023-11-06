<?php

/**
 * Create a Class to Mail the Invoice
 */

namespace App\Mail;

use App\Models\Invoice;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use Illuminate\Contracts\Queue\ShouldQueue;

class InvoiceMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    /**
     * @var Invoice
     */
    public $invoice;
    public $options;

    /**
     * Create a new message instance.
     *
     * @param Invoice $invoice
     * @return void
     */
    public function __construct($invoice, $options = [])
    {
        //set invoice
        $this->invoice = $invoice;
        $this->options = $options;
    }

    /**
     * Build the message.
     *
     * @return InvoiceMail
     */
    public function build(): InvoiceMail
    {
        //set from
        $from = 'buchhaltung@ideenlabor-agentur.de';

        //set to
        $to = $this->options['email'];

        //set bcc
        //$bcc = $this->options['bcc'];

        //set replyTo
        $replyTo = 'buchhaltung@ideenlabor-agentur.de';

        //set attach
        $attach = $this->invoice['invoice_path'];

        //set view
        $view = 'invoiceMail';

        //set data
        $data = [
            'subject' => $this->options['subject'],
            'message' => $this->options['message'],
            'pdf_path' => $attach,
        ];

        //set name
        $name = $this->invoice->customer->customer_name;

        //set mail
        $mail = $this->from($from)
            ->to($to, $name)
            ->replyTo($replyTo)
            ->subject($data['subject'])
            ->view($view)
            ->with([
                'messages' => $data['message'],
                'company_logo' => $this->options['company_logo']
            ])
            ->attach($attach);
        
        //if bcc is set attach bcc
        if (isset($this->options['bcc'])) {
            $mail->bcc($this->options['bcc']);
        }
        //return mail
        return $mail;
    }

    /**
     * Send the message.
     *
     * @return void
     */
    public function sendMail(): void
    {
        //send mail
        Mail::send($this);
    }
}

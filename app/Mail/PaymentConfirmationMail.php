<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PaymentConfirmationMail extends Mailable
{
    use Queueable, SerializesModels;

    public $details;
    public $pdfPath;
    public $subjectName;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($details, $pdfPath)
    {
        $this->details = $details;
        $this->pdfPath = $pdfPath;
        $this->subjectName = 'Payment Confirmation Invoice #'.@$details['invoiceNumber'];
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject($this->subjectName)
            ->view('emails.payment-confirmation')
            ->attach($this->pdfPath, [
                'as' => 'invoice.pdf',
                'mime' => 'application/pdf',
            ]);
    }
}

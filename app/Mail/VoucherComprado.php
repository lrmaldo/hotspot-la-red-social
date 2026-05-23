<?php

declare(strict_types=1);

namespace App\Mail;

use App\Models\Voucher;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class VoucherComprado extends Mailable
{
    use Queueable;
    use SerializesModels;

    public function __construct(public Voucher $voucher)
    {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Tu acceso WiFi está listo - ' . $this->voucher->zona->nombre,
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.voucher-comprado',
        );
    }
}

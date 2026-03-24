<?php

namespace App\Mail;

use App\Models\Souscription;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class FicheSouscriptionMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Souscription $souscription,
        public string $pdfContent
    ) {}

    public function build()
    {
        $ref = $this->souscription->ref_souscription ?? (string)$this->souscription->id;
        $filename = "Fiche_Souscription_{$ref}.pdf";

        return $this->subject("Fiche de souscription {$ref}")
            ->view('emails.fiche_souscription', ['souscription' => $this->souscription])
            ->attachData($this->pdfContent, $filename, ['mime' => 'application/pdf']);
    }
}

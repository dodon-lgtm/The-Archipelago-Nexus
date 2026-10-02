<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

/**
 * Mailable untuk form "Hubungi Kami via Email" pada Pusat Bantuan.
 *
 * Email dikirim ke inbox Pusat Bantuan (config mail.help_to) dan Reply-To
 * diarahkan ke alamat email pengirim agar balasan admin langsung sampai
 * ke user yang mengirim pesan.
 */
class HelpContactMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Data pesan kontak.
     *
     * @var array{name: string, email: string, role_label: string, category: string, category_label: string, subject: string, message: string, sent_at: string}
     */
    public array $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function build()
    {
        $category = $this->data['category_label'] ?? 'Masalah Lainnya';

        return $this->subject('[Pusat Bantuan ApexForge Labs][' . $category . '] ' . $this->data['subject'])
            ->replyTo($this->data['email'], $this->data['name'])
            ->view('emails.help-contact');
    }
}
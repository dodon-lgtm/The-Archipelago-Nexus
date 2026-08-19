<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PasswordResetOtpMail extends Mailable
{
    use Queueable, SerializesModels;

    public $otp;
    public $name;
    public $appName;

    /**
     * Create a new message instance.
     */
    public function __construct($otp, $name, $appName)
    {
        $this->otp = $otp;
        $this->name = $name;
        $this->appName = $appName;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->subject('Lupa Password - ' . $this->appName)
            ->view('emails.password-reset-otp');
    }
}
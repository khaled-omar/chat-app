<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OtpVerificationCodeEmail extends Mailable
{
    use Queueable, SerializesModels;

    public string $otpCode;

    protected $address;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(string $otpCode, $address)
    {
        $this->otpCode = $otpCode;
        $this->address = $address;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $locale = app()->getLocale();

        return $this->view('mails.otp', ['name' => $this->address])->subject(__('Welcome to HalEx, Your Gateway to the Global Halal Market!'));
    }
}

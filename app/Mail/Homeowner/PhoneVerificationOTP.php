<?php

namespace App\Mail\Homeowner;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PhoneVerificationOTP extends Mailable
{
    use Queueable, SerializesModels;

    public $otp;
    public $homeownerName;
    public $expiresIn;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($otp, $homeownerName, $expiresIn = 10)
    {
        $this->otp = $otp;
        $this->homeownerName = $homeownerName;
        $this->expiresIn = $expiresIn;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('mails.homeowner.phone-verification-otp')
                    ->subject('Verify Your Phone - ' . env('APP_NAME'))
                    ->from(env('MAIL_FROM_ADDRESS'), env('MAIL_FROM_NAME'));
    }
}

<?php

namespace App\Mail\Homeowner;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PasswordResetOTP extends Mailable
{
    use Queueable, SerializesModels;

    public $otp;
    public $userName;
    public $expiresIn;

    /**
     * Create a new message instance.
     *
     * @param string $otp
     * @param string $userName
     * @param int $expiresIn Minutes until OTP expires
     * @return void
     */
    public function __construct($otp, $userName, $expiresIn = 15)
    {
        $this->otp = $otp;
        $this->userName = $userName;
        $this->expiresIn = $expiresIn;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Password Reset Request')
                    ->view('mails.homeowner.password-reset-otp');
    }
}

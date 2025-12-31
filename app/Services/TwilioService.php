<?php

namespace App\Services;

use Twilio\Rest\Client;

class TwilioService
{
    protected $twilio;
    protected $from;

    public function __construct()
    {
        $sid = env('TWILIO_SID');
        $token = env('TWILIO_AUTH_TOKEN');
        $this->from = env('TWILIO_PHONE_NUMBER');

        if ($sid && $token) {
            // Set CA bundle for SSL verification
            $caBundlePath = storage_path('cacert.pem');
            if (file_exists($caBundlePath)) {
                putenv("SSL_CERT_FILE={$caBundlePath}");
            }
            
            $this->twilio = new Client($sid, $token);
        }
    }

    /**
     * Send SMS via Twilio
     *
     * @param string $to Phone number in E.164 format (e.g., +15551234567)
     * @param string $message Message content
     * @return bool|\Twilio\Rest\Api\V2010\Account\MessageInstance
     */
    public function sendSMS($to, $message)
    {
        if (!$this->twilio) {
            \Log::error('Twilio not configured. Set TWILIO_SID, TWILIO_AUTH_TOKEN, and TWILIO_PHONE_NUMBER in .env');
            return false;
        }

        try {
            // Disable SSL verification only in local development, enable everywhere else
            $guzzle = new \GuzzleHttp\Client([
                'verify' => env('APP_ENV') !== 'local',
                'http_errors' => false,
            ]);
            
            // Make request directly using Guzzle
            $response = $guzzle->post('https://api.twilio.com/2010-04-01/Accounts/' . env('TWILIO_SID') . '/Messages.json', [
                'auth' => [env('TWILIO_SID'), env('TWILIO_AUTH_TOKEN')],
                'form_params' => [
                    'To' => $to,
                    'From' => $this->from,
                    'Body' => $message,
                ]
            ]);

            $result = json_decode($response->getBody()->getContents(), true);
            
            if (isset($result['sid'])) {
                \Log::info('SMS sent successfully', [
                    'to' => $to,
                    'sid' => $result['sid'],
                    'status' => $result['status'] ?? 'unknown'
                ]);
                return (object)$result;
            } else {
                \Log::error('Failed to send SMS via Twilio', [
                    'to' => $to,
                    'error' => $result['message'] ?? 'Unknown error',
                    'response' => $result
                ]);
                return false;
            }
        } catch (\Exception $e) {
            \Log::error('Failed to send SMS via Twilio', [
                'to' => $to,
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }

    /**
     * Send OTP via SMS
     *
     * @param string $to Phone number
     * @param string $otp OTP code
     * @param int $expiresIn Minutes until expiration
     * @return bool
     */
    public function sendOTP($to, $otp, $expiresIn = 10)
    {
        $message = "Your verification code is: {$otp}\n\nThis code will expire in {$expiresIn} minutes.\n\nDo not share this code with anyone.";
        
        return $this->sendSMS($to, $message);
    }
}

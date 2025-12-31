<?php

namespace App\Mail\Company;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Models\BroadcastEmail;

class BroadcastMail extends Mailable {

    use Queueable,
        SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($email_id, $replaceArr) {
        $this->email_id = $email_id;
        $this->replaceArr = $replaceArr;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build() {
        $emailObj = BroadcastEmail::find($this->email_id);
        
        $companyReplaceArr = config('broadcast_email_keywords');
        $toReplaceArr = config('common_email_keywords');
        
        $finalArr = array_merge($companyReplaceArr, $toReplaceArr);
        $findArr = array_values($finalArr);
        if (count($this->replaceArr) > 0) {
            $replaceArr = array_values($this->replaceArr);

            $data['email_header'] = str_ireplace($findArr, $replaceArr, $emailObj->email_header);
            $data['email_content'] = str_ireplace($findArr, $replaceArr, $emailObj->content);
            $data['email_footer'] = str_ireplace($findArr, $replaceArr, $emailObj->email_footer);
        } else {
            $data['email_header'] = $emailObj->email_header;
            $data['email_content'] = $emailObj->content;
            $data['email_footer'] = $emailObj->email_footer;
        }

        if (!is_null($emailObj->from_email_address)) {
            $from = $emailObj->from_email_address;
        } else {
            $from = env('mail_from_address');
        }
        
        $view = $this->view('mails.company.broadcast_email')
                ->subject($emailObj->subject)
                ->from($from, env('SITE_TITLE'))
                ->with($data);

        return $view;
    }

}

<?php

namespace App\Mail\Followup;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Models\FollowUpEmail;

class FollowUpMail extends Mailable {

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
        $emailObj = FollowUpEmail::find($this->email_id);

        $footer_variables = config('common_email_keywords');
        $followup_variables = config('follow_up_email_keywords.content');

        $toReplaceArr = $footer_variables;
        if (isset($followup_variables) && count($followup_variables) > 0) {
            $toReplaceArr = array_merge($followup_variables, $footer_variables);
        }

        $findArr = array_values($toReplaceArr);

        if (count($this->replaceArr) > 0) {
            $replaceArr = array_values($this->replaceArr);

            $subject = str_ireplace($findArr, $replaceArr, $emailObj->subject);
            $data['email_header'] = str_ireplace($findArr, $replaceArr, $emailObj->email_header);
            $data['email_content'] = str_ireplace($findArr, $replaceArr, $emailObj->email_content);
            $data['email_footer'] = str_ireplace($findArr, $replaceArr, $emailObj->email_footer);
        } else {
            $subject = $emailObj->subject;
            $data['email_header'] = $emailObj->email_header;
            $data['email_content'] = $emailObj->email_content;
            $data['email_footer'] = $emailObj->email_footer;
        }

        if (!is_null($emailObj->from_email_address)) {
            $from = $emailObj->from_email_address;
        } else {
            $from = env('mail_from_address');
        }
        
        $file = 'mails.followup.followup_email';
        if ($emailObj->id == '16'){
            $file = 'mails.followup.followup_email_big';
        }
        
        $view = $this->view($file)
                ->subject($subject)
                ->from($from, env('SITE_TITLE'))
                ->with($data);

        return $view;
    }

}

<?php

namespace App\Mail\Company;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Models\NewEmail;

class CompanyMailV1 extends Mailable {

    use Queueable,
        SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($email_id, $replaceArr, $fileAttachment = []) {
        $this->email_id = $email_id;
        $this->replaceArr = $replaceArr;
        $this->fileAttachment = $fileAttachment;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build() {
        $emailObj = NewEmail::find($this->email_id);


        $footer_variables = config('common_email_keywords');
        $followup_variables = config('new_email_keywords.' . $emailObj->title);

        $toReplaceArr = $footer_variables;
        if (isset($followup_variables) && count($followup_variables) > 0) {
            $toReplaceArr = array_merge($followup_variables, $footer_variables);
        }

       // dd($toReplaceArr);

        $findArr = array_values($toReplaceArr);
        if (count($this->replaceArr) > 0) {
            $replaceArrValues = array_values($this->replaceArr);

            $content = $emailObj->content;

            foreach ($toReplaceArr as $key => $search) {
                if (isset($this->replaceArr[$key])) {
                    $replace = $this->replaceArr[$key];
                    $content = str_ireplace($search, $replace, $content);
                }
            }
            

            $subject = str_ireplace($findArr, $replaceArrValues, $emailObj->subject);

            $data['email_header'] = str_ireplace($findArr, $replaceArrValues, $emailObj->email_header);
            $data['email_content'] = str_ireplace($findArr, $replaceArrValues, $content);
            $data['email_footer'] = str_ireplace($findArr, $replaceArrValues, $emailObj->email_footer);
        } else {
            $subject = $emailObj->subject;

            $data['email_header'] = $emailObj->email_header;
            $data['email_content'] = $emailObj->content;
            $data['email_footer'] = $emailObj->email_footer;
        }

        if (!is_null($emailObj->from_email_address)) {
            $from = $emailObj->from_email_address;
        } else {
            $from = env('mail_from_address');
        }


        $view = $this->view('mails.company.company_custom_email')
                ->subject($subject)
                ->from($from, env('SITE_TITLE'))
                ->with($data);

        if (count($this->fileAttachment) > 0) {
            foreach ($this->fileAttachment AS $file_item) {
                $view->attach($file_item);
            }
        }

        return $view;
    }

}

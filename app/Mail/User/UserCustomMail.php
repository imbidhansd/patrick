<?php

namespace App\Mail\User;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

use App\Models\Email;

class UserCustomMail extends Mailable {

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
        $emailObj = Email::find($this->email_id);

        $common_variables = config('email_keywords.common');
        $footer_variables = config('email_keywords.footer');
        $company_variables = config('email_keywords.companies.'.$emailObj->email_type.'.'.$emailObj->title);

        $toReplaceArr = array_merge($common_variables, $footer_variables);
        if (isset($company_variables) && count($company_variables) > 0){
            $toReplaceArr = array_merge($company_variables, $common_variables, $footer_variables);
        }

        $replaceWithArr = $this->replaceArr;
        $replaceWithArr['yours_sincerely'] = 'Yours Sincerely';
        $replaceWithArr['phone_number'] = '+1234567890';
        $replaceWithArr['global_domain'] = env('SITE_TITLE');

        $subject = str_replace($toReplaceArr, $replaceWithArr, $emailObj->subject);
        $data['email_header'] = str_replace($toReplaceArr, $replaceWithArr, $emailObj->email_header);
        $data['email_content'] = str_replace($toReplaceArr, $replaceWithArr, $emailObj->email_content);
        $data['email_footer'] = str_replace($toReplaceArr, $replaceWithArr, $emailObj->email_footer);
        
        $view = $this->view('mails.user.user_custom_email')
                ->subject($subject)
                ->with($data);

        if (count($this->fileAttachment) > 0){
            foreach ($this->fileAttachment AS $file_item){
                $view->attach($file_item);
            }
        }

        return $view;
    }

}

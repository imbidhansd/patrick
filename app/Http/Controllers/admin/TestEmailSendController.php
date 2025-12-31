<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Mail\Company\BroadcastMail;
use App\Mail\Company\CompanyMail;
use App\Mail\Admin\AdminMail;
use App\Mail\Followup\FollowUpMail;
use App\Mail\Followup\NonMemberFollowUpMail;
use App\Mail\Followup\RegisteredMemberFollowUpMail;
use Illuminate\Support\Facades\Mail;
use App\Models\Custom;

class TestEmailSendController extends Controller {

    public function send_test_emails(Request $request) {
        $requestArr = $request->all();
        $web_settings = Custom::getSettings();
        $email_id = $requestArr['email_id'];
        if (isset($web_settings['send_test_emails']) && $web_settings['send_test_emails'] != '') {
            $email_list = explode(",", $web_settings['send_test_emails']);
        }

        if ($requestArr['type'] == 'broadcast_emails') {
            if (isset($email_list) && count($email_list) > 0) {
                foreach ($email_list AS $email_item) {
                    Mail::to(trim($email_item))->send(new BroadcastMail($email_id, []));
                }
            }
        } else if ($requestArr['type'] == 'follow_up_emails') {
            if (isset($email_list) && count($email_list) > 0) {
                foreach ($email_list AS $email_item) {
                    Mail::to(trim($email_item))->send(new FollowUpMail($email_id, []));
                }
            }
        } else if ($requestArr['type'] == 'new_emails') {
            if (isset($email_list) && count($email_list) > 0) {
                foreach ($email_list AS $email_item) {
                    //Mail::to(trim($email_item))->send(new CompanyMail($email_id, []));
                    Mail::to(trim($email_item))->send(new AdminMail($email_id, []));
                }
            }
        } else if ($requestArr['type'] == 'registered_member_emails') {
            if (isset($email_list) && count($email_list) > 0) {
                foreach ($email_list AS $email_item) {
                    Mail::to(trim($email_item))->send(new RegisteredMemberFollowUpMail($email_id, []));
                }
            }
        } else if ($requestArr['type'] == 'non_member_emails') {
            if (isset($email_list) && count($email_list) > 0) {
                foreach ($email_list AS $email_item) {
                    Mail::to(trim($email_item))->send(new NonMemberFollowUpMail($email_id, []));
                }
            }
        }


        return [
            'success' => 1,
            'title' => 'Success',
            'type' => 'success',
            'text' => 'Test Email sent successfully',
        ];
    }

}

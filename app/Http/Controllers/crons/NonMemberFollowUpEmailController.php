<?php

namespace App\Http\Controllers\crons;

use App\Http\Controllers\Controller;
use App\Mail\Followup\NonMemberFollowUpMail;
use Illuminate\Support\Facades\Mail;
use DB;
use App\Models\NonMemberFollowUpEmail;
use App\Models\Custom;

class NonMemberFollowUpEmailController extends Controller {

    public function index() {
        $web_settings = Custom::getSettings();

        $follow_up_emails = NonMemberFollowUpEmail::where(DB::raw('DATE_FORMAT(send_at, "%Y-%m-%d %H:%i")'), now()->format('Y-m-d H:i'))
                ->with(['non_member', 'non_member_email'])
                ->pending()
                ->order()
                ->get();

        if (count($follow_up_emails) > 0) {
            foreach ($follow_up_emails AS $follow_up_email_item) {
                $mail_id = $follow_up_email_item->non_member_email_id;
                $replaceArr = [
                    'company_name' => $follow_up_email_item->non_member->company_name,
                    'phone_number' => ((isset($web_settings['phone_number']) && $web_settings['phone_number'] != '') ? $web_settings['phone_number'] : ''),
                    'global_email' => ((isset($web_settings['global_email']) && $web_settings['global_email'] != '') ? $web_settings['global_email'] : ''),
                    'facebook_link' => ((isset($web_settings['facebook']) && $web_settings['facebook'] != '') ? $web_settings['facebook'] : ''),
                    'twitter_link' => ((isset($web_settings['twitter']) && $web_settings['twitter'] != '') ? $web_settings['twitter'] : ''),
                    'instagram_link' => ((isset($web_settings['instagram']) && $web_settings['instagram'] != '') ? $web_settings['instagram'] : ''),
                    'rumble_link' => ((isset($web_settings['rumble']) && $web_settings['rumble'] != '') ? $web_settings['rumble'] : ''),
                    'youtube_link' => ((isset($web_settings['youtube']) && $web_settings['youtube'] != '') ? $web_settings['youtube'] : ''),
                    'unsubscription_link' => url('get-listed/unsubscribe-page', ['company_id' => $follow_up_email_item->non_member_id]),
                    'request_generate_link' => $follow_up_email_item->non_member->email,
                    'date' => $follow_up_email_item->non_member->created_at->format(env('DATE_FORMAT')),
                    'url' => url('get-listed'),
                    'email_footer' => $follow_up_email_item->non_member->email,
                    'copyright_year' => date('Y'),
                        //'main_service_category' => '',
                ];

                /* Follow up email to non member  */
                Mail::to($follow_up_email_item->non_member->email)->send(new NonMemberFollowUpMail($mail_id, $replaceArr));

                $follow_up_email_item->status = 'sent';
                $follow_up_email_item->save();
            }
        }

        echo "Email sent.";
    }

}

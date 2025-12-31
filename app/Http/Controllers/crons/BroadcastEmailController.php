<?php

namespace App\Http\Controllers\crons;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\BroadcastEmail;
use App\Models\Custom;
use DB;

class BroadcastEmailController extends Controller {

    public function broadcast_mail_send() {
        //dd(now()->format('Y-m-d H:i'));

        $broadcastEmails = BroadcastEmail::where(DB::raw('DATE_FORMAT(send_datetime, "%Y-%m-%d %H:%i")'), now()->format('Y-m-d H:i'))
                ->where([
                    ['mail_sent', 'no'],
                    ['draft_message', 'no']
                ])
                ->get();

        //$broadcastEmails = BroadcastEmail::where('id', '27')->get();
        //dd($broadcastEmails);
        if (count($broadcastEmails) > 0) {
            foreach ($broadcastEmails AS $broadcast_email_item) {
                if (!is_null($broadcast_email_item->email_type)) {
                    if ($broadcast_email_item->email_type == 'non_members') {
                        $return1 = Custom::send_non_member_broadcast_email($broadcast_email_item);
                    } else if ($broadcast_email_item->email_type == 'registered_members') {
                        $return2 = Custom::send_registered_member_broadcast_email($broadcast_email_item);
                    } else if ($broadcast_email_item->email_type == 'official_members') {
                        $return3 = Custom::send_official_member_broadcast_email($broadcast_email_item);
                    }
                } else {
                    $return = Custom::send_lead_broadcast_email($broadcast_email_item);
                }


                $broadcast_email_item->mail_sent = 'yes';
                $broadcast_email_item->save();
            }
        }

        echo 'Email sent.';
    }

}

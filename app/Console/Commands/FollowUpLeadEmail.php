<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Mail\Followup\FollowUpMail;
use Illuminate\Support\Facades\Mail;
use DB;
use App\Models\LeadFollowUpEmail;
use App\Models\Custom;

class FollowUpLeadEmail extends Command {

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'FollowUpLeadEmail:Send';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send Leads Followup email for leads';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct() {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle() {
        $web_settings = Custom::getSettings();

        $follow_up_emails = LeadFollowUpEmail::where(DB::raw('DATE_FORMAT(send_at, "%Y-%m-%d %H:%i")'), now()->format('Y-m-d H:i'))
                ->with(['follow_up_email', 'lead'])
                ->pending()
                ->order()
                ->get();


        if (count($follow_up_emails) > 0) {
            foreach ($follow_up_emails AS $follow_up_email_item) {
                if (!is_null($follow_up_email_item->follow_up_email->subscription_type)) {
                    $subscription_type = $follow_up_email_item->follow_up_email->subscription_type;
                    $lead_detail = $follow_up_email_item->lead;

                    if ($lead_detail->$subscription_type == 'subscribe') {
                        $email_sent = true;
                    } else {
                        $email_sent = false;
                    }
                } else {
                    $email_sent = true;
                }

                if ($email_sent) {
                    Custom::lead_followup_email($follow_up_email_item);
                }

                $follow_up_email_item->status = 'sent';
                $follow_up_email_item->save();
            }
        }
        echo "Email sent.";
    }

}

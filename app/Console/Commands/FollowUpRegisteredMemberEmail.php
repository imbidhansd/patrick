<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Mail\Followup\RegisteredMemberFollowUpMail;
use Illuminate\Support\Facades\Mail;
use DB;
use App\Models\CompanyUser;
use App\Models\RegisteredMemberFollowUpEmail;
use App\Models\Custom;

class FollowUpRegisteredMemberEmail extends Command {

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'FollowUpRegisteredMemberEmail:Send';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send Registered Member Followup emails';

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

        //dd(now()->format('Y-m-d H:i'));
        $follow_up_emails = RegisteredMemberFollowUpEmail::where(DB::raw('DATE_FORMAT(send_at, "%Y-%m-%d %H:%i")'), now()->format('Y-m-d H:i'))
                ->with(['reg_member', 'reg_mem_email'])
                ->pending()
                ->order()
                ->get();
        if (count($follow_up_emails) > 0) {
            foreach ($follow_up_emails AS $follow_up_email_item) {
                $mail_id = $follow_up_email_item->reg_mem_email_id;

                $company_user_qry = CompanyUser::leftJoin('companies', 'company_users.company_id', 'companies.id')
                        ->where([
                            ['company_users.company_id', $follow_up_email_item->company_id],
                            ['company_users.company_user_type', 'company_super_admin'],
                        ])
                        ->whereIn('companies.status', ['Subscribed', 'Unsubscribed']);

                if (!is_null($follow_up_email_item->reg_mem_email->subscription_type)) {
                    $subscription_type = $follow_up_email_item->reg_mem_email->subscription_type;

                    $company_user_qry->where($subscription_type, 'subscribe');
                }
                
                $company_user = $company_user_qry->first();
                if (!is_null($company_user)) {
                    if ($follow_up_email_item->reg_member->membership_level_id == 1) {
                        $url = url('preview-trial');
                    } else if ($follow_up_email_item->reg_member->membership_level_id == 2) {
                        $url = url('full-listing');
                    } else if ($follow_up_email_item->reg_member->membership_level_id == 3) {
                        $url = url('accreditation');
                    }
                    
                    $replaceArr = [
                        'account_type' => $follow_up_email_item->reg_member->membership_level->title,
                        'company_name' => $follow_up_email_item->reg_member->company_name,
                        'user_name' => $company_user->username,
                        'user_email' => $company_user->email,
                        'login_link' => url('login'),
                        'phone_number' => ((isset($web_settings['phone_number']) && $web_settings['phone_number'] != '') ? $web_settings['phone_number'] : ''),
                        'global_email' => ((isset($web_settings['global_email']) && $web_settings['global_email'] != '') ? $web_settings['global_email'] : ''),
                        'facebook_link' => ((isset($web_settings['facebook']) && $web_settings['facebook'] != '') ? $web_settings['facebook'] : ''),
                        'twitter_link' => ((isset($web_settings['twitter']) && $web_settings['twitter'] != '') ? $web_settings['twitter'] : ''),
                        'rumble_link' => ((isset($web_settings['rumble']) && $web_settings['rumble'] != '') ? $web_settings['rumble'] : ''),
                        'instagram_link' => ((isset($web_settings['instagram']) && $web_settings['instagram'] != '') ? $web_settings['instagram'] : ''),
                        'youtube_link' => ((isset($web_settings['youtube']) && $web_settings['youtube'] != '') ? $web_settings['youtube'] : ''),
                        'unsubscription_link' => url('unsubscribe-page/company', ['company_slug' => $follow_up_email_item->reg_member->slug]),
                        'request_generate_link' => $company_user->email,
                        'date' => $follow_up_email_item->reg_member->created_at->format(env('DATE_FORMAT')),
                        'url' => $url,
                        'email_footer' => $company_user->email,
                        'copyright_year' => date('Y'),
                            //'main_service_category' => '',
                    ];

                    /* Follow up email to non member  */
                    Mail::to($company_user->email)->send(new RegisteredMemberFollowUpMail($mail_id, $replaceArr));

                    $follow_up_email_item->status = 'sent';
                    $follow_up_email_item->save();
                }
            }
        }
    }

}

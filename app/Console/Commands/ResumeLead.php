<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Mail\Company\CompanyMail;
use App\Mail\Admin\AdminMail;
use Illuminate\Support\Facades\Mail;
use App\Models\Company;
use App\Models\Custom;

class ResumeLead extends Command {

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'Lead:Resume';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Resume leads';

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
        $companyObj = Company::with('membership_level')
                ->where('lead_resume_date', now()->format(env('DB_DATE_FORMAT')))
                ->leadStatus('inactive')
                ->active()
                ->order()
                ->get();

        if (count($companyObj) > 0) {
            foreach ($companyObj AS $company_item) {
                $company_item->leads_status = "active";
                $company_item->lead_pause_date = null;
                $company_item->lead_resume_date = null;
                $company_item->save();

                /* Company Activated mail to Company */
                $companyUserObj = CompanyUser::where([
                            ['company_id', $company_item->id],
                            ['company_user_type', 'company_super_admin']
                        ])
                        ->first();
                $company_mail_id = "128"; /* Mail title: Company Membership Activated */
                $companyReplaceWithArr = [
                    'company_name' => $company_item->company_name,
                    'account_type' => $company_item->membership_level->title,
                    'phone_number' => ((isset($web_settings['phone_number']) && $web_settings['phone_number'] != '') ? $web_settings['phone_number'] : ''),
                    'global_address' => ((isset($web_settings['office_address']) && $web_settings['office_address'] != '') ? $web_settings['office_address'] : ''),
                    'global_email' => ((isset($web_settings['global_email']) && $web_settings['global_email'] != '') ? $web_settings['global_email'] : ''),
                    'facebook_link' => ((isset($web_settings['facebook']) && $web_settings['facebook'] != '') ? $web_settings['facebook'] : ''),
                    'twitter_link' => ((isset($web_settings['twitter']) && $web_settings['twitter'] != '') ? $web_settings['twitter'] : ''),
                    'instagram_link' => ((isset($web_settings['instagram']) && $web_settings['instagram'] != '') ? $web_settings['instagram'] : ''),
                    'rumble_link' => ((isset($web_settings['rumble']) && $web_settings['rumble'] != '') ? $web_settings['rumble'] : ''),
                    'youtube_link' => ((isset($web_settings['youtube']) && $web_settings['youtube'] != '') ? $web_settings['youtube'] : ''),
                    'unsubscription_link' => url('unsubscribe-page/company', ['company_slug' => $company_item->slug]),
                    'request_generate_link' => $companyUserObj->email,
                    'date' => $company_item->created_at->format(env('DATE_FORMAT')),
                    'url' => url('dashboard'),
                    'email_footer' => $companyUserObj->email,
                    'copyright_year' => date('Y'),
                        //'main_service_category' => '',
                ];

                $mailArr = Custom::generate_company_user_email_arr($company_item->company_information);
                if (!is_null($mailArr) && count($mailArr) > 0) {
                    foreach ($mailArr AS $mail_item) {
                        Mail::to($mail_item)->send(new CompanyMail($company_mail_id, $companyReplaceWithArr));
                    }
                }


                /* Company Activated mail to Admin */
                if (isset($web_settings['global_email']) && $web_settings['global_email'] != '') {
                    $active_admin_mail_id = "144"; /* Mail title: Company Lead Unpaused - Admin */
                    $adminReplaceWithArr = [
                        'company_name' => $company_item->company_name,
                    ];

                    Mail::to($web_settings['global_email'])->send(new AdminMail($active_admin_mail_id, $adminReplaceWithArr));
                }
            }
        }
    }

}

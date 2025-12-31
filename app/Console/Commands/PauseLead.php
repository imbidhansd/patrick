<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Mail\Company\CompanyMail;
use App\Mail\Admin\AdminMail;
use Illuminate\Support\Facades\Mail;
use App\Models\Company;
use App\Models\CompanyUser;
use App\Models\Custom;

class PauseLead extends Command {

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'Lead:Pause';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Pause leads';

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
                ->where('lead_pause_date', now()->format(env('DB_DATE_FORMAT')))
                ->leadStatus('active')
                ->active()
                ->order()
                ->get();

        if (count($companyObj) > 0) {
            foreach ($companyObj AS $company_item) {
                $company_item->leads_status = "inactive";
                $company_item->save();

                /* Company lead pause mail to Company */
                $companyUserObj = CompanyUser::where([
                            ['company_id', $company_item->id],
                            ['company_user_type', 'company_super_admin']
                        ])
                        ->first();
                $pause_company_mail_id = "33"; /* Mail title: Company Lead Paused */
                $companyReplaceWithArr = [
                    'company_name' => $company_item->company_name,
                    'account_type' => $company_item->membership_level->title,
                    'lead_pause_date' => Custom::date_formats($company_item->lead_pause_date, env('DB_DATE_FORMAT'), env('DATE_FORMAT')),
                    'lead_resume_date' => Custom::date_formats($company_item->lead_resume_date, env('DB_DATE_FORMAT'), env('DATE_FORMAT')),
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
                        Mail::to($mail_item)->send(new CompanyMail($pause_company_mail_id, $companyReplaceWithArr));
                    }
                }


                if (isset($web_settings['global_email']) && $web_settings['global_email'] != '') {
                    $service_category_arr = [];
                    if (count($company_item->service_category) > 0) {
                        foreach ($company_item->service_category AS $service_category_item) {
                            $service_category_arr[] = $service_category_item->service_category->title;
                        }
                    }

                    /* Company lead pause mail to Admin */
                    $pause_admin_mail_id = "34"; /* Mail title: Company Lead Paused - Admin */
                    $adminReplaceWithArr = [
                        'account_type' => $company_item->membership_level->title,
                        'company_name' => $company_item->company_name,
                        'lead_pause_date' => Custom::date_formats($company_item->lead_pause_date, env('DB_DATE_FORMAT'), env('DATE_FORMAT')),
                        'lead_resume_date' => Custom::date_formats($company_item->lead_resume_date, env('DB_DATE_FORMAT'), env('DATE_FORMAT')),
                        'internal_contact_name' => $company_item->internal_contact_name,
                        'internal_contact_phone' => $company_item->internal_contact_phone,
                        'main_company_telephone' => $company_item->main_company_telephone,
                        'address' => $company_item->company_mailing_address,
                        'city' => $company_item->city,
                        'state' => $company_item->state->name,
                        'zipcode' => $company_item->main_zipcode,
                        'main_service_category' => $company_item->main_category->title,
                        'service_category' => implode(', ', $service_category_arr),
                    ];

                    Mail::to($web_settings['global_email'])->send(new AdminMail($pause_admin_mail_id, $adminReplaceWithArr));
                }
            }
        }
    }

}

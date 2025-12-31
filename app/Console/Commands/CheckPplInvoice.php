<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Mail\Company\CompanyMail;
use App\Mail\Admin\AdminMail;
use Illuminate\Support\Facades\Mail;
use App\Models\Company;
use App\Models\CompanyUser;
use App\Models\CompanyInvoice;
use App\Models\CompanyLead;
use App\Models\Custom;

class CheckPplInvoice extends Command {

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'PplInvoice:Check';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check PPL invoices payment status for the pervious month leads';

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
        $companies = Company::select('companies.*')
                ->leftJoin('membership_levels', 'companies.membership_level_id', 'membership_levels.id')
                ->where('membership_levels.charge_type', 'ppl_price')
                ->active()
                ->order()
                ->get();
        if (count($companies) > 0) {
            foreach ($companies AS $company_item) {
                $get_company_invoices = CompanyInvoice::where([
                            ['company_id', $company_item->id]
                        ])
                        ->where('final_amount', '>', 0)
                        ->whereNull('transaction_id')
                        ->count();

                if ($get_company_invoices > 0) {
                    $company_item->status = "Unpaid Invoice";
                    $company_item->save();


                    CompanyLead::where('company_id', $company_item->id)->update(['is_hidden' => 'yes']);

                    /* PPL invoice check and status change mail to Company */
                    $companyUserObj = CompanyUser::where([
                                ['company_id', $company_item->id],
                                ['company_user_type', 'company_super_admin']
                            ])->first();
                    $mail_id = '139'; /* Mail title: PPL Company Status Change - Unpaid Invoice */
                    $replaceWithArr = [
                        'company_name' => $company_item->company_name,
                        'global_address' => ((isset($web_settings['office_address']) && $web_settings['office_address'] != '') ? $web_settings['office_address'] : ''),
                        'phone_number' => ((isset($web_settings['phone_number']) && $web_settings['phone_number'] != '') ? $web_settings['phone_number'] : ''),
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

                    $messageArr = [
                        'company_id' => $company_item->id,
                        'message_type' => 'info',
                        'link' => url('dashboard')
                    ];
                    Custom::companyMailMessageCreate($messageArr, $mail_id, $replaceWithArr);
                    $mailArr = Custom::generate_company_user_email_arr($company_item->company_information);
                    if (!is_null($mailArr) && count($mailArr) > 0) {
                        foreach ($mailArr AS $mail_item) {
                            Mail::to($mail_item)->send(new CompanyMail($mail_id, $replaceWithArr));                            Mail::to($mail_item)->send(new CompanyMail($mail_id, $replaceWithArr));
                        }
                    }


                    /* PPL invoice check and status change mail to Admin */
                    if (isset($web_settings['global_email']) && $web_settings['global_email'] != '') {
                        $admin_mail_id = "140"; /* Mail title: PPL Company Status Change - Admin */
                        $adminReplaceArr = [
                            'company_name' => $company_item->company_name,
                            //'company_status' => 'Unpaid Invoice'
                        ];
                        Mail::to($web_settings['global_email'])->send(new AdminMail($admin_mail_id, $adminReplaceArr));
                    }
                }
            }
            /* get last month company leads end */
        }
    }

}

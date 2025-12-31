<?php

namespace App\Http\Controllers\crons;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Mail\Company\CompanyMail;
use App\Mail\Admin\AdminMail;
use Illuminate\Support\Facades\Mail;
use App\Models\Company;
use App\Models\CompanyUser;
use App\Models\Custom;

class UpdateMonthlyBudgetController extends Controller {

    public function updateMonthlyBudget() {
        $web_settings = Custom::getSettings();
        $companyObj = Company::select('companies.*')
                ->leftJoin('membership_levels', 'companies.membership_level_id', 'membership_levels.id')
                ->where('membership_levels.charge_type', 'ppl_price')
                ->where('companies.id', '110')
                ->order()
                ->get();

        if (count($companyObj) > 0) {
            foreach ($companyObj AS $company_item) {
                $mail_sent = false;
                if ($company_item->temporary_budget != $company_item->permanent_budget) {
                    $mail_sent = true;
                }

                $company_item->temporary_budget = $company_item->permanent_budget;
                $company_item->save();


                if ($mail_sent) {
                    /* Company update monthly budget mail to Company */
                    $companyUserObj = CompanyUser::where([
                                ['company_id', $company_item->id],
                                ['company_user_type', 'company_super_admin']
                            ])->first();
                    $mail_id = '74'; /* Mail title: Company Monthly Budget */
                    $replaceWithArr = [
                        'company_name' => $company_item->company_name,
                        'monthly_budget' => '$' . number_format($company_item->permanent_budget, 2),
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
                        'url' => url('leads-archive-inbox'),
                        'email_footer' => $companyUserObj->email,
                        'copyright_year' => date('Y'),
                            //'main_service_category' => '',
                    ];

                    $messageArr = [
                        'company_id' => $company_item->id,
                        'message_type' => 'info',
                        'link' => url('leads-archive-inbox')
                    ];
                    Custom::companyMailMessageCreate($messageArr, $mail_id, $replaceWithArr);
                    $mailArr = Custom::generate_company_user_email_arr($company_item->company_information);
                    if (!is_null($mailArr) && count($mailArr) > 0) {
                        foreach ($mailArr AS $mail_item) {
                            Mail::to($mail_item)->send(new CompanyMail($mail_id, $replaceWithArr));
                        }
                    }

                    /* Company update monthly budget mail to Admin */

                    if (isset($web_settings['global_email']) && $web_settings['global_email'] != '') {
                        /* ppl invoice generate mail to Admin */
                        $admin_mail_id = "75"; /* Mail title: Company Monthly Budget - Admin */
                        $adminReplaceArr = [
                            'company_name' => $company_item->company_name,
                            'monthly_budget' => '$' . number_format($company_item->permanent_budget, 2),
                        ];
                        Mail::to($web_settings['global_email'])->send(new AdminMail($admin_mail_id, $adminReplaceArr));
                    }
                }
            }
        }

        echo 'Monthly budget updated successfully.';
    }

}

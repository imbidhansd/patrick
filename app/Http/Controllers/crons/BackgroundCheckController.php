<?php

namespace App\Http\Controllers\crons;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Mail\Company\CompanyMail;
use App\Mail\Admin\AdminMail;
use Illuminate\Support\Facades\Mail;
use App\Models\CompanyInformation;
use App\Models\CompanyUser;
use App\Models\Custom;
use App\Models\CompanyApprovalStatus;

class BackgroundCheckController extends Controller {

    public function background_check_process() {
        $web_settings = Custom::getSettings();
        $companyUserObj = CompanyUser::select('company_users.*', 'companies.number_of_owners', 'companies.company_name', 'companies.slug')
                ->join('companies', 'company_users.company_id', 'companies.id')
                ->where([
                    ['companies.status', 'Pending Approval'],
                ])
                ->whereNotIn('company_users.bg_check_status', ['x:ready', 'x:archived'])
                ->whereNotNull('company_users.bg_check_order_id')
                ->active()
                ->order()
                ->get();

        //dd($companyUserObj);

        if (count($companyUserObj) > 0) {
            foreach ($companyUserObj as $company_user_item) {
                $data = [];
                $data['company_user_item'] = $company_user_item;
                $data['API_USER_ID'] = env(env('API_MODE') . '_USER_ID');
                $data['API_PASSWORD'] = env(env('API_MODE') . '_PASSWORD');
                $api_link = env(env('API_MODE') . '_API_LINK');

                $str = view('company.pre_screen.bg_check_status_generate_xml', $data)->render();
                $newArr = Custom::tazworksapi($str, $api_link);

                //dd($newArr);

                if ($newArr['BackgroundReportPackage']['ScreeningStatus']['OrderStatus'] != $company_user_item->bg_check_status) {
                    $company_user_item->bg_check_status = $newArr['BackgroundReportPackage']['ScreeningStatus']['OrderStatus'];

                    if ($newArr['BackgroundReportPackage']['ScreeningStatus']['OrderStatus'] == 'x:ready') {
                        $company_user_item->bg_check_date = now()->format(env('DB_DATE_FORMAT'));
                    }
                    $company_user_item->save();

                    if ($newArr['BackgroundReportPackage']['ScreeningStatus']['OrderStatus'] == 'x:ready') {
                        /* Company Background check process mail to Company */
                        $company_mail_id = "104"; /* Mail title: Company User Background Check Process Completed */
                        $companyReplaceArr = [
                            'first_name' => $company_user_item->first_name,
                            'last_name' => $company_user_item->last_name,
                            'phone_number' => ((isset($web_settings['phone_number']) && $web_settings['phone_number'] != '') ? $web_settings['phone_number'] : ''),
                            'global_domain' => ((isset($web_settings['global_domain']) && $web_settings['global_domain'] != '') ? $web_settings['global_domain'] : ''),
                            'global_address' => ((isset($web_settings['office_address']) && $web_settings['office_address'] != '') ? $web_settings['office_address'] : ''),
                            'global_email' => ((isset($web_settings['global_email']) && $web_settings['global_email'] != '') ? $web_settings['global_email'] : ''),
                            'facebook_link' => ((isset($web_settings['facebook']) && $web_settings['facebook'] != '') ? $web_settings['facebook'] : ''),
                            'twitter_link' => ((isset($web_settings['twitter']) && $web_settings['twitter'] != '') ? $web_settings['twitter'] : ''),
                            'instagram_link' => ((isset($web_settings['instagram']) && $web_settings['instagram'] != '') ? $web_settings['instagram'] : ''),
                            'rumble_link' => ((isset($web_settings['rumble']) && $web_settings['rumble'] != '') ? $web_settings['rumble'] : ''),
                            'youtube_link' => ((isset($web_settings['youtube']) && $web_settings['youtube'] != '') ? $web_settings['youtube'] : ''),
                            'unsubscription_link' => url('unsubscribe-page/company', ['company_slug' => $company_user_item->slug]),
                            'request_generate_link' => $company_user_item->email,
                            'date' => $company_user_item->created_at->format(env('DATE_FORMAT')),
                            'url' => url('dashboard'),
                            'email_footer' => $company_user_item->email,
                            'copyright_year' => date('Y'),
                                //'main_service_category' => '',
                        ];

                        Mail::to($company_user_item->email)->send(new CompanyMail($company_mail_id, $companyReplaceArr));


                        /* Company Background check process mail to Admin */
                        if (isset($web_settings['global_email']) && $web_settings['global_email'] != '') {
                            $admin_mail_id = "105"; /* Mail title: Company User Background Check Process Completed - Admin */
                            $adminReplaceArr = [
                                'company_name' => $company_user_item->company_name,
                                'first_name' => $company_user_item->first_name,
                                'last_name' => $company_user_item->last_name,
                            ];
                            Mail::to($web_settings['global_email'])->send(new AdminMail($admin_mail_id, $adminReplaceArr));
                        }
                    }
                }


                /* check all owner completed start */
                if ($newArr['BackgroundReportPackage']['ScreeningStatus']['OrderStatus'] == 'x:ready') {
                    $company_user_count = CompanyUser::where([
                                ['company_id', $company_user_item->company_id],
                                ['bg_check_status', 'x:ready']
                            ])
                            ->count();

                    // New Code [Start]

                    $company_information_item = CompanyInformation::firstOrCreate(['company_id' => $company_user_item->company_id]);
                    $company_approval_status = CompanyApprovalStatus::firstOrCreate(['company_id' => $company_user_item->company_id]);

                    if ($company_information_item->company_owner_1_user_id == $company_user_item->id) {
                        $company_approval_status->owner_1_bg_check_document_status = 'completed';
                    }
                    if ($company_information_item->company_owner_2_user_id == $company_user_item->id) {
                        $company_approval_status->owner_2_bg_check_document_status = 'completed';
                    }
                    if ($company_information_item->company_owner_3_user_id == $company_user_item->id) {
                        $company_approval_status->owner_3_bg_check_document_status = 'completed';
                    }
                    if ($company_information_item->company_owner_4_user_id == $company_user_item->id) {
                        $company_approval_status->owner_4_bg_check_document_status = 'completed';
                    }
                    $company_approval_status->save();

                    if ($company_user_count == $company_user_item->company->number_of_owners) {
                        $company_approval_status->background_check_process = "completed";
                        $company_approval_status->save();
                        Custom::company_approval_status($company_user_item->company_id);
                    }

                    echo '<pre>';
                    print_r($company_approval_status->toArray());
                    echo '</pre>';

                    // New Code [End]
                }
                /* check all owner completed end */
            }
        }

        echo "Background process check for company user completed successfully.";
    }

}

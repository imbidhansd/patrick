<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Mail\Company\CompanyMail;
use App\Mail\Admin\AdminMail;
use Illuminate\Support\Facades\Mail;
use DB;
use App\Models\Custom;
use App\Models\Company;
use App\Models\CompanyUser;
use App\Models\RegisterationSession;
use App\Models\Email;
use App\Models\DefaultEmail;
use App\Models\FollowUpEmail;

class CustomEmailController extends Controller {

    public function custom_emails() {
        $demo_custom_emails = DB::table('demo_custom_emails')->get();

        foreach ($demo_custom_emails AS $email_item) {
            if ($email_item->email_type == 'CUSTOM') {
                $email_type = 'custom_email';
            } else if ($email_item->email_type == 'ERN') {
                $email_type = 'ern_email';
            } else if ($email_item->email_type == 'user') {
                $email_type = 'user_email';
            }

            $insertArr = [
                'title' => $email_item->email_name,
                'subject' => $email_item->subject,
                'email_header' => $email_item->email_header,
                'email_content' => $email_item->content,
                'email_footer' => $email_item->email_footer,
                'email_type' => $email_type
            ];

            Email::create($insertArr);
        }

        dd("Emails created successfully.");
    }

    public function followup_emails() {
        $followup_emails = DB::table('demo_followup_templates')->where('domain_id', '10')->get();

        foreach ($followup_emails AS $email_item) {
            if ($email_item->email_type != '') {

                if ($email_item->title == "Confirmation Email") {
                    $email_type = "confirmation_email";
                } else {
                    $email_type = "followup_email";
                }

                $trade_id = (($email_item->email_type == 'contractors') ? 1 : 2);

                if ($trade_id == 1) {
                    if ($email_item->type == 'members') {
                        $follow_up_mail_category_id = "2";
                    } else if ($email_item->type == 'non_members') {
                        $follow_up_mail_category_id = "1";
                    } else if ($email_item->type == 'non_member_inactive') {
                        $follow_up_mail_category_id = "4";
                    } else if ($email_item->type == 'active_members') {
                        $follow_up_mail_category_id = "3";
                    }
                } else if ($trade_id == 2) {
                    if ($email_item->type == 'members') {
                        $follow_up_mail_category_id = "6";
                    } else if ($email_item->type == 'non_members') {
                        $follow_up_mail_category_id = "5";
                    } else if ($email_item->type == 'non_member_inactive') {
                        $follow_up_mail_category_id = "7";
                    } else if ($email_item->type == 'active_members') {
                        $follow_up_mail_category_id = "8";
                    }
                }

                $send_time = $email_item->send_time . ' ' . ucfirst($email_item->send_after);
                $insertArr = [
                    'title' => $email_item->title,
                    'subject' => $email_item->title,
                    'email_header' => $email_item->header,
                    'email_content' => $email_item->content,
                    'email_footer' => $email_item->footer,
                    'trade_id' => $trade_id,
                    'follow_up_mail_category_id' => $follow_up_mail_category_id,
                    'email_type' => $email_type,
                    'send_time' => $send_time
                ];

                FollowUpEmail::create($insertArr);
            }
        }

        dd("Emails created successfully.");
    }

    public function followup_email_list() {
        $data['followup_emails'] = FollowUpEmail::where('email_type', 'confirmation_email')->get();

        return view('followup_email_list', $data);
    }

    public function email_list() {
        $data['emails'] = Email::where('email_type', 'custom_email')->get();

        return view('email_list', $data);
    }

    public function custom_email_variable_change() {
        $emails = Email::all();

        $to_replace = [
            '/user-name/',
            '/your-sincerely/',
            '/phone-number/',
            '/company-name/',
            '/global-domain/',
            '/domain/',
            '/city/',
            '/state/',
            '/zip/',
            '/project_info/',
            '/sub-service-category/',
            '/main-service-category/',
            '/domain-name/',
            '/name/',
            '/email/',
            '/telephone/',
            '/address/',
            '/zip-code/',
            '/street/',
            '/timeframe/',
            '/contractor-name/',
            '/contractor-email/',
            '/contractor-phone/',
            '/unsubscribe-reason/',
            '/member-profile-url/',
            '/rejected-reason/ ',
            '/company-url/',
            '/dashboard-url/',
            '/notification-details/',
            '/complaint-date/',
            '/comment-text/',
            '/submit-a-response/',
            '/view-response/',
            '/view-response-link/',
            '/customer-name/',
            '/customer-question/',
            '/customer-email/',
            '/customer-phone/',
            '/customer-city/',
            '/customer-state/',
            '/view-button/',
            '/view-link/',
            '/confirmation-link/',
            '/reason-text/',
            '/global-address/',
            '/user-name/',
            '/user-email/',
            '/first-name/',
            '/company-profile-link/',
            '/global-signature/',
            '/zip_code_name/',
            '/zip_code_link/',
            '/service-category/',
            '/confirm-request/',
            '/member-details/',
            '/location-url/',
            '/request-generated/',
            '/ip-address/',
            '/device-type/',
            '/complaint-number/',
            '/url/',
            '/facebook-link/',
            '/linkedin-link/',
            '/twitter-link/',
            '/google-plus-link/',
            '/youtube-link/',
            '/company-logo/',
            '/address1/',
            '/rating/',
            '/category-name/',
            '/members-details/'
        ];

        $replace_with = [
            '{{ USER_NAME }}',
            '{{ YOUR_SINCERELY }}',
            '{{ PHONE_NUMBER }}',
            '{{ COMPANY_NAME }}',
            '{{ GLOBAL_DOMAIN }}',
            '{{ GLOBAL_DOMAIN }}',
            '{{ CITY }}',
            '{{ STATE }}',
            '{{ ZIPCODE }}',
            '{{ PROJECT_INFO }}',
            '{{ SUB_SERVICE_CATEGORY }}',
            '{{ MAIN_SERVICE_CATEGORY }}',
            '{{ GLOBAL_DOMAIN }}',
            '{{ NAME }}',
            '{{ EMAIL }}',
            '{{ TELEPHONE }}',
            '{{ ADDRESS }}',
            '{{ ZIPCODE }}',
            '{{ STREET }}',
            '{{ TIMEFRAME }}',
            '{{ CONTRACTOR_NAME }}',
            '{{ CONTRACTOR_EMAIL }}',
            '{{ CONTRACTOR_PHONE }}',
            '{{ UNSUBSCRIBE_REASON }}',
            '{{ MEMBER_PROFILE_URL }}',
            '{{ REJECTED_REASON }}',
            '{{ COMPANY_URL }}',
            '{{ DASHBOARD_URL }}',
            '{{ NOTIFICATION_DETAIL }}',
            '{{ COMPLAINT_DATE }}',
            '{{ COMMENT_TEXT }}',
            '{{ SUBMIT_A_RESPONSE }}',
            '{{ VIEW_RESPONSE }}',
            '{{ VIEW_RESPONSE_LINK }}',
            '{{ CUSTOMER_NAME }}',
            '{{ CUSTOMER_QUESTION }}',
            '{{ CUSTOMER_EMAIL }}',
            '{{ CUSTOMER_PHONE }}',
            '{{ CUSTOMER_CITY }}',
            '{{ CUSTOMER_STATE }}',
            '{{ VIEW_BUTTON }}',
            '{{ VIEW_LINK }}',
            '{{ CONFIRMATION_LINK }}',
            '{{ REASON_TEXT }}',
            '{{ GLOBAL_ADDRESS }}',
            '{{ USER_NAME }}',
            '{{ USER_EMAIL }}',
            '{{ FIRST_NAME }}',
            '{{ COMPANY_PROFILE_LINK }}',
            '{{ GLOBAL_SIGNATURE }}',
            '{{ ZIPCODE_NAME }}',
            '{{ ZIPCODE_LINK }}',
            '{{ SERVICE_CATEGORY }}',
            '{{ CONFIRM_REQUEST }}',
            '{{ MEMBER_DETAIL }}',
            '{{ LOCATION_URL }}',
            '{{ REQUEST_GENERATED }}',
            '{{ IP_ADDRESS }}',
            '{{ DEVICE_TYPE }}',
            '{{ COMPLAINT_NUMBER }}',
            '{{ URL }}',
            '{{ FACEBOOK_LINK }}',
            '{{ LINKEDIN_LINK }}',
            '{{ TWITTER_LINK }}',
            '{{ GOOGLE_PLUS_LINK }}',
            '{{ YOUTUBE_LINK }}',
            '{{ COMPANY_LOGO }}',
            '{{ ADDRESS }}',
            '{{ RATING }}',
            '{{ CATEGORY_NAME }}',
            '{{ MEMBER_DETAIL }}'
        ];
        foreach ($emails AS $email_item) {
            $email_item->email_header = str_replace($to_replace, $replace_with, $email_item->email_header);
            $email_item->email_content = str_replace($to_replace, $replace_with, $email_item->email_content);
            $email_item->email_footer = str_replace($to_replace, $replace_with, $email_item->email_footer);
            $email_item->save();
        }

        dd("Variables change successfully.");
    }

    public function followup_email_variable_change() {
        $emails = FollowUpEmail::all();

        $to_replace = [
            '/user-name/',
            '/your-sincerely/',
            '/phone-number/',
            '/company-name/',
            '/global-domain/',
            '/domain/',
            '/city/',
            '/state/',
            '/zip/',
            '/project_info/',
            '/sub-service-category/',
            '/main-service-category/',
            '/domain-name/',
            '/name/',
            '/email/',
            '/telephone/',
            '/address/',
            '/zip-code/',
            '/street/',
            '/timeframe/',
            '/contractor-name/',
            '/contractor-email/',
            '/contractor-phone/',
            '/unsubscribe-reason/',
            '/member-profile-url/',
            '/rejected-reason/ ',
            '/company-url/',
            '/dashboard-url/',
            '/notification-details/',
            '/complaint-date/',
            '/comment-text/',
            '/submit-a-response/',
            '/view-response/',
            '/view-response-link/',
            '/customer-name/',
            '/customer-question/',
            '/customer-email/',
            '/customer-phone/',
            '/customer-city/',
            '/customer-state/',
            '/view-button/',
            '/view-link/',
            '/confirmation-link/',
            '/reason-text/',
            '/global-address/',
            '/user-name/',
            '/user-email/',
            '/first-name/',
            '/company-profile-link/',
            '/global-signature/',
            '/zip_code_name/',
            '/zip_code_link/',
            '/service-category/',
            '/confirm-request/',
            '/member-details/',
            '/location-url/',
            '/request-generated/',
            '/ip-address/',
            '/device-type/',
            '/complaint-number/',
            '/url/',
            '/facebook-link/',
            '/linkedin-link/',
            '/twitter-link/',
            '/google-plus-link/',
            '/youtube-link/',
            '/company-logo/',
            '/address1/',
            '/rating/',
            '/category-name/',
            '/members-details/'
        ];

        $replace_with = [
            '{{ USER_NAME }}',
            '{{ YOUR_SINCERELY }}',
            '{{ PHONE_NUMBER }}',
            '{{ COMPANY_NAME }}',
            '{{ GLOBAL_DOMAIN }}',
            '{{ GLOBAL_DOMAIN }}',
            '{{ CITY }}',
            '{{ STATE }}',
            '{{ ZIPCODE }}',
            '{{ PROJECT_INFO }}',
            '{{ SUB_SERVICE_CATEGORY }}',
            '{{ MAIN_SERVICE_CATEGORY }}',
            '{{ GLOBAL_DOMAIN }}',
            '{{ NAME }}',
            '{{ EMAIL }}',
            '{{ TELEPHONE }}',
            '{{ ADDRESS }}',
            '{{ ZIPCODE }}',
            '{{ STREET }}',
            '{{ TIMEFRAME }}',
            '{{ CONTRACTOR_NAME }}',
            '{{ CONTRACTOR_EMAIL }}',
            '{{ CONTRACTOR_PHONE }}',
            '{{ UNSUBSCRIBE_REASON }}',
            '{{ MEMBER_PROFILE_URL }}',
            '{{ REJECTED_REASON }}',
            '{{ COMPANY_URL }}',
            '{{ DASHBOARD_URL }}',
            '{{ NOTIFICATION_DETAIL }}',
            '{{ COMPLAINT_DATE }}',
            '{{ COMMENT_TEXT }}',
            '{{ SUBMIT_A_RESPONSE }}',
            '{{ VIEW_RESPONSE }}',
            '{{ VIEW_RESPONSE_LINK }}',
            '{{ CUSTOMER_NAME }}',
            '{{ CUSTOMER_QUESTION }}',
            '{{ CUSTOMER_EMAIL }}',
            '{{ CUSTOMER_PHONE }}',
            '{{ CUSTOMER_CITY }}',
            '{{ CUSTOMER_STATE }}',
            '{{ VIEW_BUTTON }}',
            '{{ VIEW_LINK }}',
            '{{ CONFIRMATION_LINK }}',
            '{{ REASON_TEXT }}',
            '{{ GLOBAL_ADDRESS }}',
            '{{ USER_NAME }}',
            '{{ USER_EMAIL }}',
            '{{ FIRST_NAME }}',
            '{{ COMPANY_PROFILE_LINK }}',
            '{{ GLOBAL_SIGNATURE }}',
            '{{ ZIPCODE_NAME }}',
            '{{ ZIPCODE_LINK }}',
            '{{ SERVICE_CATEGORY }}',
            '{{ CONFIRM_REQUEST }}',
            '{{ MEMBER_DETAIL }}',
            '{{ LOCATION_URL }}',
            '{{ REQUEST_GENERATED }}',
            '{{ IP_ADDRESS }}',
            '{{ DEVICE_TYPE }}',
            '{{ COMPLAINT_NUMBER }}',
            '{{ URL }}',
            '{{ FACEBOOK_LINK }}',
            '{{ LINKEDIN_LINK }}',
            '{{ TWITTER_LINK }}',
            '{{ GOOGLE_PLUS_LINK }}',
            '{{ YOUTUBE_LINK }}',
            '{{ COMPANY_LOGO }}',
            '{{ ADDRESS }}',
            '{{ RATING }}',
            '{{ CATEGORY_NAME }}',
            '{{ MEMBER_DETAIL }}'
        ];

        foreach ($emails AS $email_item) {
            $email_item->email_header = str_replace($to_replace, $replace_with, $email_item->email_header);
            $email_item->email_content = str_replace($to_replace, $replace_with, $email_item->email_content);
            $email_item->email_footer = str_replace($to_replace, $replace_with, $email_item->email_footer);
            $email_item->save();
        }

        dd("Variables change successfully.");
    }

    public function custom_email_update() {
        $emails = Email::all();

        $default_emails = DefaultEmail::find(1);
        foreach ($emails AS $email_item) {
            $email_item->email_header = $default_emails->email_header;
            $email_item->email_footer = $default_emails->email_footer;
            $email_item->save();
        }
    }

    public function send_mail() {
        $companyObj = Company::with('membership_level')->find('35');
        $companyUserObj = CompanyUser::where('company_id', '35')->first();

        $web_settings = Custom::getSettings();
        if (isset($web_settings['global_email']) && !is_null($web_settings['global_email'])) {
            $admin_mail_id = "4"; /* Mail title: Register Success Email - Admin */
            $adminReplaceWithArr = [
                'account_type' => $companyObj->membership_level->title,
                'company_name' => $companyObj->company_name,
                'first_name' => $companyUserObj->first_name,
                'last_name' => $companyUserObj->last_name,
                'company_user_email' => $companyUserObj->email,
                'phone' => $companyObj->main_company_telephone,
            ];
            //Mail::to('ajay.makwana87@gmail.com')->send(new AdminMail($mail_id, $replaceWithArr));
            Mail::to($web_settings['global_email'])->send(new AdminMail($admin_mail_id, $adminReplaceWithArr));
        }

        echo "Email Sent";
        exit;

        $reg_session = RegisterationSession::latest()->first();
        $companyObj = Company::find('35');
        $company_user = CompanyUser::where('company_id', '35')->first();

        /* Registration confirmation mail to Company */
        $mail_id = "1"; /* Mail title: Register Confirmation Email */
        $replaceWithArr = [
            'company_name' => $companyObj->company_name,
            'account_type' => ucwords(str_replace("_", " ", $reg_session->registration_type)),
            'confirmation_link' => route('company-activation-link', ['activation_key' => $companyObj->activation_key]),
        ];

        //Mail::to('ajay.makwana87@gmail.com')->send(new CompanyCustomMail($mail_id, $replaceWithArr));
        Mail::to($company_user->email)->send(new CompanyMail($mail_id, $replaceWithArr));

        echo "Mail sent.";
    }

}

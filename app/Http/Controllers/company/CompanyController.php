<?php

namespace App\Http\Controllers\company;

use Auth;
use Image;
use ImageOptimizer;
use Validator;
use Session;

use App\Http\Controllers\Controller;
use App\Mail\Company\CompanyMail;
use App\Mail\Admin\AdminMail;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

// Models
use App\Models\Media;
use App\Models\State;
use App\Models\Custom;
use App\Models\Trade;
use App\Models\Company;
use App\Models\CompanyLogo;
use App\Models\CompanyZipcode;
use App\Models\CompanyLeadNotification;
use App\Models\CompanyApplication;
use App\Models\CompanyInformation;
use App\Models\CompanyApprovalStatus;
use App\Models\CompanyLicensing;
use App\Models\CompanyDocument;
use App\Models\CompanyCustomerReference;
use App\Models\ProfessionalAffiliation;
use App\Models\KeyIdentifierType;

class CompanyController extends Controller {

    public function __construct() {
        $this->web_settings = Custom::getSettings();
        $this->view_base = 'company.';
    }

    public function getFreePreviewTrial(Request $request) {
        $data = [
            'trades' => Trade::order()->pluck('title', 'id'),
            'states' => State::order()->pluck('name', 'id'),
        ];
        return view($this->view_base . 'register.-preview-trial.free-preview-trial', $data);
    }

    // Profile Section
    public function profile() {
        $data = [
            'company_user' => Auth::guard('company_user')->user(),
            'states' => State::order()->pluck('name', 'id'),
        ];
        return view($this->view_base . 'profile.profile', $data);
    }

    public function updateProfile(Request $request) {
        if ($request->has('update_type') && $request->get('update_type') == 'company_user_bio') {
            $validator = Validator::make($request->all(), [
                        'user_bio' => 'required',
            ]);
        } else if ($request->has('update_type') && $request->get('update_type') == 'company_user_logo') {
            $validator = Validator::make($request->all(), [
                        'media' => 'required|mimes:jpg,jpeg,png,gif',
            ]);
        } else {
            $user_id = Auth::guard('company_user')->user()->id;

            $validator = Validator::make($request->all(), [
                        'first_name' => 'required',
                        'last_name' => 'required',
                        'email' => 'required|email',
                        'username' => 'required|unique:company_users,username,' . $user_id . ',id'
            ]);
        }

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        } else {
            $userObj = Auth::guard('company_user')->user();
            $companyObj = Company::find($userObj->company_id);
            $requestArr = $request->all();

            if (isset($requestArr['first_name'])) {
                //get company information
                $company_information = CompanyInformation::where([
                            ['company_id', $companyObj->id]
                        ])->latest()->first();

                for ($i = 1; $i < $companyObj->number_of_owners; $i++) {
                    $company_owner_full_name = 'company_owner_' . $i . '_full_name';
                    $email = 'company_owner_' . $i . '_email';
                    $phone = 'company_owner_' . $i . '_phone';

                    $user_id = 'company_owner_' . $i . '_user_id';

                    if ($company_information->$user_id == $userObj->id) {
                        $full_name = ((isset($requestArr['first_name']) && $requestArr['first_name'] != '') ? $requestArr['first_name'] : '') . ' ' . ((isset($requestArr['last_name']) && $requestArr['last_name'] != '') ? $requestArr['last_name'] : '');

                        $company_information->update([
                            $company_owner_full_name => $full_name,
                            $email => $requestArr['email'],
                            $phone => $requestArr['user_telephone']
                        ]);
                    }
                }
            }

            if (isset($requestArr['update_type']) && $requestArr['update_type'] == 'company_user_bio') {
                $requestArr['user_bio_status'] = 'in process';

                /* Company User Bio changed mail to Company */
                $company_mail_id = "11"; /* Mail title: Company User Bio Received */

                /* Company User Bio changed mail to Admin */
                $admin_mail_id = "13"; /* Mail title: Company User Bio Uploaded - Admin */
            } else if (isset($requestArr['update_type']) && $requestArr['update_type'] == 'company_user_logo') {
                $requestArr['user_image_status'] = 'in process';

                /* Company User Profile picture changed mail to Company */
                $company_mail_id = "12"; /* Mail title: Company User Profile Picture Received */

                /* Company User Profile picture changed mail to Admin */
                $admin_mail_id = "14"; /* Mail title: Company User Profile Picture Uploaded - Admin */
            }
            $userObj->update($requestArr);

            if ($request->hasFile('media')) {
                $imageArr = Custom::uploadFile($request->file('media'), 'company_user_');
                $userObj->media_id = $imageArr['mediaObj']->id;
                $userObj->save();
            }


            $replaceWithArr = [
                'first_name' => $userObj->first_name,
                'last_name' => $userObj->last_name,
                'profile_url' => route('companies.edit', ['company' => $companyObj->id]) . '#company_profile',
            ];

            if (isset($admin_mail_id) && $admin_mail_id != '') {
                if (isset($this->web_settings['global_email']) && $this->web_settings['global_email'] != '') {
                    Mail::to($this->web_settings['global_email'])->send(new AdminMail($admin_mail_id, $replaceWithArr));
                }
            }

            flash('Your profile has been updated successfully')->success();
            return redirect('profile');
        }
    }

    public function changePassword() {
        return view($this->view_base . 'profile.change_password');
    }

    public function postChangePassword(Request $request) {
        $validator = Validator::make($request->all(), [
                    'old_password' => 'required|min:6|max:50',
                    'new_password' => 'required|min:6|max:50',
                    'confirm_password' => 'required|min:6|max:50|same:new_password',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        } else {
            $requestArr = $request->all();

            // check for old password
            if (!(Hash::check($requestArr['old_password'], Auth::guard('company_user')->user()->password))) {
                return back()->withErrors(['old_password' => 'Old password do not matched!'])->withInput();
            }

            $formObj = Auth::guard('company_user')->user();
            $formObj->password = bcrypt($requestArr['new_password']);
            $formObj->save();


            /* Company User Password Change mail to Company User */
            $companyObj = Company::find($formObj->company_id);
            $web_settings = $this->web_settings;
            $mail_id = "15"; /* Mail Title: Company User password changed */
            $replaceWithArr = [
                'first_name' => $formObj->first_name,
                'last_name' => $formObj->last_name,
                'global_address' => ((isset($web_settings['office_address']) && $web_settings['office_address'] != '') ? $web_settings['office_address'] : ''),
                'phone_number' => ((isset($web_settings['phone_number']) && $web_settings['phone_number'] != '') ? $web_settings['phone_number'] : ''),
                'global_email' => ((isset($web_settings['global_email']) && $web_settings['global_email'] != '') ? $web_settings['global_email'] : ''),
                'facebook_link' => ((isset($web_settings['facebook']) && $web_settings['facebook'] != '') ? $web_settings['facebook'] : ''),
                'twitter_link' => ((isset($web_settings['twitter']) && $web_settings['twitter'] != '') ? $web_settings['twitter'] : ''),
                'instagram_link' => ((isset($web_settings['instagram']) && $web_settings['instagram'] != '') ? $web_settings['instagram'] : ''),
                'rumble_link' => ((isset($web_settings['rumble']) && $web_settings['rumble'] != '') ? $web_settings['rumble'] : ''),
                'youtube_link' => ((isset($web_settings['youtube']) && $web_settings['youtube'] != '') ? $web_settings['youtube'] : ''),
                'unsubscription_link' => url('unsubscribe-page/company', ['company_slug' => $companyObj->slug]),
                'request_generate_link' => $formObj->email,
                'date' => $companyObj->created_at->format(env('DATE_FORMAT')),
                'url' => url('change-password'),
                'email_footer' => $formObj->email,
                'copyright_year' => date('Y'),
                    //'main_service_category' => '',
            ];

            $messageArr = [
                'company_id' => $companyObj->id,
                'message_type' => 'info',
                'link' => url('change-password'),
            ];
            Custom::companyMailMessageCreate($messageArr, $mail_id, $replaceWithArr);
            Mail::to($formObj->email)->send(new CompanyMail($mail_id, $replaceWithArr));

            flash('Password has been changed.')->success();
            return back();
        }
    }

    // Company Profile Section
    public function company_profile() {
        $companyUserObj = Auth::guard('company_user')->user();

        $data = [
            'states' => State::order()->pluck('name', 'id'),
            'company_customer_references' => CompanyCustomerReference::where('company_id', $companyUserObj->company_id)->latest()->first(),
            'professional_affiliations' => ProfessionalAffiliation::where('trade_id', $companyUserObj->company->trade_id)->active()->order()->pluck('title', 'title')
        ];
        return view($this->view_base . 'profile.company_profile', $data);
    }

    public function update_company_profile(Request $request) {
        $requestArr = $request->all();

        if ($requestArr['update_type'] == 'company_contact_info') {
            $validator = Validator::make($request->all(), [
                        //'company_website' => 'required',
                        'main_company_telephone' => 'required',
                        'company_mailing_address' => 'required',
                        //'suite' => 'required',
                        'city' => 'required',
                        'state_id' => 'required',
                        'zipcode' => 'required',
            ]);
        } else if ($requestArr['update_type'] == 'internal_contact_info') {
            $validator = Validator::make($request->all(), [
                        'internal_contact_name' => 'required',
                        'internal_contact_email' => 'required|email',
                        'internal_contact_phone' => 'required',
            ]);
        } else if ($requestArr['update_type'] == 'company_logo') {
            $validator = Validator::make($request->all(), [
                        'company_logo' => 'required|mimes:jpg,jpeg,png,gif|dimensions:max_width=' . env('MAX_LOGO_WIDTH', 200) . ',max_height=' . env('MAX_LOGO_HEIGHT', 100),
                            ], [
                        'company_logo.dimensions' => 'Company Logo dimension must not greater than 200x100 pixels'
            ]);
        }

        if (isset($validator) && $validator->fails()) {
            return back()->withErrors($validator)->withInput();
        } else {
            $web_settings = $this->web_settings;
            $companyUserObj = Auth::guard('company_user')->user();
            $companyObj = Company::find($companyUserObj->company_id);
            $companyObj->update($requestArr);

            $company_information = CompanyInformation::firstOrCreate(['company_id' => $companyObj->id]);
            $company_approval_status = CompanyApprovalStatus::firstOrCreate(['company_id' => $companyObj->id]);

            if ($requestArr['update_type'] == 'company_bio') {
                $company_approval_status->company_bio = "in process";
                $company_approval_status->save();

                /* Company Bio change mail to Company */
                $company_mail_id = "16"; /* Mail Title: Company Bio Received */
                $replaceWithArr = [
                    'company_name' => $companyObj->company_name,
                    'global_address' => ((isset($web_settings['office_address']) && $web_settings['office_address'] != '') ? $web_settings['office_address'] : ''),
                    'phone_number' => ((isset($web_settings['phone_number']) && $web_settings['phone_number'] != '') ? $web_settings['phone_number'] : ''),
                    'global_email' => ((isset($web_settings['global_email']) && $web_settings['global_email'] != '') ? $web_settings['global_email'] : ''),
                    'facebook_link' => ((isset($web_settings['facebook']) && $web_settings['facebook'] != '') ? $web_settings['facebook'] : ''),
                    'twitter_link' => ((isset($web_settings['twitter']) && $web_settings['twitter'] != '') ? $web_settings['twitter'] : ''),
                    'instagram_link' => ((isset($web_settings['instagram']) && $web_settings['instagram'] != '') ? $web_settings['instagram'] : ''),
                    'rumble_link' => ((isset($web_settings['rumble']) && $web_settings['rumble'] != '') ? $web_settings['rumble'] : ''),
                    'youtube_link' => ((isset($web_settings['youtube']) && $web_settings['youtube'] != '') ? $web_settings['youtube'] : ''),
                    'unsubscription_link' => url('unsubscribe-page/company', ['company_slug' => $companyObj->slug]),
                    'request_generate_link' => $companyUserObj->email,
                    'date' => $companyObj->created_at->format(env('DATE_FORMAT')),
                    'url' => url('company-profile'),
                    'email_footer' => $companyUserObj->email,
                    'copyright_year' => date('Y'),
                ];

                $messageArr = [
                    'company_id' => $companyObj->id,
                    'message_type' => 'info',
                    'link' => url('company-profile'),
                ];
                Custom::companyMailMessageCreate($messageArr, $company_mail_id, $replaceWithArr);
                $mailArr = Custom::generate_company_user_email_arr($companyObj->company_information);
                if (!is_null($mailArr) && count($mailArr) > 0) {
                    foreach ($mailArr AS $mail_item) {
                        Mail::to($mail_item)->send(new CompanyMail($company_mail_id, $replaceWithArr));
                    }
                }


                /* Company Bio change mail to Admin */
                if (isset($this->web_settings['global_email']) && $this->web_settings['global_email'] != '') {
                    $admin_mail_id = "17"; /* Mail Title: Company Bio Uploaded - Admin */
                    $adminReplaceWithArr = [
                        'company_name' => $companyObj->company_name,
                        'profile_url' => route('companies.edit', ['company' => $companyObj->id]) . '#company_profile',
                    ];

                    Mail::to($this->web_settings['global_email'])->send(new AdminMail($admin_mail_id, $adminReplaceWithArr));
                }
            } else if ($requestArr['update_type'] == 'company_logo') {
                $company_approval_status->company_logo = "in process";
                $company_approval_status->save();

                /* Company Logo change mail to Company */
                $company_mail_id = "18"; /* Mail Title: Company Logo received */
                $replaceWithArr = [
                    'company_name' => $companyObj->company_name,
                    'phone_number' => ((isset($web_settings['phone_number']) && $web_settings['phone_number'] != '') ? $web_settings['phone_number'] : ''),
                    'global_domain' => ((isset($web_settings['global_domain']) && $web_settings['global_domain'] != '') ? $web_settings['global_domain'] : ''),
                    'global_address' => ((isset($web_settings['office_address']) && $web_settings['office_address'] != '') ? $web_settings['office_address'] : ''),
                    'global_email' => ((isset($web_settings['global_email']) && $web_settings['global_email'] != '') ? $web_settings['global_email'] : ''),
                    'facebook_link' => ((isset($web_settings['facebook']) && $web_settings['facebook'] != '') ? $web_settings['facebook'] : ''),
                    'twitter_link' => ((isset($web_settings['twitter']) && $web_settings['twitter'] != '') ? $web_settings['twitter'] : ''),
                    'instagram_link' => ((isset($web_settings['instagram']) && $web_settings['instagram'] != '') ? $web_settings['instagram'] : ''),
                    'rumble_link' => ((isset($web_settings['rumble']) && $web_settings['rumble'] != '') ? $web_settings['rumble'] : ''),
                    'youtube_link' => ((isset($web_settings['youtube']) && $web_settings['youtube'] != '') ? $web_settings['youtube'] : ''),
                    'unsubscription_link' => url('unsubscribe-page/company', ['company_slug' => $companyObj->slug]),
                    'request_generate_link' => $companyUserObj->email,
                    'date' => $companyObj->created_at->format(env('DATE_FORMAT')),
                    'url' => url('dashboard'),
                    'email_footer' => $companyUserObj->email,
                    'copyright_year' => date('Y'),
                        //'main_service_category' => '',
                ];
                $mailArr = Custom::generate_company_user_email_arr($companyObj->company_information);
                if (!is_null($mailArr) && count($mailArr) > 0) {
                    foreach ($mailArr AS $mail_item) {
                        Mail::to($mail_item)->send(new CompanyMail($company_mail_id, $replaceWithArr));
                    }
                }

                /* Company Logo change mail to Admin */
                if (isset($this->web_settings['global_email']) && $this->web_settings['global_email'] != '') {
                    $admin_mail_id = "19"; /* Mail Title: Company Logo Uploaded - Admin */
                    $adminReplaceWithArr = [
                        'company_name' => $companyObj->company_name,
                        'profile_url' => route('companies.edit', ['company' => $companyObj->id]) . '#company_profile',
                    ];

                    Mail::to($this->web_settings['global_email'])->send(new AdminMail($admin_mail_id, $adminReplaceWithArr));
                }
            } else if ($requestArr['update_type'] == 'company_owner_info') {
                $company_information->update($requestArr);

                if (isset($requestArr['company_owner_1_full_name']) && $requestArr['company_owner_1_full_name'] != '' && isset($requestArr['company_owner_1_email']) && $requestArr['company_owner_1_email'] != '') {
                    $company_update_arr = [
                        'owner_name' => $requestArr['company_owner_1_full_name'],
                        'owner_email' => $requestArr['company_owner_1_email']
                    ];
                    $companyObj->update($company_update_arr);
                }
            } else if ($requestArr['update_type'] == 'company_contact_info') {
                $company_information->update($requestArr);
            } else if ($requestArr['update_type'] == 'internal_contact_info') {
                $company_information_update_arr = [
                    'internal_contact_fullname' => $requestArr['internal_contact_name'],
                    'internal_contact_phone' => $requestArr['internal_contact_phone'],
                    'internal_contact_email' => $requestArr['internal_contact_email'],
                ];
                $company_information->update($company_information_update_arr);
            }


            if ($request->hasFile('company_logo')) {
                $imageArr = Custom::uploadFile($request->file('company_logo'), 'company_logo');
                $companyObj->company_logo_id = $imageArr['mediaObj']->id;
                $companyObj->save();
            }


            /* Create company page screen shot start */
            Custom::createCompanyPageScreenShot($companyObj);
            /* Create company page screen shot end */

            flash('Company profile has been updated successfully')->success();
            return back();
        }
    }

    public function update_affiliations(Request $request) {
        $validator = Validator::make($request->all(), [
                    'professional_affiliations' => 'required',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        } else {
            $companyUserObj = Auth::guard('company_user')->user();
            $companyObj = Company::find($companyUserObj->company_id);

            $requestArr = $request->all();
            $requestArr['professional_affiliations'] = json_encode($requestArr['professional_affiliations']);

            $company_customer_references = CompanyCustomerReference::where('company_id', $companyObj->id)->first();
            $company_customer_references->update($requestArr);

            flash('Company Professional Affiliations has been updated successfully')->success();
            return back();
        }
    }

    public function lead_management() {
        $data = [
            'company_item' => Company::find(Auth::guard('company_user')->user()->company_id),
            'company_lead_notifications' => CompanyLeadNotification::where('company_id', Auth::guard('company_user')->user()->company_id)->latest()->first()
        ];

        return view($this->view_base . 'profile.lead_management', $data);
    }

    public function update_company_application_leads_notifications(Request $request) {
        $company_id = Auth::guard('company_user')->user()->company_id;
        $companyObj = Company::find($company_id);
        $requestArr = $request->all();

        $company_lead_notification_obj = CompanyLeadNotification::firstOrCreate(['company_id' => $company_id]);

        $updateArr = [
            'main_email_address' => $requestArr['main_email_address'],
            'owner_2' => '',
            'owner_2_name' => '',
            'owner_2_email' => '',
            'office_manager' => '',
            'office_manager_name' => '',
            'office_manager_email' => '',
            'sales_manager' => '',
            'sales_manager_name' => '',
            'sales_manager_email' => '',
            'estimators_sales_1' => '',
            'estimators_sales_1_name' => '',
            'estimators_sales_1_email' => '',
            'estimators_sales_2' => '',
            'estimators_sales_2_name' => '',
            'estimators_sales_2_email' => '',
        ];

        if ($request->has('owner_2')) {
            $updateArr['owner_2'] = $requestArr['owner_2'];
            $updateArr['owner_2_name'] = $requestArr['owner_2_name'];
            $updateArr['owner_2_email'] = $requestArr['owner_2_email'];
        }

        if ($request->has('office_manager')) {
            $updateArr['office_manager'] = $requestArr['office_manager'];
            $updateArr['office_manager_name'] = $requestArr['office_manager_name'];
            $updateArr['office_manager_email'] = $requestArr['office_manager_email'];
        }

        if ($request->has('sales_manager')) {
            $updateArr['sales_manager'] = $requestArr['sales_manager'];
            $updateArr['sales_manager_name'] = $requestArr['sales_manager_name'];
            $updateArr['sales_manager_email'] = $requestArr['sales_manager_email'];
        }

        if ($request->has('estimators_sales_1')) {
            $updateArr['estimators_sales_1'] = $requestArr['estimators_sales_1'];
            $updateArr['estimators_sales_1_name'] = $requestArr['estimators_sales_1_name'];
            $updateArr['estimators_sales_1_email'] = $requestArr['estimators_sales_1_email'];
        }

        if ($request->has('estimators_sales_2')) {
            $updateArr['estimators_sales_2'] = $requestArr['estimators_sales_2'];
            $updateArr['estimators_sales_2_name'] = $requestArr['estimators_sales_2_name'];
            $updateArr['estimators_sales_2_email'] = $requestArr['estimators_sales_2_email'];
        }

        $company_lead_notification_obj->update($updateArr);
        $data['updateArr'] = $updateArr;

        flash('Company lead notifications updated successfully')->success();
        return back();
    }

    public function service_categories() {
        $company_id = Auth::guard('company_user')->user()->company_id;
        $category_service_list = Custom::company_service_category_list($company_id);

        $data['company_service_category_list'] = $category_service_list['company_service_category_list'];
        $data['removed_company_service_category_list'] = $category_service_list['removed_company_service_category_list'];
        //dd($data);

        return view($this->view_base . 'profile.service_categories', $data);
    }

    public function update_service_category(Request $request) {
        $requestArr = $request->all();
        $returnStr = Custom::custom_update_service_category($requestArr);

        if ($returnStr) {
            flash("Service category list updated successfully")->success();
            return back();
        } else {
            return back()->withErrors($validator)->withInput();
        }
    }

    public function zip_codes() {
        $company_id = Auth::guard('company_user')->user()->company_id;
        $data['company_zip_codes'] = CompanyZipcode::where('company_id', $company_id)
        ->orderBy('distance', 'asc')
        ->get();
        return view($this->view_base . 'profile.zip_codes', $data);
    }

    public function update_company_zipcode_list(Request $request) {
        $validator = Validator::make($request->all(), [
                    'main_zipcode' => 'required',
                    'mile_range' => 'required'
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        } else {
            $requestArr = $request->all();
            $companyUserObj = Auth::guard('company_user')->user();
            $companyObj = Company::find($companyUserObj->company_id);

            if ($companyObj->main_zipcode != $requestArr['main_zipcode']) {
                try {
                    $mainZipcodeCity = Custom::getZipcodeDetail($requestArr['main_zipcode']);
                    if (count($mainZipcodeCity) > 0) {
                        $requestArr['main_zipcode_city'] = $mainZipcodeCity['city'];
                    }
                } catch (Exception $e) {
                    return 'fail';
                }
            }
            $companyObj->update($requestArr);

            try {
                $zipCodes = Custom::getZipCodeRange($companyObj->main_zipcode, $requestArr['mile_range']);

                if (count($zipCodes) > 0) {
                    $data = [];
                    $currentZipCodes = CompanyZipcode::where('company_id', $companyObj->id);
                    $oldZipCodes = $currentZipCodes->get()->toArray();
                    $currentZipCodes->delete();
                    //CompanyZipcode::where('company_id', $companyObj->id)->delete();
                    Log::channel('custom_db')->info("Company zipcode deleted for $companyObj->company_name", [
                        'data' => $data,
                        'key_identifier' =>  $companyObj->id,
                        'key_identifier_type' => KeyIdentifierType::ZipcodeUpdate
                    ]);

                    foreach ($zipCodes as $zipcode_item) {
                        $stateObj = State::where('short_name', $zipcode_item['state'])->first();
                        $insertZipcodeArr = [
                            'company_id' => $companyObj->id,
                            'zip_code' => $zipcode_item['zip_code'],
                            'distance' => $zipcode_item['distance'],
                            'city' => $zipcode_item['city'],
                            'state' => $zipcode_item['state'],
                            'state_id' => ((!is_null($stateObj)) ? $stateObj->id : null),
                        ];

                        if (isset($requestArr['zipcode_item']) && count($requestArr['zipcode_item']) > 0 && in_array($zipcode_item['zip_code'], $requestArr['zipcode_item'])) {
                            $insertZipcodeArr['status'] = 'active';
                        } else {
                            $insertZipcodeArr['status'] = 'inactive';
                        }

                        CompanyZipcode::create($insertZipcodeArr);
                    }

                    //Audit
                    $newZipCodes = CompanyZipcode::where('company_id', $companyObj->id)->get()->toArray();
                    $zipCodeChanges = $this->identifyChanges($oldZipCodes,$newZipCodes);
                    // $data["old"] = $oldZipCodes;
                    // $data["new"] = $newZipCodes;
                    $data["addedZipCodes"] = $zipCodeChanges["added"];
                    $data["removedZipCodes"] = $zipCodeChanges["removed"];
                    $data["masquerading"] = Session::has('company_mask') && Session::get('company_mask') == true;
                    Log::channel('custom_db')->info("Company zipcode updated for $companyObj->company_name", [
                        'data' => $data,
                        'key_identifier' =>  $companyObj->id,
                        'key_identifier_type' => KeyIdentifierType::ZipcodeUpdate
                    ]);
                }
            } catch (Exception $e) {
                Log::channel('custom_db')->info("Exception while updating Company zipcode for $companyObj->company_name", [
                    'data' => $e->getMessage(),
                    'key_identifier' =>  $companyObj->id,
                    'key_identifier_type' => KeyIdentifierType::ZipcodeUpdate
                ]);
                return 'fail';
            }

            flash("Zipcode list updated successfully")->success();
            return back();
        }
    }

    private function identifyChanges($old, $new) {
        $added = [];
        $removed = [];

        // Track all new zip codes
        $newZipCodes = array_map(function ($item) {
            return $item['zip_code'];
        }, $new);

        // Track all old zip codes
        $oldZipCodes = array_map(function ($item) {
            return $item['zip_code'];
        }, $old);

        // Find added zip codes
        foreach ($new as $newItem) {
            $newZipCode = $newItem['zip_code'];
            $newStatus = $newItem['status'];

            // Check if this zip code exists in old
            $index = array_search($newZipCode, $oldZipCodes);
            if ($index === false) {
                // Zip code is only in new, consider it added
                $added[] = [
                    'zip_code' => $newZipCode,
                    'city' => $newItem['city'],
                    'state' => $newItem['state'],
                    'status' => $newStatus
                ];
            } else {
                // Zip code exists in both, check status
                if ($newStatus === 'active' && $old[$index]['status'] === 'inactive') {
                    // Zip code was inactive in old but is active in new, consider it added
                    $added[] = [
                        'zip_code' => $newZipCode,
                        'city' => $newItem['city'],
                        'state' => $newItem['state'],
                        'status' => $newStatus
                    ];
                } elseif ($newStatus === 'inactive' && $old[$index]['status'] === 'active') {
                    // Zip code was active in old but is inactive in new, consider it removed
                    $removed[] = [
                        'zip_code' => $newZipCode,
                        'city' => $newItem['city'],
                        'state' => $newItem['state'],
                        'status' => $newStatus
                    ];
                }
            }
        }

        // Find removed zip codes
        foreach ($old as $oldItem) {
            $oldZipCode = $oldItem['zip_code'];
            $oldStatus = $oldItem['status'];

            // Check if this zip code exists in new
            $index = array_search($oldZipCode, $newZipCodes);
            if ($index === false) {
                // Zip code is only in old, consider it removed
                $removed[] = [
                    'zip_code' => $oldZipCode,
                    'city' => $oldItem['city'],
                    'state' => $oldItem['state'],
                    'status' => $oldStatus
                ];
            }
        }

        return [
            'added' => $added,
            'removed' => $removed
        ];
    }

    public function zipcode_list_display(Request $request) {
        if (
                ($request->has('zipcode') && $request->get('zipcode') != '') && ($request->has('mile_range') && $request->get('mile_range') != '')
        ) {
            try {
                $requestArr = $request->all();
                $company_id = Auth::guard('company_user')->user()->company_id;
                $zipCodes = Custom::getZipCodeRange($requestArr['zipcode'], $requestArr['mile_range']);
                $distances = array_column($zipCodes, 'distance');
                array_multisort($distances, SORT_ASC, $zipCodes);
                $data['inactive_company_zipcodes'] = CompanyZipcode::where([
                            ['company_id', $company_id],
                            ['status', 'inactive']
                        ])
                        ->pluck('zip_code')
                        ->toArray();

                 $data['zipcode'] = $this->removeDuplicateZipCodes($zipCodes);

                return view($this->view_base . 'profile._zipcode_list_display', $data);
            } catch (Exception $e) {
                return 'fail';
            }
        } else {
            return [
                'success' => 0,
                'message' => 'Select mile range first.'
            ];
        }
    }

    function removeDuplicateZipCodes($array) {
        $uniqueZipCodes = [];
        $seenZipCodes = [];

        foreach ($array as $item) {
            if (!in_array($item['zip_code'], $seenZipCodes)) {
                $uniqueZipCodes[] = $item;
                $seenZipCodes[] = $item['zip_code'];
            }
        }

        return $uniqueZipCodes;
    }

    public function contact_us() {
        return view($this->view_base . 'pages.contact_us');
    }

    public function update_company_subscription(Request $request) {
        $validator = Validator::make($request->all(), [
                    'sub_type' => 'required'
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        } else {
            $web_settings = $this->web_settings;

            $requestArr = $request->all();
            $companyUserObj = Auth::guard('company_user')->user();
            $companyObj = Company::find($companyUserObj->company_id);
            $companyObj->update($requestArr);

            if ($requestArr['sub_type'] == 'unsubscribe') {
                $companyObj->status = "Unsubscribed";
                $companyObj->company_subscribe_status = "unsubscribed";
                $companyObj->save();
            } else if ($requestArr['sub_type'] == 'subscribe') {
                $companyObj->status = "Subscribed";
                $companyObj->company_subscribe_status = "subscribed";
                $companyObj->save();
            }


            /* Company subscription status change mail to Company */
            if ($companyObj->membership_level_id == 1) {
                if ($requestArr['sub_type'] == 'unsubscribe') {
                    $company_mail_id = "120"; /* Mail title: Preview Trial Unsubscribed Email - Company */
                    $companyReplaceArr = [
                        'company_name' => $companyObj->company_name,
                        'upgrade_link' => url('referral-list/full-listing-more'),
                        'phone_number' => ((isset($web_settings['phone_number']) && $web_settings['phone_number'] != '') ? $web_settings['phone_number'] : ''),
                        'global_domain' => ((isset($web_settings['global_domain']) && $web_settings['global_domain'] != '') ? $web_settings['global_domain'] : ''),
                        'global_address' => ((isset($web_settings['office_address']) && $web_settings['office_address'] != '') ? $web_settings['office_address'] : ''),
                        'global_email' => ((isset($web_settings['global_email']) && $web_settings['global_email'] != '') ? $web_settings['global_email'] : ''),
                        'facebook_link' => ((isset($web_settings['facebook']) && $web_settings['facebook'] != '') ? $web_settings['facebook'] : ''),
                        'twitter_link' => ((isset($web_settings['twitter']) && $web_settings['twitter'] != '') ? $web_settings['twitter'] : ''),
                        'instagram_link' => ((isset($web_settings['instagram']) && $web_settings['instagram'] != '') ? $web_settings['instagram'] : ''),
                        'rumble_link' => ((isset($web_settings['rumble']) && $web_settings['rumble'] != '') ? $web_settings['rumble'] : ''),
                        'youtube_link' => ((isset($web_settings['youtube']) && $web_settings['youtube'] != '') ? $web_settings['youtube'] : ''),
                        'unsubscription_link' => url('unsubscribe-page/company', ['company_slug' => $companyObj->slug]),
                        'request_generate_link' => $companyUserObj->email,
                        'date' => $companyObj->created_at->format(env('DATE_FORMAT')),
                        'url' => url('dashboard'),
                        'email_footer' => $companyUserObj->email,
                        'copyright_year' => date('Y'),
                            //'main_service_category' => '',
                    ];
                } else if ($requestArr['sub_type'] == 'subscribe') {
                    $company_mail_id = "121"; /* Mail title: Preview Trial Subscribed Email - Company */
                    $companyReplaceArr = [
                        'company_name' => $companyObj->company_name,
                        'phone_number' => ((isset($web_settings['phone_number']) && $web_settings['phone_number'] != '') ? $web_settings['phone_number'] : ''),
                        'global_address' => ((isset($web_settings['office_address']) && $web_settings['office_address'] != '') ? $web_settings['office_address'] : ''),
                        'global_email' => ((isset($web_settings['global_email']) && $web_settings['global_email'] != '') ? $web_settings['global_email'] : ''),
                        'facebook_link' => ((isset($web_settings['facebook']) && $web_settings['facebook'] != '') ? $web_settings['facebook'] : ''),
                        'twitter_link' => ((isset($web_settings['twitter']) && $web_settings['twitter'] != '') ? $web_settings['twitter'] : ''),
                        'instagram_link' => ((isset($web_settings['instagram']) && $web_settings['instagram'] != '') ? $web_settings['instagram'] : ''),
                        'rumble_link' => ((isset($web_settings['rumble']) && $web_settings['rumble'] != '') ? $web_settings['rumble'] : ''),
                        'youtube_link' => ((isset($web_settings['youtube']) && $web_settings['youtube'] != '') ? $web_settings['youtube'] : ''),
                        'unsubscription_link' => url('unsubscribe-page/company', ['company_slug' => $companyObj->slug]),
                        'request_generate_link' => $companyUserObj->email,
                        'date' => $companyObj->created_at->format(env('DATE_FORMAT')),
                        'url' => url('dashboard'),
                        'email_footer' => $companyUserObj->email,
                        'copyright_year' => date('Y'),
                            //'main_service_category' => '',
                    ];
                }
            } else {


                  $company_mail_id = "35"; // Mail title: Company Subscription Status Change
                  $companyReplaceArr = [
                  'company_name' => $companyObj->company_name,
                  'subscription_status' => $requestArr['sub_type'] == 'subscribe' ? 'subscribed' : 'unsubscribed',
                  'phone_number' => ((isset($web_settings['phone_number']) && $web_settings['phone_number'] != '') ? $web_settings['phone_number'] : ''),
                  'global_address' => ((isset($web_settings['office_address']) && $web_settings['office_address'] != '') ? $web_settings['office_address'] : ''),
                  'global_email' => ((isset($web_settings['global_email']) && $web_settings['global_email'] != '') ? $web_settings['global_email'] : ''),
                  'facebook_link' => ((isset($web_settings['facebook']) && $web_settings['facebook'] != '') ? $web_settings['facebook'] : ''),
                  'twitter_link' => ((isset($web_settings['twitter']) && $web_settings['twitter'] != '') ? $web_settings['twitter'] : ''),
                  'instagram_link' => ((isset($web_settings['instagram']) && $web_settings['instagram'] != '') ? $web_settings['instagram'] : ''),
                  'rumble_link' => ((isset($web_settings['rumble']) && $web_settings['rumble'] != '') ? $web_settings['rumble'] : ''),
                  'youtube_link' => ((isset($web_settings['youtube']) && $web_settings['youtube'] != '') ? $web_settings['youtube'] : ''),
                  'unsubscription_link' => url('unsubscribe-page/company', ['company_slug' => $companyObj->slug]),
                  'request_generate_link' => $companyUserObj->email,
                  'date' => $companyObj->created_at->format(env('DATE_FORMAT')),
                  'url' => url('dashboard'),
                  'email_footer' => $companyUserObj->email,
                  'copyright_year' => date('Y'),
                  //'main_service_category' => '',
                  ];


            }


            if (isset($company_mail_id) && $company_mail_id != '') {

                $messageArr = [
                    'company_id' => $companyObj->id,
                    'message_type' => 'info',
                    'link' => url('dashboard'),
                ];
                Custom::companyMailMessageCreate($messageArr, $company_mail_id, $companyReplaceArr);

                $mailArr = Custom::generate_company_user_email_arr($companyObj->company_information);
                if (!is_null($mailArr) && count($mailArr) > 0) {
                    foreach ($mailArr AS $mail_item) {
                        //Mail::to('ajay.makwana87@gmail.com')->send(new CompanyMail($company_mail_id, $companyReplaceArr));
                        Mail::to($mail_item)->send(new CompanyMail($company_mail_id, $companyReplaceArr));
                    }
                }
            }

            /* Company subscription status change mail to Admin */
            if (isset($this->web_settings['global_email']) && $this->web_settings['global_email'] != '') {
                if ($companyObj->membership_level->paid_members == 'no' && $requestArr['sub_type'] == 'unsubscribe') {
                    $admin_mail_id = "123"; // Mail title: Company Unsubscribe Email - Admin
                    $adminReplaceArr = [
                        'company_name' => $companyObj->company_name,
                        'unsubscribe_reason' => '',
                        'unsubscribe_from' => '',
                    ];
                } else {
                    $admin_mail_id = "36"; /* Mail title: Company Subscription Status Change - Admin */
                    $adminReplaceArr = [
                        'company_name' => $companyObj->company_name,
                        'subscription_status' => $requestArr['sub_type'] == 'subscribe' ? 'subscribed' : 'unsubscribed',
                    ];
                }

                Mail::to($this->web_settings['global_email'])->send(new AdminMail($admin_mail_id, $adminReplaceArr));
            }

            flash("Subscription status updated successfully")->success();
            return back();
        }
    }

    public function update_company_lead_status(Request $request) {
        //dd($request->all());
        $validator = Validator::make($request->all(), [
                    'lead_status' => 'required'
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        } else {
            $web_settings = $this->web_settings;

            $requestArr = $request->all();
            $companyUserObj = Auth::guard('company_user')->user();
            $companyObj = Company::find($companyUserObj->company_id);

            $replaceWithArr = [
                'company_name' => $companyObj->company_name
            ];

            if ($requestArr['lead_status'] == 'Pause') {
                $tmp_pause_date = null;
                $tmp_resume_date = null;

                if ($request['lead_pause_option'] == 'today') {
                    $companyObj->lead_pause_date = \Carbon\Carbon::today()->toDateString();
                    $tmp_pause_date = \Carbon\Carbon::today();
                    $companyObj->leads_status = "inactive";
                } else {
                    $companyObj->lead_pause_date = Custom::date_formats($requestArr['lead_pause_date'], env('DATE_FORMAT'), env('DB_DATE_FORMAT'));
                    $tmp_pause_date = \Carbon\Carbon::createFromFormat(env('DATE_FORMAT'), $requestArr['lead_pause_date']);
                }


                if ($request['lead_resume_option'] == 'next_month') {
                    $companyObj->lead_resume_date = \Carbon\Carbon::now()->startOfMonth()->modify('+1 month')->toDateString();
                    $tmp_resume_date = \Carbon\Carbon::now()->startOfMonth()->modify('+1 month');
                } else {
                    $companyObj->lead_resume_date = Custom::date_formats($requestArr['lead_resume_date'], env('DATE_FORMAT'), env('DB_DATE_FORMAT'));
                    $tmp_resume_date = \Carbon\Carbon::createFromFormat(env('DATE_FORMAT'), $requestArr['lead_resume_date']);
                    ;
                }

                if ($tmp_pause_date > $tmp_resume_date) {
                    flash('Pause date must be lower then resume date')->error();
                    return back();
                }

                $companyObj->save();


                /* Company lead pause mail to Company */
                $mailCompanyObj = Company::find($companyUserObj->company_id);
                if ($request['lead_pause_option'] == 'today') {
                    $lead_pause_date = 'Immediately';
                } else {
                    $lead_pause_date = Custom::date_formats($mailCompanyObj->lead_pause_date, env('DB_DATE_FORMAT'), env('DATE_FORMAT'));
                }

                $company_mail_id = "33"; /* Mail title: Company Lead Paused */
                $companyReplaceWithArr = [
                    'company_name' => $mailCompanyObj->company_name,
                    'account_type' => $mailCompanyObj->membership_level->title,
                    'lead_pause_date' => $lead_pause_date,
                    'lead_resume_date' => Custom::date_formats($mailCompanyObj->lead_resume_date, env('DB_DATE_FORMAT'), env('DATE_FORMAT')),
                    'phone_number' => ((isset($web_settings['phone_number']) && $web_settings['phone_number'] != '') ? $web_settings['phone_number'] : ''),
                    'global_address' => ((isset($web_settings['office_address']) && $web_settings['office_address'] != '') ? $web_settings['office_address'] : ''),
                    'global_email' => ((isset($web_settings['global_email']) && $web_settings['global_email'] != '') ? $web_settings['global_email'] : ''),
                    'facebook_link' => ((isset($web_settings['facebook']) && $web_settings['facebook'] != '') ? $web_settings['facebook'] : ''),
                    'twitter_link' => ((isset($web_settings['twitter']) && $web_settings['twitter'] != '') ? $web_settings['twitter'] : ''),
                    'instagram_link' => ((isset($web_settings['instagram']) && $web_settings['instagram'] != '') ? $web_settings['instagram'] : ''),
                    'rumble_link' => ((isset($web_settings['rumble']) && $web_settings['rumble'] != '') ? $web_settings['rumble'] : ''),
                    'youtube_link' => ((isset($web_settings['youtube']) && $web_settings['youtube'] != '') ? $web_settings['youtube'] : ''),
                    'unsubscription_link' => url('unsubscribe-page/company', ['company_slug' => $mailCompanyObj->slug]),
                    'request_generate_link' => $companyUserObj->email,
                    'date' => $mailCompanyObj->created_at->format(env('DATE_FORMAT')),
                    'url' => url('dashboard'),
                    'email_footer' => $companyUserObj->email,
                    'copyright_year' => date('Y'),
                        //'main_service_category' => '',
                ];

                $messageArr = [
                    'company_id' => $mailCompanyObj->id,
                    'message_type' => 'info',
                    'link' => url('dashboard'),
                ];
                Custom::companyMailMessageCreate($messageArr, $company_mail_id, $companyReplaceWithArr);

                $service_category_arr = [];
                if (count($mailCompanyObj->service_category) > 0){
                    foreach ($mailCompanyObj->service_category AS $service_category_item){
                        $service_category_arr[] = $service_category_item->service_category->title;
                    }
                }

                /* Company lead pause mail to Admin */
                $admin_mail_id = "34"; /* Mail title: Company Lead Paused - Admin */
                $adminReplaceWithArr = [
                    'account_type' => $mailCompanyObj->membership_level->title,
                    'company_name' => $mailCompanyObj->company_name,
                    'lead_pause_date' => $lead_pause_date,
                    'lead_resume_date' => Custom::date_formats($mailCompanyObj->lead_resume_date, env('DB_DATE_FORMAT'), env('DATE_FORMAT')),
                    'internal_contact_name' => $mailCompanyObj->internal_contact_name,
                    'internal_contact_phone' => $mailCompanyObj->internal_contact_phone,
                    'main_company_telephone' => $mailCompanyObj->main_company_telephone,
                    'address' => $mailCompanyObj->company_mailing_address,
                    'city' => $mailCompanyObj->city,
                    'state' => $mailCompanyObj->state->name,
                    'zipcode' => $mailCompanyObj->main_zipcode,
                    'main_service_category' => $mailCompanyObj->main_category->title,
                    'service_category' => implode(', ', $service_category_arr),
                ];
            } else if ($requestArr['lead_status'] == 'Reactive Listing') {
                $companyObj->leads_status = "active";
                $companyObj->lead_pause_date = null;
                $companyObj->lead_resume_date = null;
                $companyObj->save();


                /* Company Activated mail to Company */
                $company_mail_id = "128"; /* Mail title: Company Membership Activated */
                $companyReplaceWithArr = [
                    'company_name' => $companyObj->company_name,
                    'account_type' => $companyObj->membership_level->title,
                    'phone_number' => ((isset($web_settings['phone_number']) && $web_settings['phone_number'] != '') ? $web_settings['phone_number'] : ''),
                    'global_address' => ((isset($web_settings['office_address']) && $web_settings['office_address'] != '') ? $web_settings['office_address'] : ''),
                    'global_email' => ((isset($web_settings['global_email']) && $web_settings['global_email'] != '') ? $web_settings['global_email'] : ''),
                    'facebook_link' => ((isset($web_settings['facebook']) && $web_settings['facebook'] != '') ? $web_settings['facebook'] : ''),
                    'twitter_link' => ((isset($web_settings['twitter']) && $web_settings['twitter'] != '') ? $web_settings['twitter'] : ''),
                    'instagram_link' => ((isset($web_settings['instagram']) && $web_settings['instagram'] != '') ? $web_settings['instagram'] : ''),
                    'rumble_link' => ((isset($web_settings['rumble']) && $web_settings['rumble'] != '') ? $web_settings['rumble'] : ''),
                    'youtube_link' => ((isset($web_settings['youtube']) && $web_settings['youtube'] != '') ? $web_settings['youtube'] : ''),
                    'unsubscription_link' => url('unsubscribe-page/company', ['company_slug' => $companyObj->slug]),
                    'request_generate_link' => $companyUserObj->email,
                    'date' => $companyObj->created_at->format(env('DATE_FORMAT')),
                    'url' => url('dashboard'),
                    'email_footer' => $companyUserObj->email,
                    'copyright_year' => date('Y'),
                        //'main_service_category' => '',
                ];

                /* Company Activated mail to Admin */
                $admin_mail_id = "32"; /* Mail title: Company Membership Activated - Admin */
                $adminReplaceWithArr = [
                    'company_name' => $companyObj->company_name,
                ];
            }


            $mailArr = Custom::generate_company_user_email_arr($companyObj->company_information);
            if (!is_null($mailArr) && count($mailArr) > 0) {
                foreach ($mailArr AS $mail_item) {
                    Mail::to($mail_item)->send(new CompanyMail($company_mail_id, $companyReplaceWithArr));
                }
            }

            if (isset($admin_mail_id) && $admin_mail_id != '') {
                if (isset($this->web_settings['global_email']) && $this->web_settings['global_email'] != '') {
                    Mail::to($this->web_settings['global_email'])->send(new AdminMail($admin_mail_id, $adminReplaceWithArr));
                }
            }

            flash("Leads status updated successfully")->success();
            return back();
        }
    }

    public function account_application() {
        return view($this->view_base . 'profile.application.index');
    }

    public function postAccountApplication(Request $request) {
        $userObj = Company::find(Auth::guard('company_user')->user()->company_id);
        $requestArr = $request->all();

        if (isset($requestArr['application']) && count($requestArr['application']) > 0) {
            CompanyApplication::where('company_id', $userObj->id)->delete();
            foreach ($requestArr['application'] as $application_key => $application_item) {
                $insertArr = [
                    'company_id' => $userObj->id,
                    'application_key' => $application_key
                ];

                if (is_file($requestArr['application'][$application_key])) {
                    $fileArr = $requestArr['application'][$application_key];
                    $imageArr = Custom::uploadFile($fileArr, 'company_application_');
                    $insertArr['application_value'] = $imageArr['mediaObj']->id;
                    $insertArr['application_value_type'] = 'File';
                } else {
                    $insertArr['application_value'] = $application_item;
                }

                CompanyApplication::create($insertArr);
            }
        }

        flash("Company Application saved successfully")->success();
        return back();
    }

    // Upload Company Document
    public function uploadCompanyDocument(Request $request) {
        if ($request->hasFile('file')) {
            $title_arr = [
                'registered_legally_to_state' => 'State Business Registration',
                'proof_of_ownership' => 'Proof Of Ownership',
                'state_licensing' => 'State Licensing',
                'country_licensing' => 'Country Licensing',
                'city_licensing' => 'City Licensing',
                'work_agreements_warranty' => 'Work Agreements Warranty',
                'subcontractor_agreement' => 'Subcontractor Agreement',
                'customer_references' => 'Customer References File',
            ];

            $companyUserObj = Auth::guard('company_user')->user();
            $companyObj = Company::find($companyUserObj->company_id);
            $document_type = $request->get('document_type');

            $mediaObj = Custom::uploadFile($request->file('file'), 'registered_legally_to_state');

            $company_document_type = $document_type;
            if ($document_type == 'work_agreements_warranty') {
                $company_document_type = 'written_warrenty';
            } else if ($document_type == 'subcontractor_agreement') {
                $company_document_type = 'subcontractor_agreement_file';
            } else if ($document_type == 'customer_references') {
                $company_document_type = 'references_form_file';
            }

            $companyDocumentObj = CompanyDocument::create([
                        'company_id' => $companyUserObj->company_id,
                        'document_type' => $company_document_type,
                        'file_id' => $mediaObj['mediaObj']->id,
                        'upload_by' => 'Company Owner',
                        'company_owner_id' => $companyUserObj->id,
            ]);
            $field = $request->get('file_field_name');

            $company_licensing_obj = CompanyLicensing::firstOrCreate(['company_id' => $companyUserObj->company_id]);

            if (!is_null($company_licensing_obj->$field)) {
                $old_document = CompanyDocument::where([
                            ['company_id', $companyObj->id],
                            ['id', $company_licensing_obj->$field]
                        ])->first();

                $get_media = Media::find($old_document->file_id);
                if (!is_null($get_media) && file_exists('uploads/media/' . $get_media->file_name)) {
                    unlink('uploads/media/' . $get_media->file_name);
                }
                $get_media->delete();
                $old_document->delete();
            }


            if ($document_type == 'customer_references') {
                $company_customer_references = CompanyCustomerReference::firstOrCreate(['company_id' => $companyUserObj->company_id]);
                $company_customer_references->$field = $companyDocumentObj->id;
                $company_customer_references->save();
            } else {
                $company_licensing_obj->$field = $companyDocumentObj->id;
                $company_licensing_obj->save();
            }


            $company_approval_status_obj = CompanyApprovalStatus::where('company_id', $companyUserObj->company_id)->first();
            $company_approval_status_obj->$document_type = 'in process';
            $company_approval_status_obj->save();

            /* Company Document received mail to Admin */
            if (isset($this->web_settings['global_email']) && $this->web_settings['global_email'] != '') {
                $admin_mail_id = "21";  /* Mail title: Company Document Uploaded - Admin */
                $adminReplaceWithArr = [
                    'company_name' => $companyObj->company_name,
                    'document_type' => $title_arr[$document_type],
                    'profile_url' => route('companies.edit', ['company' => $companyObj->id]) . '#company_documents',
                ];
                Mail::to($this->web_settings['global_email'])->send(new AdminMail($admin_mail_id, $adminReplaceWithArr));
            }

            flash($title_arr[$document_type] . ' file has been uploaded succesfully')->success();
        } else {
            flash('Document upload size 25MB maximum allowed')->error();
        }

        return back();
    }

    public function checkCompany(Request $request) {
        $company_logo_item = CompanyLogo::where('unique_key', $request->get('unique_key'))->where('url', $request->get('origin'))->first();

        if (!is_null($company_logo_item)) {
            if (!is_null($company_logo_item->company) && $company_logo_item->company->status == 'Active') {

                $show_banner = true;
                if ($company_logo_item->site_logo->banner_for == 'Founding Member' && $company_logo_item->company->is_founding_member != 'yes') {
                    $show_banner = false;
                }


                if ($show_banner) {
                    $image = $company_logo_item->site_logo->banner_url;

                    // Read image path, convert to base64 encoding
                    $imageData = base64_encode(file_get_contents($image));

                    // Format the image SRC:  data:{mime};base64,{data};
                    $src = 'data: image/png ;base64,' . $imageData;

                    return ['status' => true, 'src' => $src];
                }
            }
        } else {

        }
    }

    public function uploadCompanyLogo(Request $request) {
        $companyUserObj = Auth::guard('company_user')->user();
        $web_settings = $this->web_settings;
        $companyObj = Auth::guard('company_user')->user()->company;

        $file_name = 'abc_company_logo_' . $companyObj->id . '.png';
        $file_path = 'uploads/media/' . $file_name;

        Image::make($request->get('file'))->save($file_path);

        $mediaObj = Media::create([
                    'file_name' => $file_name,
                    'original_file_name' => $file_name,
                    'file_type' => 'image/png',
                    'file_extension' => 'png',
        ]);

        // Optimize Image
        ImageOptimizer::optimize($file_path, $file_path);
        $image_obj = Image::make($file_path);

        if (env('FIT_THUMBS') != '') {
            Custom::createThumbnails($file_path, 'fit_thumbs', $file_name, env('FIT_THUMBS'));
        }
        if (env('HEIGHT_THUMBS') != '') {
            Custom::createThumbnails($file_path, 'height_thumbs', $file_name, env('HEIGHT_THUMBS'));
        }
        if (env('WIDTH_THUMBS') != '') {
            Custom::createThumbnails($file_path, 'width_thumbs', $file_name, env('WIDTH_THUMBS'));
        }

        $companyObj->company_logo_id = $mediaObj->id;
        $companyObj->save();


        $company_approval_status_obj = CompanyApprovalStatus::where('company_id', $companyObj->id)->first();
        $company_approval_status_obj->company_logo = 'in process';
        $company_approval_status_obj->save();

        /* Company Logo change mail to Company */
        $company_mail_id = "18"; /* Mail Title: Company Logo received */
        $replaceWithArr = [
            'company_name' => $companyObj->company_name,
            'phone_number' => ((isset($web_settings['phone_number']) && $web_settings['phone_number'] != '') ? $web_settings['phone_number'] : ''),
            'global_domain' => ((isset($web_settings['global_domain']) && $web_settings['global_domain'] != '') ? $web_settings['global_domain'] : ''),
            'global_address' => ((isset($web_settings['office_address']) && $web_settings['office_address'] != '') ? $web_settings['office_address'] : ''),
            'global_email' => ((isset($web_settings['global_email']) && $web_settings['global_email'] != '') ? $web_settings['global_email'] : ''),
            'facebook_link' => ((isset($web_settings['facebook']) && $web_settings['facebook'] != '') ? $web_settings['facebook'] : ''),
            'twitter_link' => ((isset($web_settings['twitter']) && $web_settings['twitter'] != '') ? $web_settings['twitter'] : ''),
            'instagram_link' => ((isset($web_settings['instagram']) && $web_settings['instagram'] != '') ? $web_settings['instagram'] : ''),
            'rumble_link' => ((isset($web_settings['rumble']) && $web_settings['rumble'] != '') ? $web_settings['rumble'] : ''),
            'youtube_link' => ((isset($web_settings['youtube']) && $web_settings['youtube'] != '') ? $web_settings['youtube'] : ''),
            'unsubscription_link' => url('unsubscribe-page/company', ['company_slug' => $companyObj->slug]),
            'request_generate_link' => $companyUserObj->email,
            'date' => $companyObj->created_at->format(env('DATE_FORMAT')),
            'url' => url('company-profile'),
            'email_footer' => $companyUserObj->email,
            'copyright_year' => date('Y'),
                //'main_service_category' => '',
        ];

        $messageArr = [
            'company_id' => $companyObj->id,
            'message_type' => 'info',
            'link' => url('company-profile'),
        ];
        Custom::companyMailMessageCreate($messageArr, $company_mail_id, $replaceWithArr);

        $mailArr = Custom::generate_company_user_email_arr($companyObj->company_information);
        if (!is_null($mailArr) && count($mailArr) > 0) {
            foreach ($mailArr AS $mail_item) {
                //Mail::to('ajay.makwana87@gmail.com')->send(new CompanyMail($company_mail_id, $replaceWithArr));
                Mail::to($mail_item)->send(new CompanyMail($company_mail_id, $replaceWithArr));
            }
        }

        /* Company Logo change mail to Admin */
        if (isset($this->web_settings['global_email']) && $this->web_settings['global_email'] != '') {
            $admin_mail_id = "19"; /* Mail Title: Company Logo Uploaded - Admin */
            $adminReplaceWithArr = [
                'company_name' => $companyObj->company_name,
                'profile_url' => route('companies.edit', ['company' => $companyObj->id]) . '#company_profile',
            ];

            Mail::to($this->web_settings['global_email'])->send(new AdminMail($admin_mail_id, $adminReplaceWithArr));
        }

        /* Create company page screen shot start */
        Custom::createCompanyPageScreenShot($companyObj);
        /* Create company page screen shot end */

        flash('Company logo has been updated successfull')->success();
    }

    public function company_unsubscribe($company_slug) {
        $check_company = Company::whereSlug($company_slug)->first();
        if (is_null($check_company)) {
            flash("You haven't access this page.")->warning();
            return redirect('/');
        }

        if ($check_company->membership_level_id == 1 || $check_company->membership_level_id == 2 || $check_company->membership_level_id == 3) {
            $check_company->status = 'Unsubscribed';
            $check_company->save();
        } else {
            $check_company->company_subscribe_status = 'unsubscribed';
            $check_company->save();
        }

        $data = [
            'companyObj' => $check_company,
            'web_settings' => $this->web_settings
        ];

        return view($this->view_base . 'unsubscribe.unsubscribe_page', $data);
    }

    public function post_company_unsubscribe(Request $request) {
        $validator = Validator::make($request->all(), [
                    'company_slug' => 'required',
                    'why_unsubscribe' => 'required',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        } else {
            $requestArr = $request->all();
            $companyObj = Company::whereSlug($requestArr['company_slug'])->first();
            if (is_null($companyObj)) {
                flash("You haven't access this page.")->warning();
                return redirect('/');
            }
            $companyObj->update($requestArr);
            Session::put('company_id', $companyObj->id);

            flash('Unsubscribed Successfully <br /> You have been unsubscribed from all emails regarding TrustPatrick.com referral network.')->success();
            return redirect('unsubscribe-success');
        }
    }

    public function company_unsubscribe_success() {
        $company_id = Session::get('company_id');
        if ($company_id == null || $company_id == '') {
            return redirect('/');
        }

        $check_company = Company::find($company_id);
        /* if ($check_company->status != "Unsubscribed") {
          flash("You haven't access this page.")->warning();
          return redirect('/');
          } */

        $data = [
            'web_settings' => $this->web_settings,
            'companyObj' => $check_company,
        ];

        Session::forget('company_id');
        return view($this->view_base . 'unsubscribe.unsubscribe_success', $data);
    }
}

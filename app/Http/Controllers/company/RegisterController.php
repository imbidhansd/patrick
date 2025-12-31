<?php

namespace App\Http\Controllers\company;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Mail\Company\CompanyMail;
use App\Mail\Admin\AdminMail;
use App\Mail\Followup\RegisteredMemberFollowUpMail;
use Illuminate\Support\Facades\Mail;
use App\Rules\ValidRecaptcha;
use Str;
use Auth;
use Session;
use Validator;
// Models
use App\Models\Page;
use App\Models\State;
use App\Models\Custom;
use App\Models\TopLevelCategory;
use App\Models\MainCategory;
use App\Models\ServiceCategory;
use App\Models\Trade;
use App\Models\Company;
use App\Models\CompanyUser;
use App\Models\CompanyServiceCategory;
use App\Models\CompanyZipcode;
use App\Models\CompanyLeadNotification;
use App\Models\RegisterationSession;
use App\Models\CompanyInformation;
use App\Models\RegisteredMemberEmail;
use App\Models\RegisteredMemberFollowUpEmail;
use App\Models\NonMember;
use App\Models\ServiceCategoryType;
use App\Models\AffiliateMainCategory;
use App\Models\Affiliate;

class RegisterController extends Controller {

    public function __construct() {
        $this->web_settings = Custom::getSettings();
        $this->view_base = 'company.';
    }

    public function index(Request $request) {
        // redirect if already logged in
        if (Auth::guard('company_user')->check() == true) {
            return redirect('dashboard');
        }

        if (Auth::check()) {
            return redirect('admin/dashboard');
        }

        $data = [
            'admin_page_title' => 'Free Listing',
            'trades' => Trade::order()->pluck('title', 'id'),
            'states' => State::order()->pluck('name', 'id'),
            'terms_page' => Page::find('7'),
            'web_settings' => $this->web_settings
        ];

        Session::put('membership_type', 'preview_trial');
        return view($this->view_base . 'register.index', $data);
    }

    public function accreditation(Request $request) {
        // redirect if already logged in
        if (Auth::guard('company_user')->check() == true) {
            return redirect('dashboard');
        }

        if (Auth::check()) {
            return redirect('admin/dashboard');
        }

        $data = [
            'admin_page_title' => 'Accredited Member Registration',
            'trades' => Trade::order()->pluck('title', 'id'),
            'states' => State::order()->pluck('name', 'id'),
            'terms_page' => Page::find('7'),
            'web_settings' => $this->web_settings
        ];

        Session::put('membership_type', 'accreditation');
        return view($this->view_base . 'register.index', $data);
    }

    public function full_listing(Request $request) {
        // redirect if already logged in
        if (Auth::guard('company_user')->check() == true) {
            return redirect('dashboard');
        }

        if (Auth::check()) {
            return redirect('admin/dashboard');
        }
        $data = [
            'admin_page_title' => 'Generate Leads',
            'trades' => Trade::order()->pluck('title', 'id')->except([2]),
            'states' => State::order()->pluck('name', 'id'),
            'terms_page' => Page::find('7'),
            'web_settings' => $this->web_settings
        ];

        Session::put('membership_type', 'full_listing');
        return view($this->view_base . 'register.index', $data);
    }
    public function get_phone_in_lead(Request $request) {
        // redirect if already logged in
        if (Auth::guard('company_user')->check() == true) {
            return redirect('dashboard');
        }

        if (Auth::check()) {
            return redirect('admin/dashboard');
        }
        $timeframeArr = config('config.timeframe');
        $data = [
            'admin_page_title' => 'Generate Lead',
            'states' => State::order()->pluck('name', 'id'),
            'service_category_type' => ServiceCategoryType::active()->order()->pluck('title', 'id'),
            'main_category' => MainCategory::active()->order()->pluck('title', 'id'),
            'timeframe' => array_combine($timeframeArr, $timeframeArr),
            'affiliates' => Affiliate::active()->order()->pluck('domain', 'id'),
        ];
        return view($this->view_base . 'leads.phone_in_lead', $data);
    }

    public function post_phone_in_lead(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'full_name' => 'required',
            'email' => 'required|email',
            'phone' => 'required',
            'main_category_id' => 'required',
            'service_category_id' => 'required',
            'timeframe' => 'required',
            'project_address' => 'required',
            //'state_id' => 'required',
            //'city' => 'required',
            'zipcode' => 'required',
            'content' => 'required',
            'g-recaptcha-response' => ['required', new ValidRecaptcha]
        ]);

        $correlationId = Str::uuid()->toString();
        // Log::channel('custom_db')->info('Job creation started for general lead', [
        //     'data' => $request->json()->all(),
        //     'key_identifier' =>  $correlationId,
        //     'key_identifier_type' => KeyIdentifierType::GeneralLead
        // ]);

        // $data = [];
        // $data['correlation_id'] =  $correlationId;
        // $data['ip_address'] = $request->ip();
        // $data["full_name"] = $request->name;
        // $data["email"] = $request->email;
        // $data["phone"] = $request->phone;
        // $data["service_category_type_id"] = isset($request->service_type_id) ? $request->service_type_id : null;
        // $data["main_category_id"] = isset($request->main_category_id) ? $request->main_category_id : null;
        // $data["service_category_id"] = isset($request->category_id) ? $request->category_id : null;
        // $data["project_address"] = $request->address;
        // $data["timeframe"] = isset($request->timeframe)  ? self::GetTimeframe($request->timeframe) : null;
        // $data["zipcode"] = $request->zip;
        // $data["content"] = $request->additional_info;
        // $data["signup_url"] = $request->signup_url;
        // $data["api_key"] = $request->header('apikey');
        // $data["cert_url"] =  $request->cert_url;

        // Log::channel('custom_db')->info('Lead data mapped', [
        //     'data' => $data,
        //     'key_identifier' =>  $correlationId,
        //     'key_identifier_type' => KeyIdentifierType::GeneralLead
        // ]);

        // $validator = Validator::make($data, [
        //     'full_name' => 'required',
        //     'email' => 'required',
        //     'phone' => 'required',
        //     'service_category_type_id' => 'required',
        //     'main_category_id' => 'required',
        //     'service_category_id' => 'required',
        //     'project_address' => 'required',
        //     'timeframe' => 'required',
        //     'zipcode' => 'required',
        //     'content' => 'required',
        //     'api_key' => 'required'
        // ]);

        // if (isset($validator) && $validator->fails()) {
        //     $validation_message = $validator->messages()->getMessages();
        //         Log::channel('custom_db')->warning("Request validation failed", [
        //             'validation_data' =>  $validation_message,
        //             'key_identifier' =>  $correlationId,
        //             'key_identifier_type' => KeyIdentifierType::GeneralLead
        //         ]);

        //     return [
        //         'success' => 0,
        //         'message' =>$validation_message,
        //         'correlationid' => $correlationId
        //     ];
        // }
        // Log::channel('custom_db')->warning("Creating general request job", [
        //     'data' => $data,
        //     'key_identifier' =>  $correlationId,
        //     'key_identifier_type' => KeyIdentifierType::GeneralLead
        // ]);
        // ProcessGeneralRequestJob::dispatch($data);
        // return [
        //     'success' =>1,
        //     'message' => 'ProcessGeneralRequestJob job created.',
        //     'correlationid' => $correlationId
        // ];

        $successMsg = "<p><br /><br />Please note the lead reference number: <br />{$correlationId}</p>";

        $returnArr = [
            'success' => 1,
            'title' => "Lead generated successfullly!",
            'type' => 'success',
            'message' => $successMsg
        ];
        return $returnArr;
    }

    public function get_maincategories(Request $request)
    {
        $mainCategories = [];
        $validator = Validator::make($request->all(), [
            'service_category_type' => 'required',
            'source' => 'required',
        ]);

        if ($validator->fails()) {
            $returnArr = [
                'success' => 0,
                'title' => 'Error',
                'type' => 'error',
                'message' => 'Source or Service category type not found.',
            ];

            return $returnArr;
        } else {
            $requestArr = $request->all();
            $affiliate = Affiliate::where('id', $requestArr['source'])->first();
            //return  $affiliate;
            if ($affiliate && $affiliate->main_category_list->isNotEmpty()) {
                // Loop through each main category in the collection
                foreach ($affiliate->main_category_list as $mainCategory) {
                    // Extract main_category title and id
                    //return $mainCategory;
                    if($mainCategory->service_category_type_id == $requestArr['service_category_type'])
                    {
                        $mainCategories[] = [
                            'id' => $mainCategory->main_category->id,
                            'title' => $mainCategory->main_category->title,
                        ];
                    }
                }

                usort($mainCategories, function ($a, $b) {
                    return strcmp($a['title'], $b['title']);
                });
            }
        }
        $returnArr = [
            'success' => 1,
            'data' => $mainCategories,
        ];
        return $returnArr;
    }
    public function get_servicecategories(Request $request)
    {
        $serviceCategories = [];
        $validator = Validator::make($request->all(), [
            'main_category' => 'required',
            'source' => 'required',
            'service_category_type' => 'required',
        ]);

        if ($validator->fails()) {
            $returnArr = [
                'success' => 0,
                'title' => 'Error',
                'type' => 'error',
                'message' => 'Source or Service Category Type or Main Category not found.',
            ];

            return $returnArr;
        } else {
            $requestArr = $request->all();
            $services_offered= ServiceCategory::select(
                'service_categories.service_category_type_id',
                'sct.title as service_category_type',
                'service_categories.main_category_id',
                'mc.title as main_category',
                'service_categories.id  as service_category_id',
                'service_categories.title  as service_category'
                )
                ->join('service_category_types as sct', 'sct.id','service_categories.service_category_type_id')
                ->join('main_categories as mc', 'mc.id', 'service_categories.main_category_id')
                ->where('service_categories.service_category_type_id', $requestArr['service_category_type'])
                ->where('service_categories.main_category_id', $requestArr['main_category'])
                ->orderBy('sct.sort_order', 'asc')
                ->orderBy('mc.title', 'asc')
                ->orderBy('service_categories.title', 'asc')
                ->get();
            if ($services_offered && $services_offered->isNotEmpty()) {
                foreach ($services_offered as $service) {
                    $serviceCategories[] = [
                        'id' => $service->service_category_id,
                        'title' => $service->service_category,
                    ];
                }

                usort($serviceCategories, function ($a, $b) {
                    return strcmp($a['title'], $b['title']);
                });
            }
        }
        $returnArr = [
            'success' => 1,
            'data' => $serviceCategories,
        ];
        return $returnArr;
    }

    private function saveRegData($step, $requestArr) {
        $session_id = Session::getId();

        $reg_session = RegisterationSession::firstOrCreate(['session_id' => $session_id]);
        $content = json_decode($reg_session->content, true);

        $content[$step] = null;
        unset($content[$step]);

        $content[$step] = $requestArr;
        $content = json_encode($content);
        $reg_session->content = $content;
        $reg_session->registration_type = Session::get('membership_type');
        $reg_session->save();
    }

    public function postStep1(Request $request) {
        $validation_arr = [
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required',
            'con_email' => 'required',
            'username' => 'required',
            'password' => 'required',
            'confirm_password' => 'required',
        ];

        $validator = Validator::make($request->all(), $validation_arr);

        if ($validator->fails()) {
            return response()->json(['status' => '0', 'Please fill all required fields']);
        }

        // check for unique Email
        if (CompanyUser::where('email', $request->get('email'))->count() > 0) {
            return ['status' => 0, 'message' => 'Email already exists in our system<br/>Kindly try with other email address'];
        }
        // check for unique Username
        if (CompanyUser::where('username', $request->get('username'))->count() > 0) {
            return ['status' => 0, 'message' => 'Username already exists in our system<br/>Kindly try with other username'];
        }

        $this->saveRegData('step1', $request->all());
        return ['status' => 1];
    }

    public function postStep2(Request $request) {
        //dd($request->all());

        $validation_arr = [
            'company_name' => 'required',
            //'company_website' => 'required',
            'main_company_telephone' => 'required',
            'company_mailing_address' => 'required',
            //'suite' => 'required',
            'city' => 'required',
            'state_id' => 'required',
            'zipcode' => 'required',
        ];

        $validator = Validator::make($request->all(), $validation_arr);

        if ($validator->fails()) {
            return response()->json(['status' => '0', 'Please fill all required fields']);
        }

        $this->saveRegData('step2', $request->all());

        return ['status' => 1];
    }

    public function postStep3(Request $request) {
        $this->saveRegData('step3', $request->all());
        return ['status' => 1];
    }

    public function postStep4(Request $request) {
        $this->saveRegData('step4', $request->all());
        return ['status' => 1];
    }

    public function postStep5(Request $request) {
        $this->saveRegData('step5', $request->all());
        return ['status' => 1];
    }

    public function postStep6(Request $request) {
        $this->saveRegData('step6', $request->all());
        return ['status' => 1];
    }

    public function postStep7(Request $request) {
        // Google Recaptcha Check
        $validation_arr = [
            'g-recaptcha-response' => ['required', new ValidRecaptcha]
        ];

        $validator = Validator::make($request->all(), $validation_arr);
        if ($validator->fails()) {
            flash('Captcha Error, Please reload page and try again.')->error();
            return back();
        }

        /* $resultJson = Custom::check_captcha($request);
          if ($resultJson->success != true) {
          flash('Captcha Error, Please reload page and try again.')->error();
          return back();
          } */

        $this->saveRegData('step7', $request->all());
        $session_id = Session::getId();

        $reg_session = RegisterationSession::firstOrCreate(['session_id' => $session_id]);
        $content = json_decode($reg_session->content, true);

        if (!isset($content['step1']['email'])) {
            flash('Something went wrong, Please try again')->error();
            return back();
        }

        //dd($content);
        // check for unique Email
        if (CompanyUser::where('email', $content['step1']['email'])->count() > 0) {
            flash('Email already exists.')->error();
            return back();
        }

        // check for unique Username
        if (CompanyUser::where('username', $content['step1']['username'])->count() > 0) {
            flash('Username already exists')->error();
            return back();
        }

        $flash_msg_text = "You have been registered successfully";
        if ($reg_session->registration_type == 'preview_trial') {
            $membership_level_id = "1";
            $flash_msg_text = "You have been registered for Free Trial successfully";
            $url = url('preview-trial');
        } else if ($reg_session->registration_type == 'accreditation') {
            $membership_level_id = "3";
            $flash_msg_text = "You have been registered for Accreditation successfully";
            $url = url('accreditation');
        } else if ($reg_session->registration_type == 'full_listing') {
            $membership_level_id = "2";
            $flash_msg_text = "You have been registered for Full Listing successfully";
            $url = url('full-listing');
        }

        $requestArr = [
            //
            'membership_level_id' => $membership_level_id, // 1 For Free Preview Trial
            // Step 2
            'company_name' => $content['step2']['company_name'],
            'company_website' => $content['step2']['company_website'],
            'main_company_telephone' => $content['step2']['main_company_telephone'],
            'secondary_telephone' => $content['step2']['secondary_telephone'],
            'company_mailing_address' => $content['step2']['company_mailing_address'],
            'suite' => $content['step2']['suite'],
            'city' => $content['step2']['city'],
            'state_id' => $content['step2']['state_id'],
            //'county' => $content['step2']['county'],
            'zipcode' => $content['step2']['zipcode'],
            // Step 3
            'trade_id' => $content['step3']['trade_id'],
            // Step 4
            'main_category_id' => $content['step4']['main_category_id'],
            // Step 5
            'secondary_main_category_id' => isset($content['step5']['secondary_main_category_id']) ? $content['step5']['secondary_main_category_id'] : null,
            // Step 6
            'include_rest_categories' => isset($content['step6']['include_rest_categories']) ? $content['step6']['include_rest_categories'] : 'no',
            // Step 7
            'main_zipcode' => $content['step7']['main_zipcode'],
            'mile_range' => $content['step7']['mile_range'],
            // Other
            //'status' => 'Waiting for activation',
            'status' => 'Registered',
            'activation_key' => Str::random(60),
        ];

        try {
            $mainZipcodeCity = Custom::getZipcodeDetail($content['step7']['main_zipcode']);
            if (count($mainZipcodeCity) > 0) {
                $requestArr['main_zipcode_city'] = $mainZipcodeCity['city'];
            }
        } catch (Exception $e) {
            return 'fail';
        }
        $companyObj = Company::create($requestArr);

        // Add Company Lead Notification
        CompanyLeadNotification::create([
            'company_id' => $companyObj->id,
            'main_email_address' => strtolower($content['step1']['email']),
        ]);

        // Add Company User
        $company_user = CompanyUser::create([
                    'company_id' => $companyObj->id,
                    'first_name' => $content['step1']['first_name'],
                    'last_name' => $content['step1']['last_name'],
                    'email' => strtolower($content['step1']['email']),
                    'username' => $content['step1']['username'],
                    'password' => bcrypt($content['step1']['password']),
        ]);

        // Service Categories
        $service_categories = [];
        $main_sc_arr = $sub_sc_arr = $extra_sc_arr = [];
        if (isset($content['step4']['service_category_ids']) && is_array($content['step4']['service_category_ids'])) {
            $service_categories = array_merge($service_categories, $content['step4']['service_category_ids']);
            $main_sc_arr = $content['step4']['service_category_ids'];
        }
        if (isset($content['step5']['service_category_ids']) && is_array($content['step5']['service_category_ids'])) {
            $service_categories = array_merge($service_categories, $content['step5']['service_category_ids']);
            $sub_sc_arr = $content['step5']['service_category_ids'];
        }
        if (isset($content['step6']['service_category_ids']) && $content['step6']['include_rest_categories'] == 'yes' && is_array($content['step6']['service_category_ids'])) {
            $service_categories = array_merge($service_categories, $content['step6']['service_category_ids']);
            $extra_sc_arr = $content['step6']['service_category_ids'];
        }

        if (count($service_categories) > 0) {
            foreach ($service_categories as $service_category_id) {

                if ($service_category_id != '') {
                    $service_category_item = ServiceCategory::find($service_category_id);

                    if (in_array($service_category_id, $main_sc_arr)) {
                        $category_type = "main";
                    } else if (in_array($service_category_id, $sub_sc_arr)) {
                        $category_type = "sub";
                    } else if (in_array($service_category_id, $extra_sc_arr)) {
                        $category_type = "extra";
                    }

                    $insertArr = [
                        'company_id' => $companyObj->id,
                        'top_level_category_id' => $service_category_item->top_level_category_id,
                        'main_category_id' => $service_category_item->main_category_id,
                        'service_category_id' => $service_category_item->id,
                        'service_category_type_id' => $service_category_item->service_category_type_id,
                        'category_type' => $category_type
                    ];

                    //print_r($insertArr);
                    CompanyServiceCategory::create($insertArr);
                }
            }
        }


        // Zipcode Insert Process

        try {
            $zipCodes = Custom::getZipCodeRange($requestArr['zipcode'], $requestArr['mile_range']);

            if (count($zipCodes) > 0) {
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

                    CompanyZipcode::create($insertZipcodeArr);
                }
            }
        } catch (Exception $e) {
            return 'fail';
        }

        $web_settings = $this->web_settings;

        /* Registration confirmation mail to Company */
        $mail_id = "2";
        $replaceArr = [
            'company_name' => $companyObj->company_name,
            'account_type' => ucwords(str_replace("_", " ", $reg_session->registration_type)),
            'confirmation_link' => route('company-activation-link', ['activation_key' => $companyObj->activation_key]),
            'phone_number' => ((isset($web_settings['phone_number']) && $web_settings['phone_number'] != '') ? $web_settings['phone_number'] : ''),
            'global_email' => ((isset($web_settings['global_email']) && $web_settings['global_email'] != '') ? $web_settings['global_email'] : ''),
            'facebook_link' => ((isset($web_settings['facebook']) && $web_settings['facebook'] != '') ? $web_settings['facebook'] : ''),
            'twitter_link' => ((isset($web_settings['twitter']) && $web_settings['twitter'] != '') ? $web_settings['twitter'] : ''),
            'instagram_link' => ((isset($web_settings['instagram']) && $web_settings['instagram'] != '') ? $web_settings['instagram'] : ''),
            'rumble_link' => ((isset($web_settings['rumble']) && $web_settings['rumble'] != '') ? $web_settings['rumble'] : ''),
            'youtube_link' => ((isset($web_settings['youtube']) && $web_settings['youtube'] != '') ? $web_settings['youtube'] : ''),
            'unsubscription_link' => url('unsubscribe-page/company', ['company_slug' => $companyObj->slug]),
            'request_generate_link' => $company_user->email,
            'date' => $companyObj->created_at->format(env('DATE_FORMAT')),
            'url' => $url,
            'email_footer' => $company_user->email,
            'copyright_year' => date('Y'),
                //'main_service_category' => '',
        ];
        Mail::to($company_user->email)->send(new RegisteredMemberFollowUpMail($mail_id, $replaceArr));


        // Register success mail to Admin
        if (isset($web_settings['global_email']) && !is_null($web_settings['global_email'])) {
            $address_arr = [];
            if ($companyObj->company_mailing_address != '') {
                $address_arr[] = $companyObj->company_mailing_address;
            }

            if ($companyObj->suite != '') {
                $address_arr[] = $companyObj->suite;
            }

            if ($companyObj->city != '') {
                $address_arr[] = $companyObj->city;
            }


            $top_level_category_list = "";
            $top_level_categories = CompanyServiceCategory::with('top_level_category')->where('company_id', $companyObj->id)
                    ->active()
                    ->groupBy('top_level_category_id')
                    ->get();
            if (count($top_level_categories) > 0) {
                foreach ($top_level_categories AS $top_level_category_item) {
                    $top_level_category_list .= $top_level_category_item->top_level_category->title;
                }
            }


            $service_category_list = "";
            $service_categories = CompanyServiceCategory::with('service_category')->where('company_id', $companyObj->id)
                    ->active()
                    ->get();
            $data = ['service_categories' => $service_categories];
            $service_category_list = view('mails.admin.company_service_category_list', $data)->render();

            $admin_mail_id = "4"; // Mail title: Register Success Email - Admin
            $adminReplaceWithArr = [
                'account_type' => $companyObj->membership_level->title,
                'company_name' => $companyObj->company_name,
                'first_name' => $company_user->first_name,
                'last_name' => $company_user->last_name,
                'phone' => $companyObj->main_company_telephone,
                'company_user_email' => $company_user->email,
                'company_address' => implode(", ", $address_arr),
                'state' => (!is_null($companyObj->state) && $companyObj->state->short_name != '') ? $companyObj->state->short_name : '',
                'zipcode' => $companyObj->zipcode,
                'trade' => $companyObj->trade->title,
                'top_level_category' => $top_level_category_list,
                'service_categories' => $service_category_list,
                'region' => 'Main zipcode: ' . $companyObj->main_zipcode . ' Radius: ' . $companyObj->mile_range . ' Miles',
            ];
            Mail::to($web_settings['global_email'])->send(new AdminMail($admin_mail_id, $adminReplaceWithArr));
        }

        // Add Company Application Item [Start]
        $company_information_obj = CompanyInformation::firstOrCreate(['company_id' => $companyObj->id]);

        $company_information_obj->update([
            'legal_company_name' => $requestArr['company_name'],
            'main_company_telephone' => $requestArr['main_company_telephone'],
            'website' => $requestArr['company_website'],
            'mailing_address' => $requestArr['company_mailing_address'],
            'suite' => $requestArr['suite'],
            'city' => $requestArr['city'],
            'state_id' => $requestArr['state_id'],
            //'county' => $requestArr['county'],
            'zipcode' => $requestArr['zipcode'],
            //
            'company_owner_1_full_name' => $company_user->first_name . ' ' . $company_user->last_name,
            'company_owner_1_email' => $company_user->email,
            'company_owner_1_user_id' => $company_user->id,
            'company_owner_1_status' => 'registered',
        ]);


        /* Delete Registration Session */
        $reg_session->delete();


        /* check the company is available in non members */
        $non_member_company = NonMember::where('company_name', $companyObj->company_name)->first();
        if (!is_null($non_member_company)) {
            $non_member_company->delete();
        }
        // Add Company Application Item [End]

        flash($flash_msg_text)->success();
        return redirect(url('thankyou'));
    }

    public function checkAvailableEmail(Request $request) {
        $user_obj = CompanyUser::whereEmail($request->get('email'))->first();
        if ($user_obj) {
            return [
                'status' => '0',
                'message' => 'Email Already Exists with our system',
            ];
        } else {
            return [
                'status' => '1',
                'message' => '',
            ];
        }
    }

    public function checkAvailableUsername(Request $request) {
        $user_obj = CompanyUser::whereUsername($request->get('username'))->first();
        if ($user_obj) {
            return [
                'status' => '0',
                'message' => 'Username Already Exists with our system',
            ];
        } else {
            return [
                'status' => '1',
                'message' => '',
            ];
        }
    }

    /* Activate Company Account */

    public function activateAccount($activation_key) {
        // Find Company User With activation_key

        $companyObj = Company::with('membership_level')->where('activation_key', $activation_key)->first();

        if (is_null($companyObj)) {
            flash('Company not found with specified activation key')->error();
            return redirect(url('login'));
        }


        $companyObj->activation_key = null;
        $companyObj->activated_at = \Carbon\Carbon::now()->format(env('DB_DATETIME_FORMAT'));
        $companyObj->status = 'Subscribed';
        $companyObj->save();

        // Registration confirmation success mail to Company
        $companyUserObj = CompanyUser::where([
                    ['company_id', $companyObj->id],
                    ['company_user_type', 'company_super_admin']
                ])->first();
        $web_settings = $this->web_settings;

        if ($companyObj->membership_level_id == '1') {
            $url = url('preview-trial');
        } else if ($companyObj->membership_level_id == '2') {
            $url = url('full-listing');
        } else if ($companyObj->membership_level_id == '3') {
            $url = url('accreditation');
        }

        $mail_id = "2"; // Mail title: Register Confirmation Success Email
        $replaceWithArr = [
            'first_name' => $companyUserObj->first_name,
            'account_type' => $companyObj->membership_level->title,
            'user_name' => $companyUserObj->username,
            'user_email' => $companyUserObj->email,
            'login_link' => url('login'),
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
            'url' => $url,
            'email_footer' => $companyUserObj->email,
            'copyright_year' => date('Y'),
                //'main_service_category' => '',
        ];

        $messageArr = [
            'company_id' => $companyObj->id,
            'message_type' => 'info',
            'link' => $url,
        ];

        Custom::companyMailMessageCreate($messageArr, $mail_id, $replaceWithArr);
        Mail::to($companyUserObj->email)->send(new CompanyMail($mail_id, $replaceWithArr));


        // Create Folloup Email Schedule [Start]
        $follow_up_email_list = RegisteredMemberEmail::where([
                    ['status', 'active'],
                    ['email_type', 'followup_email']
                ])->get();

        if (!is_null($follow_up_email_list) && count($follow_up_email_list) > 0) {
            foreach ($follow_up_email_list as $follow_up_email_item) {
                $next_min = \Carbon\Carbon::now()->addMinute(1);
                if ($follow_up_email_item->send_time != '') {
                    $send_time_arr = explode(' ', $follow_up_email_item->send_time);

                    if ($send_time_arr['1'] == 'Seconds') {
                        $send_at = $next_min->addSeconds($send_time_arr['0'])->format('Y-m-d H:i:00');
                    } elseif ($send_time_arr['1'] == 'Minutes') {
                        $send_at = $next_min->addMinutes($send_time_arr['0'])->format('Y-m-d H:i:00');
                    } elseif ($send_time_arr['1'] == 'Hours') {
                        $send_at = $next_min->addHours($send_time_arr['0'])->format('Y-m-d H:i:00');
                    } elseif ($send_time_arr['1'] == 'Days') {
                        $send_at = $next_min->addDays($send_time_arr['0'])->format('Y-m-d H:i:00');
                    }
                } else {
                    $send_at = $next_min->format('Y-m-d H:i:00');
                }


                $arr = [
                    'company_id' => $companyObj->id,
                    'reg_mem_email_id' => $follow_up_email_item->id,
                    'send_at' => $send_at,
                ];

                RegisteredMemberFollowUpEmail::create($arr);
            }
        }

        /* Create company page screen shot start */
        Custom::createCompanyPageScreenShot($companyObj);
        /* Create company page screen shot end */

        // Create Folloup Email Schedule [End]
        return redirect('activation-complete');
    }

    public function thankyou() {
        return view('company.register.thankyou');
    }

    public function activationComplete() {
        return view('company.register.activation_complete');
    }

    public function getTopLevelCategoryList(Request $request) {
        $top_level_categories = null;
        // Find Top Level Categories from trade_id

        if ($request->has('trade_id') && $request->get('trade_id') > 0) {
            $top_level_categories = TopLevelCategory::active()
                    ->leftJoin('top_level_category_trades', 'top_level_category_trades.top_level_category_id', '=', 'top_level_categories.id')
                    ->where('top_level_category_trades.trade_id', $request->get('trade_id'))
                    //->orderBy('top_level_category_trades.sort_order', 'ASC')
                    ->orderBy('top_level_categories.title', 'ASC')
                    ->select('top_level_categories.title', 'top_level_categories.id')
                    ->get();
        }

        $selected_top_level_category_ids = [];
        if (Auth::guard('company_user')->check()) {
            $companyObj = Company::find(Auth::guard('company_user')->user()->company_id);

            $top_level_category_ids = CompanyServiceCategory::where('company_id', $companyObj->id)->distinct()->pluck('top_level_category_id');
            if (!is_null($top_level_category_ids)) {
                $selected_top_level_category_ids = $top_level_category_ids->toArray();
            } else {
                $selected_top_level_category_ids = [];
            }
        }

        $data = [
            'top_level_categories' => $top_level_categories,
            'selected_top_level_category_ids' => $selected_top_level_category_ids,
            'show_back_btn' => $request->has('show_back_btn') ? true : false,
        ];
        return view('company.register.step3_top_level_categories', $data);
    }

    public function getMainCategoryList(Request $request) {
        $main_categories = null;
        if ($request->has('top_level_category_ids') && count($request->get('top_level_category_ids')) > 0) {

            $query = MainCategory::leftJoin('main_category_top_level_categories', 'main_category_top_level_categories.main_category_id', '=', 'main_categories.id')
                    ->whereIn('main_category_top_level_categories.top_level_category_id', $request->get('top_level_category_ids'))
                    ->active()
                    //->orderBy('main_category_top_level_categories.sort_order', 'ASC')
                    ->orderBy('main_categories.title', 'ASC')
                    ->select('main_categories.*');

            if ($request->has('main_category_id') && $request->get('main_category_id') > 0) {
                $data['show_none'] = true;
                $query->where('main_categories.id', '!=', $request->get('main_category_id'));
            }

            $main_categories = $query->get();

            if (is_null($main_categories) || count($main_categories) <= 0) {
                return 'false';
            }

            $selected_category_ids = [];
            if (Auth::guard('company_user')->check()) {
                $companyObj = Company::find(Auth::guard('company_user')->user()->company_id);
                $selected_category_ids[] = $companyObj->main_category_id;
                if ($request->has('main_category_id') && $request->get('main_category_id') > 0) {
                    $selected_category_ids[] = $companyObj->secondary_main_category_id;
                }
            }

            $data['main_categories'] = $main_categories;
            $data['selected_category_ids'] = $selected_category_ids;

            return view('company.register.step4_main_categories', $data);
        }
    }

    public function getRestCategoryList(Request $request) {
        $service_categories = null;

        if ($request->has('main_category_id') && $request->get('main_category_id') != '') {

            $query = ServiceCategory::whereIn('top_level_category_id', $request->get('top_level_category_ids'))
                    ->active()
                    ->orderBy('service_category_type_id', 'ASC')
                    ->orderBy('main_category_id', 'ASC')
                    ->orderBy('title', 'ASC');

            if ($request->has('main_category_id') && $request->get('main_category_id') > 0) {
                $query->where('main_category_id', '!=', $request->get('main_category_id'));
            }

            if ($request->has('secondary_main_category_id') && $request->get('secondary_main_category_id') > 0) {
                $query->where('main_category_id', '!=', $request->get('secondary_main_category_id'));
            }

            $service_categories = $query->get();

            $service_category_arr = [];

            if (!is_null($service_categories)) {
                foreach ($service_categories as $service_category_item) {
                    $service_category_arr[$service_category_item->service_category_type_id]['service_category_type_id'] = $service_category_item->service_category_type_id;
                    $service_category_arr[$service_category_item->service_category_type_id]['service_category_type_title'] = $service_category_item->service_category_type->title;
                    $service_category_arr[$service_category_item->service_category_type_id]['main_category'][$service_category_item->main_category_id]['main_category_id'] = $service_category_item->main_category_id;
                    $service_category_arr[$service_category_item->service_category_type_id]['main_category'][$service_category_item->main_category_id]['main_category_title'] = $service_category_item->main_category->title;
                    $service_category_arr[$service_category_item->service_category_type_id]['main_category'][$service_category_item->main_category_id]['service_categories'][] = $service_category_item;
                }
            }

            $selected_service_category_ids = [];
            if (Auth::guard('company_user')->check()) {
                $companyObj = Company::find(Auth::guard('company_user')->user()->company_id);

                $selected_service_cats = CompanyServiceCategory::where('company_id', $companyObj->id)->pluck('service_category_id');
                if (!is_null($selected_service_cats) && count($selected_service_cats) > 0) {
                    $selected_service_category_ids = $selected_service_cats->toArray();
                }
            }

            $data = [
                'service_categories' => $service_categories,
                'service_category_arr' => $service_category_arr,
                'selected_service_category_ids' => $selected_service_category_ids,
            ];

            return view('company.register.step6_rest_service_categories', $data);
        }
    }

    public function getServiceCategoryList(Request $request) {
        $service_categories = null;

        if ($request->has('main_category_id') && $request->get('main_category_id') != '') {

            $service_categories = ServiceCategory::where('main_category_id', $request->get('main_category_id'))
                    ->active()
                    ->orderBy('service_category_type_id', 'ASC')
                    ->orderBy('sort_order', 'ASC')
                    ->get();

            $service_category_arr = [];

            if (!is_null($service_categories)) {
                foreach ($service_categories as $service_category_item) {
                    $service_category_arr[$service_category_item->service_category_type_id]['service_category_type_id'] = $service_category_item->service_category_type_id;
                    $service_category_arr[$service_category_item->service_category_type_id]['service_category_type_title'] = $service_category_item->service_category_type->title;
                    $service_category_arr[$service_category_item->service_category_type_id]['main_category'][$service_category_item->main_category_id]['main_category_id'] = $service_category_item->main_category_id;
                    $service_category_arr[$service_category_item->service_category_type_id]['main_category'][$service_category_item->main_category_id]['main_category_title'] = $service_category_item->main_category->title;
                    $service_category_arr[$service_category_item->service_category_type_id]['main_category'][$service_category_item->main_category_id]['service_categories'][] = $service_category_item;
                }
            }

            //dd($service_category_arr);

            $selected_service_category_ids = [];
            if (Auth::guard('company_user')->check()) {
                $companyObj = Company::find(Auth::guard('company_user')->user()->company_id);

                $selected_service_cats = CompanyServiceCategory::where('company_id', $companyObj->id)->pluck('service_category_id');
                if (!is_null($selected_service_cats) && count($selected_service_cats) > 0) {
                    $selected_service_category_ids = $selected_service_cats->toArray();
                }
            }

            $data = [
                'service_categories' => $service_categories,
                'service_category_arr' => $service_category_arr,
                'selected_service_category_ids' => $selected_service_category_ids,
                'step_no' => $request->has('step_no') ? $request->get('step_no') : 'def-step',
            ];

            return view('company.register._service_categories', $data);
        }
    }

}

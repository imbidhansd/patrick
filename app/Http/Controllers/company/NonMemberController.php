<?php

namespace App\Http\Controllers\company;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Mail\Followup\NonMemberFollowUpMail;
use App\Mail\Admin\AdminMail;
use Illuminate\Support\Facades\Mail;
use App\Rules\ValidRecaptcha;
use DB;
use Str;
use Validator;
use Session;
use App\Models\State;
use App\Models\ServiceCategoryType;
use App\Models\TopLevelCategory;
use App\Models\NonMember;
use App\Models\NonMemberZipcode;
use App\Models\NonMemberTopLevelCategory;
use App\Models\MainCategory;
use App\Models\AffiliateMainCategory;
use App\Models\NonMemberEmail;
use App\Models\NonMemberFollowUpEmail;
use App\Models\Custom;

class NonMemberController extends Controller {

    public function __construct() {
        $this->web_settings = Custom::getSettings();
        $this->view_base = 'company.non_members.';

        // Common Model
        $this->modelObj = new NonMember;

        $this->how_did_you_hear_about_us = [
            'Radio',
            'Youtube Show Consumers Corner',
            'Online Search Engine',
            'TV',
            'Customer',
        ];
    }

    public function index() {
        $trades = [
            '1' => 'Home/Commercial Property Improvement Contractors',
            '2' => 'Professionals - Professional Services'
        ];
        $data = [
            'admin_page_title' => 'Get Listed',
            'states' => State::active()->order()->pluck('name', 'id'),
            //'trades' => Trade::active()->order()->pluck('title', 'id'),
            'trades' => $trades,
            'service_category_types' => ServiceCategoryType::where('id', '!=', '3')->active()->order()->pluck('title', 'id'),
            'how_did_you_hear_about_us' => array_combine($this->how_did_you_hear_about_us, $this->how_did_you_hear_about_us),
            'web_settings' => $this->web_settings
        ];

        return view($this->view_base . 'index', $data);
    }

    public function postGetListed(Request $request) {
        if ($request->has('re_register') && $request->get('re_register') == 'no') {
            $emailValidator = Validator::make($request->all(), [
                        'email' => 'required',
            ]);

            if ($emailValidator->fails()) {
                $errorMessage = "";
                foreach ($emailValidator->messages()->getMessages() as $field_name => $messages) {
                    foreach ($messages AS $message_item) {
                        $errorMessage .= $message_item . '<br />';
                    }
                }

                return [
                    'success' => 0,
                    'title' => 'Warning',
                    'type' => 'warning',
                    'message' => $errorMessage
                ];
            }


            $emailUniqueValidator = Validator::make($request->all(), [
                        'email' => 'unique:non_members,email',
            ]);

            if ($emailUniqueValidator->fails()) {
                $errorMessage = '<br />Looks like you registered with this email address previously. <br /><br />What would you like to do? <br /><br />Re-register with the same email address or enter a different email address?';
                return [
                    'success' => 2,
                    'title' => 'Oops!',
                    'type' => 'warning',
                    'message' => $errorMessage
                ];
            }
        } else if ($request->has('re_register') && $request->get('re_register') == 'yes') {
            $emailValidator = Validator::make($request->all(), [
                        'email' => 'required',
            ]);

            if ($emailValidator->fails()) {
                $errorMessage = "";
                foreach ($emailValidator->messages()->getMessages() as $field_name => $messages) {
                    foreach ($messages AS $message_item) {
                        $errorMessage .= $message_item . '<br />';
                    }
                }

                return [
                    'success' => 0,
                    'title' => 'Warning',
                    'type' => 'warning',
                    'message' => $errorMessage
                ];
            } else {
                $this->modelObj->where('email', $request->get('email'))->delete();
            }
        }

        $validator = Validator::make($request->all(), [
                    'first_name' => 'required',
                    'last_name' => 'required',
                    'company_name' => 'required',
                    'phone' => 'required',
                    'address' => 'required',
                    'city' => 'required',
                    'state_id' => 'required',
                    'zipcode' => 'required',
                    'mile_range' => 'required',
                    'trade_id' => 'required',
                    'top_level_categories' => 'required',
                    'how_did_you_hear_about_us' => 'required',
                    'g-recaptcha-response' => ['required', new ValidRecaptcha]
        ]);

        if (isset($validator) && $validator->fails()) {
            $errorMessage = "";
            foreach ($validator->messages()->getMessages() as $field_name => $messages) {
                foreach ($messages AS $message_item) {
                    $errorMessage .= $message_item . '<br />';
                }
            }

            return [
                'success' => 0,
                'title' => 'Warning',
                'type' => 'warning',
                'message' => $errorMessage
            ];
        } else {
            $requestArr = $request->all();
            $requestArr['activation_key'] = Str::random(60);
            if (isset($requestArr['top_level_categories']) && count($requestArr['top_level_categories']) > 0) {
                $top_level_categories = $requestArr['top_level_categories'];
                $requestArr['top_level_categories'] = implode(",", $requestArr['top_level_categories']);
            }

            if ($requestArr['service_category_type_id'] == 'both') {
                $requestArr['service_category_type_id'] = null;
            }

            $itemObj = $this->modelObj->create($requestArr);

            if (isset($top_level_categories) && count($top_level_categories) > 0) {
                foreach ($top_level_categories AS $top_level_category_id) {
                    $insertArr = [
                        'non_member_id' => $itemObj->id,
                        'top_level_category_id' => $top_level_category_id
                    ];
                    NonMemberTopLevelCategory::create($insertArr);
                }
            }

            if (isset($requestArr['zipcode']) && $requestArr['zipcode'] != '' && isset($requestArr['mile_range']) && $requestArr['mile_range'] != '') {
                try {
                    $zipCodes = Custom::getZipCodeRange($requestArr['zipcode'], $requestArr['mile_range']);
                    if (count($zipCodes) > 0) {
                        foreach ($zipCodes as $zipcode_item) {
                            $stateObj = State::where('short_name', $zipcode_item['state'])->first();
                            $insertZipcodeArr = [
                                'non_member_id' => $itemObj->id,
                                'zipcode' => $zipcode_item['zip_code'],
                                'distance' => $zipcode_item['distance'],
                                'city' => $zipcode_item['city'],
                                'state' => $zipcode_item['state'],
                                'state_id' => ((!is_null($stateObj)) ? $stateObj->id : null),
                            ];

                            NonMemberZipcode::create($insertZipcodeArr);
                        }
                    }
                } catch (Exception $e) {
                    return 'fail';
                }
            }


            /* Get listed company activation email to consumer */
            $mail_id = "2"; /* Mail title: Non Member Activation Email */
            $web_settings = $this->web_settings;
            $replaceArr = [
                'company_name' => $itemObj->company_name,
                'first_name' => $itemObj->first_name,
                'confirmation_link' => url('/get-listed/activation/' . $itemObj->activation_key),
                'phone_number' => ((isset($web_settings['phone_number']) && $web_settings['phone_number'] != '') ? $web_settings['phone_number'] : ''),
                'global_email' => ((isset($web_settings['global_email']) && $web_settings['global_email'] != '') ? $web_settings['global_email'] : ''),
                'global_address' => ((isset($web_settings['office_address']) && $web_settings['office_address'] != '') ? $web_settings['office_address'] : ''),
                'facebook_link' => ((isset($web_settings['facebook']) && $web_settings['facebook'] != '') ? $web_settings['facebook'] : ''),
                'twitter_link' => ((isset($web_settings['twitter']) && $web_settings['twitter'] != '') ? $web_settings['twitter'] : ''),
                'instagram_link' => ((isset($web_settings['instagram']) && $web_settings['instagram'] != '') ? $web_settings['instagram'] : ''),
                'rumble_link' => ((isset($web_settings['rumble']) && $web_settings['rumble'] != '') ? $web_settings['rumble'] : ''),
                'youtube_link' => ((isset($web_settings['youtube']) && $web_settings['youtube'] != '') ? $web_settings['youtube'] : ''),
                'unsubscription_link' => url('get-listed/unsubscribe-page/', ['company_id' => $itemObj->id]),
                'request_generate_link' => $itemObj->email,
                'date' => $itemObj->created_at->format(env('DATE_FORMAT')),
                'url' => url('get-listed'),
                'email_footer' => $itemObj->email,
                'copyright_year' => date('Y'),
                    //'main_service_category' => '',
            ];
            Mail::to($itemObj->email)->send(new NonMemberFollowUpMail($mail_id, $replaceArr));


            /* Register success mail to Admin */
            if (isset($this->web_settings['global_email']) && !is_null($this->web_settings['global_email'])) {
                $admin_mail_id = "118"; /* Mail title: Non Member Register Confirmation Success Email - Admin */

                $top_level_category_list = "";
                if (!is_null($itemObj->top_level_categories)) {
                    $top_level_categories = TopLevelCategory::whereIn('id', explode(',', $itemObj->top_level_categories))
                            ->active()
                            ->get();

                    if (count($top_level_categories) > 0) {
                        foreach ($top_level_categories AS $top_level_category_item) {
                            $top_level_category_list .= $top_level_category_item->title . ',';
                        }
                    }
                }


                if ($itemObj->trade_id == '1') {
                    if (!is_null($itemObj->service_category_type_id)) {
                        $service_type = $itemObj->service_category_type->title;
                    } else {
                        $service_type = 'Both';
                    }
                } else {
                    $service_type = '';
                }

                $stateObj = State::find($requestArr['state_id']);
                $adminReplaceArr = [
                    'company_name' => $itemObj->company_name,
                    'first_name' => $itemObj->first_name,
                    'last_name' => $itemObj->last_name,
                    'email' => $itemObj->email,
                    'phone' => $itemObj->phone,
                    'address' => $itemObj->address,
                    'city' => $itemObj->city,
                    'state' => $stateObj->name,
                    'zipcode' => $itemObj->zipcode,
                    'zipcode_range' => $itemObj->mile_range . ' Mile',
                    'service_provider' => $itemObj->trade->title,
                    'service_offered' => rtrim($top_level_category_list, ','),
                    'service_type' => $service_type,
                    'how_did_you_hear_about_us' => (!is_null($itemObj->how_did_you_hear_about_us) ? $itemObj->how_did_you_hear_about_us : ''),
                    'comment' => (!is_null($itemObj->comments) ? $itemObj->comments : ''),
                ];
                Mail::to($this->web_settings['global_email'])->send(new AdminMail($admin_mail_id, $adminReplaceArr));
            }

            $successMsg = '<p>Important Please check your email inbox <br /><br />We just sent an email confirmation to <a href="mailto:' . $requestArr['email'] . '">' . $requestArr['email'] . '</a>. If you entered the wrong email address, please close this window and resubmit the form. <br /><br />Thank you for visiting TrustPatrick.com</p>';

            return [
                'success' => 1,
                'title' => "You're Almost Finished!",
                'type' => 'success',
                'message' => $successMsg
            ];
        }
    }

    public function activateAccount($activation_key) {
        $companyObj = $this->modelObj->where([
                    ['activation_key', $activation_key],
                    ['status', 'pending']
                ])->first();

        if (is_null($companyObj)) {
            flash("Company not found with specified activation key")->error();
        } else {
            $companyObj->activation_key = null;
            $companyObj->activation_date = now()->format(env('DB_DATE_FORMAT'));
            $companyObj->status = 'active';
            $companyObj->save();

            // Create Folloup Email Schedule [Start]
            $follow_up_email_list = NonMemberEmail::where([
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
                        'non_member_id' => $companyObj->id,
                        'non_member_email_id' => $follow_up_email_item->id,
                        'send_at' => $send_at,
                    ];

                    NonMemberFollowUpEmail::create($arr);
                }
            }

            // Create Folloup Email Schedule [End]
            flash("Your listing is activated.")->success();
        }

        //return redirect('get-listed');
        return redirect('https://opp.trustpatrick.com/');
    }

    /* Ajax methods start */

    public function get_top_level_categories(Request $request) {
        $validator = Validator::make($request->all(), [
                    'trade_id' => 'required',
        ]);

        if (isset($validator) && $validator->fails()) {
            return [
                'success' => 0,
                'title' => 'Warning',
                'type' => 'warning',
                'message' => 'Select Trade first.'
            ];
        } else {
            $requestArr = $request->all();

            if (isset($requestArr['edit_form']) && $requestArr['edit_form'] != ''){
                $data['selected_top_level_categories'] = NonMemberTopLevelCategory::where('non_member_id', $requestArr['non_member_id'])->pluck('top_level_category_id')->toArray();
            }

            if (isset($requestArr['configure_form']) && $requestArr['configure_form'] != ''){
                $data['selected_top_level_categories'] = AffiliateTopLevelCategory::where('affiliate_id', $requestArr['affiliate_id'])->pluck('top_level_category_id')->toArray();
            }

            $data['top_level_categories'] = TopLevelCategory::leftJoin('top_level_category_trades', 'top_level_categories.id', 'top_level_category_trades.top_level_category_id')
                    ->where('top_level_category_trades.trade_id', $requestArr['trade_id'])
                    ->active()
                    ->order()
                    ->pluck('top_level_categories.title', 'top_level_categories.id');

            return view($this->view_base . '_top_level_categories', $data);
        }
    }

    public function get_main_categories(Request $request) {

        $validator = Validator::make($request->all(), [
                    'service_category_type_id' => 'required',
        ]);

        if (isset($validator) && $validator->fails()) {
            return [
                'success' => 0,
                'title' => 'Warning',
                'type' => 'warning',
                'message' => 'Select Service Category Type first.'
            ];
        } else {
            $requestArr = $request->all();

            if (isset($requestArr['configure_form']) && $requestArr['configure_form'] != ''){
                $data['selected_main_categories'] = AffiliateMainCategory::where('affiliate_id', $requestArr['affiliate_id'])
                ->select(DB::raw("CONCAT(main_category_id, '-', service_category_type_id) AS new_id"))
                ->pluck('new_id')->toArray();
            }

            $data['main_categories'] = $result = DB::table('main_categories AS MC')
            ->leftJoin('service_categories AS SC', 'SC.main_category_id', '=', 'MC.id')
            ->join('service_category_types AS SCT', 'SCT.id', '=', 'SC.service_category_type_id')
            ->whereIn('SC.service_category_type_id', $requestArr['service_category_type_id'])
            ->orderByDesc('title')
            ->select(DB::raw("DISTINCT CONCAT(MC.id, '-', SC.service_category_type_id) AS id, CONCAT(MC.title, '-',
                CASE
                    WHEN SCT.title = 'Residential' THEN 'RES'
                    WHEN SCT.title = 'Commercial' THEN 'COM'
                    ELSE 'Unknown'
                END) AS title"))
            ->pluck('title','id');


            return view($this->view_base . '_main_categories', $data);
        }
    }

    public function company_unsubscribe($company_id) {
        $check_company = $this->modelObj->active()->find($company_id);
        if (is_null($check_company)) {
            flash("You haven't access this page.")->warning();
            return redirect('/');
        }

        $check_company->subscribe_status = 'unsubscribed';
        $check_company->save();

        $data = [
            'companyObj' => $check_company,
            'web_settings' => $this->web_settings
        ];

        return view($this->view_base . 'unsubscribe_page', $data);
    }

    public function post_company_unsubscribe(Request $request) {
        $validator = Validator::make($request->all(), [
                    'company_id' => 'required',
                    'why_unsubscribe' => 'required',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        } else {
            $requestArr = $request->all();
            $companyObj = $this->modelObj->active()->find($requestArr['company_id']);
            if (is_null($companyObj)) {
                flash("You haven't access this page.")->warning();
                return redirect('/');
            }
            $companyObj->update($requestArr);
            Session::put('company_id', $companyObj->id);

            flash('Unsubscribed Successfully <br /> You have been unsubscribed from all emails regarding TrustPatrick.com referral network.')->success();
            return redirect('get-listed/unsubscribe-success');
        }
    }

    public function company_unsubscribe_success() {
        $company_id = Session::get('company_id');
        if ($company_id == null || $company_id == '') {
            return redirect('/');
        }

        $check_company = $this->modelObj->active()->find($company_id);
        if (is_null($check_company)) {
            flash("You haven't access this page.")->warning();
            return redirect('/');
        }

        $data = [
            'web_settings' => $this->web_settings
        ];

        Session::forget('company_id');
        return view($this->view_base . 'unsubscribe_success', $data);
    }

}

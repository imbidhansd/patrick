<?php

namespace App\Http\Controllers\leads;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Mail\Admin\AdminMail;
use Illuminate\Support\Facades\Mail;
use Validator;
use Session;
use App\Models\Lead;
use App\Models\Trade;
use App\Models\ServiceCategoryType;
use App\Models\TopLevelCategory;
use App\Models\MainCategory;
use App\Models\ServiceCategory;
use App\Models\LeadFollowUpEmail;
use App\Models\Custom;

class LeadController extends Controller {

    public function __construct() {
        $this->web_settings = Custom::getSettings();
        $this->view_base = 'leads.';
    }

    public function find_a_pro() {
        $data = [
            'trades' => Trade::active()->order()->pluck('title', 'id'),
            'web_settings' => $this->web_settings
        ];
        return view($this->view_base . 'find_a_pro', $data);
    }

    public function generate_lead(Request $request) {
        //dd($request->all());

        $validator = Validator::make($request->all(), [
                    'full_name' => 'required',
                    'email' => 'required',
                    'phone' => 'required',
                    'trade_id' => 'required',
                    'main_category_id' => 'required',
                    //'service_category_type_id' => 'required',
                    'service_category_id' => 'required',
                    'timeframe' => 'required',
                    'project_address' => 'required',
                    //'state_id' => 'required',
                    //'city' => 'required',
                    'zipcode' => 'required',
                    'content' => 'required',
        ]);

        if (isset($validator) && $validator->fails()) {
            return back()->withErrors($validator)->withInput();
        } else {
            $requestArr = $request->all();
            $requestArr['lead_activation_key'] = Custom::getRandomString(50);
            $requestArr['ip_address'] = $request->ip();
            $lead = Lead::create($requestArr);

            // Send confirmation email to consumer
            Custom::lead_confirmation_email($lead);
            Custom::generateCompanyLeads($lead);


            $lead_counter = Custom::get_number_of_companies_who_get_leads($lead);
            if (
                    (isset($this->web_settings['sent_to_networx']) && $this->web_settings['sent_to_networx'] == 'yes') &&
                    $lead->trade_id == 1 &&
                    $lead_counter <= 0
            ) {
                $networx_response = Custom::networxCall($lead);
                if ($networx_response['statusCode'] == '200') {
                    $lead->networx_code = $networx_response['successCode'];
                    $lead->save();
                }
            }

            Custom::lead_email_admin($lead);

            flash("Your lead is generated successfully. Check email for activate lead.")->success();
            return redirect('find-a-pro');
        }
    }

    public function activate_lead($activation_key, Request $request) {
        $lead = Lead::where([
                    ['lead_activation_key', $activation_key],
                    ['lead_activated', 'no']
                ])->with(['follow_up_mail_category', 'follow_up_mail_category.follow_up_emails'])->first();

        if (!is_null($lead)) {
            $lead->lead_activation_key = null;
            $lead->lead_active_date = \Carbon\Carbon::now()->format(env('DB_DATETIME_FORMAT'));
            $lead->lead_activated = 'yes';
            $lead->subscribe = 'yes';
            $lead->subscribe_at = \Carbon\Carbon::now()->format(env('DB_DATETIME_FORMAT'));
            $lead->save();

            // Create Folloup Email Schedule [Start]

            if ($lead->follow_up_mail_category_id > 0 && !is_null($lead->follow_up_mail_category->follow_up_emails)) {
                foreach ($lead->follow_up_mail_category->follow_up_emails as $follow_up_email_item) {

                    if ($follow_up_email_item->status == 'active') {

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
                            'lead_id' => $lead->id,
                            'follow_up_mail_category_id' => $lead->follow_up_mail_category_id,
                            'follow_up_email_id' => $follow_up_email_item->id,
                            'email_for' => $follow_up_email_item->email_for,
                            'send_at' => $send_at,
                        ];

                        LeadFollowUpEmail::create($arr);
                    }
                }
            }

            flash("Your lead is activated successfully.")->success();
        } else {
            flash("Lead not found.")->error();
        }

        return redirect('/lead-activated');
    }

    public function lead_unsubscribe($lead_id) {
        $lead = Lead::find($lead_id);

        if (is_null($lead)) {
            flash("Lead not found.")->error();
            return redirect('/');
        } else {
            $data = [
                'lead' => $lead,
                'web_settings' => $this->web_settings
            ];

            return view($this->view_base . '.lead_unsubscribe', $data);
        }
    }

    public function lead_unsubscribe_first_step(Request $request) {
        $validator = Validator::make($request->all(), [
                    'lead_id' => 'required',
                    'regarding_your_request' => 'required',
                    'special_offers' => 'required',
                    'scams_updates' => 'required',
                    'general_updates' => 'required',
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
            $email_sent = true;
            $requestArr = $request->all();
            $lead = Lead::find($requestArr['lead_id']);
            if ($lead->regarding_your_request == $requestArr['regarding_your_request'] && $lead->special_offers == $requestArr['special_offers'] && $lead->scams_updates == $requestArr['scams_updates'] && $lead->general_updates == $requestArr['general_updates']) {
                $email_sent = false;
            }

            $lead->update($requestArr);

            if ($lead->regarding_your_request == 'unsubscribe' && $lead->special_offers == 'unsubscribe' && $lead->scams_updates == 'unsubscribe' && $lead->general_updates == 'unsubscribe') {
                $lead->subscribe = 'no';
                $lead->unsubscribe_at = now()->format(env('DB_DATETIME_FORMAT'));
                $lead->save();
            } else {
                $lead->subscribe = 'yes';
                $lead->subscribe_at = now()->format(env('DB_DATETIME_FORMAT'));
                $lead->save();
            }


            if ($email_sent) {
                /* Lead Unsubscribe Email - Admin */
                if (isset($this->web_settings['global_email']) && $this->web_settings['global_email'] != '') {
                    $unsubscribe_from = "";

                    if ($lead->regarding_your_request == 'unsubscribe') {
                        $unsubscribe_from = "Regarding Your Request, ";
                    }

                    if ($lead->special_offers == 'unsubscribe') {
                        $unsubscribe_from .= "Special Promotions/Offers, ";
                    }

                    if ($lead->scams_updates == 'unsubscribe') {
                        $unsubscribe_from .= "Scams & Ripoffs Updates, ";
                    }

                    if ($lead->general_updates == 'unsubscribe') {
                        $unsubscribe_from .= "General Updates, ";
                    }


                    $admin_mail_id = "122"; /* Mail title: Lead Unsubscribe Email - Admin */
                    $replaceWithArr = [
                        'customer_name' => $lead->full_name,
                        'customer_email' => $lead->email,
                        'unsubscribe_from' => rtrim($unsubscribe_from, ', '),
                    ];

                    Mail::to($this->web_settings['global_email'])->send(new AdminMail($admin_mail_id, $replaceWithArr));
                }
            }


            return [
                'success' => 1
            ];
        }
    }

    public function post_lead_unsubscribe(Request $request) {
        $validator = Validator::make($request->all(), [
                    'lead_id' => 'required',
        ]);

        if (isset($validator) && $validator->fails()) {
            return back()->withErrors($validator)->withInput();
        } else {
            $email_sent = true;
            $requestArr = $request->all();
            $lead = Lead::find($requestArr['lead_id']);

            if ($requestArr['why_unsubscribe'] != 'other' && $lead->why_unsubscribe == $requestArr['why_unsubscribe']) {
                $email_sent = false;
            }

            $lead->update($requestArr);

            if ($email_sent) {
                /* Lead Unsubscribe Email - Admin */
                if (isset($this->web_settings['global_email']) && $this->web_settings['global_email'] != '') {
                    $unsubscribe_reason = "";

                    if ($lead->why_unsubscribe == 'other') {
                        $unsubscribe_reason = $lead->unsubscribe_reason;
                    } else if ($lead->why_unsubscribe == 'no_longer_want_to_receive_emails') {
                        $unsubscribe_reason = "I no longer want to receive these emails";
                    } else if ($lead->why_unsubscribe == 'never_signup_for_mailing_list') {
                        $unsubscribe_reason = "I never signed up for this mailing list";
                    } else if ($lead->why_unsubscribe == 'emails_inappropriate') {
                        $unsubscribe_reason = "The emails are inappropriate";
                    } else if ($lead->why_unsubscribe == 'emails_spam_reported') {
                        $unsubscribe_reason = "The emails are spam and should be reported";
                    }


                    $admin_mail_id = "129"; /* Mail title: Lead Unsubscribe Email - Admin */
                    $replaceWithArr = [
                        'customer_name' => $lead->full_name,
                        'customer_email' => $lead->email,
                        'unsubscribe_reason' => $unsubscribe_reason,
                    ];

                    Mail::to($this->web_settings['global_email'])->send(new AdminMail($admin_mail_id, $replaceWithArr));
                }
            }


            Session::put('lead_id', $lead->id);
            return redirect('/leads/unsubscribe-success');
        }
    }

    public function unsubscribe_success() {
        $lead_id = Session::get('lead_id');
        $lead = Lead::find($lead_id);

        if (is_null($lead)) {
            flash("You haven't access this page.")->warning();
            return redirect('/');
        }

        $data = [
            'web_settings' => $this->web_settings,
            'lead' => $lead
        ];

        Session::forget('lead_id');
        return view($this->view_base . 'lead_unsubscribe_success', $data);
    }

    /* Ajax Call methods */

    public function get_service_category_types(Request $request) {
        $data = [
            'service_category_types' => ServiceCategoryType::where('id', '!=', '3')->active()->order()->pluck('title', 'id')
        ];

        return view($this->view_base . '_service_category_types', $data);
    }

    public function get_top_level_categories(Request $request) {
        $top_level_categories = TopLevelCategory::active()->order()->groupBy('top_level_categories.id');
        if ($request->has('trade_id') && $request->get('trade_id') != '') {
            $top_level_categories->leftJoin('top_level_category_trades', 'top_level_categories.id', 'top_level_category_trades.top_level_category_id')
                    ->join('main_category_top_level_categories', 'top_level_categories.id', 'main_category_top_level_categories.top_level_category_id')
                    ->join('service_categories', 'top_level_categories.id', 'service_categories.top_level_category_id')
                    ->where('top_level_category_trades.trade_id', $request->get('trade_id'));
        }

        $data = [
            'top_level_categories' => $top_level_categories->pluck('top_level_categories.title', 'top_level_categories.id')->toArray()
        ];

        return view($this->view_base . '_top_level_categories', $data);
    }

    public function get_category_selection(Request $request) {
        $main_categories = MainCategory::active()->order()->groupBy('main_categories.id');
        if ($request->has('top_level_category_id') && $request->get('top_level_category_id') != '') {
            $main_categories->leftJoin('main_category_top_level_categories', 'main_categories.id', 'main_category_top_level_categories.main_category_id')
                    ->join('service_categories', 'main_categories.id', 'service_categories.main_category_id')
                    ->where('main_category_top_level_categories.top_level_category_id', $request->get('top_level_category_id'));
        }

        $timeframeArr = config('config.timeframe');
        $data = [
            'main_categories' => $main_categories->pluck('main_categories.title', 'main_categories.id')->toArray(),
            'timeframe' => array_combine($timeframeArr, $timeframeArr)
        ];

        return view($this->view_base . '_category_selection', $data);
    }

    public function get_service_categories(Request $request) {
        $service_category = ServiceCategory::active()->order();

        if ($request->has('top_level_category_id') && $request->get('top_level_category_id') != '') {
            $service_category->where('top_level_category_id', $request->get('top_level_category_id'));
        }

        if ($request->has('service_category_type_id') && $request->get('service_category_type_id') != '') {
            $service_category->where('service_category_type_id', $request->get('service_category_type_id'));
        }

        if ($request->has('main_category_id') && $request->get('main_category_id') != '') {
            $service_category->where('main_category_id', $request->get('main_category_id'));
        }

        $data['service_category'] = $service_category->pluck('title', 'id');
        return view($this->view_base . '_service_category_dropdown', $data);
    }

    public function lead_activated(Request $request) {
        return view($this->view_base . 'lead_activated');
    }

}

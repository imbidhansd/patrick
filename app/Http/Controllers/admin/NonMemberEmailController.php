<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use View;
use Validator;
use Str;
// Models [start]
use App\Models\Custom;
use App\Models\NonMember;
use App\Models\NonMemberFollowUpEmail;
use App\Models\DefaultEmailHeaderFooter;

class NonMemberEmailController extends Controller {

    public function __construct() {
        $segment = \Request::segment(2);
        if ($segment == 're-order') {
            $segment = \Request::segment(3);
        }

        $url_key = $segment;
        $module_display_name = Str::singular(ucwords(str_replace('_', ' ', $segment)));

        // Links
        $this->urls = Custom::getModuleUrls($url_key);

        // Common Model
        if ($module_display_name != '') {
            $model_name = '\\App\\Models\\' . str_replace(' ', '', $module_display_name);
            $this->modelObj = new $model_name;
        }

        //Post Types
        $this->post_type = $url_key;
        $module_display_name = "Non Member Follow Up Emails";

        // Module Message
        $this->module_messages = Custom::getModuleFlashMessages($module_display_name);

        // Singular and Plural Name of Module
        $this->singular_display_name = Str::singular($module_display_name);
        $this->module_plural_name = Str::plural($module_display_name);

        $this->common_data = [
            'module_singular_name' => $this->singular_display_name,
            'module_plural_name' => $this->module_plural_name,
            'url_key' => $url_key,
            'module_urls' => $this->urls,
        ];

        View::share($this->common_data);

        // View
        $this->view_base = 'admin.' . $url_key;
    }

    public function index() {
        $admin_page_title = 'Manage ' . $this->module_plural_name;

        $data = [
            'admin_page_title' => $admin_page_title,
            'confirmation_email' => $this->modelObj->where('email_type', 'confirmation_email')->latest()->first(),
            'followup_emails' => $this->modelObj->where('email_type', 'followup_email')->order()->get(),
        ];

        return view($this->view_base . '.index', $data);
    }

    public function create() {
        $data = [
            'admin_page_title' => 'Create ' . $this->singular_display_name,
            //'default_email' => DefaultEmail::active()->order()->first(),
            'header_emails' => DefaultEmailHeaderFooter::emailtype('header')->pluck('title', 'id'),
            'footer_emails' => DefaultEmailHeaderFooter::emailtype('footer')->pluck('title', 'id'),
        ];

        return view($this->view_base . '.create', $data);
    }

    public function store(Request $request) {
        //dd($request->all());
        $validator = Validator::make($request->all(), [
                    'title' => 'required',
                    'from_email_address' => 'required',
                    'subject' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect($this->urls['add'])->withErrors($validator)->withInput();
        } else {
            $requestArr = $request->all();
            $requestArr['send_time'] = "";

            if (isset($requestArr['sendtime']) && $requestArr['sendtime'] != '') {
                $requestArr['send_time'] .= $requestArr['sendtime'];
            }

            if (isset($requestArr['sendtime_selection']) && $requestArr['sendtime_selection'] != '') {
                $requestArr['send_time'] .= ' ' . $requestArr['sendtime_selection'];
            }

            $requestArr['email_type'] = 'followup_email';
            $itemObj = $this->modelObj->create($requestArr);

            /* Create New Non Member Follow Up Email [Start] */

            // Step 1. Get All Leads which have same follow_up_mail_category_id

            $non_member_list = NonMember::where([
                        ['status', 'active']
                    ])->get();


            // Step 2. Add new LeadFollowUpEmail records

            if (!is_null($non_member_list) && count($non_member_list) > 0) {
                foreach ($non_member_list as $non_member_item) {

                    $next_min = \Carbon\Carbon::parse($non_member_item->activation_date)->addMinute(1);

                    if ($itemObj->send_time != '') {
                        $send_time_arr = explode(' ', $itemObj->send_time);

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
                        'non_member_id' => $non_member_item->id,
                        'non_member_email_id' => $itemObj->id,
                        'send_at' => $send_at,
                    ];

                    NonMemberFollowUpEmail::create($arr);
                }
            }

            /* Create New Non Member Follow Up Email [End] */

            flash($this->module_messages['add'])->success();
            return redirect($this->urls['list']);
        }
    }

    public function edit($id) {
        $formObj = $this->modelObj->findOrFail($id);

        if ($formObj->send_time != '') {
            $send_time = explode(' ', $formObj->send_time);
            $formObj->sendtime = $send_time[0];
            if (isset($send_time[1]) && $send_time[1] != '') {
                $formObj->sendtime_selection = $send_time[1];
            }
        }

        $data = [
            'admin_page_title' => 'Edit ' . $this->singular_display_name,
            'formObj' => $formObj,
            'header_emails' => DefaultEmailHeaderFooter::emailtype('header')->pluck('title', 'id'),
            'footer_emails' => DefaultEmailHeaderFooter::emailtype('footer')->pluck('title', 'id'),
        ];

        return view($this->view_base . '.edit', $data);
    }

    public function update(Request $request, $id) {
        $itemObj = $this->modelObj->findOrFail($id);

        $temp_send_time = $itemObj->send_time;


        $validator = Validator::make($request->all(), [
                    'title' => 'required',
                    'from_email_address' => 'required',
                    'subject' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect(route($this->urls['edit'], [$this->urls['url_key_singular'] => $id]))
                            ->withErrors($validator)
                            ->withInput();
        } else {
            $requestArr = $request->all();
            $requestArr['send_time'] = "";
            if (isset($requestArr['sendtime']) && $requestArr['sendtime'] != '') {
                $requestArr['send_time'] .= $requestArr['sendtime'];
            }

            if (isset($requestArr['sendtime_selection']) && $requestArr['sendtime_selection'] != '') {
                $requestArr['send_time'] .= ' ' . $requestArr['sendtime_selection'];
            }

            $itemObj->update($requestArr);

            
            if ($temp_send_time != $itemObj->send_time) {
                /* Update Lead Follow Up Email's Send At Field [Start] */

                // Step 1. Get All Lead Follow Up Emails which have same follow_up_email_id

                $non_member_follow_up_email_list = NonMemberFollowUpEmail::where([
                            ['non_member_email_id', $itemObj->id],
                            ['status', 'pending']
                        ])->with(['non_member'])->get();


                // Step 2. Reset Send At for all lead_follow_up_email_list

                if (!is_null($non_member_follow_up_email_list) && count($non_member_follow_up_email_list) > 0) {
                    foreach ($non_member_follow_up_email_list as $non_member_follow_up_email_item) {


                        if (!is_null($non_member_follow_up_email_item->non_member)) {


                            $next_min = \Carbon\Carbon::parse($non_member_follow_up_email_item->non_member->activation_date)->addMinute(1);


                            if ($itemObj->send_time != '') {
                                $send_time_arr = explode(' ', $itemObj->send_time);

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


                            $non_member_follow_up_email_item->send_at = $send_at;
                            $non_member_follow_up_email_item->save();
                        }
                    }
                }

                /* Update Lead Follow Up Email's Send At Field */
            }

            flash($this->module_messages['update'])->success();
            return redirect($this->urls['list']);
        }
    }

    public function destroy($id) {
        $modelObj = $this->modelObj->findOrFail($id);
        $modelObjTemp = $modelObj;
        try {
            $modelObj->delete();
            flash($this->module_messages['delete'])->warning();
            return back();
        } catch (Exception $e) {
            flash($this->module_messages['delete_error'])->danger();
            return back();
        }
    }

}

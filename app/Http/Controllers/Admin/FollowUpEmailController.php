<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use View;
use Validator;
use Str;
// Models [start]
use App\Models\Custom;
use App\Models\Trade;
use App\Models\Lead;
use App\Models\LeadFollowUpEmail;
use App\Models\FollowUpMailCategory;
use App\Models\DefaultEmailHeaderFooter;

class FollowUpEmailController extends Controller {

    public function __construct(Request $request) {
        if ($request->has('follow_up_mail_category_id')) {
            $this->follow_up_mail_category_id = $request->get('follow_up_mail_category_id');
        }

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

        // Module Message
        $this->module_messages = Custom::getModuleFlashMessages($module_display_name);

        // Singular and Plural Name of Module
        $this->singular_display_name = Str::singular($module_display_name);
        $this->module_plural_name = Str::plural($module_display_name);

        $subscription_type = [
            'regarding_your_request' => 'Regarding Your Request',
            'special_offers' => 'Special Promotions/Offers',
            'scams_updates' => 'Scams & Ripoffs Updates',
            'general_updates' => 'General Updates',
        ];

        $this->common_data = [
            'module_singular_name' => $this->singular_display_name,
            'module_plural_name' => $this->module_plural_name,
            'url_key' => $url_key,
            'module_urls' => $this->urls,
            'trades' => Trade::with('follow_up_mail_category')->active()->order()->get(),
            'subscription_type' => $subscription_type,
        ];

        View::share($this->common_data);

        // View
        $this->view_base = 'admin.' . $url_key;
    }

    public function index(Request $request) {
        $admin_page_title = 'Manage ' . $this->module_plural_name;
        if ($request->has('trade_id') && $request->get('trade_id') != '') {
            $trade_id = $request->get('trade_id');
        } else {
            $trade_id = 1;
        }

        $data = [
            'admin_page_title' => $admin_page_title,
            'trade_item' => Trade::with('follow_up_mail_category')->find($trade_id)
        ];

        return view($this->view_base . '.index', $data);
    }

    public function create() {
        $data = [
            'admin_page_title' => 'Create ' . $this->singular_display_name,
            'follow_up_mail_category_id' => $this->follow_up_mail_category_id,
            'header_emails' => DefaultEmailHeaderFooter::emailtype('header')->pluck('title', 'id'),
            'footer_emails' => DefaultEmailHeaderFooter::emailtype('footer')->pluck('title', 'id'),
        ];
        return view($this->view_base . '.create', $data);
    }

    public function store(Request $request) {
        //dd($request->all());
        $validator = Validator::make($request->all(), [
                    //'title' => 'required',
                    'from_email_address' => 'required',
                    //'subscription_type' => 'required',
                    'subject' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect($this->urls['add'])
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

            $follow_up_mail_cateogry = FollowUpMailCategory::find($requestArr['follow_up_mail_category_id']);
            $requestArr['trade_id'] = $follow_up_mail_cateogry->trade_id;
            $itemObj = $this->modelObj->create($requestArr);


            /* Create New Lead Follow Up Email [Start] */

            // Step 1. Get All Leads which have same follow_up_mail_category_id

            $lead_list = Lead::where([
                        ['follow_up_mail_category_id', $requestArr['follow_up_mail_category_id']],
                        ['lead_activated', 'yes']
                    ])->get();


            // Step 2. Add new LeadFollowUpEmail records

            if (!is_null($lead_list) && count($lead_list) > 0) {
                foreach ($lead_list as $lead_item) {

                    //$next_min = \Carbon\Carbon::parse($lead_item->lead_active_date)->addMinute(1);
                    $next_min = \Carbon\Carbon::parse($lead_item->created_at)->addMinute(1);


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
                        'lead_id' => $lead_item->id,
                        'follow_up_mail_category_id' => $lead_item->follow_up_mail_category_id,
                        'follow_up_email_id' => $itemObj->id,
                        'email_for' => $itemObj->email_for,
                        'send_at' => $send_at,
                    ];

                    LeadFollowUpEmail::create($arr);
                }
            }
            /* Create New Lead Follow Up Email [End] */

            flash($this->module_messages['add'])->success();
            return redirect($this->urls['list'] . '?trade_id=' . $itemObj->trade_id);
        }
    }

    public function edit($id) {
        //dd(\Carbon\Carbon::now());
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
            'follow_up_mail_category_id' => $formObj->follow_up_mail_category_id,
            'header_emails' => DefaultEmailHeaderFooter::emailtype('header')->pluck('title', 'id'),
            'footer_emails' => DefaultEmailHeaderFooter::emailtype('footer')->pluck('title', 'id'),
        ];

        $data['formObj'] = $formObj;
        $data['follow_up_mail_category_id'] = $formObj->follow_up_mail_category_id;
        return view($this->view_base . '.edit', $data);
    }

    public function update($id, Request $request) {
        $itemObj = $this->modelObj->findOrFail($id);
        $temp_send_time = $itemObj->send_time;

        $validator = Validator::make($request->all(), [
                    //'title' => 'required',
                    'from_email_address' => 'required',
                    //'subscription_type' => 'required',
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

                $lead_follow_up_email_list = LeadFollowUpEmail::where([
                            ['follow_up_email_id', $itemObj->id],
                            ['status', 'pending']
                        ])->with(['lead'])->get();


                // Step 2. Reset Send At for all lead_follow_up_email_list

                if (!is_null($lead_follow_up_email_list) && count($lead_follow_up_email_list) > 0) {
                    foreach ($lead_follow_up_email_list as $lead_follow_up_email_item) {


                        if (!is_null($lead_follow_up_email_item->lead)) {


                            //$next_min = \Carbon\Carbon::parse($lead_follow_up_email_item->lead->lead_active_date)->addMinute(1);
                            $next_min = \Carbon\Carbon::parse($lead_follow_up_email_item->lead->created_at)->addMinute(1);


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



                            $lead_follow_up_email_item->send_at = $send_at;
                            $lead_follow_up_email_item->save();
                        }
                    }
                }

                /* Update Lead Follow Up Email's Send At Field */
            }

            flash($this->module_messages['update'])->success();
            return redirect($this->urls['list'] . '?trade_id=' . $itemObj->trade_id);
        }
    }

    public function destroy(Request $request, $id) {
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

    public function reorder() {
        $data['admin_page_title'] = 'Reorder ' . $this->module_plural_name;
        $data['item_list'] = $this->modelObj->
                where([
                    ['follow_up_mail_category_id', $this->follow_up_mail_category_id],
                    ['email_type', 'followup_email']
                ])
                ->order()
                ->get();

        return view($this->view_base . '.reorder', $data);
    }

    public function updateOrder(Request $request) {
        if ($request->has('items') && count($request->get('items')) > 0) {
            $counter = 1;
            foreach ($request->get('items') as $item) {
                $this->modelObj::where('id', $item)->update(['sort_order' => $counter++]);
            }
        }
    }

}

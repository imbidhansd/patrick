<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;
use View;
use Str;
// Models [start]
use App\Models\Lead;
use App\Models\LeadFollowUpEmail;
use App\Models\Custom;

class LeadSubscriberController extends Controller {

    public function __construct() {
        $this->modelObj = new Lead;

        //Post Types
        $this->post_type = 'leads';

        // View
        $this->view_base = 'admin.leads.subscribers.';

        $this->common_data = [
            'module_singular_name' => 'Manage Subscribers',
            'module_plural_name' => 'Manage Subscribers',
            'module_urls' => [
                'url_key' => 'manage_subscribers',
                'url_key_singular' => Str::singular('manage_subscribers'),
                'list' => url('admin/manage_subscribers'),
                'reorder' => '',
            ],
        ];

        View::share($this->common_data);
    }

    public function index(Request $request) {
        $list_params = Custom::getListParams($request);
        $rows = $this->modelObj->getSubscriberList($list_params);

        if (count($rows) <= 0 && $request->has('page') && $request->get('page') > 1) {
            $list_params['page'] = $rows->lastPage();
            return redirect($this->urls['list'] . http_build_query($list_params));
        }

        $data = [
            'admin_page_title' => 'Manage Subscribers',
            'rows' => $rows,
            'list_params' => $list_params,
            'searchColumns' => $this->modelObj->searchColumns,
        ];

        return view($this->view_base . 'index', $data);
    }

    public function update_basic_info(Request $request) {
        $validator = Validator::make($request->all(), [
                    'lead_id' => 'required',
                    'ad_tracking' => 'required',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        } else {
            $requestArr = $request->all();
            $lead = $this->modelObj->find($requestArr['lead_id']);

            $lead->update($requestArr);

            flash('Subscriber basic info updated successfully.')->success();
            return back();
        }
    }

    public function send_confirmation_email(Request $request) {
        $validator = Validator::make($request->all(), [
                    'lead_id' => 'required',
        ]);

        if ($validator->fails()) {
            $messageArr = [];
            foreach ($validator->messages()->getMessages() AS $key => $value) {
                $messageArr[$key] = $value[0];
            }

            return [
                'success' => 0,
                'title' => 'Error',
                'type' => 'error',
                'message' => implode("<br />", $messageArr),
            ];
        } else {
            $requestArr = $request->all();
            $lead = $this->modelObj->find($requestArr['lead_id']);

            Custom::lead_confirmation_email($lead);

            return [
                'success' => 1,
                'title' => 'Success',
                'type' => 'success',
                'message' => 'Confirmation email send successfully.'
            ];
        }
    }

    public function send_followup_email(Request $request) {
        $validator = Validator::make($request->all(), [
                    'followup_email_id' => 'required',
        ]);

        if ($validator->fails()) {
            $messageArr = [];
            foreach ($validator->messages()->getMessages() AS $key => $value) {
                $messageArr[$key] = $value[0];
            }

            return [
                'success' => 0,
                'title' => 'Error',
                'type' => 'error',
                'message' => implode("<br />", $messageArr),
            ];
        } else {
            $requestArr = $request->all();
            $follow_up_emails = LeadFollowUpEmail::find($requestArr['followup_email_id']);

            Custom::lead_followup_email($follow_up_emails);

            $follow_up_emails->status = 'sent';
            $follow_up_emails->send_at = now()->format(env('DB_DATETIME_FORMAT'));
            $follow_up_emails->save();

            return [
                'success' => 1,
                'title' => 'Success',
                'type' => 'success',
                'message' => 'Confirmation email send successfully.'
            ];
        }
    }

}

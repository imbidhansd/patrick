<?php

namespace App\Http\Controllers\Admin;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Mail\Admin\AdminMail;
use App\Mail\Company\CompanyMail;
use Illuminate\Support\Facades\Mail;
use View;
use Validator;
use Str;
// Models [start]
use App\Models\Custom;
use App\Models\CompanyUser;
use App\Models\CompanyLead;
use App\Models\MainCategory;
use App\Models\ServiceCategoryType;
use App\Models\ServiceCategory;
use App\Models\State;
use App\Models\CustomLog;

class LeadController extends Controller {

    public function __construct() {
        $this->web_settings = Custom::getSettings();
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

        $timeframeArr = config('config.timeframe');
        $this->common_data = [
            'module_singular_name' => $this->singular_display_name,
            'module_plural_name' => $this->module_plural_name,
            'url_key' => $url_key,
            'module_urls' => $this->urls,
            'service_category_type' => ServiceCategoryType::active()->order()->pluck('title', 'id'),
            'main_category' => MainCategory::active()->order()->pluck('title', 'id'),
            'states' => State::order()->pluck('name', 'id'),
            'timeframe' => array_combine($timeframeArr, $timeframeArr),
        ];

        View::share($this->common_data);

        // View
        $this->view_base = 'admin.' . $url_key;
    }

    public function index(Request $request) {
        $list_params = Custom::getListParams($request);
        $admin_page_title = 'Manage ' . $this->module_plural_name;
        $rows = $this->modelObj->getAdminList($list_params);

        if (count($rows) <= 0 && $request->has('page') && $request->get('page') > 1) {
            $list_params['page'] = $rows->lastPage();
            return redirect($this->urls['list'] . http_build_query($list_params));
        }

        $service_category_options = [];
        if (isset($list_params['search']['leads.main_category_id']) && $list_params['search']['leads.main_category_id'] > 0) {
            $service_category_options = ServiceCategory::where('main_category_id', $list_params['search']['leads.main_category_id'])->order()->pluck('title', 'id');
        }

        $data = [
            'admin_page_title' => $admin_page_title,
            'rows' => $rows,
            'list_params' => $list_params,
            'searchColumns' => $this->modelObj->searchColumns,
            'with_date' => 1,
            'action_arr' => Custom::getActionArr($this->common_data['url_key']),
            'search' => [
                'leads.service_category_type_id' => [
                    'title' => 'Service Category Type',
                    'options' => $this->common_data['service_category_type'],
                    'id' => 'service_category_type_id'
                ],
                'leads.main_category_id' => [
                    'title' => 'Main Category',
                    'options' => $this->common_data['main_category'],
                    'id' => 'main_category_id'
                ],
                'leads.service_category_id' => [
                    'title' => 'Service Category',
                    'options' => $service_category_options,
                    'id' => 'service_category_id'
                ],
                'leads.state_id' => [
                    'title' => 'State',
                    'options' => $this->common_data['states'],
                    'id' => 'state_id'
                ],
                'leads.timeframe' => [
                    'title' => 'Timeframe',
                    'options' => $this->common_data['timeframe'],
                ],
            ]
        ];

        return view($this->view_base . '.index', $data);
    }

    public function create() {
        $data = ['admin_page_title' => 'Create ' . $this->singular_display_name];
        return view($this->view_base . '.create', $data);
    }

    public function store(Request $request) {
        //dd($request->all());
        $validator = Validator::make($request->all(), [
                    'full_name' => 'required',
                    'email' => 'required',
                    'phone' => 'required',
                    'main_category_id' => 'required',
                    'service_category_type_id' => 'required',
                    'service_category_id' => 'required',
                    'timeframe' => 'required',
                    'project_address' => 'required',
                    //'state_id' => 'required',
                    'city' => 'required',
                    'zipcode' => 'required',
                    'content' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect($this->urls['add'])
                            ->withErrors($validator)
                            ->withInput();
        } else {
            $requestArr = $request->all();
            $requestArr['lead_activated'] = 'yes';
            $requestArr['lead_active_date'] = now()->format(env('DB_DATE_FORMAT'));
            $itemObj = $this->modelObj->create($requestArr);

            Custom::generateCompanyLeads($itemObj);

            /* Lead active email to Admin */
            if (isset($this->web_settings['global_email']) && $this->web_settings['global_email'] != '') {
                $admin_mail_id = "53"; /* Mail title: Find A Pro Activation - Admin */
                $data['company_list'] = CompanyLead::with('company_name_admin_list')->where('lead_id', $itemObj->id)->get();

                $company_list = view('leads._company_list_who_get_lead', $data)->render();
                $replaceWithArr = [
                    'customer_name' => $itemObj->full_name,
                    'customer_phone' => $itemObj->phone,
                    'customer_email' => $itemObj->email,
                    'street' => $itemObj->project_address,
                    'zipcode' => $itemObj->zipcode,
                    'service_category' => $itemObj->service_category->title,
                    'project_info' => $itemObj->content,
                    'company_list' => $company_list
                ];

                Mail::to($this->web_settings['global_email'])->send(new AdminMail($admin_mail_id, $replaceWithArr));
            }

            flash($this->module_messages['add'])->success();
            return redirect($this->urls['list']);
        }
    }

    public function edit($id) {
        $formObj = $this->modelObj->with(['main_category', 'service_category'])->findOrFail($id);
        $data['admin_page_title'] = 'Edit ' . $this->singular_display_name;
        $data['formObj'] = $formObj;

        return view($this->view_base . '.edit', $data);
    }

    public function update($id, Request $request) {
        $itemObj = $this->modelObj->findOrFail($id);

        $validator = Validator::make($request->all(), [
                    'full_name' => 'required',
                    'email' => 'required',
                    'phone' => 'required',
                    'main_category_id' => 'required',
                    'service_category_type_id' => 'required',
                    'service_category_id' => 'required',
                    'timeframe' => 'required',
                    'project_address' => 'required',
                    //'state_id' => 'required',
                    'city' => 'required',
                    'zipcode' => 'required',
                    'content' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect(route($this->urls['edit'], [$this->urls['url_key_singular'] => $id]))
                            ->withErrors($validator)
                            ->withInput();
        } else {
            $requestArr = $request->all();
            $itemObj->update($requestArr);

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

    /* Open Dispute list start */

    public function open_disputes(Request $request) {
        $list_params = Custom::getListParams($request);
        $admin_page_title = 'Manage Open Disputes ' . $this->module_plural_name;
        $rows = $this->modelObj->getOpenDisputeAdminList($list_params);

        if (count($rows) <= 0 && $request->has('page') && $request->get('page') > 1) {
            $list_params['page'] = $rows->lastPage();
            return redirect($this->urls['list'] . http_build_query($list_params));
        }


        $service_category_options = [];
        if (isset($list_params['search']['leads.main_category_id']) && $list_params['search']['leads.main_category_id'] > 0) {
            $service_category_options = ServiceCategory::where('main_category_id', $list_params['search']['leads.main_category_id'])->order()->pluck('title', 'id');
        }

        $data = [
            'admin_page_title' => $admin_page_title,
            'rows' => $rows,
            'list_params' => $list_params,
            'searchColumns' => $this->modelObj->searchColumns,
            'with_date' => 0,
            'action_arr' => Custom::getActionArr($this->common_data['url_key']),
            'search' => [
                'leads.service_category_type_id' => [
                    'title' => 'Service Category Type',
                    'options' => $this->common_data['service_category_type'],
                    'id' => 'service_category_type_id'
                ],
                'leads.main_category_id' => [
                    'title' => 'Main Category',
                    'options' => $this->common_data['main_category'],
                    'id' => 'main_category_id'
                ],
                'leads.service_category_id' => [
                    'title' => 'Service Category',
                    'options' => $service_category_options,
                    'id' => 'service_category_id'
                ],
                'leads.state_id' => [
                    'title' => 'State',
                    'options' => $this->common_data['states'],
                    'id' => 'state_id'
                ],
            ]
        ];

        return view($this->view_base . '.open_disputes', $data);
    }

    /* Open Dispute list end */

    /* Closed Dispute list start */

    public function closed_disputes(Request $request) {
        $list_params = Custom::getListParams($request);
        $admin_page_title = 'Manage Closed Disputes ' . $this->module_plural_name;
        $rows = $this->modelObj->getClosedDisputeAdminList($list_params);

        if (count($rows) <= 0 && $request->has('page') && $request->get('page') > 1) {
            $list_params['page'] = $rows->lastPage();
            return redirect($this->urls['list'] . http_build_query($list_params));
        }


        $service_category_options = [];
        if (isset($list_params['search']['leads.main_category_id']) && $list_params['search']['leads.main_category_id'] > 0) {
            $service_category_options = ServiceCategory::where('main_category_id', $list_params['search']['leads.main_category_id'])->order()->pluck('title', 'id');
        }

        $data = [
            'admin_page_title' => $admin_page_title,
            'rows' => $rows,
            'list_params' => $list_params,
            'searchColumns' => $this->modelObj->searchColumns,
            'with_date' => 0,
            'action_arr' => Custom::getActionArr($this->common_data['url_key']),
            'search' => [
                'leads.service_category_type_id' => [
                    'title' => 'Service Category Type',
                    'options' => $this->common_data['service_category_type'],
                    'id' => 'service_category_type_id'
                ],
                'leads.main_category_id' => [
                    'title' => 'Main Category',
                    'options' => $this->common_data['main_category'],
                    'id' => 'main_category_id'
                ],
                'leads.service_category_id' => [
                    'title' => 'Service Category',
                    'options' => $service_category_options,
                    'id' => 'service_category_id'
                ],
                'leads.state_id' => [
                    'title' => 'State',
                    'options' => $this->common_data['states'],
                    'id' => 'state_id'
                ],
            ]
        ];

        return view($this->view_base . '.closed_disputes', $data);
    }

    /* Closed Dispute list end */

    /* Change dispute status start */

    public function change_dispute_status(Request $request) {
        $validator = Validator::make($request->all(), [
                    'lead_id' => 'required',
                    'dispute_status' => 'required',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        } else {
            $requestArr = $request->all();
            $lead = $this->modelObj->with(['company', 'service_category'])->find($requestArr['lead_id']);
            //$lead->dispute_status = $requestArr['dispute_status'];
            $lead->update($requestArr);

            if ($requestArr['dispute_status'] == 'approved') {
                $company_lead = CompanyLead::where([
                            ['company_id', $lead->company_id],
                            ['lead_id', $lead->id]
                        ])->first();

                if (!is_null($company_lead)) {
                    $company_lead->fee = 0;
                    $company_lead->save();
                }
            }

            $web_settings = $this->web_settings;
            $companyUserObj = CompanyUser::where([
                        ['company_id', $lead->company->id],
                        ['company_user_type', 'company_super_admin']
                    ])
                    ->first();


            /* Lead dispute status change mail to Company */
            $company_mail_id = "59"; /* Mail title: Lead Dispute Status Change */
            $companyReplaceArr = [
                'company_name' => $lead->company->company_name,
                'dispute_status' => ucwords($requestArr['dispute_status']),
                'service_category' => $lead->service_category->title,
                'global_address' => ((isset($web_settings['office_address']) && $web_settings['office_address'] != '') ? $web_settings['office_address'] : ''),
                'phone_number' => ((isset($web_settings['phone_number']) && $web_settings['phone_number'] != '') ? $web_settings['phone_number'] : ''),
                'global_email' => ((isset($web_settings['global_email']) && $web_settings['global_email'] != '') ? $web_settings['global_email'] : ''),
                'facebook_link' => ((isset($web_settings['facebook']) && $web_settings['facebook'] != '') ? $web_settings['facebook'] : ''),
                'twitter_link' => ((isset($web_settings['twitter']) && $web_settings['twitter'] != '') ? $web_settings['twitter'] : ''),
                'instagram_link' => ((isset($web_settings['instagram']) && $web_settings['instagram'] != '') ? $web_settings['instagram'] : ''),
                'rumble_link' => ((isset($web_settings['rumble']) && $web_settings['rumble'] != '') ? $web_settings['rumble'] : ''),
                'youtube_link' => ((isset($web_settings['youtube']) && $web_settings['youtube'] != '') ? $web_settings['youtube'] : ''),
                'unsubscription_link' => url('unsubscribe-page/company', ['company_slug' => $lead->company->slug]),
                'request_generate_link' => $lead->email,
                'date' => $lead->created_at->format(env('DATE_FORMAT')),
                'url' => url('leads-archive-inbox'),
                'email_footer' => $lead->email,
                'copyright_year' => date('Y'),
                    //'main_service_category' => '',
            ];

            if ($requestArr['dispute_status'] == 'declined') {
                $company_mail_id = "143"; /* Mail title: Lead Dispute Declined */
                $companyReplaceArr = [
                    'company_name' => $lead->company->company_name,
                    //'dispute_status' => ucwords($requestArr['dispute_status']),
                    'service_category' => $lead->service_category->title,
                    'reason' => $requestArr['dispute_decline_reason'],
                    'global_address' => ((isset($web_settings['office_address']) && $web_settings['office_address'] != '') ? $web_settings['office_address'] : ''),
                    'phone_number' => ((isset($web_settings['phone_number']) && $web_settings['phone_number'] != '') ? $web_settings['phone_number'] : ''),
                    'global_email' => ((isset($web_settings['global_email']) && $web_settings['global_email'] != '') ? $web_settings['global_email'] : ''),
                    'facebook_link' => ((isset($web_settings['facebook']) && $web_settings['facebook'] != '') ? $web_settings['facebook'] : ''),
                    'twitter_link' => ((isset($web_settings['twitter']) && $web_settings['twitter'] != '') ? $web_settings['twitter'] : ''),
                    'instagram_link' => ((isset($web_settings['instagram']) && $web_settings['instagram'] != '') ? $web_settings['instagram'] : ''),
                    'rumble_link' => ((isset($web_settings['rumble']) && $web_settings['rumble'] != '') ? $web_settings['rumble'] : ''),
                    'youtube_link' => ((isset($web_settings['youtube']) && $web_settings['youtube'] != '') ? $web_settings['youtube'] : ''),
                    'unsubscription_link' => url('unsubscribe-page/company', ['company_slug' => $lead->company->slug]),
                    'request_generate_link' => $lead->email,
                    'date' => $lead->created_at->format(env('DATE_FORMAT')),
                    'url' => url('leads-archive-inbox'),
                    'email_footer' => $lead->email,
                    'copyright_year' => date('Y'),
                        //'main_service_category' => '',
                ];
            }

            $mailArr = Custom::generate_company_user_email_arr($lead->company->ppl_company_information);


            $messageArr = [
                'company_id' => $lead->company->id,
                'message_type' => 'info',
                'link' => url('leads-archive-inbox')
            ];
            Custom::companyMailMessageCreate($messageArr, $company_mail_id, $companyReplaceArr);
            if (!is_null($mailArr) && count($mailArr) > 0) {
                foreach ($mailArr AS $mail_item) {
                    Mail::to($mail_item)->send(new CompanyMail($company_mail_id, $companyReplaceArr));
                }
            }

            flash("Lead dispute status updated successfully")->success();
            return back();
        }
    }

    /* Change dispute status end */

    public function get_service_categories(Request $request) {
        $requestArr = $request->all();
        if (
                isset($requestArr['main_category_id']) && $requestArr['main_category_id'] != '' &&
                isset($requestArr['service_category_type_id']) && $requestArr['service_category_type_id'] != ''
        ) {
            $service_category = ServiceCategory::where([
                        ['main_category_id', $requestArr['main_category_id']],
                        ['service_category_type_id', $requestArr['service_category_type_id']]
                    ])
                    ->active()
                    ->order()
                    ->pluck('title', 'id');
            return $service_category;
        } else {
            return [
                'success' => 0,
                'message' => 'Select Main Category/Service Category type first.'
            ];
        }
    }

    public function get_logs(Request $request) {
        $requestArr = $request->all();
        $keyIdentifier = $requestArr['correlationid'];
        if (isset($keyIdentifier)) {
            $logs = CustomLog::where('key_identifier', $keyIdentifier)
            ->select('level', 'message', 'context', 'key_identifier_type')
            ->get();
            return $logs;
        } else {
            return [
                'success' => 0,
                'message' => 'Correlation Id not found.'
            ];
        }
    }

}

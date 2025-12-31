<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Mail\Company\CompanyMail;
use App\Mail\Admin\AdminMail;
use App\Mail\consumer\ConsumerMail;
use Illuminate\Support\Facades\Mail;
use Auth;
use View;
use Validator;
use Str;
// Models [start]
use App\Models\Custom;
use App\Models\Company;
use App\Models\ComplaintFile;
use App\Models\ComplaintResponse;

class ComplaintController extends Controller {

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

        $this->complaint_status = $complaint_status = [
            'Submitted',
            'Confirmed',
            'In Progress',
            'Posted'
        ];

        $this->common_data = [
            'module_singular_name' => $this->singular_display_name,
            'module_plural_name' => $this->module_plural_name,
            'url_key' => $url_key,
            'module_urls' => $this->urls,
            'companies' => Company::order()->pluck('company_name', 'id'),
            'complaint_statuses' => array_combine($complaint_status, $complaint_status)
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

        $data = [
            'admin_page_title' => $admin_page_title,
            'rows' => $rows,
            'list_params' => $list_params,
            'searchColumns' => $this->modelObj->searchColumns,
            'with_date' => 0,
            'action_arr' => Custom::getActionArr($this->common_data['url_key']),
            'search' => [
                'complaints.company_id' => [
                    'title' => 'Company',
                    'options' => $this->common_data['companies'],
                    'id' => 'company_id'
                ],
                'complaints.complaint_status' => [
                    'title' => 'Status',
                    'options' => array_combine($this->complaint_status, $this->complaint_status),
                    'id' => 'complaint_status',
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
                    'company_id' => 'required',
                    'customer_name' => 'required',
                    'customer_email' => 'required',
                    'customer_phone' => 'required',
                    'complaint_status' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect($this->urls['add'])
                            ->withErrors($validator)
                            ->withInput();
        } else {
            $web_settings = $this->web_settings;

            $requestArr = $request->all();
            $requestArr['complaint_id'] = $this->modelObj->getComplaintNumber();
            $requestArr['complaint_status'] = 'Confirmed';
            $itemObj = $this->modelObj->create($requestArr);

            $companyObj = Company::find($requestArr['company_id']);

            if ($request->hasFile('media')) {
                $images = $request->file('media');
                if (count($images) > 0) {
                    foreach ($images as $file) {
                        $imageArr = Custom::uploadFile($file, $this->post_type);
                        $insertArr = [
                            'company_id' => $itemObj->company_id,
                            'complaint_id' => $itemObj->id,
                            'media_id' => $imageArr['mediaObj']->id
                        ];

                        ComplaintFile::create($insertArr);
                    }
                }
            }

            /* Company Complaint created mail to Company */
            $company_mail_id = "45"; /* Mail title: Company Complaint Created */
            $mailArr = Custom::generate_company_user_email_arr($companyObj->company_information);
            $companyReplaceArr = [
                'complaint_number' => $itemObj->complaint_id,
                'company_name' => $companyObj->company_name,
                'complaint_date' => $itemObj->created_at->format(env('DATE_FORMAT')),
                'view_link' => url('feedback'),
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
                'request_generate_link' => $itemObj->customer_email,
                'date' => $itemObj->created_at->format(env('DATE_FORMAT')),
                'url' => url('/', ['company_slug' => $companyObj->slug]),
                'email_footer' => $itemObj->customer_email,
                'copyright_year' => date('Y'),
                    //'main_service_category' => '',
            ];

            $messageArr = [
                'company_id' => $companyObj->id,
                'message_type' => 'info',
                'link' => url('feedback')
            ];
            Custom::companyMailMessageCreate($messageArr, $company_mail_id, $companyReplaceArr);
            if (!is_null($mailArr) && count($mailArr) > 0) {
                foreach ($mailArr AS $mail_item) {
                    Mail::to($mail_item)->send(new CompanyMail($company_mail_id, $companyReplaceArr));
                }
            }

            /* Company Complaint created mail to Consumer */
            $consumer_mail_id = "46"; /* Mail title: Company Complaint Created - Consumer */
            $consumerReplaceArr = [
                'customer_name' => $itemObj->customer_name,
                'company_name' => $companyObj->company_name,
                'complaint_number' => $itemObj->complaint_id,
                'phone_number' => ((isset($web_settings['phone_number']) && $web_settings['phone_number'] != '') ? $web_settings['phone_number'] : ''),
                'global_address' => ((isset($web_settings['office_address']) && $web_settings['office_address'] != '') ? $web_settings['office_address'] : ''),
                'global_email' => ((isset($web_settings['global_email']) && $web_settings['global_email'] != '') ? $web_settings['global_email'] : ''),
                'facebook_link' => ((isset($web_settings['facebook']) && $web_settings['facebook'] != '') ? $web_settings['facebook'] : ''),
                'twitter_link' => ((isset($web_settings['twitter']) && $web_settings['twitter'] != '') ? $web_settings['twitter'] : ''),
                'instagram_link' => ((isset($web_settings['instagram']) && $web_settings['instagram'] != '') ? $web_settings['instagram'] : ''),
                'rumble_link' => ((isset($web_settings['rumble']) && $web_settings['rumble'] != '') ? $web_settings['rumble'] : ''),
                'youtube_link' => ((isset($web_settings['youtube']) && $web_settings['youtube'] != '') ? $web_settings['youtube'] : ''),
                'unsubscription_link' => '',
                'request_generate_link' => $itemObj->customer_email,
                'date' => $itemObj->created_at->format(env('DATE_FORMAT')),
                'url' => url('/', ['company_slug' => $companyObj->slug]),
                'email_footer' => $itemObj->customer_email,
                'copyright_year' => date('Y'),
                    //'main_service_category' => '',
            ];

            Mail::to($itemObj->customer_email)->send(new ConsumerMail($consumer_mail_id, $consumerReplaceArr));

            if (isset($this->web_settings['global_email']) && $this->web_settings['global_email'] != '') {
                /* Company Complaint created mail to Admin */
                $admin_mail_id = "47"; /* Mail title: Company Complaint Created - Admin */
                $adminReplaceArr = [
                    'company_name' => $companyObj->company_name,
                    'complaint_number' => $itemObj->complaint_id,
                    'customer_name' => $itemObj->customer_name,
                    'customer_phone' => $itemObj->customer_phone,
                    'customer_email' => $itemObj->customer_email,
                ];

                Mail::to($this->web_settings['global_email'])->send(new AdminMail($admin_mail_id, $adminReplaceArr));
            }

            flash($this->module_messages['add'])->success();
            return redirect($this->urls['list']);
        }
    }

    public function edit($id) {
        $formObj = $this->modelObj->with(['company', 'complaint_files'])->findOrFail($id);

        $data['admin_page_title'] = 'Edit ' . $this->singular_display_name;
        $data['formObj'] = $formObj;

        return view($this->view_base . '.edit', $data);
    }

    public function update($id, Request $request) {
        $itemObj = $this->modelObj->findOrFail($id);

        $validator = Validator::make($request->all(), [
                    'company_id' => 'required',
                    'customer_name' => 'required',
                    'customer_email' => 'required',
                    'customer_phone' => 'required',
                    'complaint_status' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect(route($this->urls['edit'], [$this->urls['url_key_singular'] => $id]))
                            ->withErrors($validator)
                            ->withInput();
        } else {
            $requestArr = $request->all();
            $itemObj->update($requestArr);

            if ($request->hasFile('media')) {
                $images = $request->file('media');
                if (count($images) > 0) {
                    foreach ($images as $file) {
                        $imageArr = Custom::uploadFile($file, $this->post_type);
                        $insertArr = [
                            'company_id' => $itemObj->company_id,
                            'complaint_id' => $itemObj->id,
                            'media_id' => $imageArr['mediaObj']->id
                        ];

                        ComplaintFile::create($insertArr);
                    }
                }
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

    public function change_status(Request $request) {
        $validator = Validator::make($request->all(), [
                    'complaint_id' => 'required',
                    'complaint_status' => 'required',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        } else {
            $web_settings = $this->web_settings;

            $requestArr = $request->all();
            $complaint = $this->modelObj->find($requestArr['complaint_id']);
            $complaint->complaint_status = $requestArr['complaint_status'];
            $complaint->save();

            $companyObj = Company::find($complaint->company_id);

            if ($requestArr['complaint_status'] == 'Confirmed') {
                /*$complaint->confirmed_mail_sent = 'yes';
                $complaint->save();*/

                $company_mail_id = "45"; /* Mail title: Company Complaint Created */
                $companyReplaceArr = [
                    'complaint_number' => $complaint->complaint_id,
                    'company_name' => $companyObj->company_name,
                    'complaint_date' => $complaint->created_at->format(env('DATE_FORMAT')),
                    'view_link' => url('feedback'),
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
                    'request_generate_link' => $complaint->customer_email,
                    'date' => $complaint->created_at->format(env('DATE_FORMAT')),
                    'url' => url('/', ['company_slug' => $companyObj->slug]),
                    'email_footer' => $complaint->customer_email,
                    'copyright_year' => date('Y'),
                        //'main_service_category' => '',
                ];
            } else {
                $company_mail_id = "87"; /* Mail title: Company Complaint Status Change */
                $companyReplaceArr = [
                    'company_name' => $companyObj->company_name,
                    'complaint_number' => $complaint->complaint_id,
                    'complaint_status' => $complaint->complaint_status,
                    'global_address' => ((isset($web_settings['office_address']) && $web_settings['office_address'] != '') ? $web_settings['office_address'] : ''),
                    'phone_number' => ((isset($web_settings['phone_number']) && $web_settings['phone_number'] != '') ? $web_settings['phone_number'] : ''),
                    'global_email' => ((isset($web_settings['global_email']) && $web_settings['global_email'] != '') ? $web_settings['global_email'] : ''),
                    'facebook_link' => ((isset($web_settings['facebook']) && $web_settings['facebook'] != '') ? $web_settings['facebook'] : ''),
                    'twitter_link' => ((isset($web_settings['twitter']) && $web_settings['twitter'] != '') ? $web_settings['twitter'] : ''),
                    'instagram_link' => ((isset($web_settings['instagram']) && $web_settings['instagram'] != '') ? $web_settings['instagram'] : ''),
                    'rumble_link' => ((isset($web_settings['rumble']) && $web_settings['rumble'] != '') ? $web_settings['rumble'] : ''),
                    'youtube_link' => ((isset($web_settings['youtube']) && $web_settings['youtube'] != '') ? $web_settings['youtube'] : ''),
                    'unsubscription_link' => url('unsubscribe-page/company', ['company_slug' => $companyObj->slug]),
                    'request_generate_link' => $complaint->customer_email,
                    'date' => $complaint->created_at->format(env('DATE_FORMAT')),
                    'url' => url('feedback'),
                    'email_footer' => $complaint->customer_email,
                    'copyright_year' => date('Y'),
                        //'main_service_category' => '',
                ];
            }


            /* Complaint status change mail to Company */
            $mailArr = Custom::generate_company_user_email_arr($companyObj->company_information);
            $messageArr = [
                'company_id' => $companyObj->id,
                'message_type' => 'info',
                'link' => url('feedback')
            ];
            Custom::companyMailMessageCreate($messageArr, $company_mail_id, $companyReplaceArr);
            if (!is_null($mailArr) && count($mailArr) > 0) {
                foreach ($mailArr AS $mail_item) {
                    Mail::to($mail_item)->send(new CompanyMail($company_mail_id, $companyReplaceArr));
                }
            }

            /* Complaint status change mail to Consumer */
            $consumer_mail_id = "88"; /* Mail title: Company Complaint Status Change - Consumer */
            $consumerReplaceArr = [
                'change_by' => 'Admin',
                'customer_name' => $complaint->customer_name,
                'company_name' => $companyObj->company_name,
                'complaint_number' => $complaint->complaint_id,
                'complaint_status' => $complaint->complaint_status,
                'global_address' => ((isset($web_settings['office_address']) && $web_settings['office_address'] != '') ? $web_settings['office_address'] : ''),
                'phone_number' => ((isset($web_settings['phone_number']) && $web_settings['phone_number'] != '') ? $web_settings['phone_number'] : ''),
                'global_email' => ((isset($web_settings['global_email']) && $web_settings['global_email'] != '') ? $web_settings['global_email'] : ''),
                'facebook_link' => ((isset($web_settings['facebook']) && $web_settings['facebook'] != '') ? $web_settings['facebook'] : ''),
                'twitter_link' => ((isset($web_settings['twitter']) && $web_settings['twitter'] != '') ? $web_settings['twitter'] : ''),
                'instagram_link' => ((isset($web_settings['instagram']) && $web_settings['instagram'] != '') ? $web_settings['instagram'] : ''),
                'rumble_link' => ((isset($web_settings['rumble']) && $web_settings['rumble'] != '') ? $web_settings['rumble'] : ''),
                'youtube_link' => ((isset($web_settings['youtube']) && $web_settings['youtube'] != '') ? $web_settings['youtube'] : ''),
                'unsubscription_link' => '',
                'request_generate_link' => $complaint->customer_email,
                'date' => $complaint->created_at->format(env('DATE_FORMAT')),
                'url' => url('/', ['company_slug' => $companyObj->slug]),
                'email_footer' => $complaint->customer_email,
                'copyright_year' => date('Y'),
                    //'main_service_category' => '',
            ];
            Mail::to($complaint->customer_email)->send(new ConsumerMail($consumer_mail_id, $consumerReplaceArr));

            flash("Complaint status updated successfully")->success();
            return back();
        }
    }

    public function complaint_responses($complaint_id, Request $request) {
        $formObj = $this->modelObj->with(['company', 'complaint_files', 'complaint_response'])->findOrFail($complaint_id);

        $data['admin_page_title'] = $this->singular_display_name . ' Responses';
        $data['formObj'] = $formObj;

        return view($this->view_base . '.complaint_response', $data);
    }

    public function add_complaint_response(Request $request) {
        $validator = Validator::make($request->all(), [
                    'complaint_id' => 'required',
                    'content' => 'required',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        } else {
            $web_settings = $this->web_settings;

            $requestArr = $request->all();
            $complaint = $this->modelObj->find($requestArr['complaint_id']);
            $companyObj = Company::find($complaint->company_id);

            $requestArr['couser_id'] = Auth::id();
            $requestArr['couser_type'] = 'App/User';

            $complaintResponseObj = ComplaintResponse::create($requestArr);

            if ($request->hasFile('media')) {
                $imageArr = Custom::uploadFile($request->file('media'), 'complaints');

                $complaintResponseObj->media_id = $imageArr['mediaObj']->id;
                $complaintResponseObj->save();
            }


            /* Company complaint response mail to Consumer */
            $consumer_mail_id = "48"; /* Mail title: Company Complaint Response - Consumer */
            $consumerReplaceArr = [
                'customer_name' => $complaint->customer_name,
                'change_by' => 'Admin',
                'complaint_date' => $complaint->created_at->format(env('DATE_FORMAT')),
                'comment_text' => $requestArr['content'],
                'submit_a_response' => '',
                'phone_number' => ((isset($web_settings['phone_number']) && $web_settings['phone_number'] != '') ? $web_settings['phone_number'] : ''),
                'global_domain' => ((isset($web_settings['global_domain']) && $web_settings['global_domain'] != '') ? $web_settings['global_domain'] : ''),
                'global_address' => ((isset($web_settings['office_address']) && $web_settings['office_address'] != '') ? $web_settings['office_address'] : ''),
                'global_email' => ((isset($web_settings['global_email']) && $web_settings['global_email'] != '') ? $web_settings['global_email'] : ''),
                'facebook_link' => ((isset($web_settings['facebook']) && $web_settings['facebook'] != '') ? $web_settings['facebook'] : ''),
                'twitter_link' => ((isset($web_settings['twitter']) && $web_settings['twitter'] != '') ? $web_settings['twitter'] : ''),
                'instagram_link' => ((isset($web_settings['instagram']) && $web_settings['instagram'] != '') ? $web_settings['instagram'] : ''),
                'rumble_link' => ((isset($web_settings['rumble']) && $web_settings['rumble'] != '') ? $web_settings['rumble'] : ''),
                'youtube_link' => ((isset($web_settings['youtube']) && $web_settings['youtube'] != '') ? $web_settings['youtube'] : ''),
                'unsubscription_link' => '',
                'request_generate_link' => $complaint->customer_email,
                'date' => $complaintResponseObj->created_at->format(env('DATE_FORMAT')),
                'url' => url('/', ['company_slug' => $companyObj->slug]),
                'email_footer' => $complaint->customer_email,
                'copyright_year' => date('Y'),
                    //'main_service_category' => '',
            ];
            Mail::to($complaint->customer_email)->send(new ConsumerMail($consumer_mail_id, $consumerReplaceArr));


            /* Company complaint response mail to Company */
            $company_mail_id = "50"; /* Mail title: Company Complaint Response */
            $mailArr = Custom::generate_company_user_email_arr($companyObj->company_information);
            $companyReplaceArr = [
                'company_name' => $companyObj->company_name,
                'complaint_date' => $complaint->created_at->format(env('DATE_FORMAT')),
                'comment_text' => $requestArr['content'],
                'global_address' => ((isset($web_settings['office_address']) && $web_settings['office_address'] != '') ? $web_settings['office_address'] : ''),
                'phone_number' => ((isset($web_settings['phone_number']) && $web_settings['phone_number'] != '') ? $web_settings['phone_number'] : ''),
                'global_email' => ((isset($web_settings['global_email']) && $web_settings['global_email'] != '') ? $web_settings['global_email'] : ''),
                'facebook_link' => ((isset($web_settings['facebook']) && $web_settings['facebook'] != '') ? $web_settings['facebook'] : ''),
                'twitter_link' => ((isset($web_settings['twitter']) && $web_settings['twitter'] != '') ? $web_settings['twitter'] : ''),
                'instagram_link' => ((isset($web_settings['instagram']) && $web_settings['instagram'] != '') ? $web_settings['instagram'] : ''),
                'rumble_link' => ((isset($web_settings['rumble']) && $web_settings['rumble'] != '') ? $web_settings['rumble'] : ''),
                'youtube_link' => ((isset($web_settings['youtube']) && $web_settings['youtube'] != '') ? $web_settings['youtube'] : ''),
                'unsubscription_link' => url('unsubscribe-page/company', ['company_slug' => $companyObj->slug]),
                'request_generate_link' => $complaint->customer_email,
                'date' => $complaintResponseObj->created_at->format(env('DATE_FORMAT')),
                'url' => url('feedback'),
                'email_footer' => $complaint->customer_email,
                'copyright_year' => date('Y'),
                    //'main_service_category' => '',
            ];

            $messageArr = [
                'company_id' => $companyObj->id,
                'message_type' => 'info',
                'link' => url('feedback')
            ];
            Custom::companyMailMessageCreate($messageArr, $company_mail_id, $companyReplaceArr);
            if (!is_null($mailArr) && count($mailArr) > 0) {
                foreach ($mailArr AS $mail_item) {
                    Mail::to($mail_item)->send(new CompanyMail($company_mail_id, $companyReplaceArr));
                }
            }

            flash('Complaint Response added successfully.')->success();
            return back();
        }
    }

}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Mail\Company\CompanyMail;
use App\Mail\Admin\AdminMail;
use App\Mail\consumer\ConsumerMail;
use Illuminate\Support\Facades\Mail;
use View;
use Validator;
use Str;
// Models [start]
use App\Models\Custom;
use App\Models\Company;
use App\Models\FeedbackFile;

class FeedbackController extends Controller {

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

        $module_display_name = 'Review';

        // Module Message
        $this->module_messages = Custom::getModuleFlashMessages($module_display_name);

        // Singular and Plural Name of Module
        $this->singular_display_name = Str::singular($module_display_name);
        $this->module_plural_name = Str::plural($module_display_name);

        $this->feedback_status = $feedback_status = [
            'Submitted',
            'Confirmed',
            'Pre Approved',
            'Member Approved',
            'Member Rejected',
            'Posted',
            'Rejected'
        ];

        $this->common_data = [
            'module_singular_name' => $this->singular_display_name,
            'module_plural_name' => $this->module_plural_name,
            'url_key' => $url_key,
            'module_urls' => $this->urls,
            'companies' => Company::order()->pluck('company_name', 'id'),
            'feedback_statuses' => array_combine($feedback_status, $feedback_status)
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
                'feedback.company_id' => [
                    'title' => 'Company',
                    'options' => $this->common_data['companies'],
                    'id' => 'company_id'
                ],
                'feedback.feedback_status' => [
                    'title' => 'Status',
                    'options' => array_combine($this->feedback_status, $this->feedback_status),
                    'id' => 'feedback_status',
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
                    'feedback_status' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect($this->urls['add'])
                            ->withErrors($validator)
                            ->withInput();
        } else {
            $web_settings = $this->web_settings;

            $requestArr = $request->all();
            $requestArr['feedback_id'] = $this->modelObj->getFeedbackNumber();
            $requestArr['feedback_status'] = 'Submitted';
            $requestArr['activation_key'] = Str::random(60);

            $itemObj = $this->modelObj->create($requestArr);

            $companyObj = Company::find($requestArr['company_id']);
            if ($request->hasFile('media')) {
                $images = $request->file('media');
                if (count($images) > 0) {
                    foreach ($images as $file) {
                        $imageArr = Custom::uploadFile($file, $this->post_type);
                        $insertArr = [
                            'company_id' => $itemObj->company_id,
                            'feedback_id' => $itemObj->id,
                            'media_id' => $imageArr['mediaObj']->id
                        ];

                        FeedbackFile::create($insertArr);
                    }
                }
            }

            /* Company Feedback created mail to Company */
            $company_mail_id = "39"; /* Mail title: Company Feedback Created */
            $mailArr = Custom::generate_company_user_email_arr($companyObj->company_information);
            $companyReplaceArr = [
                'company_name' => $companyObj->company_name,
                'global_domain' => '',
                'view_link' => url('feedback'),
                'phone_number' => ((isset($web_settings['phone_number']) && $web_settings['phone_number'] != '') ? $web_settings['phone_number'] : ''),
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

            /* Company Feedback created mail to Consumer */
            $consumer_mail_id = "40"; /* Mail title: Company Feedback Created - Consumer */
            $consumerReplaceArr = [
                'customer_name' => $itemObj->customer_name,
                'company_name' => $companyObj->company_name,
                'confirmation_link' => url('/confirm-review/' . $itemObj->activation_key),
                'global_address' => ((isset($web_settings['office_address']) && $web_settings['office_address'] != '') ? $web_settings['office_address'] : ''),
                'phone_number' => ((isset($web_settings['phone_number']) && $web_settings['phone_number'] != '') ? $web_settings['phone_number'] : ''),
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
                /* Company Feedback created mail to Admin */
                $admin_mail_id = "41"; /* Mail title: Company Feedback Created - Admin */
                $adminReplaceArr = [
                    'company_name' => $companyObj->company_name,
                    'feedback_number' => $itemObj->feedback_id,
                    'customer_name' => $itemObj->customer_name,
                    'customer_phone' => $itemObj->customer_phone,
                    'customer_email' => $itemObj->customer_email,
                    'rating' => $itemObj->ratings,
                    'review' => $itemObj->content,
                ];

                Mail::to($this->web_settings['global_email'])->send(new AdminMail($admin_mail_id, $adminReplaceArr));
            }

            flash($this->module_messages['add'])->success();
            return redirect($this->urls['list']);
        }
    }

    public function edit($id) {
        $formObj = $this->modelObj->with(['company', 'feedback_files'])->findOrFail($id);

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
                    'feedback_status' => 'required',
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
                            'feedback_id' => $itemObj->id,
                            'media_id' => $imageArr['mediaObj']->id
                        ];

                        FeedbackFile::create($insertArr);
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
                    'feedback_id' => 'required',
                    'feedback_status' => 'required',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        } else {
            $web_settings = $this->web_settings;

            $requestArr = $request->all();
            $feedback = $this->modelObj->find($requestArr['feedback_id']);
            $feedback->feedback_status = $requestArr['feedback_status'];
            $feedback->save();

            $companyObj = Company::find($feedback->company_id);

            /* Feedback status change mail to Company */
            $company_mail_id = "42"; /* Mail title: Company Feedback Status Change */
            $mailArr = Custom::generate_company_user_email_arr($companyObj->company_information);
            $companyReplaceArr = [
                'company_name' => $companyObj->company_name,
                'feedback_number' => $feedback->feedback_id,
                'feedback_status' => $feedback->feedback_status,
                'global_address' => ((isset($web_settings['office_address']) && $web_settings['office_address'] != '') ? $web_settings['office_address'] : ''),
                'phone_number' => ((isset($web_settings['phone_number']) && $web_settings['phone_number'] != '') ? $web_settings['phone_number'] : ''),
                'global_email' => ((isset($web_settings['global_email']) && $web_settings['global_email'] != '') ? $web_settings['global_email'] : ''),
                'facebook_link' => ((isset($web_settings['facebook']) && $web_settings['facebook'] != '') ? $web_settings['facebook'] : ''),
                'twitter_link' => ((isset($web_settings['twitter']) && $web_settings['twitter'] != '') ? $web_settings['twitter'] : ''),
                'instagram_link' => ((isset($web_settings['instagram']) && $web_settings['instagram'] != '') ? $web_settings['instagram'] : ''),
                'rumble_link' => ((isset($web_settings['rumble']) && $web_settings['rumble'] != '') ? $web_settings['rumble'] : ''),
                'youtube_link' => ((isset($web_settings['youtube']) && $web_settings['youtube'] != '') ? $web_settings['youtube'] : ''),
                'unsubscription_link' => url('unsubscribe-page/company', ['company_slug' => $companyObj->slug]),
                'request_generate_link' => $feedback->customer_email,
                'date' => $feedback->created_at->format(env('DATE_FORMAT')),
                'url' => url('feedback'),
                'email_footer' => $feedback->customer_email,
                'copyright_year' => date('Y'),
                    //'main_service_category' => '',
            ];

            $messageArr = [
                'company_id' => $feedback->company_id,
                'message_type' => 'info',
                'link' => url('feedback')
            ];
            Custom::companyMailMessageCreate($messageArr, $company_mail_id, $companyReplaceArr);
            if (!is_null($mailArr) && count($mailArr) > 0) {
                foreach ($mailArr AS $mail_item) {
                    Mail::to($mail_item)->send(new CompanyMail($company_mail_id, $companyReplaceArr));
                }
            }

            /* Feedback status change mail to Consumer */
            $consumer_mail_id = "43"; /* Mail title: Company Feedback Status Change - Consumer */
            $consumerReplaceArr = [
                'change_by' => 'Admin',
                'customer_name' => $feedback->customer_name,
                'company_name' => $companyObj->company_name,
                'feedback_number' => $feedback->feedback_id,
                'feedback_status' => $feedback->feedback_status,
                'global_address' => ((isset($web_settings['office_address']) && $web_settings['office_address'] != '') ? $web_settings['office_address'] : ''),
                'phone_number' => ((isset($web_settings['phone_number']) && $web_settings['phone_number'] != '') ? $web_settings['phone_number'] : ''),
                'global_email' => ((isset($web_settings['global_email']) && $web_settings['global_email'] != '') ? $web_settings['global_email'] : ''),
                'facebook_link' => ((isset($web_settings['facebook']) && $web_settings['facebook'] != '') ? $web_settings['facebook'] : ''),
                'twitter_link' => ((isset($web_settings['twitter']) && $web_settings['twitter'] != '') ? $web_settings['twitter'] : ''),
                'instagram_link' => ((isset($web_settings['instagram']) && $web_settings['instagram'] != '') ? $web_settings['instagram'] : ''),
                'rumble_link' => ((isset($web_settings['rumble']) && $web_settings['rumble'] != '') ? $web_settings['rumble'] : ''),
                'youtube_link' => ((isset($web_settings['youtube']) && $web_settings['youtube'] != '') ? $web_settings['youtube'] : ''),
                'unsubscription_link' => '',
                'request_generate_link' => $feedback->customer_email,
                'date' => $companyObj->created_at->format(env('DATE_FORMAT')),
                'url' => url('/', ['company_slug' => $companyObj->slug]),
                'email_footer' => $feedback->customer_email,
                'copyright_year' => date('Y'),
                    //'main_service_category' => '',
            ];
            Mail::to($feedback->customer_email)->send(new ConsumerMail($consumer_mail_id, $consumerReplaceArr));

            flash("Feedback status updated successfully")->success();
            return back();
        }
    }

}

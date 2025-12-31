<?php

namespace App\Http\Controllers\company;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Mail\Admin\AdminMail;
use App\Mail\consumer\ConsumerMail;
use Illuminate\Support\Facades\Mail;
use Auth;
use Validator;
// Models [start]
use App\Models\Custom;
use App\Models\Feedback;
use App\Models\Company;
use App\Models\Complaint;
use App\Models\ComplaintResponse;

class ComplaintController extends Controller {

    public function __construct() {
        $this->web_settings = Custom::getSettings();
        $this->modelObj = new Complaint;
        $this->view_base = 'company.feedback.';
    }

    public function feedback(Request $request) {
        $company_id = Auth::guard('company_user')->user()->company_id;
        $data = [
            'feedback' => Feedback::with('feedback_files')->where('company_id', $company_id)->order()->paginate(env('APP_RECORDS_PER_PAGE'), ['*'], 'feedback'),
            'complaints' => Complaint::with('complaint_files')->where([['company_id', $company_id], ['complaint_status', '!=', 'Submitted']])->order()->paginate(env('APP_RECORDS_PER_PAGE'), ['*'], 'complaints'),
        ];
        return view($this->view_base . 'feedback', $data);
    }

    public function complaint_responses($complaint_id, Request $request) {
        $formObj = $this->modelObj->with(['company', 'complaint_files', 'complaint_response'])->findOrFail($complaint_id);

        $data = [
            'formObj' => $formObj
        ];

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

            $fileAttachment = [];
            $requestArr = $request->all();
            $requestArr['couser_id'] = Auth::guard('company_user')->user()->company_id;
            $requestArr['couser_type'] = 'App/Company';

            $complaintResponseObj = ComplaintResponse::create($requestArr);

            $complaintObj = $this->modelObj->find($requestArr['complaint_id']);
            $complaintObj->complaint_status = 'In Progress';
            $complaintObj->save();

            $companyObj = Company::find($requestArr['couser_id']);

            if ($request->hasFile('media')) {
                $imageArr = Custom::uploadFile($request->file('media'), 'complaints');

                $complaintResponseObj->media_id = $imageArr['mediaObj']->id;
                $complaintResponseObj->save();
                $fileAttachment[] = 'uploads/media/' . $imageArr['mediaObj']->file_name;
            }


            /* Company complaint response mail to Consumer */
            $consumer_mail_id = "48"; /* Mail title: Company Complaint Response - Consumer */
            $consumerReplaceArr = [
                'customer_name' => $complaintObj->customer_name,
                'change_by' => 'Company',
                'complaint_date' => $complaintObj->created_at->format(env('DATE_FORMAT')),
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
                'request_generate_link' => $complaintObj->customer_email,
                'date' => $complaintResponseObj->created_at->format(env('DATE_FORMAT')),
                'url' => url('/', ['company_slug' => $companyObj->slug]),
                'email_footer' => $complaintObj->customer_email,
                'copyright_year' => date('Y'),
                    //'main_service_category' => '',
            ];
            Mail::to($complaintObj->customer_email)->send(new ConsumerMail($consumer_mail_id, $consumerReplaceArr));


            /* Company complaint response mail to Admin */
            if (isset($this->web_settings['global_email']) && $this->web_settings['global_email'] != '') {
                $admin_mail_id = "49"; /* Mail title: Company Complaint Response - Admin */
                $adminReplaceArr = [
                    'company_name' => $companyObj->company_name,
                    'complaint_date' => $complaintObj->created_at->format(env('DATE_FORMAT')),
                    'comment_text' => $requestArr['content'],
                ];

                Mail::to($this->web_settings['global_email'])->send(new AdminMail($admin_mail_id, $adminReplaceArr));
            }

            flash('Complaint Response added successfully.')->success();
            return back();
        }
    }

}

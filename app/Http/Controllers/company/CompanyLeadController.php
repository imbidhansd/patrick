<?php

namespace App\Http\Controllers\company;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Mail\Company\CompanyMail;
use App\Mail\Admin\AdminMail;
use Illuminate\Support\Facades\Mail;
use DB;
use Session;
use Auth;
use Validator;
use App\Models\Custom;
use App\Models\Company;
use App\Models\Lead;
use App\Models\CompanyLead;
use App\Models\CompanyServiceCategory;

class CompanyLeadController extends Controller {

    public function __construct() {
        $this->web_settings = Custom::getSettings();
        $this->view_base = 'company.leads.';

        $this->modelObj = new CompanyLead;
    }

    public function index(Request $request) {
        $search_list_params = [];
        $company_id = Auth::guard('company_user')->user()->company_id;
        $companyObj = Company::with('membership_level')->find($company_id);

        $company_service_categories = CompanyServiceCategory::with('service_category', 'main_category')
                ->where('company_service_categories.company_id', $companyObj->id)
                ->active()
                ->orderBy('service_category_type_id', 'ASC')
                ->orderBy('top_level_category_id', 'ASC')
                ->orderBy('main_category_id', 'ASC')
                ->get();


        $current_used_budget = $remaining_budget = 0;
        if ($companyObj->membership_level->charge_type == 'ppl_price') {
            $current_used_budget = CompanyLead::where('company_leads.company_id', $company_id)
                    ->leftJoin('leads', 'company_leads.lead_id', 'leads.id')
                    ->where(function ($q){
                        $q->whereNull('leads.dispute_status');
                        $q->orWhereIn('leads.dispute_status', ['in process', 'declined', 'cancelled']);
                    })
                    ->where(DB::raw('MONTH(company_leads.created_at)'), now()->format('m'))
                    ->sum('company_leads.fee');

            $remaining_budget = $companyObj->temporary_budget - $current_used_budget;
        }

        /* get leads start */
        $leads = $this->modelObj->select('company_leads.*')->with('lead')
                ->where('company_leads.company_id', $company_id)
                ->orderBy('company_leads.id', 'DESC');
                //->orderBy('is_checked', 'ASC');

        $search_form_open = false;

        $requestArr = $request->all();
        if (isset($requestArr['service_category']) && $requestArr['service_category'] != '') {
            $leads->leftJoin('leads AS l', 'company_leads.lead_id', 'l.id')
                    ->where('l.service_category_id', $requestArr['service_category']);
            $search_list_params['service_category'] = $requestArr['service_category'];
            $search_form_open = true;
        }

        if (isset($requestArr['zipcode']) && $requestArr['zipcode'] != '') {
            $leads->leftJoin('leads AS l1', 'company_leads.lead_id', 'l1.id')
                    ->where('l1.zipcode', $requestArr['zipcode']);

            $search_list_params['zipcode'] = $requestArr['zipcode'];
            $search_form_open = true;
        }

        if (isset($requestArr['search_by']) && $requestArr['search_by'] != '') {
            $leads->leftJoin('leads AS l2', 'company_leads.lead_id', 'l2.id')
                    ->where(function ($query) use ($requestArr) {
                        $query->where('l2.full_name', 'LIKE', '%' . $requestArr['search_by'] . '%');
                        $query->orWhere('l2.email', 'LIKE', '%' . $requestArr['search_by'] . '%');
                        $query->orWhere('l2.phone', 'LIKE', '%' . $requestArr['search_by'] . '%');
                    });

            $search_list_params['search_by'] = $requestArr['search_by'];
            $search_form_open = true;
        }

        if (isset($requestArr['from_date']) && $requestArr['from_date'] != '') {
            $from_date = \Carbon\Carbon::createFromFormat(env('DATE_FORMAT'), $requestArr['from_date'])->format(env('DB_DATE_FORMAT'));
            $leads->leftJoin('leads AS l3', 'company_leads.lead_id', 'l3.id')
                    ->where('l3.created_at', '>=', $from_date);
            $search_list_params['from_date'] = $requestArr['from_date'];
            $search_form_open = true;
        }

        if (isset($requestArr['to_date']) && $requestArr['to_date'] != '') {
            $to_date = \Carbon\Carbon::createFromFormat(env('DATE_FORMAT'), $requestArr['to_date'])->format(env('DB_DATE_FORMAT'));
            $leads->leftJoin('leads AS l4', 'company_leads.lead_id', 'l4.id')
                    ->where('l4.created_at', '<=', $to_date);
            $search_list_params['to_date'] = $requestArr['to_date'];
            $search_form_open = true;
        }
        /* get leads end */

        $data = [
            'admin_page_title' => 'Leads Archive Inbox',
            'search_list_params' => $search_list_params,
            'leads' => $leads->paginate(env('APP_RECORDS_PER_PAGE')),
            'all_leads' => $this->modelObj->where('company_id', $company_id)->order()->count(),
            'read_leads' => $this->modelObj->where([['company_id', $company_id], ['is_checked', 'yes']])->order()->count(),
            'unread_leads' => $this->modelObj->where([['company_id', $company_id], ['is_checked', 'no']])->order()->count(),
            'current_used_budget' => $current_used_budget,
            'remaining_budget' => $remaining_budget,
            'company_service_categories' => $company_service_categories,
            'search_form_open' => $search_form_open,
        ];

        return view($this->view_base . 'index', $data);
    }

    public function mark_lead_as_read(Request $request) {
        if (!Session::has('company_mask')) {
            $validator = Validator::make($request->all(), [
                        'company_lead_id' => 'required',
            ]);

            if ($validator->fails()) {
                return [
                    'success' => 0,
                    'message' => 'Company Lead not found.'
                ];
            } else {
                $requestArr = $request->all();
                $company_id = Auth::guard('company_user')->user()->company_id;

                $company_lead = CompanyLead::where([
                            ['company_id', $company_id],
                            ['id', $requestArr['company_lead_id']]
                        ])->first();

                if (!is_null($company_lead)) {
                    $company_lead->is_checked = 'yes';
                    $company_lead->save();

                    return [
                        'success' => 1,
                        'message' => 'Company Lead mark as read done successfully.'
                    ];
                } else {
                    return [
                        'success' => 0,
                        'message' => 'Company Lead not found.'
                    ];
                }
            }
        }
    }

    public function generate_lead_dispute(Request $request) {
        $validator = Validator::make($request->all(), [
                    'lead_id' => 'required',
                    'dispute_content' => 'required',
                    'is_phone' => 'required',
                    'is_email' => 'required'
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        } else {
            $web_settings = $this->web_settings;
            $requestArr = $request->all();

            $companyUserObj = Auth::guard('company_user')->user();
            $leadObj = Lead::find($requestArr['lead_id']);
            $companyObj = Company::find(Auth::guard('company_user')->user()->company_id);

            if (!is_null($leadObj)) {
                $requestArr['company_id'] = $companyObj->id;
                $requestArr['dispute_status'] = 'in process';
                $leadObj->update($requestArr);

                /* Company generate dispute mail to Company */
                $company_mail_id = "55"; /* Mail title: Lead Dispute */
                $companyReplaceArr = [
                    'company_name' => $companyObj->company_name,
                    'service_category' => $leadObj->service_category->title,
                    'phone_number' => ((isset($web_settings['phone_number']) && $web_settings['phone_number'] != '') ? $web_settings['phone_number'] : ''),
                    'global_address' => ((isset($web_settings['office_address']) && $web_settings['office_address'] != '') ? $web_settings['office_address'] : ''),
                    'global_email' => ((isset($web_settings['global_email']) && $web_settings['global_email'] != '') ? $web_settings['global_email'] : ''),
                    'facebook_link' => ((isset($web_settings['facebook']) && $web_settings['facebook'] != '') ? $web_settings['facebook'] : ''),
                    'twitter_link' => ((isset($web_settings['twitter']) && $web_settings['twitter'] != '') ? $web_settings['twitter'] : ''),
                    'instagram_link' => ((isset($web_settings['instagram']) && $web_settings['instagram'] != '') ? $web_settings['instagram'] : ''),
                    'rumble_link' => ((isset($web_settings['rumble']) && $web_settings['rumble'] != '') ? $web_settings['rumble'] : ''),
                    'youtube_link' => ((isset($web_settings['youtube']) && $web_settings['youtube'] != '') ? $web_settings['youtube'] : ''),
                    'unsubscription_link' => url('unsubscribe-page/company', ['company_slug' => $companyObj->slug]),
                    'request_generate_link' => $leadObj->email,
                    'date' => $leadObj->created_at->format(env('DATE_FORMAT')),
                    'url' => url('leads-archive-inbox'),
                    'email_footer' => $leadObj->email,
                    'copyright_year' => date('Y'),
                        //'main_service_category' => '',
                ];

                $messageArr = [
                    'company_id' => $companyObj->id,
                    'message_type' => 'info',
                    'link' => url('leads-archive-inbox')
                ];
                Custom::companyMailMessageCreate($messageArr, $company_mail_id, $companyReplaceArr);
                $mailArr = Custom::generate_company_user_email_arr($companyObj->company_information);
                if (!is_null($mailArr) && count($mailArr) > 0) {
                    foreach ($mailArr AS $mail_item) {
                        Mail::to($mail_item)->send(new CompanyMail($company_mail_id, $companyReplaceArr));
                    }
                }


                if (isset($this->web_settings['global_email']) && $this->web_settings['global_email'] != '') {
                    /* Company generate dispute mail to Admin */
                    $admin_mail_id = "57"; /* Mail title: Lead Dispute - Admin */
                    $adminReplaceArr = [
                        'service_category' => $leadObj->service_category->title,
                        'company_name' => $companyObj->company_name,
                    ];
                    Mail::to($this->web_settings['global_email'])->send(new AdminMail($admin_mail_id, $adminReplaceArr));
                }

                flash("Lead dispute generated.")->success();
            } else {
                flash("Lead not found.")->warning();
            }

            return back();
        }
    }

    public function cancel_lead_dispute(Request $request) {
        $validator = Validator::make($request->all(), [
                    'lead_id' => 'required',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        } else {
            $web_settings = $this->web_settings;
            $requestArr = $request->all();

            $companyUserObj = Auth::guard('company_user')->user();
            $leadObj = Lead::find($requestArr['lead_id']);
            $companyObj = Company::find(Auth::guard('company_user')->user()->company_id);

            if (!is_null($leadObj)) {
                if ($companyObj->id == $leadObj->company_id) {
                    $leadObj->dispute_status = 'cancelled';
                    $leadObj->save();

                    /* Company generate dispute mail to Company */
                    $company_mail_id = "109"; /* Mail title: Lead Dispute */
                    $companyReplaceArr = [
                        'service_category' => $leadObj->service_category->title,
                        'company_name' => $companyObj->company_name,
                        'phone_number' => ((isset($web_settings['phone_number']) && $web_settings['phone_number'] != '') ? $web_settings['phone_number'] : ''),
                        'global_address' => ((isset($web_settings['office_address']) && $web_settings['office_address'] != '') ? $web_settings['office_address'] : ''),
                        'global_email' => ((isset($web_settings['global_email']) && $web_settings['global_email'] != '') ? $web_settings['global_email'] : ''),
                        'facebook_link' => ((isset($web_settings['facebook']) && $web_settings['facebook'] != '') ? $web_settings['facebook'] : ''),
                        'twitter_link' => ((isset($web_settings['twitter']) && $web_settings['twitter'] != '') ? $web_settings['twitter'] : ''),
                        'instagram_link' => ((isset($web_settings['instagram']) && $web_settings['instagram'] != '') ? $web_settings['instagram'] : ''),
                        'rumble_link' => ((isset($web_settings['rumble']) && $web_settings['rumble'] != '') ? $web_settings['rumble'] : ''),
                        'youtube_link' => ((isset($web_settings['youtube']) && $web_settings['youtube'] != '') ? $web_settings['youtube'] : ''),
                        'unsubscription_link' => url('unsubscribe-page/company', ['company_slug' => $companyObj->slug]),
                        'request_generate_link' => $leadObj->email,
                        'date' => $leadObj->created_at->format(env('DATE_FORMAT')),
                        'url' => url('leads-archive-inbox'),
                        'email_footer' => $leadObj->email,
                        'copyright_year' => date('Y'),
                    ];

                    $messageArr = [
                        'company_id' => $companyObj->id,
                        'message_type' => 'info',
                        'link' => url('leads-archive-inbox')
                    ];
                    Custom::companyMailMessageCreate($messageArr, $company_mail_id, $companyReplaceArr);
                    $mailArr = Custom::generate_company_user_email_arr($companyObj->company_information);
                    if (!is_null($mailArr) && count($mailArr) > 0) {
                        foreach ($mailArr AS $mail_item) {
                            Mail::to($mail_item)->send(new CompanyMail($company_mail_id, $companyReplaceArr));
                        }
                    }

                    if (isset($this->web_settings['global_email']) && $this->web_settings['global_email'] != '') {
                        /* Company generate dispute mail to Admin */
                        $admin_mail_id = "111"; /* Mail title: Lead Dispute - Admin */
                        $adminReplaceArr = [
                            'company_name' => $companyObj->company_name,
                            'service_category' => $leadObj->service_category->title,
                        ];
                        Mail::to($this->web_settings['global_email'])->send(new AdminMail($admin_mail_id, $adminReplaceArr));
                    }
                } else {
                    flash("You haven't authorize to cancel dispute.")->warning();
                }
            } else {
                flash("Lead not found.")->warning();
            }

            return back();
        }
    }

    public function delete_lead($company_lead_id) {
        $modelObj = $this->modelObj->findOrFail($company_lead_id);

        try {
            $modelObj->delete();
            flash("Lead has been deleted successfully!")->success();
            return back();
        } catch (Exception $e) {
            flash("Lead can not be deleted!")->danger();
            return back();
        }
    }

    public function update_monthly_budget(Request $request) {
        $validator = Validator::make($request->all(), [
                    'monthly_budget_type' => 'required',
                    //'monthly_budget_effect' => 'required',
                    'monthly_budget' => 'required'
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        } else {
            $requestArr = $request->all();
            $companyUserObj = Auth::guard('company_user')->user();
            $companyObj = Company::find(Auth::guard('company_user')->user()->company_id);

            $update_both = false;
            if ($requestArr['monthly_budget_type'] == 'Permanent') {

                if ($requestArr['monthly_budget_effect'] == 'Yes') {
                    $update_both = true;
                } else {
                    $companyObj->permanent_budget = $requestArr['monthly_budget'];
                }
                $companyObj->save();
            } else if ($requestArr['monthly_budget_type'] == 'Temporary') {
                $update_both = true;
            }

            if ($update_both) {
                $companyObj->permanent_budget = $requestArr['monthly_budget'];
                $companyObj->temporary_budget = $requestArr['monthly_budget'];
                $companyObj->save();

                /* Company update monthly budget mail to Company */
                $web_settings = $this->web_settings;
                $mail_id = '74'; /* Mail title: Company Monthly Budget */
                $replaceWithArr = [
                    'company_name' => $companyObj->company_name,
                    'monthly_budget' => '$'.number_format($requestArr['monthly_budget'], 2),
                    'phone_number' => ((isset($web_settings['phone_number']) && $web_settings['phone_number'] != '') ? $web_settings['phone_number'] : ''),
                    'global_address' => ((isset($web_settings['office_address']) && $web_settings['office_address'] != '') ? $web_settings['office_address'] : ''),
                    'global_email' => ((isset($web_settings['global_email']) && $web_settings['global_email'] != '') ? $web_settings['global_email'] : ''),
                    'facebook_link' => ((isset($web_settings['facebook']) && $web_settings['facebook'] != '') ? $web_settings['facebook'] : ''),
                    'twitter_link' => ((isset($web_settings['twitter']) && $web_settings['twitter'] != '') ? $web_settings['twitter'] : ''),
                    'instagram_link' => ((isset($web_settings['instagram']) && $web_settings['instagram'] != '') ? $web_settings['instagram'] : ''),
                    'rumble_link' => ((isset($web_settings['rumble']) && $web_settings['rumble'] != '') ? $web_settings['rumble'] : ''),
                    'youtube_link' => ((isset($web_settings['youtube']) && $web_settings['youtube'] != '') ? $web_settings['youtube'] : ''),
                    'unsubscription_link' => url('unsubscribe-page/company', ['company_slug' => $companyObj->slug]),
                    'request_generate_link' => $companyUserObj->email,
                    'date' => $companyObj->created_at->format(env('DATE_FORMAT')),
                    'url' => url('leads-archive-inbox'),
                    'email_footer' => $companyUserObj->email,
                    'copyright_year' => date('Y'),
                        //'main_service_category' => '',
                ];
                $mailArr = Custom::generate_company_user_email_arr($companyObj->company_information);
                if (!is_null($mailArr) && count($mailArr) > 0) {
                    foreach ($mailArr AS $mail_item) {
                        //Mail::to('ajay.makwana87@gmail.com')->send(new CompanyMail($mail_id, $replaceWithArr));
                        Mail::to($mail_item)->send(new CompanyMail($mail_id, $replaceWithArr));
                    }
                }

                /* Company update monthly budget mail to Admin */
                if (isset($web_settings['global_email']) && $web_settings['global_email'] != '') {
                    /* ppl invoice generate mail to Admin */
                    $admin_mail_id = "75"; /* Mail title: Company Monthly Budget - Admin */
                    $adminReplaceArr = [
                        'company_name' => $companyObj->company_name,
                        'monthly_budget' => '$'.number_format($requestArr['monthly_budget'], 2),
                    ];
                    Mail::to($web_settings['global_email'])->send(new AdminMail($admin_mail_id, $adminReplaceArr));
                }
            }

            flash("Monthly budget updated successfully.")->success();
            return back();
        }
    }

}

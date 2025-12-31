<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Mail\User\UserCustomMail;
use App\Mail\Company\CompanyCustomMail;
use App\Mail\Admin\AdminCustomMail;
use Illuminate\Support\Facades\Mail;
use Validator;
use DB;
use App\Models\Lead;
use App\Models\CompanyLead;
use App\Models\Trade;
use App\Models\Company;
use App\Models\ServiceCategoryType;
use App\Models\TopLevelCategory;
use App\Models\MainCategory;
use App\Models\ServiceCategory;
use App\Models\Custom;

class LeadController extends Controller {

    public function __construct() {
        $this->view_base = 'leads.';
    }

    public function find_a_pro() {
        $data = [
            'trades' => Trade::active()->order()->pluck('title', 'id')
        ];
        return view($this->view_base . 'find_a_pro', $data);
    }

    public function generate_lead(Request $request) {
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
                    'zipcode' => 'required',
        ]);

        if (isset($validator) && $validator->fails()) {
            return back()->withErrors($validator)->withInput();
        } else {
            $requestArr = $request->all();
            $requestArr['lead_activation_key'] = Custom::getRandomString(50);

            $lead = Lead::create($requestArr);


            /* Lead confirmation success mail to User Mail */
            $mail_id = '83';
            $replaceWithArr = [
                'first_name' => $lead->full_name,
                'service_category' => $lead->service_category->title,
                'confirm_request' => url('/activate-lead', ['activation_key' => $lead->lead_activation_key])
            ];
            Mail::to($requestArr['email'])->send(new UserCustomMail($mail_id, $replaceWithArr));
            //Mail::to('ajay.makwana87@gmail.com')->send(new UserCustomMail($mail_id, $replaceWithArr));

            flash("Your lead is generated successfully. Check email for activate lead.")->success();
            return redirect('find-a-pro');
        }
    }

    public function activate_lead($activation_key, Request $request) {

        $lead = Lead::where([
                    ['lead_activation_key', $activation_key],
                    ['lead_activated', 'no']
                ])->first();

        if (!is_null($lead)) {
            $lead->lead_activation_key = null;
            $lead->lead_active_date = now()->format(env('DB_DATE_FORMAT'));
            $lead->lead_activated = 'yes';
            $lead->save();

            Custom::generateCompanyLeads($lead);

            flash("Your lead is activated successfully.")->success();
        } else {
            flash("Lead not found.")->error();
        }

        return redirect('/');
    }

    public function send_leads() {
        $leads = Lead::activated()->get();

        if (count($leads) > 0) {
            foreach ($leads AS $lead_item) {
                $companies = Company::select('companies.id', 'membership_levels.hide_leads')
                        ->leftJoin('membership_levels', 'companies.membership_level_id', 'membership_levels.id')
                        ->leftJoin('company_zipcodes', 'companies.id', 'company_zipcodes.company_id')
                        ->leftJoin('company_service_categories', 'companies.id', 'company_service_categories.company_id')
                        ->where([
                            ['membership_levels.paid_members', 'yes'],
                            ['membership_levels.lead_access', 'yes'],
                            ['company_zipcodes.zip_code', $lead_item->zipcode],
                            ['company_service_categories.service_category_id', $lead_item->service_category_id]
                        ])
                        ->leadStatus('active')
                        ->active()
                        ->orderBy('companies.activated_at', 'ASC')
                        ->orderBy('companies.membership_level_id', 'ASC')
                        ->limit(3)
                        ->get();

                //dd($companies->toArray());

                if (count($companies) > 0) {
                    foreach ($companies AS $i => $company_item) {
                        $insertArr = [
                            'company_id' => $company_item->id,
                            'lead_id' => $lead_item->id,
                            'is_hidden' => $company_item->hide_leads,
                            'priority' => $i + 1
                        ];

                        CompanyLead::create($insertArr);
                    }
                }


                $preview_trial_companies = Company::select('companies.id', 'membership_levels.hide_leads')
                        ->leftJoin('membership_levels', 'companies.membership_level_id', 'membership_levels.id')
                        ->leftJoin('company_zipcodes', 'companies.id', 'company_zipcodes.company_id')
                        ->leftJoin('company_service_categories', 'companies.id', 'company_service_categories.company_id')
                        ->where([
                            ['membership_levels.id', '1'],
                            ['membership_levels.lead_access', 'yes'],
                            ['company_zipcodes.zip_code', $lead_item->zipcode],
                            ['company_service_categories.service_category_id', $lead_item->service_category_id]
                        ])
                        ->leadStatus('active')
                        ->order()
                        ->get();

                //dd($preview_trial_companies);

                if (count($preview_trial_companies) > 0) {
                    $get_id = CompanyLead::where('lead_id', $lead_item->id)->count();

                    foreach ($preview_trial_companies AS $company_item) {
                        $get_id++;
                        $insertArr = [
                            'company_id' => $company_item->id,
                            'lead_id' => $lead_item->id,
                            'is_hidden' => $company_item->hide_leads,
                            'priority' => $get_id
                        ];

                        CompanyLead::create($insertArr);
                    }
                }
            }

            dd("Company Lead generated.");
        }
    }

    public function send_lead_emails() {
        $lead = Lead::activated()->find(1);
        $company_leads = CompanyLead::where('lead_id', $lead->id)->isNotChecked()->order()->get();

        if (count($company_leads) > 0) {
            $owners_list = ['owner_2', 'owner_3', 'owner_4', 'office_manager', 'sales_manager', 'estimators_sales_1', 'estimators_sales_2'];
            $owners_list_email = ['owner_2_email', 'owner_3_email', 'owner_4_email', 'office_manager_email', 'sales_manager_email', 'estimators_sales_1_email', 'estimators_sales_2_email'];

            foreach ($company_leads AS $company_lead_item) {
                if (!is_null($company_lead_item->company->company_lead_notification)) {
                    $company_lead_notifications = $company_lead_item->company->company_lead_notification;

                    $email_addresses = [
                        $company_lead_notifications->main_email_address
                    ];
                    if ($company_lead_notifications->receive_a_copy == 'yes') {
                        foreach ($owners_list AS $i => $owner_item) {
                            $owner_email = $owners_list_email[$i];
                            if ($company_lead_notifications->$owner_item == 'yes' && !is_null($company_lead_notifications->$owner_email)) {
                                $email_addresses[] = $company_lead_notifications->$owner_email;
                            }
                        }
                    }

                    if (count($email_addresses) > 0) {
                        foreach ($email_addresses AS $email_address_item) {
                            /* Lead Generation mail to Company Mail */
                            $mail_id = '79';
                            $replaceWithArr = [
                                'name' => $lead->full_name,
                                'telephone' => $lead->phone,
                                'email' => $lead->email,
                                'street' => $lead->project_address,
                                'zipcode' => $lead->zipcode,
                                'project_info' => $lead->content,
                                'company_name' => $company_lead_item->company->company_name,
                            ];

                            Mail::to($email_address_item)->send(new CompanyCustomMail($mail_id, $replaceWithArr));
                            //Mail::to('ajay.makwana87@gmail.com')->send(new CompanyCustomMail($mail_id, $replaceWithArr));
                        }
                    }
                }
            }
        }
        dd("Company Lead Email sent successfully.");
    }

    /* Ajax Call methods */

    public function get_service_category_types(Request $request) {
        $data = [
            'service_category_types' => ServiceCategoryType::where('id', '!=', '3')->active()->order()->pluck('title', 'id')
        ];

        return view($this->view_base . '_service_category_types', $data);
    }

    public function get_top_level_categories(Request $request) {
        $top_level_categories = TopLevelCategory::active()->order();
        if ($request->has('trade_id') && $request->get('trade_id') != '') {
            $top_level_categories->leftJoin('top_level_category_trades', 'top_level_categories.id', 'top_level_category_trades.top_level_category_id')
                    ->leftJoin('service_categories', 'top_level_categories.id', 'service_categories.top_level_category_id')
                    ->where('top_level_category_trades.trade_id', $request->get('trade_id'))
                    ->where(DB::raw('COUNT(service_categories.id)'), '>', '0');
        }

        $data = [
            'top_level_categories' => $top_level_categories->pluck('top_level_categories.title', 'top_level_categories.id')->toArray()
        ];
        
        dd($data);

        return view($this->view_base . '_top_level_categories', $data);
    }

    public function get_category_selection(Request $request) {
        $main_categories = MainCategory::active()->order();
        if ($request->has('top_level_category_id') && $request->get('top_level_category_id') != '') {
            $main_categories->leftJoin('main_category_top_level_categories', 'main_categories.id', 'main_category_top_level_categories.main_category_id')
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

}

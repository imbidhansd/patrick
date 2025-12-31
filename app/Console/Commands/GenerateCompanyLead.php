<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Company;
use App\Models\Lead;
use App\Models\CompanyLead;

class GenerateCompanyLead extends Command {

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'CompanyLead:Generate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate Leads for Company.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct() {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle() {
        $company_lead_ids = [];
        $companyleads = CompanyLead::pluck('lead_id')->toArray();
        if (count($companyleads) > 0) {
            $leads = Lead::whereNotIn('id', $companyleads)->activated()->get();
        } else {
            $leads = Lead::activated()->get();
        }


        if (count($leads) > 0) {
            foreach ($leads AS $lead_item) {
                $paid_counter = 1;
                $paid_companies = Company::select('companies.id', 'companies.temporary_budget', 'membership_levels.hide_leads', 'membership_levels.charge_type', 'company_service_categories.fee')
                        ->leftJoin('membership_levels', 'companies.membership_level_id', 'membership_levels.id')
                        ->leftJoin('company_zipcodes', 'companies.id', 'company_zipcodes.company_id')
                        ->leftJoin('company_service_categories', 'companies.id', 'company_service_categories.company_id')
                        ->with('company_lead_notification')
                        ->where([
                            ['membership_levels.paid_members', 'yes'],
                            ['membership_levels.lead_access', 'yes'],
                            ['membership_levels.slug', '!=', 'accredited-member'],
                            ['membership_levels.status', 'active'],
                            ['company_zipcodes.zip_code', $lead_item->zipcode],
                            ['company_zipcodes.status', 'active'],
                            ['company_service_categories.service_category_id', $lead_item->service_category_id],
                            ['company_service_categories.status', 'active'],
                        ])
                        ->leadStatus('active')
                        ->active()
                        ->orderBy('companies.activated_at', 'ASC')
                        //->orderBy('companies.membership_level_id', 'ASC')
                        ->limit(3)
                        ->get();

                //dd($companies->toArray());

                if (count($paid_companies) > 0) {
                    foreach ($paid_companies AS $i => $company_item) {
                        $insertLead = true;
                        if ($company_item->charge_type == 'ppl_price') {
                            // check monthly budget exceed or not
                            $current_used_budget = CompanyLead::where('company_id', $company_item->id)
                                    ->where(DB::raw('MONTH(created_at)'), now()->format('m'))
                                    ->sum('fee');

                            if ($current_used_budget >= $company_item->temporary_budget) {
                                $insertLead = false;
                            }
                        }

                        if ($insertLead) {
                            $insertArr = [
                                'company_id' => $company_item->id,
                                'lead_id' => $lead_item->id,
                                'is_hidden' => $company_item->hide_leads,
                                'priority' => $i + 1
                            ];

                            if ($company_item->charge_type == 'ppl_price') {
                                $insertArr['fee'] = $company_item->fee;
                            }

                            $company_lead = CompanyLead::create($insertArr);

                            $company_lead_ids[] = $company_lead->id;
                            $paid_counter++;
                        }
                    }
                }


                // send leads for accrediation
                if ($paid_counter < 3) {
                    $paid_company_counter = Company::select('companies.id', 'companies.temporary_budget', 'membership_levels.hide_leads', 'membership_levels.charge_type', 'company_service_categories.fee')
                            ->leftJoin('membership_levels', 'companies.membership_level_id', 'membership_levels.id')
                            ->leftJoin('company_zipcodes', 'companies.id', 'company_zipcodes.company_id')
                            ->leftJoin('company_service_categories', 'companies.id', 'company_service_categories.company_id')
                            ->with('company_lead_notification')
                            ->where([
                                ['membership_levels.paid_members', 'yes'],
                                ['membership_levels.lead_access', 'yes'],
                                ['membership_levels.slug', '!=', 'accredited-member'],
                                ['membership_levels.status', 'active'],
                                ['company_zipcodes.zip_code', $lead_item->zipcode],
                                ['company_zipcodes.status', 'active'],
                                ['company_service_categories.service_category_id', $lead_item->service_category_id],
                                ['company_service_categories.status', 'active'],
                            ])
                            ->whereIn('companies.status', ['Unpaid Invoice', 'Suspended With Cause', 'Declined Payment', 'Temporarily Suspended'])
                            ->leadStatus('active')
                            ->orderBy('companies.activated_at', 'ASC')
                            //->orderBy('companies.membership_level_id', 'ASC')
                            ->count();

                    //Unpaid Invoice, Suspended With Cause, Declined Payment, Temporarily Suspended

                    if ($paid_company_counter >= 6) {
                        $accredited_companies = Company::select('companies.id', 'membership_levels.hide_leads')
                                ->leftJoin('membership_levels', 'companies.membership_level_id', 'membership_levels.id')
                                ->leftJoin('company_zipcodes', 'companies.id', 'company_zipcodes.company_id')
                                ->leftJoin('company_service_categories', 'companies.id', 'company_service_categories.company_id')
                                ->where([
                                    ['membership_levels.paid_members', 'yes'],
                                    ['membership_levels.lead_access', 'yes'],
                                    ['membership_levels.slug', 'accredited-member'],
                                    ['membership_levels.status', 'active'],
                                    ['company_zipcodes.zip_code', $lead_item->zipcode],
                                    ['company_zipcodes.status', 'active'],
                                    ['company_service_categories.service_category_id', $lead_item->service_category_id],
                                    ['company_service_categories.status', 'active'],
                                ])
                                ->leadStatus('active')
                                ->active()
                                ->orderBy('companies.activated_at', 'ASC')
                                ->get();

                        if (count($accredited_companies) > 0) {
                            $lead_priority = $paid_counter;
                            foreach ($accredited_companies AS $company_item) {
                                $insertArr = [
                                    'company_id' => $company_item->id,
                                    'lead_id' => $lead_item->id,
                                    'is_hidden' => $company_item->hide_leads,
                                    'priority' => $lead_priority
                                ];

                                $company_lead = CompanyLead::create($insertArr);

                                $company_lead_ids[] = $company_lead->id;
                                $lead_priority++;
                            }
                        }
                    }
                }


                // preview trial leads
                $preview_trial_companies = Company::select('companies.id', 'membership_levels.hide_leads')
                        ->leftJoin('membership_levels', 'companies.membership_level_id', 'membership_levels.id')
                        ->leftJoin('company_zipcodes', 'companies.id', 'company_zipcodes.company_id')
                        ->leftJoin('company_service_categories', 'companies.id', 'company_service_categories.company_id')
                        ->where([
                            /* Added on 17-2-2020 */
                            ['companies.company_subscribe_status', 'subscribed'],
                            ['membership_levels.id', '1'],
                            ['membership_levels.lead_access', 'yes'],
                            ['membership_levels.status', 'active'],
                            ['company_zipcodes.zip_code', $lead_item->zipcode],
                            ['company_zipcodes.status', 'active'],
                            ['company_service_categories.service_category_id', $lead_item->service_category_id],
                            ['company_service_categories.status', 'active'],
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

                        $company_lead = CompanyLead::create($insertArr);
                        $company_lead_ids[] = $company_lead->id;
                    }
                }
            }

            if (count($company_lead_ids) > 0) {
                $company_leads = CompanyLead::with(['company', 'lead'])
                        ->whereIn('lead_id', $company_lead_ids)
                        ->isNotChecked()
                        ->order()
                        ->get();

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

                                    if ($company_lead_item->is_hidden == 'yes') {
                                        $replaceWithArr = [
                                            'name' => '*****',
                                            'telephone' => '*****',
                                            'email' => '*****',
                                            'street' => '*****',
                                            'zipcode' => '*****',
                                            'project_info' => '*****',
                                            'company_name' => $company_lead_item->company->company_name . ' ' . $company_lead_item->company_id,
                                        ];
                                    } else {
                                        $replaceWithArr = [
                                            'name' => $company_lead_item->lead->full_name,
                                            'telephone' => $company_lead_item->lead->phone,
                                            'email' => $company_lead_item->lead->email,
                                            'street' => $company_lead_item->lead->project_address,
                                            'zipcode' => $company_lead_item->lead->zipcode,
                                            'project_info' => $company_lead_item->lead->content,
                                            'company_name' => $company_lead_item->company->company_name . ' ' . $company_lead_item->company_id,
                                        ];
                                    }

                                    //Mail::to('ajay.makwana87@gmail.com')->send(new CompanyCustomMail($mail_id, $replaceWithArr));
                                    Mail::to($email_address_item)->send(new CompanyCustomMail($mail_id, $replaceWithArr));
                                }
                            }
                        }
                    }
                }
            }
        }
    }

}

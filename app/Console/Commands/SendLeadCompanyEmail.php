<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Mail\Company\CompanyCustomMail;
use Illuminate\Support\Facades\Mail;
use App\Models\Lead;
use App\Models\CompanyLead;

class SendLeadCompanyEmail extends Command {

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'CompanyLead:Email';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send Emails to companies for new leads.';

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

                            //Mail::to('ajay.makwana87@gmail.com')->send(new CompanyCustomMail($mail_id, $replaceWithArr));
                        }
                    }
                }
            }
        }
        //dd("Company Lead Email sent successfully.");
    }

}

<?php

namespace App\Console\Commands;

use App\Mail\Company\CompanyMail;
use App\Mail\Admin\AdminMail;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use PDF;
use Log;
use App\Models\Company;
use App\Models\CompanyUser;
use App\Models\CompanyInvoice;
use App\Models\CompanyInvoiceItem;
use App\Models\CompanyServiceCategory;
use App\Models\Custom;
use Carbon\Carbon;

class GeneratePplInvoice extends Command {

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'PplInvoice:Generate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate PPL invoices for the pervious month leads';

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
        Log::info("GeneratePplInvoice called at " . Carbon::now('UTC'));

        $web_settings = Custom::getSettings();
        $service_category_type = ['main', 'sub', 'extra'];

        $companies = Company::select('companies.*')
                ->with(['ppl_company_leads', 'ppl_company_information'])
                ->leftJoin('membership_levels', 'companies.membership_level_id', 'membership_levels.id')
                ->where('membership_levels.charge_type', 'ppl_price')
                ->active()
                ->order()
                ->get();
        if (count($companies) > 0) {
            foreach ($companies AS $company_item) {
                /* get last month company leads start */
                if (isset($company_item->ppl_company_leads) && count($company_item->ppl_company_leads) > 0) {
                    $fileAttachments = [];
                    $lead_total_fee = 0;
                    $category_listing_desc = $service_category_arr = [];
                    foreach ($company_item->ppl_company_leads AS $company_lead_item) {
                        $lead_total_fee += $company_lead_item->fee;
                    }

                    $invoice_date = now()->format(env('DATE_FORMAT'));
                    $invoice_id = CompanyInvoice::getOrderNumber();

                    $company_invoice_insert_arr = [
                        'company_id' => $company_item->id,
                        'invoice_type' => 'PPL Lead Invoice',
                        'invoice_date' => $invoice_date,
                        'invoice_id' => $invoice_id,
                        'invoice_for' => "Referral List Pay-Per Lead Listing",
                        'final_amount' => $lead_total_fee,
                        'status' => $lead_total_fee > 0 ? 'pending' : 'no_payment_required'
                    ];

                    $company_invoice = CompanyInvoice::create($company_invoice_insert_arr);
                    $company_invoice_item_arr = [];
                    foreach ($company_item->ppl_company_leads AS $ary_count => $company_lead_item) {
                        $company_invoice_item_arr[$ary_count]['company_invoice_id'] = $company_invoice->id;
                        $company_invoice_item_arr[$ary_count]['title'] = $company_lead_item->lead->service_category->title;
                        $company_invoice_item_arr[$ary_count]['amount'] = $company_lead_item->fee;
                        $company_invoice_item_arr[$ary_count]['qty'] = '1';
                        $company_invoice_item_arr[$ary_count]['total'] = $company_lead_item->fee;
                        $company_invoice_item_arr[$ary_count]['description'] = $company_lead_item->lead->full_name.' - '.$company_lead_item->lead->zipcode;
                        $ary_count++;
                    }

                    CompanyInvoiceItem::insert($company_invoice_item_arr);
                    $data['company_invoice'] = $company_invoice;
                    $pdf = PDF::loadView('company.invoices.pdf', $data);
                    //$basePath = base_path();
                    //$uploadsPath = $basePath . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR . 'uploads' . DIRECTORY_SEPARATOR . 'company-invoices' . DIRECTORY_SEPARATOR;
                    $uploadsPath = storage_path('app' . DIRECTORY_SEPARATOR . 'private' . DIRECTORY_SEPARATOR . 'company-invoices'. DIRECTORY_SEPARATOR);
                    $fileName = $company_invoice->invoice_id . '.pdf';
                    $path = $uploadsPath . $fileName;
                    $pdf->save($path);
                    chown($path, 'www-data');
                    $fileAttachments[] = $path;

                    /* ppl invoice generate mail to Company Mail */
                    $companyUserObj = CompanyUser::where([
                                ['company_id', $company_item->id],
                                ['company_user_type', 'company_super_admin']
                            ])->first();
                    $mail_id = '67'; /* Mail title: Company Pay Per Lead Listing Invoice */
                    $replaceWithArr = [
                        'company_name' => $company_item->company_name,
                        'pay_now' => url('billing/invoice-payment', ['invoice_id' => $company_invoice->invoice_id]),
                        'phone_number' => ((isset($web_settings['phone_number']) && $web_settings['phone_number'] != '') ? $web_settings['phone_number'] : ''),
                        'global_domain' => ((isset($web_settings['global_domain']) && $web_settings['global_domain'] != '') ? $web_settings['global_domain'] : ''),
                        'global_address' => ((isset($web_settings['office_address']) && $web_settings['office_address'] != '') ? $web_settings['office_address'] : ''),
                        'global_email' => ((isset($web_settings['global_email']) && $web_settings['global_email'] != '') ? $web_settings['global_email'] : ''),
                        'facebook_link' => ((isset($web_settings['facebook']) && $web_settings['facebook'] != '') ? $web_settings['facebook'] : ''),
                        'twitter_link' => ((isset($web_settings['twitter']) && $web_settings['twitter'] != '') ? $web_settings['twitter'] : ''),
                        'rumble_link' => ((isset($web_settings['rumble']) && $web_settings['rumble'] != '') ? $web_settings['rumble'] : ''),
                        'instagram_link' => ((isset($web_settings['instagram']) && $web_settings['instagram'] != '') ? $web_settings['instagram'] : ''),
                        'youtube_link' => ((isset($web_settings['youtube']) && $web_settings['youtube'] != '') ? $web_settings['youtube'] : ''),
                        'unsubscription_link' => url('unsubscribe-page/company', ['company_slug' => $company_item->slug]),
                        'request_generate_link' => $companyUserObj->email,
                        'date' => $company_invoice->created_at->format(env('DATE_FORMAT')),
                        'url' => url('leads-archive-inbox'),
                        'email_footer' => $companyUserObj->email,
                        'copyright_year' => date('Y'),
                            //'main_service_category' => '',
                    ];

                    $messageArr = [
                        'company_id' => $company_item->id,
                        'message_type' => 'info',
                        'link' => url('leads-archive-inbox')
                    ];
                    Custom::companyMailMessageCreate($messageArr, $mail_id, $replaceWithArr);

                    $mailArr = Custom::generate_company_user_email_arr($company_item->company_information);
                    if (!is_null($mailArr) && count($mailArr) > 0) {
                        foreach ($mailArr AS $mail_item) {
                            Mail::to($mail_item)->send(new CompanyMail($mail_id, $replaceWithArr, $fileAttachments));
                        }
                    }


                    /* ppl invoice generate mail to Admin */
                    if (isset($web_settings['global_email']) && $web_settings['global_email'] != '') {
                        $admin_mail_id = "68"; /* Mail title: Company Pay Per Lead Listing Invoice - Admin */
                        $adminReplaceArr = [
                            'company_name' => $company_item->company_name,
                        ];
                        Mail::to($web_settings['global_email'])->send(new AdminMail($admin_mail_id, $adminReplaceArr, $fileAttachments));
                    }
                }
            }
        }
    }

}

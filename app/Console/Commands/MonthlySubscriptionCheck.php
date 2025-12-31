<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Mail\Company\CompanyMail;
use App\Mail\Admin\AdminMail;
use Illuminate\Support\Facades\Mail;
use App\Models\Custom;
use App\Models\Company;
use App\Models\CompanyUser;
use App\Models\CompanyInvoice;
use App\Models\CompanyInvoiceSubscription;

class MonthlySubscriptionCheck extends Command {

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'MonthlySubscription:Check';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check monthly subscription and generate invoices';

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
        $web_settings = Custom::getSettings();
        $services_list = [
            'Main Category Listing',
            'Secondary Category Listing',
            'Extra Category Listing',
        ];

        $companies = Company::select('companies.*')
                ->with('membership_level')
                ->leftJoin('membership_levels', 'companies.membership_level_id', 'membership_levels.id')
                ->where([
                    ['membership_levels.charge_type', 'monthly_price'],
                    ['companies.renewal_date', '<=', now()->format(env('DB_DATE_FORMAT'))]
                ])
                ->whereNotNull('companies.subscription_id')
                ->active()
                ->order()
                ->get();
        if (count($companies) > 0) {
            foreach ($companies AS $company_item) {
                /* Get Subscription details */
                $subscription_transactions = Custom::get_subscription($company_item->subscription_id);

                if (!is_null($subscription_transactions) && !isset($subscription_transactions['success'])) {

                    foreach ($subscription_transactions AS $transaction_item) {
                        $company_invoice_subscription = CompanyInvoiceSubscription::where([
                                    ['company_id', $company_item->id],
                                    ['transaction_id', $transaction_item->getTransId()]
                                ])->first();

                        if (is_null($company_invoice_subscription)) {
                            $company_invoice = CompanyInvoice::where([
                                        ['company_id', $company_item->id],
                                        ['invoice_type', 'Referral List'],
                                        ['status', 'paid']
                                    ])
                                    ->latest()
                                    ->first();

                            $final_subscription_amount = 0;
                            $company_invoice_items = $company_invoice->company_invoice_item;
                            if (count($company_invoice_items) > 0) {
                                foreach ($company_invoice_items AS $company_invoice_item) {
                                    if (in_array($company_invoice_item->title, $services_list)) {
                                        $final_subscription_amount += $company_invoice_item->amount;
                                    }
                                }
                            }

                            $createNewInvoice = [
                                'company_id' => $company_item->id,
                                'invoice_type' => 'Monthly Subscription Invoice',
                                'payment_type' => 'credit_card',
                                'invoice_date' => now()->format(env('DATE_FORMAT')),
                                'invoice_id' => CompanyInvoice::getOrderNumber(),
                                'invoice_for' => 'Referral List Monthly Listing',
                                'final_amount' => $final_subscription_amount,
                                'transaction_id' => $transaction_item->getTransId(),
                                'invoice_paid_date' => now()->format(env('DATE_FORMAT')),
                                'status' => 'paid',
                            ];

                            $newInvoice = CompanyInvoice::create($createNewInvoice);
                            if (count($company_invoice_items) > 0) {
                                foreach ($company_invoice_items AS $company_invoice_item) {
                                    if (in_array($company_invoice_item->title, $services_list)) {
                                        $newInvoiceItem = $company_invoice_item->replicate();
                                        $newInvoiceItem->company_invoice_id = $newInvoice->id;
                                        $newInvoiceItem->save();
                                    }
                                }
                            }

                            $insertArr = [
                                'company_id' => $company_item->id,
                                'invoice_id' => $newInvoice->id,
                                'transaction_id' => $transaction_item->getTransId()
                            ];
                            CompanyInvoiceSubscription::create($insertArr);
                        }
                    }


                    /* Company monthly subscription mail to Company */
                    $companyUserObj = CompanyUser::where([
                                ['company_id', $company_item->id],
                                ['company_user_type', 'company_super_admin']
                            ])->first();
                    $company_mail_id = "62"; /* Mail title: Company Monthly Subscription */
                    $companyReplaceArr = [
                        'company_name' => $company_item->company_name,
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
                        'date' => $company_item->created_at->format(env('DATE_FORMAT')),
                        'url' => url('dashboard'),
                        'email_footer' => $companyUserObj->email,
                        'copyright_year' => date('Y'),
                            //'main_service_category' => '',
                    ];

                    $messageArr = [
                        'company_id' => $company_item->id,
                        'message_type' => 'info',
                        'link' => url('dashboard')
                    ];
                    Custom::companyMailMessageCreate($messageArr, $company_mail_id, $companyReplaceArr);

                    $mailArr = Custom::generate_company_user_email_arr($company_item->company_information);
                    if (!is_null($mailArr) && count($mailArr) > 0) {
                        foreach ($mailArr AS $mail_item) {
                            Mail::to($mail_item)->send(new CompanyMail($company_mail_id, $companyReplaceArr));
                        }
                    }


                    /* Company monthly subscription mail to Admin */
                    if (isset($web_settings['global_email']) && $web_settings['global_email'] != '') {
                        $admin_mail_id = "63"; /* Mail title: Company Monthly Subscription - Admin */
                        $adminReplaceArr = [
                            'company_name' => $company_item->company_name,
                        ];
                        Mail::to($web_settings['global_email'])->send(new AdminMail($admin_mail_id, $adminReplaceArr));
                    }
                } else {
                    $company_invoice = CompanyInvoice::where([
                                ['company_id', $company_item->id],
                                ['invoice_type', 'Referral List'],
                                ['status', 'paid']
                            ])
                            ->latest()
                            ->first();

                    $final_subscription_amount = 0;
                    $company_invoice_items = $company_invoice->company_invoice_item;
                    if (count($company_invoice_items) > 0) {
                        foreach ($company_invoice_items AS $company_invoice_item) {
                            if (in_array($company_invoice_item->title, $services_list)) {
                                $final_subscription_amount += $company_invoice_item->amount;
                            }
                        }
                    }

                    $createNewInvoice = [
                        'company_id' => $company_item->id,
                        'invoice_type' => 'Monthly Subscription Invoice',
                        //'payment_type' => 'credit_card',
                        'invoice_date' => now()->format(env('DATE_FORMAT')),
                        'invoice_id' => CompanyInvoice::getOrderNumber(),
                        'invoice_for' => 'Referral List Monthly Listing',
                        'final_amount' => $final_subscription_amount,
                        'status' => 'pending',
                    ];

                    $newInvoice = CompanyInvoice::create($createNewInvoice);
                    if (count($company_invoice_items) > 0) {
                        foreach ($company_invoice_items AS $company_invoice_item) {
                            if (in_array($company_invoice_item->title, $services_list)) {
                                $newInvoiceItem = $company_invoice_item->replicate();
                                $newInvoiceItem->company_invoice_id = $newInvoice->id;
                                $newInvoiceItem->save();
                            }
                        }
                    }
                    $company_item->status = "Unpaid Invoice";
                    $company_item->save();

                    /* Company monthly subscription failed mail to Company */
                    $companyUserObj = CompanyUser::where([
                                ['company_id', $company_item->id],
                                ['company_user_type', 'company_super_admin']
                            ])->first();
                    $company_mail_id = "60"; /* Mail title: Company Monthly Subscription Failed */
                    $companyReplaceArr = [
                        'company_name' => $company_item->company_name,
                        'global_address' => ((isset($web_settings['office_address']) && $web_settings['office_address'] != '') ? $web_settings['office_address'] : ''),
                        'phone_number' => ((isset($web_settings['phone_number']) && $web_settings['phone_number'] != '') ? $web_settings['phone_number'] : ''),
                        'global_email' => ((isset($web_settings['global_email']) && $web_settings['global_email'] != '') ? $web_settings['global_email'] : ''),
                        'facebook_link' => ((isset($web_settings['facebook']) && $web_settings['facebook'] != '') ? $web_settings['facebook'] : ''),
                        'twitter_link' => ((isset($web_settings['twitter']) && $web_settings['twitter'] != '') ? $web_settings['twitter'] : ''),
                        'rumble_link' => ((isset($web_settings['rumble']) && $web_settings['rumble'] != '') ? $web_settings['rumble'] : ''),
                        'instagram_link' => ((isset($web_settings['instagram']) && $web_settings['instagram'] != '') ? $web_settings['instagram'] : ''),
                        'youtube_link' => ((isset($web_settings['youtube']) && $web_settings['youtube'] != '') ? $web_settings['youtube'] : ''),
                        'unsubscription_link' => url('unsubscribe-page/company', ['company_slug' => $company_item->slug]),
                        'request_generate_link' => $companyUserObj->email,
                        'date' => $company_item->created_at->format(env('DATE_FORMAT')),
                        'url' => url('dashboard'),
                        'email_footer' => $companyUserObj->email,
                        'copyright_year' => date('Y'),
                            //'main_service_category' => '',
                    ];

                    $messageArr = [
                        'company_id' => $company_item->id,
                        'message_type' => 'info',
                        'link' => url('dashboard')
                    ];
                    Custom::companyMailMessageCreate($messageArr, $company_mail_id, $companyReplaceArr);
                    $mailArr = Custom::generate_company_user_email_arr($company_item->company_information);
                    if (!is_null($mailArr) && count($mailArr) > 0) {
                        foreach ($mailArr AS $mail_item) {
                            Mail::to($mail_item)->send(new CompanyMail($company_mail_id, $companyReplaceArr));
                        }
                    }


                    /* Company monthly subscription failed mail to Admin */
                    if (isset($this->web_settings['global_email']) && $this->web_settings['global_email'] != '') {
                        $admin_mail_id = "61"; /* Mail title: Company Monthly Subscription Failed - Admin */
                        $adminReplaceArr = [
                            'company_name' => $company_item->company_name,
                        ];
                        Mail::to($this->web_settings['global_email'])->send(new AdminMail($admin_mail_id, $adminReplaceArr));
                    }


                    /* Monthly Listing invoice generate mail to Company Mail */
                    $companyUserObj = CompanyUser::where([
                                ['company_id', $company_item->id],
                                ['company_user_type', 'company_super_admin']
                            ])->first();
                    $mail_id = '142'; /* Mail title: Monthly Listing Company Status Change - Unpaid Invoice */
                    $replaceWithArr = [
                        'company_name' => $company_item->company_name,
                        'global_address' => ((isset($web_settings['office_address']) && $web_settings['office_address'] != '') ? $web_settings['office_address'] : ''),
                        'phone_number' => ((isset($web_settings['phone_number']) && $web_settings['phone_number'] != '') ? $web_settings['phone_number'] : ''),
                        'global_email' => ((isset($web_settings['global_email']) && $web_settings['global_email'] != '') ? $web_settings['global_email'] : ''),
                        'facebook_link' => ((isset($web_settings['facebook']) && $web_settings['facebook'] != '') ? $web_settings['facebook'] : ''),
                        'twitter_link' => ((isset($web_settings['twitter']) && $web_settings['twitter'] != '') ? $web_settings['twitter'] : ''),
                        'instagram_link' => ((isset($web_settings['instagram']) && $web_settings['instagram'] != '') ? $web_settings['instagram'] : ''),
                        'rumble_link' => ((isset($web_settings['rumble']) && $web_settings['rumble'] != '') ? $web_settings['rumble'] : ''),
                        'youtube_link' => ((isset($web_settings['youtube']) && $web_settings['youtube'] != '') ? $web_settings['youtube'] : ''),
                        'unsubscription_link' => url('unsubscribe-page/company', ['company_slug' => $company_item->slug]),
                        'request_generate_link' => $companyUserObj->email,
                        'date' => $company_item->created_at->format(env('DATE_FORMAT')),
                        'url' => url('dashboard'),
                        'email_footer' => $companyUserObj->email,
                        'copyright_year' => date('Y'),
                            //'main_service_category' => '',
                    ];

                    $messageArr = [
                        'company_id' => $company_item->id,
                        'message_type' => 'info',
                        'link' => url('dashboard')
                    ];
                    Custom::companyMailMessageCreate($messageArr, $mail_id, $replaceWithArr);
                    $mailArr = Custom::generate_company_user_email_arr($company_item->company_information);
                    if (!is_null($mailArr) && count($mailArr) > 0) {
                        foreach ($mailArr AS $mail_item) {
                            Mail::to($mail_item)->send(new CompanyMail($mail_id, $replaceWithArr));
                        }
                    }


                    if (isset($web_settings['global_email']) && $web_settings['global_email'] != '') {
                        /* Monthly Listing invoice generate mail to Admin */
                        $admin_mail_id = "141"; /* Mail title: Monthly Listing Company Status Change - Admin */
                        $adminReplaceArr = [
                            'company_name' => $company_item->company_name,
                            //'company_status' => 'Unpaid Invoice'
                        ];
                        Mail::to($web_settings['global_email'])->send(new AdminMail($admin_mail_id, $adminReplaceArr));
                    }
                }
            }
        }
    }

}

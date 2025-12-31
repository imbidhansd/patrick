<?php

namespace App\Http\Controllers\crons;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Mail\Company\CompanyMail;
use App\Mail\Admin\AdminMail;
use Illuminate\Support\Facades\Mail;
use PDF;
use App\Models\Company;
use App\Models\CompanyUser;
use App\Models\CompanyLead;
use App\Models\CompanyInvoice;
use App\Models\CompanyInvoiceItem;
use App\Models\CompanyInvoiceSubscription;
use App\Models\CompanyServiceCategory;
use App\Models\Custom;

class PaymentController extends Controller {

    public function subscription_process_check() {
        $web_settings = Custom::getSettings();
        $services_list = [
            'Main Category Listing',
            'Secondary Category Listing',
            'Extra Category Listing',
        ];

        //now()->format(env('DB_DATE_FORMAT'))
        /* $companies = Company::select('companies.*')
          ->with('membership_level')
          ->leftJoin('membership_levels', 'companies.membership_level_id', 'membership_levels.id')
          ->where([
          ['membership_levels.charge_type', 'monthly_price'],
          ['companies.renewal_date', '<=', now()->format(env('DB_DATE_FORMAT'))]
          ])
          ->whereNotNull('companies.subscription_id')
          ->active()
          ->order()
          ->get(); */
        $companies = Company::select('companies.*')
                ->with('membership_level')
                ->where('id', '60')
                ->whereNotNull('subscription_id')
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

    public function generate_ppl_invoice() {
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


                    // get referral list invoice details
                    $referral_list_invoice = CompanyInvoice::where([
                                ['company_id', $company_item->id],
                                ['invoice_type', 'Referral List'],
                            ])
                            ->latest()
                            ->first();


                    $invoice_date = now()->format(env('DATE_FORMAT'));
                    $invoice_id = CompanyInvoice::getOrderNumber();

                    $company_invoice_insert_arr = [
                        'company_id' => $company_item->id,
                        'ship_address_id' => ((!is_null($referral_list_invoice)) ? $referral_list_invoice->ship_address_id : null),
                        'bill_address_id' => ((!is_null($referral_list_invoice)) ? $referral_list_invoice->bill_address_id : null),
                        'invoice_type' => 'PPL Lead Invoice',
                        'invoice_date' => $invoice_date,
                        'invoice_id' => $invoice_id,
                        'invoice_for' => "Referral List Pay-Per Lead Listing",
                        'final_amount' => $lead_total_fee,
                        'status' => 'pending'
                    ];

                    $company_invoice = CompanyInvoice::create($company_invoice_insert_arr);
                    $company_invoice_item_arr = [];
                    foreach ($company_item->ppl_company_leads AS $ary_count => $company_lead_item) {
                        $company_invoice_item_arr[$ary_count]['company_invoice_id'] = $company_invoice->id;
                        $company_invoice_item_arr[$ary_count]['title'] = $company_lead_item->lead->service_category->title;
                        $company_invoice_item_arr[$ary_count]['amount'] = $company_lead_item->fee;
                        $company_invoice_item_arr[$ary_count]['qty'] = '1';
                        $company_invoice_item_arr[$ary_count]['total'] = $company_lead_item->fee;
                        $company_invoice_item_arr[$ary_count]['description'] = $company_lead_item->lead->full_name . ' - ' . $company_lead_item->lead->zipcode;
                        $ary_count++;
                    }
                    CompanyInvoiceItem::insert($company_invoice_item_arr);


                    $data['company_invoice'] = $company_invoice;
                    $pdf = PDF::loadView('company.invoices.pdf', $data);
                    // $pdf->save('uploads/company-invoices/' . $company_invoice->invoice_id . '.pdf');
                    // $fileAttachments[] = 'uploads/company-invoices/' . $company_invoice->invoice_id . '.pdf';
                    $pdf_save_path = storage_path('app' . DIRECTORY_SEPARATOR . 'private' . DIRECTORY_SEPARATOR . 'company-invoices'. DIRECTORY_SEPARATOR . $company_invoice->invoice_id . '.pdf');
                    $pdf->save($pdf_save_path);
                    $fileAttachments[] = $pdf_save_path;


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
                        'instagram_link' => ((isset($web_settings['instagram']) && $web_settings['instagram'] != '') ? $web_settings['instagram'] : ''),
                        'rumble_link' => ((isset($web_settings['rumble']) && $web_settings['rumble'] != '') ? $web_settings['rumble'] : ''),
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
                            //Mail::to('kuldeep.ows@gmail.com')->send(new CompanyMail($mail_id, $replaceWithArr, $fileAttachments));
                        }
                    }

                    if (isset($web_settings['global_email']) && $web_settings['global_email'] != '') {
                        /* ppl invoice generate mail to Admin */
                        $admin_mail_id = "68"; /* Mail title: Company Pay Per Lead Listing Invoice - Admin */
                        $adminReplaceArr = [
                            'company_name' => $company_item->company_name,
                        ];
                        Mail::to($web_settings['global_email'])->send(new AdminMail($admin_mail_id, $adminReplaceArr, $fileAttachments));
                        //Mail::to('kuldeep.ows@gmail.com')->send(new AdminMail($admin_mail_id, $adminReplaceArr, $fileAttachments));
                    }
                }
            }
        }

        echo ("Invoice generated successfully.");
    }

    public function check_ppl_invoice() {
        $web_settings = Custom::getSettings();
        /* $companies = Company::select('companies.*')
          ->leftJoin('membership_levels', 'companies.membership_level_id', 'membership_levels.id')
          ->where('membership_levels.charge_type', 'ppl_price')
          ->active()
          ->order()
          ->get(); */
        $companies = Company::select('companies.*')
                ->leftJoin('membership_levels', 'companies.membership_level_id', 'membership_levels.id')
                ->where('membership_levels.charge_type', 'ppl_price')
                ->active()
                ->order()
                ->where('companies.id', '60')
                ->get();
        if (count($companies) > 0) {
            foreach ($companies AS $company_item) {
                $get_company_invoices = CompanyInvoice::where([
                            ['company_id', $company_item->id]
                        ])
                        ->whereNull('transaction_id')
                        ->count();

                if ($get_company_invoices > 0) {
                    $company_item->status = "Unpaid Invoice";
                    $company_item->save();

                    CompanyLead::where('company_id', $company_item->id)->update(['is_hidden' => 'yes']);
                    /* ppl invoice generate mail to Company Mail */
                    $companyUserObj = CompanyUser::where([
                                ['company_id', $company_item->id],
                                ['company_user_type', 'company_super_admin']
                            ])->first();
                    $mail_id = '139'; /* Mail title: PPL Company Status Change - Unpaid Invoice */
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
                            //Mail::to('ajay.makwana87@gmail.com')->send(new CompanyMail($mail_id, $replaceWithArr));
                            Mail::to($mail_item)->send(new CompanyMail($mail_id, $replaceWithArr));
                        }
                    }


                    if (isset($web_settings['global_email']) && $web_settings['global_email'] != '') {
                        /* ppl invoice generate mail to Admin */
                        $admin_mail_id = "140"; /* Mail title: PPL Company Status Change - Admin */
                        $adminReplaceArr = [
                            'company_name' => $company_item->company_name,
                                //'company_status' => 'Unpaid Invoice'
                        ];
                        Mail::to($web_settings['global_email'])->send(new AdminMail($admin_mail_id, $adminReplaceArr));
                    }
                }
            }
            /* get last month company leads end */
        }

        echo "Company status change successfully.";
    }

}

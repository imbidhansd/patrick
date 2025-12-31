<?php

namespace App\Http\Controllers\company;

use App\Http\Controllers\Controller;
use App\Mail\Company\CompanyMail;
use App\Mail\Admin\AdminMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Redirect;
use Auth;
use Validator;
use PDF;
// Models
use App\Models\Custom;
use App\Models\Company;
use App\Models\CompanyInformation;
use App\Models\CompanyInvoice;
use App\Models\CompanyApprovalStatus;
use App\Models\CompanyInvoiceAddress;
use App\Models\Order;
use App\Models\Page;
use App\Models\State;

class CompanyInvoiceController extends Controller {

    public function __construct() {
        $this->web_settings = Custom::getSettings();
        $this->view_base = 'company.invoices.';
    }

    public function billing(Request $request) {
        $companyObj = Company::find(Auth::guard('company_user')->user()->company_id);
        $data['company_invoice'] = CompanyInvoice::where('company_id', $companyObj->id)->order()->paginate(env('APP_RECORDS_PER_PAGE'));

        return view($this->view_base . 'company_invoice', $data);
    }

    public function view_invoice($invoice_id) {
        $company_invoice = CompanyInvoice::where('invoice_id', $invoice_id)->first();
        if (is_null($company_invoice)) {
            flash('Invoice Not found.')->error();
            return redirect('billing');
        }
        $data['company_invoice'] = $company_invoice;

        return view($this->view_base . '.company_invoice_detail', $data);
    }

    public function invoice_payment($invoice_id, Request $request) {
        $companyObj = Company::find(Auth::guard('company_user')->user()->company_id);
        $company_invoice = CompanyInvoice::where([
                    ['company_id', $companyObj->id],
                    ['invoice_id', $invoice_id],
                    ['status', 'pending']
                ])->first();

        $last_invoice_item = CompanyInvoice::with(['ship_address', 'bill_address'])
                ->where([
                    ['company_id', $companyObj->id],
                    ['status', 'paid']
                ])
                ->latest()
                ->first();

        if (is_null($company_invoice)) {
            flash("No Invoice found.")->error();
            return back();
        }

        $company_information_obj = CompanyInformation::where('company_id', $companyObj->id)->first();

        $exp_month_list = [
            '01' => '01', '02' => '02', '03' => '03', '04' => '04',
            '05' => '05', '06' => '06', '07' => '07', '08' => '08',
            '09' => '09', '10' => '10', '11' => '11', '12' => '12',
        ];

        $exp_year_list = [];
        for ($i = date('Y'); $i <= date('Y') + 20; $i++) {
            $exp_year_list[$i] = (int) $i;
        }

        $data = [
            'admin_page_title' => 'Submit Payment',
            'terms_use_page' => Page::find(7),
            'company_user_obj' => Auth::guard('company_user')->user(),
            'companyObj' => $companyObj,
            'company_information_obj' => $company_information_obj,
            'company_invoice' => $company_invoice,
            'exp_month_list' => $exp_month_list,
            'exp_year_list' => $exp_year_list,
            'states' => State::order()->pluck('name', 'id'),
            'last_invoice_item' => $last_invoice_item,
        ];

        return view($this->view_base . 'invoice_payment', $data);
    }

    public function postInvoicePayment(Request $request) {
        $validationArr = [
            'company_id' => 'required',
            'invoice_id' => 'required',
            // Shipping
            'ship.company_name' => 'required',
            'ship.first_name' => 'required',
            'ship.last_name' => 'required',
            'ship.mailing_address' => 'required',
            //'ship.suite' => 'required',
            'ship.city' => 'required',
            'ship.state_id' => 'required',
            //'ship.county' => 'required',
            'ship.zipcode' => 'required',
            'ship.phone' => 'required',
            // Billing
            'bill.company_name' => 'required',
            'bill.first_name' => 'required',
            'bill.last_name' => 'required',
            'bill.mailing_address' => 'required',
            //'bill.suite' => 'required',
            'bill.city' => 'required',
            'bill.state_id' => 'required',
            //'bill.county' => 'required',
            'bill.zipcode' => 'required',
            'bill.phone' => 'required',
        ];

        if ($request->has('payment_option') && $request->get('payment_option') == 'credit_card') {
            $validator = Validator::make($request->all(), $validationArr);
        } else if ($request->has('payment_option') && $request->get('payment_option') == 'check') {
            $validator = Validator::make($request->all(), $validationArr);
        } else if (!$request->has('payment_option')) {
            flash('Payment Method not found.')->error();
            return back();
        }


        if (isset($validator) && $validator->fails()) {
            return back()->withErrors($validator)->withInput();
        } else {
            $web_settings = $this->web_settings;

            $fileAttachments = [];
            $requestArr = $request->all();
            $companyUserObj = Auth::guard('company_user')->user();
            $companyObj = Company::with('membership_level')->find($companyUserObj->company_id);

            $company_invoice = CompanyInvoice::where([
                        ['company_id', $companyObj->id],
                        ['invoice_id', $requestArr['invoice_id']],
                        ['status', 'pending']
                    ])->first();

            if (is_null($company_invoice)) {
                flash('Invoice Not found.')->error();
                return back();
            }

            $ship_address_arr = $requestArr['ship'];
            $ship_address_arr['company_id'] = $companyUserObj->company_id;
            $ship_address_arr['address_type'] = 'ship';
            $ship_address_obj = CompanyInvoiceAddress::firstOrCreate($ship_address_arr);

            $bill_address_arr = $requestArr['ship'];
            $bill_address_arr['company_id'] = $companyUserObj->company_id;
            $bill_address_arr['address_type'] = 'bill';
            $bill_address_obj = CompanyInvoiceAddress::firstOrCreate($bill_address_arr);

            $company_invoice->ship_address_id = $ship_address_obj->id;
            $company_invoice->bill_address_id = $bill_address_obj->id;
            $company_invoice->save();

            if ($requestArr['payment_option'] == 'credit_card') {
                $final_amount = 0;

                if ($companyObj->membership_level->charge_type == 'ppl_price' || ($companyObj->membership_level->charge_type == 'annual_price' && $companyObj->membership_level_id == '7')) {
                    if ($companyObj->membership_level->charge_type == 'ppl_price' && in_array($company_invoice->invoice_type, ['Referral List', 'PPL Lead Invoice'])) {
                        $final_amount = $company_invoice->final_amount;
                    } else {
                        $company_invoice_items = $company_invoice->company_invoice_item;
                        if (count($company_invoice_items) > 0) {
                            foreach ($company_invoice_items AS $company_invoice_item) {
                                if ($company_invoice_item->title == "Membership Fee") {
                                    $final_amount += $company_invoice_item->amount;
                                }
                            }
                        }
                    }
                } else {
                    $final_amount = $company_invoice->final_amount;
                }

                $payment_fields = [
                    // 'card_number' => $requestArr['card_number'],
                    // 'exp_year' => $requestArr['exp_year'],
                    // 'exp_month' => $requestArr['exp_month'],
                    'final_amount' => $final_amount,
                    // billing
                    'bill_first_name' => $bill_address_obj->first_name,
                    'bill_last_name' => $bill_address_obj->last_name,
                    'bill_company_name' => $bill_address_obj->company_name,
                    'bill_address' => $bill_address_obj->mailing_address,
                    'bill_city' => $bill_address_obj->city,
                    'bill_state' => $bill_address_obj->state->short_name,
                    'bill_zipcode' => $bill_address_obj->zipcode,
                    'bill_county' => $bill_address_obj->county,
                    // shipping
                    'ship_first_name' => $ship_address_obj->first_name,
                    'ship_last_name' => $ship_address_obj->last_name,
                    'ship_company_name' => $ship_address_obj->company_name,
                    'ship_address' => $ship_address_obj->mailing_address,
                    'ship_city' => $ship_address_obj->city,
                    'ship_state' => $ship_address_obj->state->short_name,
                    'ship_zipcode' => $ship_address_obj->zipcode,
                    'ship_county' => $ship_address_obj->county,
                    'payment_name' => $company_invoice->invoice_for,
                    'success_url' => env('APP_URL').'/billing/invoice-payment/'.$company_invoice->invoice_id.'/checkout-success?session_id={CHECKOUT_SESSION_ID}',
                    'cancel_url' => env('APP_URL').'/billing/invoice-payment/'.$company_invoice->invoice_id.'/checkout-cancel?session_id={CHECKOUT_SESSION_ID}'
                ];

                //dd($payment_fields);

                //$payment_response = Custom::authorizePayment($payment_fields);
                $payment_response = Custom::authorizeStripePayment($payment_fields);

                //create order
                $order = new Order();
                $order->status = $payment_response->payment_status;
                $order->total_price = ($payment_response->amount_total)/100;
                $order->session_id = $payment_response->id;
                $order->company_id = $companyUserObj->company_id;
                $order->company_invoice1_id = $company_invoice->id;
                $order->save();
                return Redirect::away($payment_response->url);

                if ($payment_response->getResponseCode() == "1") {
                    $transaction_id = $payment_response->getTransId();

                    $company_invoice->status = "paid";
                    $company_invoice->invoice_paid_date = now()->format(env('DATE_FORMAT'));
                    $company_invoice->payment_type = "credit_card";
                    $company_invoice->transaction_id = $transaction_id;
                    $company_invoice->save();


                    if ($companyObj->membership_level->charge_type == 'monthly_price' && $company_invoice->invoice_type == 'Referral List') {
                        $final_subscription_amount = 0;
                        $company_invoice_items = $company_invoice->company_invoice_item;
                        if (count($company_invoice_items) > 0) {
                            foreach ($company_invoice_items AS $company_invoice_item) {
                                if ($company_invoice_item->title != "Annual Membership/Endorsment Fee") {
                                    $final_subscription_amount += $company_invoice_item->amount;
                                }
                            }
                        }


                        if ($final_subscription_amount != 0) {
                            $subcsription_payment_fields = [
                                'company_name' => $companyObj->company_name,
                                'invoice_id' => $company_invoice->invoice_id,
                                'invoice_type' => $company_invoice->invoice_type,
                                'first_name' => $companyUserObj->first_name,
                                'last_name' => $companyUserObj->last_name,
                                'card_number' => $requestArr['card_number'],
                                'exp_year' => $requestArr['exp_year'],
                                'exp_month' => $requestArr['exp_month'],
                                'final_amount' => $final_subscription_amount,
                                'subscription_occurance' => '11',
                                'subscription_start_date' => \Carbon\Carbon::now()->addDays(30),
                            ];

                            $monthly_subscription = Custom::monthly_subscription($subcsription_payment_fields);
                            if (($monthly_subscription != null) && ($monthly_subscription->getMessages()->getResultCode() == "Ok")) {
                                $companyObj->subscription_id = $monthly_subscription->getSubscriptionId();
                                $companyObj->save();
                            }
                        }
                    }
                } else {
                    if (!is_null($payment_response)) {
                        $errorMessages = $payment_response->getErrors();
                        $error_msg = "Response : " . $errorMessages[0]->getErrorCode() . " - " . $errorMessages[0]->getErrorText();
                    } else {
                        $error_msg = "Error while processing your transaction, Kindly try again or contact to our administrator";
                    }

                    flash($error_msg)->error();
                    return back()->withErrors($validator)->withInput();
                    //return back();
                }


                if ($company_invoice->invoice_type == 'One Time Setup Fee & Prescreen/Background Check Fees') {
                    $company_approval_status = CompanyApprovalStatus::firstOrCreate(['company_id' => $companyObj->id]);
                    $company_approval_status->one_time_setup_fee = 'completed';
                    $company_approval_status->background_check_pre_screen_fees = 'completed';
                    $company_approval_status->save();
                }



                if (in_array($company_invoice->invoice_type, ['Referral List', 'PPL Lead Invoice', 'Monthly Subscription Invoice'])) {
                    if ($company_invoice->invoice_type != 'PPL Lead Invoice') {
                        $daysToAdd = $companyObj->membership_level->number_of_days;
                        $approval_date = \Carbon\Carbon::now();
                        $renewal_date = \Carbon\Carbon::now()->addDays($daysToAdd);
                        $registered_date = \Carbon\Carbon::now()->format('m/Y');

                        $companyObj->approval_date = $approval_date->format(env('DB_DATE_FORMAT'));
                        $companyObj->renewal_date = $renewal_date->format(env('DB_DATE_FORMAT'));
                    }


                    if ($company_invoice->invoice_type == 'PPL Lead Invoice') {
                        \App\Models\CompanyLead::where('company_id', $companyObj->id)->update(['is_hidden' => 'no']);
                    }

                    $companyObj->status = "Active";
                    if ($company_invoice->invoice_type == 'Referral List') {
                        $companyObj->leads_status = "active";
                    } else {
                        if ($companyObj->leads_status == 'inactive' && (!is_null($companyObj->lead_resume_date) || $companyObj->lead_resume_date <= now()->format(env('DB_DATE_FORMAT')))) {
                            $companyObj->leads_status = "active";
                        }
                    }
                    $companyObj->save();

                    if ($company_invoice->invoice_type == 'Referral List') {
                        /* Company Activated mail to Company */
                        //$active_company_mail_id = "31"; /* Mail title: Company Membership Activated */
                        /* Company Activated mail to Admin */
                        $active_admin_mail_id = "32"; /* Mail title: Company Membership Activated - Admin */
                    }


                    $data['company_invoice'] = $company_invoice;
                    $pdf = PDF::loadView('company.invoices.pdf', $data);
                    $pdf->mpdf->setWatermarkText('PAID');
                    $pdf->mpdf->showWatermarkText = true;
                    //$pdf->setWatermarkImage(env('APP_URL') . 'images/paid.png');
                    $uploadsPath = storage_path('app' . DIRECTORY_SEPARATOR . 'private' . DIRECTORY_SEPARATOR . 'company-invoices'. DIRECTORY_SEPARATOR. $data['company_invoice']->invoice_id . '.pdf');
                    // $pdf->save('uploads/company-invoices/' . $data['company_invoice']->invoice_id . '.pdf');
                    // $fileAttachments[] = 'uploads/company-invoices/' . $data['company_invoice']->invoice_id . '.pdf';
                    $pdf->save($uploadsPath);
                    $fileAttachments[] = $uploadsPath;

                    /* Credit Card Payment mail to Company Mail */
                    $company_mail_id = "29"; /* Mail title: Company Approved Credit Card Payment Email */
                    $admin_mail_id = '30'; /* Mail title: Company Approved Credit Card Payment Email - Admin */
                    if ($company_invoice->invoice_type == 'PPL Lead Invoice') {
                        $company_mail_id = "131"; /* Mail title: Company PPL Lead Invoice Credit Card Payment Email */
                        $admin_mail_id = '133'; /* Mail title: Company PPL Lead Invoice Credit Card Payment Email - Admin */
                    } else if ($company_invoice->invoice_type == 'Monthly Subscription Invoice') {
                        $company_mail_id = "132"; /* Mail title: Company Monthly subscription Invoice Credit Card Payment Email */
                        $admin_mail_id = '134'; /* Mail title: Company Monthly subscription Invoice Credit Card Payment Email - Admin */
                    }
                }
            } else {
                $company_invoice->payment_type = "check";
                $company_invoice->save();

                /* Check Payment mail to Company Mail */
                $company_mail_id = '27'; /* Mail title: Company Approved Check Payment Email */
                $admin_mail_id = '28'; /* Mail title: Company Approved Check Payment Email - Admin */

                if ($company_invoice->invoice_type == 'PPL Lead Invoice') {
                    $company_mail_id = "135"; /* Mail title: Company PPL Lead Invoice Check Payment Email */
                    $admin_mail_id = '137'; /* Mail title: Company PPL Lead Invoice Check Payment Email - Admin */
                } else if ($company_invoice->invoice_type == 'Monthly Subscription Invoice') {
                    $company_mail_id = "136"; /* Mail title: Company Monthly subscription Check Payment Email */
                    $admin_mail_id = '138'; /* Mail title: Company Monthly subscription Check Payment Email - Admin */
                }
            }


            /* Company upgrade account mail to Company */
            if (isset($company_mail_id) && $company_mail_id != '') {
                $companyReplaceWithArr = [
                    'company_name' => $companyObj->company_name,
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
                    'request_generate_link' => $companyUserObj->email,
                    'date' => $companyObj->created_at->format(env('DATE_FORMAT')),
                    'url' => url('billing'),
                    'email_footer' => $companyUserObj->email,
                    'copyright_year' => date('Y'),
                        //'main_service_category' => '',
                ];

                $messageArr = [
                    'company_id' => $companyObj->id,
                    'message_type' => 'info',
                    'link' => url('billing'),
                ];
                Custom::companyMailMessageCreate($messageArr, $company_mail_id, $companyReplaceWithArr);
                $mailArr = Custom::generate_company_user_email_arr($companyObj->company_information);
                if (!is_null($mailArr) && count($mailArr) > 0) {
                    foreach ($mailArr AS $mail_item) {
                        Mail::to($mail_item)->send(new CompanyMail($company_mail_id, $companyReplaceWithArr, $fileAttachments));
                    }
                }
            }

            /* Company upgrade account mail to Admin */
            if (isset($admin_mail_id) && $admin_mail_id != '') {
                $adminReplaceWithArr = [
                    'company_name' => $companyObj->company_name,
                ];

                if (isset($this->web_settings['global_email']) && $this->web_settings['global_email'] != '') {
                    Mail::to($this->web_settings['global_email'])->send(new AdminMail($admin_mail_id, $adminReplaceWithArr, $fileAttachments));
                }
            }


            /* Company Active emails */
            if (isset($active_company_mail_id) && $active_company_mail_id != '') {
                $companyReplaceWithArr = [
                    'company_name' => $companyObj->company_name,
                    'account_type' => $companyObj->membership_level->title,
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
                    'url' => url('dashboard'),
                    'email_footer' => $companyUserObj->email,
                    'copyright_year' => date('Y'),
                        //'main_service_category' => '',
                ];

                $messageArr = [
                    'company_id' => $companyObj->id,
                    'message_type' => 'info',
                    'link' => url('dashboard'),
                ];
                Custom::companyMailMessageCreate($messageArr, $active_company_mail_id, $companyReplaceWithArr);
                $mailArr = Custom::generate_company_user_email_arr($companyObj->company_information);
                if (!is_null($mailArr) && count($mailArr) > 0) {
                    foreach ($mailArr AS $mail_item) {
                        Mail::to($mail_item)->send(new CompanyMail($active_company_mail_id, $companyReplaceWithArr));
                    }

                }
            }


            if (isset($active_admin_mail_id) && $active_admin_mail_id != '') {
                $adminReplaceWithArr = [
                    'company_name' => $companyObj->company_name,
                ];

                if (isset($this->web_settings['global_email']) && $this->web_settings['global_email'] != '') {
                    Mail::to($this->web_settings['global_email'])->send(new AdminMail($active_admin_mail_id, $adminReplaceWithArr));
                }
            }

            flash("Invoice #" . $company_invoice->invoice_id . " has been paid successfully.")->success();
            return redirect("dashboard");
        }
    }

    public function checkout_cancel($invoice_id,Request $request) {
        $companyObj = Company::with('membership_level')->find(Auth::guard('company_user')->user()->company_id);
        $session_id = $request->get('session_id');
        $stripe = new \Stripe\StripeClient(env('STRIPE_SECRET_KEY'));
        $session = $stripe->checkout->sessions->retrieve($session_id);
         //Get the order details
         $orderBySessionId = Order::where(
            [
                'session_id' => $session_id,
                'company_id' => $companyObj->id,
            ])->first();
        $orderBySessionId->status = 'cancelled';
        $orderBySessionId->save();
        return redirect("/billing/invoice-payment/{$invoice_id}");
    }

    public function checkout_success(Request $request) {
        $companyUserObj = Auth::guard('company_user')->user();
        $companyObj = Company::with('membership_level')->find($companyUserObj->company_id);
        $session_id = $request->get('session_id');
        $stripe = new \Stripe\StripeClient(env('STRIPE_SECRET_KEY'));
        $session = $stripe->checkout->sessions->retrieve($session_id);
        //dd($session);
        //$customer =  $stripe->customers->retrieve($session->customer);
        //Get the order details
        $orderBySessionId = Order::where(
            [
                'session_id' => $session_id,
                'company_id' => $companyObj->id,
            ])->first();
        $orderBySessionId->status = 'paid';
        $orderBySessionId->save();
        $company_invoice = CompanyInvoice::where('id', $orderBySessionId->company_invoice1_id)->first();

        $company_invoice->status = "paid";
        $company_invoice->invoice_paid_date = now()->format(env('DATE_FORMAT'));
        $company_invoice->payment_type = "credit_card";
        $company_invoice->transaction_id = $session->payment_intent;
        $company_invoice->save();

        // if ($companyObj->membership_level->charge_type == 'monthly_price' && $company_invoice->invoice_type == 'Referral List') {
        //     $final_subscription_amount = 0;
        //     $company_invoice_items = $company_invoice->company_invoice_item;
        //     if (count($company_invoice_items) > 0) {
        //         foreach ($company_invoice_items AS $company_invoice_item) {
        //             if ($company_invoice_item->title != "Annual Membership/Endorsment Fee") {
        //                 $final_subscription_amount += $company_invoice_item->amount;
        //             }
        //         }
        //     }

        //     if ($final_subscription_amount != 0) {
        //         $subcsription_payment_fields = [
        //             'company_name' => $companyObj->company_name,
        //             'invoice_id' => $company_invoice->invoice_id,
        //             'invoice_type' => $company_invoice->invoice_type,
        //             'first_name' => $companyUserObj->first_name,
        //             'last_name' => $companyUserObj->last_name,
        //             'card_number' => $requestArr['card_number'],
        //             'exp_year' => $requestArr['exp_year'],
        //             'exp_month' => $requestArr['exp_month'],
        //             'final_amount' => $final_subscription_amount,
        //             'subscription_occurance' => '11',
        //             'subscription_start_date' => \Carbon\Carbon::now()->addDays(30),
        //         ];

        //         $monthly_subscription = Custom::monthly_subscription($subcsription_payment_fields);
        //         if (($monthly_subscription != null) && ($monthly_subscription->getMessages()->getResultCode() == "Ok")) {
        //             $companyObj->subscription_id = $monthly_subscription->getSubscriptionId();
        //             $companyObj->save();
        //         }
        //     }
        // }

        if ($company_invoice->invoice_type == 'One Time Setup Fee & Prescreen/Background Check Fees') {
            $company_approval_status = CompanyApprovalStatus::firstOrCreate(['company_id' => $companyObj->id]);
            $company_approval_status->one_time_setup_fee = 'completed';
            $company_approval_status->background_check_pre_screen_fees = 'completed';
            $company_approval_status->save();
        }

        if (in_array($company_invoice->invoice_type, ['Referral List', 'PPL Lead Invoice', 'Monthly Subscription Invoice'])) {
            if ($company_invoice->invoice_type != 'PPL Lead Invoice') {
                $daysToAdd = $companyObj->membership_level->number_of_days;
                $approval_date = \Carbon\Carbon::now();
                $renewal_date = \Carbon\Carbon::now()->addDays($daysToAdd);
                $registered_date = \Carbon\Carbon::now()->format('m/Y');

                $companyObj->approval_date = $approval_date->format(env('DB_DATE_FORMAT'));
                $companyObj->renewal_date = $renewal_date->format(env('DB_DATE_FORMAT'));
            }


            if ($company_invoice->invoice_type == 'PPL Lead Invoice') {
                \App\Models\CompanyLead::where('company_id', $companyObj->id)->update(['is_hidden' => 'no']);
            }

            $companyObj->status = "Active";
            if ($company_invoice->invoice_type == 'Referral List') {
                $companyObj->leads_status = "active";
            } else {
                if ($companyObj->leads_status == 'inactive' && (!is_null($companyObj->lead_resume_date) || $companyObj->lead_resume_date <= now()->format(env('DB_DATE_FORMAT')))) {
                    $companyObj->leads_status = "active";
                }
            }
            $companyObj->save();

            if ($company_invoice->invoice_type == 'Referral List') {
                /* Company Activated mail to Company */
                //$active_company_mail_id = "31"; /* Mail title: Company Membership Activated */
                /* Company Activated mail to Admin */
                $active_admin_mail_id = "32"; /* Mail title: Company Membership Activated - Admin */
            }


            $data['company_invoice'] = $company_invoice;
            $pdf = PDF::loadView('company.invoices.pdf', $data);
            $pdf->mpdf->setWatermarkText('PAID');
            $pdf->mpdf->showWatermarkText = true;
            //$pdf->setWatermarkImage(env('APP_URL') . 'images/paid.png');
            $uploadsPath = storage_path('app' . DIRECTORY_SEPARATOR . 'private' . DIRECTORY_SEPARATOR . 'company-invoices'. DIRECTORY_SEPARATOR. $data['company_invoice']->invoice_id . '.pdf');
            // $pdf->save('uploads/company-invoices/' . $data['company_invoice']->invoice_id . '.pdf');
            // $fileAttachments[] = 'uploads/company-invoices/' . $data['company_invoice']->invoice_id . '.pdf';
            $pdf->save($uploadsPath);
            $fileAttachments[] = $uploadsPath;


            /* Credit Card Payment mail to Company Mail */
            $company_mail_id = "29"; /* Mail title: Company Approved Credit Card Payment Email */
            $admin_mail_id = '30'; /* Mail title: Company Approved Credit Card Payment Email - Admin */
            if ($company_invoice->invoice_type == 'PPL Lead Invoice') {
                $company_mail_id = "131"; /* Mail title: Company PPL Lead Invoice Credit Card Payment Email */
                $admin_mail_id = '133'; /* Mail title: Company PPL Lead Invoice Credit Card Payment Email - Admin */
            } else if ($company_invoice->invoice_type == 'Monthly Subscription Invoice') {
                $company_mail_id = "132"; /* Mail title: Company Monthly subscription Invoice Credit Card Payment Email */
                $admin_mail_id = '134'; /* Mail title: Company Monthly subscription Invoice Credit Card Payment Email - Admin */
            }
        }

         /* Company upgrade account mail to Company */
         if (isset($company_mail_id) && $company_mail_id != '') {
            $companyReplaceWithArr = [
                'company_name' => $companyObj->company_name,
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
                'request_generate_link' => $companyUserObj->email,
                'date' => $companyObj->created_at->format(env('DATE_FORMAT')),
                'url' => url('billing'),
                'email_footer' => $companyUserObj->email,
                'copyright_year' => date('Y'),
                    //'main_service_category' => '',
            ];

            $messageArr = [
                'company_id' => $companyObj->id,
                'message_type' => 'info',
                'link' => url('billing'),
            ];
            Custom::companyMailMessageCreate($messageArr, $company_mail_id, $companyReplaceWithArr);
            $mailArr = Custom::generate_company_user_email_arr($companyObj->company_information);
            if (!is_null($mailArr) && count($mailArr) > 0) {
                foreach ($mailArr AS $mail_item) {
                    Mail::to($mail_item)->send(new CompanyMail($company_mail_id, $companyReplaceWithArr, $fileAttachments));
                }
            }
        }

        /* Company upgrade account mail to Admin */
        if (isset($admin_mail_id) && $admin_mail_id != '') {
            $adminReplaceWithArr = [
                'company_name' => $companyObj->company_name,
            ];

            if (isset($this->web_settings['global_email']) && $this->web_settings['global_email'] != '') {
                Mail::to($this->web_settings['global_email'])->send(new AdminMail($admin_mail_id, $adminReplaceWithArr, $fileAttachments));
            }
        }

        /* Company Active emails */
        if (isset($active_company_mail_id) && $active_company_mail_id != '') {
            $companyReplaceWithArr = [
                'company_name' => $companyObj->company_name,
                'account_type' => $companyObj->membership_level->title,
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
                'url' => url('dashboard'),
                'email_footer' => $companyUserObj->email,
                'copyright_year' => date('Y'),
                    //'main_service_category' => '',
            ];

            $messageArr = [
                'company_id' => $companyObj->id,
                'message_type' => 'info',
                'link' => url('dashboard'),
            ];
            Custom::companyMailMessageCreate($messageArr, $active_company_mail_id, $companyReplaceWithArr);
            $mailArr = Custom::generate_company_user_email_arr($companyObj->company_information);
            if (!is_null($mailArr) && count($mailArr) > 0) {
                foreach ($mailArr AS $mail_item) {
                    Mail::to($mail_item)->send(new CompanyMail($active_company_mail_id, $companyReplaceWithArr));
                }
            }
        }

        if (isset($active_admin_mail_id) && $active_admin_mail_id != '') {
            $adminReplaceWithArr = [
                'company_name' => $companyObj->company_name,
            ];

            if (isset($this->web_settings['global_email']) && $this->web_settings['global_email'] != '') {
                Mail::to($this->web_settings['global_email'])->send(new AdminMail($active_admin_mail_id, $adminReplaceWithArr));
            }
        }

        flash("Invoice #" . $company_invoice->invoice_id . " has been paid successfully.")->success();
        return redirect("dashboard");
    }

    public function download_invoice($invoice_id) {
        $data['company_invoice'] = CompanyInvoice::where('invoice_id', $invoice_id)->first();

        $pdf = PDF::loadView('company.invoices.pdf', $data);
        if ($data['company_invoice']->status == 'paid') {
            $pdf->mpdf->setWatermarkText('PAID');
            $pdf->mpdf->showWatermarkText = true;
            //$pdf->setWatermarkImage(env('APP_URL') . 'images/paid.png');
        }
        $uploadsPath = storage_path('app' . DIRECTORY_SEPARATOR . 'private' . DIRECTORY_SEPARATOR . 'company-invoices'. DIRECTORY_SEPARATOR. $data['company_invoice']->invoice_id . '.pdf');
        // $pdf->save('uploads/company-invoices/' . $data['company_invoice']->invoice_id . '.pdf');
        // $pdf->download('invoice-' . $data['company_invoice']->invoice_id . '.pdf');
        $pdf->save($uploadsPath);
        $pdf->download($uploadsPath);
    }

    public function cancel_subscription(Request $request) {
        $validator = Validator::make($request->all(), [
                    'company_id' => 'required',
        ]);

        if (isset($validator) && $validator->fails()) {
            return back()->withErrors($validator)->withInput();
        } else {
            $requestArr = $request->all();
            $companyUserObj = Auth::guard('company_user')->user();
            $companyObj = Company::with('membership_level')->find(Auth::guard('company_user')->user()->company_id);

            if ($companyObj->membership_level->charge_type == 'monthly_price' && $companyObj->status == 'Active') {
                $subscription_request = Custom::cancel_subscription($companyObj->subscription_id);
                if (!$subscription_request['success']) {
                    flash($subscription_request['message'])->error();
                    return back();
                }

                $companyObj->status = 'Temporarily Suspended';
                $companyObj->save();


                /* Company monthly subscription cancelled mail to company */
                $web_settings = $this->web_settings;
                $company_mail_id = "65"; /* Mail title: Company Monthly Subscription Cancelled */
                $companyReplaceArr = [
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
                    'request_generate_link' => $companyUserObj->email,
                    'date' => $companyObj->created_at->format(env('DATE_FORMAT')),
                    'url' => url('billing'),
                    'email_footer' => $companyUserObj->email,
                    'copyright_year' => date('Y'),
                        //'main_service_category' => '',
                ];

                $messageArr = [
                    'company_id' => $companyObj->id,
                    'message_type' => 'info',
                    'link' => url('billing')
                ];
                Custom::companyMailMessageCreate($messageArr, $company_mail_id, $companyReplaceArr);

                $mailArr = Custom::generate_company_user_email_arr($companyObj->company_information);
                if (!is_null($mailArr) && count($mailArr) > 0) {
                    foreach ($mailArr AS $mail_item) {
                        Mail::to($mail_item)->send(new CompanyMail($company_mail_id, $companyReplaceArr));
                    }
                }


                /* Company monthly subscription cancelled mail to admin */
                if (isset($this->web_settings['global_email']) && $this->web_settings['global_email'] != '') {
                    $admin_mail_id = "66"; /* Mail title: Company Monthly Subscription Cancelled - Admin */
                    $adminReplaceArr = [
                        'company_name' => $companyObj->company_name,
                    ];
                    Mail::to($this->web_settings['global_email'])->send(new AdminMail($admin_mail_id, $adminReplaceArr));
                }

                flash($subscription_request['message'])->success();
            } else {
                flash("You cannot cancel subscription.")->error();
            }

            return back();
        }
    }

}

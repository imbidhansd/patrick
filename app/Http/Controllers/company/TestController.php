<?php

namespace App\Http\Controllers\company;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use PDF;
use App\Models\Company;
use App\Models\CompanyUser;
use App\Models\CompanyInvoice;
use App\Models\CompanyInvoiceItem;
use App\Models\CompanyServiceCategory;
use App\Models\Custom;
use App\Models\CompanyZipcode;
use App\Models\CompanyLeadPriority;
use net\authorize\api\contract\v1 as AnetAPI;
use net\authorize\api\controller as AnetController;
use Spatie\Browsershot\Browsershot;

use App\Mail\Company\CompanyMail;
use App\Mail\Admin\AdminMail;
use Illuminate\Support\Facades\Mail;

class TestController extends Controller {

    public function index() {

        define("AUTHORIZENET_LOG_FILE", "phplog");

        // Common setup for API credentials
        $merchantAuthentication = new AnetAPI\MerchantAuthenticationType();
        $merchantAuthentication->setName('64Wh6SsR');
        $merchantAuthentication->setTransactionKey('4UEN958kSd2E9qks');
        $refId = 'ref123' . time();

        // Create the payment data for a credit card
        $creditCard = new AnetAPI\CreditCardType();
        $creditCard->setCardNumber("4111111111111111");
        $creditCard->setExpirationDate("2038-12");
        $paymentOne = new AnetAPI\PaymentType();
        $paymentOne->setCreditCard($creditCard);

        // Set the customer's Bill To address
        $customerAddress = new AnetAPI\CustomerAddressType();
        $customerAddress->setFirstName("Ellen");
        $customerAddress->setLastName("Johnson");
        $customerAddress->setCompany("Souveniropolis");
        $customerAddress->setAddress("14 Main Street");
        $customerAddress->setCity("Pecan Springs");
        $customerAddress->setState("TX");
        $customerAddress->setZip("44628");
        $customerAddress->setCountry("USA");

        // Create a transaction
        $transactionRequestType = new AnetAPI\TransactionRequestType();
        $transactionRequestType->setTransactionType("authCaptureTransaction");
        $transactionRequestType->setAmount(151.51);
        $transactionRequestType->setPayment($paymentOne);
        $transactionRequestType->setBillTo($customerAddress);
        $transactionRequestType->setShipTo($customerAddress);


        $request = new AnetAPI\CreateTransactionRequest();
        $request->setMerchantAuthentication($merchantAuthentication);
        $request->setRefId($refId);
        $request->setTransactionRequest($transactionRequestType);
        $controller = new AnetController\CreateTransactionController($request);
        $response = $controller->executeWithApiResponse(\net\authorize\api\constants\ANetEnvironment::PRODUCTION);

        dd($response);
        if ($response != null) {
            $tresponse = $response->getTransactionResponse();

            dd($tresponse);

            if (($tresponse != null) && ($tresponse->getResponseCode() == "1")) {
                echo "Charge Credit Card AUTH CODE : " . $tresponse->getAuthCode() . "\n";
                echo "Charge Credit Card TRANS ID  : " . $tresponse->getTransId() . "\n";
            } else {
                echo "Charge Credit Card ERROR :  Invalid response\n";
            }
        } else {
            echo "Charge Credit Card Null response returned";
        }
    }

    public function subscription() {
        define("AUTHORIZENET_LOG_FILE", "phplog");

        // Common setup for API credentials
        $merchantAuthentication = new AnetAPI\MerchantAuthenticationType();
        $merchantAuthentication->setName('64Wh6SsR');
        $merchantAuthentication->setTransactionKey('4UEN958kSd2E9qks');


        $refId = 'ref' . time();

        // Subscription Type Info
        $subscription = new AnetAPI\ARBSubscriptionType();
        $subscription->setName("Sample Subscription");

        $interval = new AnetAPI\PaymentScheduleType\IntervalAType();
        $interval->setLength(7);
        $interval->setUnit("days");

        $paymentSchedule = new AnetAPI\PaymentScheduleType();
        $paymentSchedule->setInterval($interval);
        $paymentSchedule->setStartDate(now());
        $paymentSchedule->setTotalOccurrences("12");
        //$paymentSchedule->setTrialOccurrences("1");

        $subscription->setPaymentSchedule($paymentSchedule);
        $subscription->setAmount(rand(1, 99999) / 12.0 * 12);
        //$subscription->setTrialAmount("0.00");

        $creditCard = new AnetAPI\CreditCardType();
        $creditCard->setCardNumber("4111111111111111");
        $creditCard->setExpirationDate("2038-12");

        $payment = new AnetAPI\PaymentType();
        $payment->setCreditCard($creditCard);
        $subscription->setPayment($payment);

        $order = new AnetAPI\OrderType();
        $order->setInvoiceNumber("1234354");
        $order->setDescription("Description of the subscription");
        $subscription->setOrder($order);

        $billTo = new AnetAPI\NameAndAddressType();
        $billTo->setFirstName("John");
        $billTo->setLastName("Smith");

        $subscription->setBillTo($billTo);

        $request = new AnetAPI\ARBCreateSubscriptionRequest();
        $request->setmerchantAuthentication($merchantAuthentication);
        $request->setRefId($refId);
        $request->setSubscription($subscription);
        $controller = new AnetController\ARBCreateSubscriptionController($request);

        $response = $controller->executeWithApiResponse(\net\authorize\api\constants\ANetEnvironment::PRODUCTION);

        dd($response);

        if (($response != null) && ($response->getMessages()->getResultCode() == "Ok")) {
            dd($response->getSubscriptionId());
            echo "SUCCESS: Subscription ID : " . $response->getSubscriptionId() . "\n";
        } else {
            echo "ERROR :  Invalid response\n";
            $errorMessages = $response->getMessages()->getMessage();
            echo "Response : " . $errorMessages[0]->getCode() . "  " . $errorMessages[0]->getText() . "\n";
        }
        return $response;

        //exit;

        dd("Company Lead Priority added successfully.");
        //dd($companies);

        /* Set Lead Priority [End] */
    }

    public function check_subscription() {
        define("AUTHORIZENET_LOG_FILE", "phplog");

        // Common setup for API credentials
        $merchantAuthentication = new AnetAPI\MerchantAuthenticationType();
        $merchantAuthentication->setName('9X7Rq6a5h');
        $merchantAuthentication->setTransactionKey('767c35DB9r5wP6kX');


        $refId = 'ref' . time();

        $subscriptionId = '6354160';

        $request = new AnetAPI\ARBGetSubscriptionStatusRequest();
        $request->setMerchantAuthentication($merchantAuthentication);
        $request->setRefId($refId);
        $request->setSubscriptionId($subscriptionId);

        $controller = new AnetController\ARBGetSubscriptionStatusController($request);

        $response = $controller->executeWithApiResponse(\net\authorize\api\constants\ANetEnvironment::SANDBOX);

        dd($response);

        if (($response != null) && ($response->getMessages()->getResultCode() == "Ok")) {
            echo "SUCCESS: Subscription Status : " . $response->getStatus() . "\n";
        } else {
            echo "ERROR :  Invalid response\n";
            $errorMessages = $response->getMessages()->getMessage();
            echo "Response : " . $errorMessages[0]->getCode() . "  " . $errorMessages[0]->getText() . "\n";
        }

        return $response;
    }

    public function get_subscription() {
        define("AUTHORIZENET_LOG_FILE", "phplog");

        // Common setup for API credentials
        $merchantAuthentication = new AnetAPI\MerchantAuthenticationType();
        $merchantAuthentication->setName('9X7Rq6a5h');
        $merchantAuthentication->setTransactionKey('767c35DB9r5wP6kX');

        $subscriptionId = '6355722';


        // Set the transaction's refId
        $refId = 'ref' . time();

        // Creating the API Request with required parameters
        $request = new AnetAPI\ARBGetSubscriptionRequest();
        $request->setMerchantAuthentication($merchantAuthentication);
        $request->setRefId($refId);
        $request->setSubscriptionId($subscriptionId);
        $request->setIncludeTransactions(true);

        // Controller
        $controller = new AnetController\ARBGetSubscriptionController($request);

        // Getting the response
        $response = $controller->executeWithApiResponse(\net\authorize\api\constants\ANetEnvironment::SANDBOX);

        if ($response != null) {
            dd($response);

            if ($response->getMessages()->getResultCode() == "Ok") {
                // Success
                echo "SUCCESS: GetSubscription:" . "<br />";
                // Displaying the details
                echo "Subscription Name: " . $response->getSubscription()->getName() . "<br />";
                echo "Subscription Invoice No: " . $response->getSubscription()->getOrder()->getInvoiceNumber() . "<br />";
                echo "Subscription amount: " . $response->getSubscription()->getAmount() . "<br />";
                echo "Subscription status: " . $response->getSubscription()->getStatus() . "<br />";
                echo "Subscription Description: " . $response->getSubscription()->getProfile()->getDescription() . "<br />";
                echo "Customer Profile ID: " . $response->getSubscription()->getProfile()->getCustomerProfileId() . "<br />";
                echo "Customer payment Profile ID: " . $response->getSubscription()->getProfile()->getPaymentProfile()->getCustomerPaymentProfileId() . "<br />";
                $transactions = $response->getSubscription()->getArbTransactions();
                if ($transactions != null) {
                    foreach ($transactions as $transaction) {
                        echo "Transaction ID : " . $transaction->getTransId() . " -- " . $transaction->getResponse() . " -- Pay Number : " . $transaction->getPayNum() . "<br />";
                    }
                }
            } else {
                // Error
                echo "ERROR :  Invalid response\n";
                $errorMessages = $response->getMessages()->getMessage();
                echo "Response : " . $errorMessages[0]->getCode() . "  " . $errorMessages[0]->getText() . "\n";
            }
        } else {
            // Failed to get response
            echo "Null Response Error";
        }

        //echo $response;
        exit;
    }

    public function cancel_subscription() {
        define("AUTHORIZENET_LOG_FILE", "phplog");

        // Common setup for API credentials
        $merchantAuthentication = new AnetAPI\MerchantAuthenticationType();
        $merchantAuthentication->setName('9X7Rq6a5h');
        $merchantAuthentication->setTransactionKey('767c35DB9r5wP6kX');

        $subscriptionId = '6352487';


        // Set the transaction's refId
        $refId = 'ref' . time();

        $request = new AnetAPI\ARBCancelSubscriptionRequest();
        $request->setMerchantAuthentication($merchantAuthentication);
        $request->setRefId($refId);
        $request->setSubscriptionId($subscriptionId);

        $controller = new AnetController\ARBCancelSubscriptionController($request);

        $response = $controller->executeWithApiResponse(\net\authorize\api\constants\ANetEnvironment::SANDBOX);

        if (($response != null) && ($response->getMessages()->getResultCode() == "Ok")) {
            $successMessages = $response->getMessages()->getMessage();
            echo "SUCCESS : " . $successMessages[0]->getCode() . "  " . $successMessages[0]->getText() . "\n";
        } else {
            echo "ERROR :  Invalid response\n";
            $errorMessages = $response->getMessages()->getMessage();
            echo "Response : " . $errorMessages[0]->getCode() . "  " . $errorMessages[0]->getText() . "\n";
        }

        echo $response;
        exit;
    }

    public function lead_view() {
        $data = [
            'admin_page_title' => 'Pre Screen Questions',
            'company_lead_priority' => CompanyLeadPriority::with(['company', 'service_category'])->where('company_id', '26')->orderBy('priority', 'ASC')->get()
        ];

        return view('company.leads.index', $data);
    }

    public function insert_zipcodes() {

        $companyArr = ['21'];
        $zipcodeArr = [
            '80536', '82059', '82001', '82009', '82006', '80648', '82052', '82061', '82005', '82002', '82008', '80535', '80549', '80612', '82003', '82010', '82060'
        ];

        foreach ($companyArr as $company_item) {
            foreach ($zipcodeArr as $zipcode_item) {
                CompanyZipcode::create([
                    'company_id' => $company_item,
                    'zip_code' => $zipcode_item
                ]);
            }
        }
    }

    public function checkCompany(Request $request) {


        $image = 'http://localhost/laravel/map_demo/public/images/logo.png';

// Read image path, convert to base64 encoding
        $imageData = base64_encode(file_get_contents($image));
//dd($imageData);
// Format the image SRC:  data:{mime};base64,{data};
        $src = 'data: image/png ;base64,' . $imageData;

// Echo out a sample image
//echo '<img src="' . $src . '">';


        return ['status' => true, 'src' => $src];
    }

    public function zipcode_api_check() {

        $zipcodes = \App\Models\Custom::getZipCodeRange('82801', 30);
        dd($zipcodes);
    }

    public function shot() {


        $pathToImage = 'test.jpg';

        Browsershot::url('http://localhost/laravel/map_demo/public/ows')
                ->windowSize(1920, 2000)
                ->setScreenshotType('jpeg', 100)
                ->save($pathToImage);

        dd('test');
    }


    public function test_ppl_invoice(){

        $companies = Company::select('companies.*')
                ->with(['ppl_company_leads', 'ppl_company_information'])
                ->leftJoin('membership_levels', 'companies.membership_level_id', 'membership_levels.id')
                ->where('membership_levels.charge_type', 'ppl_price')
                ->where('companies.id', 89)
                ->active()
                ->order()
                ->get();

        if (count($companies) > 0) {
            foreach ($companies AS $company_item) {

                if (isset($company_item->ppl_company_leads) && count($company_item->ppl_company_leads) > 0){

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
                        $company_invoice_item_arr[$ary_count]['description'] = $company_lead_item->lead->full_name.' - '.$company_lead_item->lead->zipcode;
                        $ary_count++;
                    }

                    CompanyInvoiceItem::insert($company_invoice_item_arr);

                    $data['company_invoice'] = $company_invoice;
                    $pdf = PDF::loadView('company.invoices.pdf', $data);
                    $uploadsPath = storage_path('app' . DIRECTORY_SEPARATOR . 'private' . DIRECTORY_SEPARATOR . 'company-invoices'. DIRECTORY_SEPARATOR. $company_invoice->invoice_id . '.pdf');
                    // $pdf->save('uploads/company-invoices/' . $company_invoice->invoice_id . '.pdf');
                    // $fileAttachments[] = 'uploads/company-invoices/' . $company_invoice->invoice_id . '.pdf';
                    $pdf->save($uploadsPath);
                    $fileAttachments[] = $uploadsPath;

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
                            //Mail::to($mail_item)->send(new CompanyMail($mail_id, $replaceWithArr, $fileAttachments));
                        }
                    }


                    /* ppl invoice generate mail to Admin */
                    if (isset($web_settings['global_email']) && $web_settings['global_email'] != '') {
                        $admin_mail_id = "68"; /* Mail title: Company Pay Per Lead Listing Invoice - Admin */
                        $adminReplaceArr = [
                            'company_name' => $company_item->company_name,
                        ];
                        //Mail::to($web_settings['global_email'])->send(new AdminMail($admin_mail_id, $adminReplaceArr, $fileAttachments));
                    }

                }

            }
        }

        dd($companies->count());




    }

}

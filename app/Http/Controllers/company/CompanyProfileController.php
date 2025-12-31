<?php

namespace App\Http\Controllers\company;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Mail\Company\CompanyMail;
use App\Mail\Admin\AdminMail;
use App\Mail\consumer\ConsumerMail;
use Illuminate\Support\Facades\Mail;
use App\Rules\ValidRecaptcha;
use Auth;
use Session;
use Str;
use DB;
use Validator;
use App\Models\Page;
use App\Models\Company;
use App\Models\CompanyZipcode;
use App\Models\CompanyServiceCategory;
use App\Models\CompanyUser;
use App\Models\CompanyGallery;
use App\Models\Feedback;
use App\Models\FeedbackFile;
use App\Models\Complaint;
use App\Models\ComplaintFile;
use App\Models\Trade;
use App\Models\ServiceCategoryType;
use App\Models\TopLevelCategory;
use App\Models\TopLevelCategoryTrade;
use App\Models\MainCategory;
use App\Models\ServiceCategory;
use App\Models\Lead;
use App\Models\CompanyLead;
use App\Models\CompanyProfileView;
use App\Models\CompanyContactView;
use App\Models\Custom;

class CompanyProfileController extends Controller {

    public function __construct() {
        $this->web_settings = Custom::getSettings();
        $this->modelObj = new Company;
        $this->view_base = 'company.profile_page.';
    }

    public function index($company_slug, Request $request) {
        $companyObj = $this->modelObj->with(['company_logo', 'company_information'])->whereSlug($company_slug)->first();

        if (is_null($companyObj)) {
            abort('404');
        }

        // add profile view record [start]

        $profile_view = true;
        if (Auth::guard('company_user')->check() && $companyObj->id == Auth::guard('company_user')->user()->company_id) {
            $profile_view = false;
        }
        // check for admin logged in
        if (Auth::check()) {
            $profile_view = false;
        }

        if ($request->has('browsershot') && $request->get('browsershot') == 'yes') {
            $profile_view = false;
        }

        if ($profile_view == true) {
            CompanyProfileView::firstOrCreate([
                'company_id' => $companyObj->id,
                'session' => Session::getId(),
                'ip_address' => $request->ip(),
            ]);
        }
        // add profile view record [end]

        $category_service_list = Custom::company_service_category_list($companyObj->id);
        $company_super_admin = CompanyUser::where([
                    ['company_id', $companyObj->id],
                    ['company_user_type', 'company_super_admin']
                ])
                ->active()
                ->latest()
                ->first();

        $average_ratings = Feedback::select(DB::raw('AVG(ratings) AS average_ratings'), DB::raw('COUNT(id) AS total_reviews'))
                ->where([
                    ['company_id', $companyObj->id],
                    ['feedback_status', 'Posted']
                ])
                ->first();
        $total_complaints = Complaint::where([
                    ['company_id', $companyObj->id],
                    ['complaint_status', 'Posted']
                ])
                ->count();

        $latest_reviews = Feedback::where([
                    ['company_id', $companyObj->id],
                    ['feedback_status', 'Posted']
                ])
                ->order()
                ->get();
        $latest_complaints = Complaint::where([
                    ['company_id', $companyObj->id],
                    ['complaint_status', 'Posted']
                ])
                ->order()
                ->get();

        $timeframeArr = config('config.timeframe');
        $service_category_types = ServiceCategoryType::leftJoin('company_service_categories AS csc', 'service_category_types.id', 'csc.service_category_type_id')
                ->where('csc.company_id', $companyObj->id)
                ->active()
                ->order()
                ->pluck('service_category_types.title', 'service_category_types.id');

        $top_level_categories = TopLevelCategory::leftJoin('company_service_categories AS csc', 'top_level_categories.id', 'csc.top_level_category_id')
                ->where('csc.company_id', $companyObj->id)
                ->active()
                ->order()
                ->pluck('top_level_categories.title', 'top_level_categories.id');


        $rest_main_categories = CompanyServiceCategory::with('main_category')
                ->where([
                    ['company_id', $companyObj->id],
                    ['category_type', 'extra'],
                ])
                ->active()
                ->groupBy('main_category_id')
                ->get();

        $meta_main_category_arr = $main_category_arr = [];
        $meta_main_category_arr[] = $companyObj->main_category->title;
        $main_category_arr[] = $companyObj->main_category->title;
        if (!is_null($companyObj->secondary_main_category_id)) {
            $meta_main_category_arr[] = $companyObj->secondary_main_category->title;

            if (count($rest_main_categories) > 0) {
                $main_category_arr[] = $companyObj->secondary_main_category->title;
                foreach ($rest_main_categories AS $i => $rest_main_category_item) {
                    $main_category_arr[] = $rest_main_category_item->main_category->title;
                }
            } else {
                $main_category_arr[] = $companyObj->secondary_main_category->title;
            }
        }

        if (count($meta_main_category_arr) > 1) {
            $last_element = array_pop($meta_main_category_arr);
            $meta_main_categories = implode(', ', $meta_main_category_arr) . ' and ' . $last_element;
        } else {
            $meta_main_categories = implode(', ', $meta_main_category_arr);
        }

        if (count($main_category_arr) > 1) {
            $last_element = array_pop($main_category_arr);
            $main_categories = implode(', ', $main_category_arr) . ' and ' . $last_element;
        } else {
            $main_categories = implode(', ', $main_category_arr);
        }


        /* $recent_bg_check = CompanyUser::where('company_id', $companyObj->id)
          ->whereNotNull('bg_check_date')
          ->orderBy('bg_check_date', 'DESC')
          ->first(); */


        $data = [
            'admin_page_title' => 'Company Page',
            'terms_page' => Page::find(13),
            'trades' => Trade::active()->order()->pluck('title', 'id'),
            'service_category_types' => $service_category_types,
            'top_level_categories' => $top_level_categories,
            'companyObj' => $companyObj,
            'meta_main_categories' => $meta_main_categories,
            'main_categories' => $main_categories,
            'company_gallery' => CompanyGallery::with('media')->where('company_id', $companyObj->id)->status('approved')->order()->get(),
            'company_service_category_list' => $category_service_list['company_service_category_list'],
            'company_service_areas' => CompanyZipcode::where([['company_id', $companyObj->id], ['status', 'active']])->orderBy('distance', 'ASC')->get(),
            'company_super_admin' => $company_super_admin,
            'average_ratings' => $average_ratings,
            'total_complaints' => $total_complaints,
            'latest_reviews' => $latest_reviews,
            'latest_complaints' => $latest_complaints,
            'timeframe' => array_combine($timeframeArr, $timeframeArr),
            //'recent_bg_check' => $recent_bg_check,
            'web_settings' => $this->web_settings,
        ];

        return view($this->view_base . 'profile_page', $data);
    }

    public function reviews($company_slug, Request $request) {
        $companyObj = $this->modelObj->whereSlug($company_slug)->first();

        if (is_null($companyObj)) {
            abort('404');
        }

        $average_ratings = Feedback::select(DB::raw('AVG(ratings) AS average_ratings'), DB::raw('COUNT(id) AS total_reviews'))
                ->where('company_id', $companyObj->id)
                ->first();

        $data = [
            'companyObj' => $companyObj,
            'feedback' => Feedback::where('company_id', $companyObj->id)->with('feedback_files')->order()->paginate(env('APP_RECORDS_PER_PAGE')),
            'average_ratings' => $average_ratings,
        ];

        return view($this->view_base . 'company_profile_reviews', $data);
    }

    public function complaints($company_slug, Request $request) {
        $companyObj = $this->modelObj->whereSlug($company_slug)->first();

        if (is_null($companyObj)) {
            abort('404');
        }

        $average_ratings = Feedback::select(DB::raw('AVG(ratings) AS average_ratings'), DB::raw('COUNT(id) AS total_reviews'))
                ->where('company_id', $companyObj->id)
                ->first();
        $total_complaints = Complaint::where('company_id', $companyObj->id)->count();

        $data = [
            'companyObj' => $companyObj,
            'complaints' => Complaint::where('company_id', $companyObj->id)->with(['complaint_files', 'contract_agreement_file'])->order()->paginate(env('APP_RECORDS_PER_PAGE')),
            'total_complaints' => $total_complaints,
            'average_ratings' => $average_ratings,
        ];

        return view($this->view_base . 'company_profile_complaints', $data);
    }

    public function generate_lead(Request $request) {
        //dd($request->all());
        // Google Recaptcha Check
        /* $resultJson = Custom::check_captcha($request);
          if ($resultJson->success != true) {
          flash('Captcha Error, Please reload page and try again.')->error();
          return back();
          } */

        $validator = Validator::make($request->all(), [
                    //'lead_generate_for' => 'required',
                    'full_name' => 'required',
                    'email' => 'required',
                    'phone' => 'required',
                    'service_category_type_id' => 'required',
                    'top_level_category_id' => 'required',
                    'main_category_id' => 'required',
                    'service_category_id' => 'required',
                    'timeframe' => 'required',
                    'project_address' => 'required',
                    //'state_id' => 'required',
                    //'city' => 'required',
                    'zipcode' => 'required',
                    'content' => 'required',
                    //'lead_terms' => 'required',
                    'g-recaptcha-response' => ['required', new ValidRecaptcha]
        ]);

        if (isset($validator) && $validator->fails()) {
            $errorMessage = "";
            foreach ($validator->messages()->getMessages() as $field_name => $messages) {
                foreach ($messages AS $message_item) {
                    $errorMessage .= $message_item . '<br />';
                }
            }

            return [
                'success' => 0,
                'title' => 'Warning',
                'type' => 'warning',
                'message' => $errorMessage
            ];
        } else {
            $requestArr = $request->all();
            $requestArr['ip_address'] = $request->ip();
            $requestArr['lead_activation_key'] = Custom::getRandomString(50);
            $get_trade = TopLevelCategoryTrade::where('top_level_category_id', $requestArr['top_level_category_id'])->first();
            $requestArr['trade_id'] = $get_trade->trade_id;

            if (isset($requestArr['lead_generate_for']) && $requestArr['lead_generate_for'] != '') {
                $companyObj = Company::find($requestArr['lead_generate_for']);

                /* check company having service category and zipcode */
                $company_service_category = CompanyServiceCategory::where([
                            ['company_id', $requestArr['lead_generate_for']],
                            ['top_level_category_id', $requestArr['top_level_category_id']],
                            ['main_category_id', $requestArr['main_category_id']],
                            ['service_category_type_id', $requestArr['service_category_type_id']],
                            ['service_category_id', $requestArr['service_category_id']],
                        ])->active()->first();


                if (is_null($company_service_category)) {
                    return [
                        'success' => 0,
                        'title' => 'Warning',
                        'type' => 'warning',
                        'message' => 'Company not working in the service category you selected.'
                    ];
                }

                $company_zipcodes = CompanyZipcode::where([
                            ['company_id', $requestArr['lead_generate_for']],
                            ['zip_code', $requestArr['zipcode']]
                        ])->active()->first();

                if (is_null($company_zipcodes)) {
                    return [
                        'success' => 0,
                        'title' => 'Warning',
                        'type' => 'warning',
                        'message' => 'Company not working in the service area you selected.'
                    ];
                }

                $lead = Lead::create($requestArr);
                // Send confirmation email to consumer
                Custom::lead_confirmation_email($lead);

                $company_lead_generate = true;
                $insertArr = [
                    'company_id' => $companyObj->id,
                    'lead_id' => $lead->id,
                    'is_hidden' => $companyObj->membership_level->hide_leads,
                    'priority' => '1'
                ];

                if ($company_lead_generate) {
                    $company_lead = CompanyLead::create($insertArr);
                    $company_lead_ids[] = $company_lead->id;
                    Custom::lead_generation_email_to_company($company_lead_ids);
                }
            } else {
                $lead = Lead::create($requestArr);

                // Send confirmation email to consumer
                Custom::lead_confirmation_email($lead);
                Custom::generateCompanyLeads($lead);


                $lead_counter = Custom::get_number_of_companies_who_get_leads($lead);
                if (
                        (isset($this->web_settings['sent_to_networx']) && $this->web_settings['sent_to_networx'] == 'yes') &&
                        $lead->trade_id == 1 &&
                        $lead_counter <= 0
                ) {
                    $networx_response = Custom::networxCall($lead);
                    if ($networx_response['statusCode'] == '200') {
                        $lead->networx_code = $networx_response['successCode'];
                        $lead->save();
                    }
                }
            }

            Custom::lead_email_admin($lead);

            $successMsg = '<p>Important Please check your email inbox <br /><br />We just sent an email confirmation to <a href="mailto:' . $requestArr['email'] . '">' . $requestArr['email'] . '</a>. If you entered the wrong email address, please close this window and resubmit your request <br /><br />Thank you for visiting TrustPatrick.com</p>';
            return [
                'success' => 1,
                'title' => "You're Almost Finished!",
                'type' => 'success',
                'message' => $successMsg
            ];
        }
    }

    public function confirm_review($activation_key) {
        $feedback = Feedback::where('activation_key', $activation_key)->first();

        if (is_null($feedback)) {
            flash('You already confirmed this review')->warning();
            return redirect('confirm-review/error');
        }

        $feedback->activation_key = null;
        $feedback->feedback_status = 'Confirmed';
        $feedback->save();


        /* Company Feedback created mail to Company */
        $companyObj = Company::find($feedback->company_id);
        $web_settings = $this->web_settings;
        $company_mail_id = "39"; /* Mail title: Company Feedback Created */
        $mailArr = Custom::generate_company_user_email_arr($companyObj->company_information);
        $companyReplaceArr = [
            'company_name' => $companyObj->company_name,
            'global_domain' => ((isset($web_settings['global_domain']) && $web_settings['global_domain'] != '') ? $web_settings['global_domain'] : ''),
            'view_link' => url('feedback'),
            'phone_number' => ((isset($web_settings['phone_number']) && $web_settings['phone_number'] != '') ? $web_settings['phone_number'] : ''),
            'global_address' => ((isset($web_settings['office_address']) && $web_settings['office_address'] != '') ? $web_settings['office_address'] : ''),
            'global_email' => ((isset($web_settings['global_email']) && $web_settings['global_email'] != '') ? $web_settings['global_email'] : ''),
            'facebook_link' => ((isset($web_settings['facebook']) && $web_settings['facebook'] != '') ? $web_settings['facebook'] : ''),
            'twitter_link' => ((isset($web_settings['twitter']) && $web_settings['twitter'] != '') ? $web_settings['twitter'] : ''),
            'instagram_link' => ((isset($web_settings['instagram']) && $web_settings['instagram'] != '') ? $web_settings['instagram'] : ''),
            'rumble_link' => ((isset($web_settings['rumble']) && $web_settings['rumble'] != '') ? $web_settings['rumble'] : ''),
            'youtube_link' => ((isset($web_settings['youtube']) && $web_settings['youtube'] != '') ? $web_settings['youtube'] : ''),
            'unsubscription_link' => url('unsubscribe-page/company', ['company_slug' => $companyObj->slug]),
            'request_generate_link' => $feedback->customer_email,
            'date' => $feedback->created_at->format(env('DATE_FORMAT')),
            'url' => url('/', ['company_slug' => $companyObj->slug]),
            'email_footer' => $feedback->customer_email,
            'copyright_year' => date('Y'),
                //'main_service_category' => '',
        ];

        $messageArr = [
            'company_id' => $companyObj->id,
            'message_type' => 'info',
            'link' => url('feedback')
        ];
        Custom::companyMailMessageCreate($messageArr, $company_mail_id, $companyReplaceArr);
        if (!is_null($mailArr) && count($mailArr) > 0) {
            foreach ($mailArr AS $mail_item) {
                Mail::to($mail_item)->send(new CompanyMail($company_mail_id, $companyReplaceArr));
            }
        }

        Session::put('confirm_review_success', 'yes');

        flash('Feedback confirmed successfully.')->success();
        return redirect('confirm-review/success');
    }

    public function confirm_review_success() {
        Session::forget('confirm_review_success');
        return view($this->view_base . 'confirm_review_success');
    }

    /* Ajax calls start */

    public function check_company_zipcode(Request $request) {
        if ($request->has('company_id') && $request->get('company_id') != '' && $request->has('zipcode') && $request->get('zipcode') != '') {
            $companyObj = Company::find($request->get('company_id'));
            $check_zipcode = CompanyZipcode::where([
                        ['company_id', $request->get('company_id')],
                        ['zip_code', $request->get('zipcode')]
                    ])->active()->first();

            if (!is_null($check_zipcode)) {
                return [
                    'success' => 1,
                ];
            } else {
                return [
                    'success' => 0,
                    'message' => $companyObj->company_name . ' does not service zip code ' . $request->get('zipcode') . ' <br />Would you like us to submit the request to our other members in your service area?',
                    'title' => 'Warning',
                    'type' => 'warning'
                ];
            }
        } else {
            return [
                'success' => 0,
                'message' => 'Fill zipcode first',
                'title' => 'Warning',
                'type' => 'warning'
            ];
        }
    }

    public function get_main_categories(Request $request) {
        $validator = Validator::make($request->all(), [
                    'service_category_type_id' => 'required',
                    'top_level_category_id' => 'required',
                    'company_id' => 'required',
        ]);

        if (isset($validator) && $validator->fails()) {
            $errorMessage = "";
            foreach ($validator->messages()->getMessages() as $field_name => $messages) {
                foreach ($messages AS $message_item) {
                    $errorMessage .= $message_item . '<br />';
                }
            }

            return [
                'success' => 0,
                'title' => 'Warning',
                'type' => 'warning',
                'message' => $errorMessage
            ];
        } else {
            $requestArr = $request->all();

            $data['main_categories'] = MainCategory::leftJoin('main_category_top_level_categories AS mct', 'main_categories.id', 'mct.main_category_id')
                    ->leftJoin('company_service_categories AS csc', 'main_categories.id', 'csc.main_category_id')
                    ->where([
                        ['mct.top_level_category_id', $requestArr['top_level_category_id']],
                        ['csc.company_id', $requestArr['company_id']],
                        ['csc.top_level_category_id', $requestArr['top_level_category_id']],
                        ['csc.service_category_type_id', $requestArr['service_category_type_id']],
                        ['csc.status', 'active']
                    ])
                    ->active()
                    ->order()
                    ->pluck('main_categories.title', 'main_categories.id');


            if (count($data['main_categories']) > 0) {
                return view($this->view_base . '_main_categories', $data);
            } else {
                return [
                    'success' => 0,
                    'title' => 'Warning',
                    'type' => 'warning',
                    'message' => 'No Main Category found.'
                ];
            }
        }
    }

    public function get_service_categories(Request $request) {
        $validator = Validator::make($request->all(), [
                    'service_category_type_id' => 'required',
                    'top_level_category_id' => 'required',
                    'main_category_id' => 'required',
                    'company_id' => 'required',
        ]);

        if (isset($validator) && $validator->fails()) {
            $errorMessage = "";
            foreach ($validator->messages()->getMessages() as $field_name => $messages) {
                foreach ($messages AS $message_item) {
                    $errorMessage .= $message_item . '<br />';
                }
            }

            return [
                'success' => 0,
                'title' => 'Warning',
                'type' => 'warning',
                'message' => $errorMessage
            ];
        } else {
            $requestArr = $request->all();

            $data['service_categories'] = ServiceCategory::leftJoin('company_service_categories AS csc', 'service_categories.id', 'csc.service_category_id')
                    ->where([
                        ['service_categories.service_category_type_id', $requestArr['service_category_type_id']],
                        ['service_categories.top_level_category_id', $requestArr['top_level_category_id']],
                        ['service_categories.main_category_id', $requestArr['main_category_id']],
                        ['csc.company_id', $requestArr['company_id']],
                        ['csc.service_category_type_id', $requestArr['service_category_type_id']],
                        ['csc.top_level_category_id', $requestArr['top_level_category_id']],
                        ['csc.main_category_id', $requestArr['main_category_id']],
                        ['csc.status', 'active']
                    ])
                    ->active()
                    ->order()
                    ->pluck('service_categories.title', 'service_categories.id');

            if (count($data['service_categories']) > 0) {
                return view($this->view_base . '_service_categories', $data);
            } else {
                return [
                    'success' => 0,
                    'title' => 'Warning',
                    'type' => 'warning',
                    'message' => 'No Service Category found.'
                ];
            }
        }
    }

    public function submit_review(Request $request) {
        $validator = Validator::make($request->all(), [
                    'company_id' => 'required',
                    'first_last_name' => 'required',
                    'customer_name' => 'required',
                    'customer_email' => 'required',
                    'customer_phone' => 'required',
                    'zipcode' => 'required',
                    'review_terms' => 'required',
        ]);

        if ($validator->fails()) {
            $errorMessage = "";
            foreach ($validator->messages()->getMessages() as $field_name => $messages) {
                foreach ($messages AS $message_item) {
                    $errorMessage .= $message_item . '<br />';
                }
            }

            return [
                'success' => 0,
                'title' => 'Warning',
                'type' => 'warning',
                'message' => $errorMessage
            ];
        } else {
            $web_settings = $this->web_settings;

            $requestArr = $request->all();
            $requestArr['feedback_id'] = Feedback::getFeedbackNumber();
            $requestArr['feedback_status'] = 'Submitted';
            $requestArr['activation_key'] = Str::random(60);

            $itemObj = Feedback::create($requestArr);
            $companyObj = Company::find($requestArr['company_id']);
            if ($request->hasFile('media')) {
                $images = $request->file('media');
                if (count($images) > 0) {
                    foreach ($images as $file) {
                        $imageArr = Custom::uploadFile($file, 'feedback');
                        $insertArr = [
                            'company_id' => $itemObj->company_id,
                            'feedback_id' => $itemObj->id,
                            'media_id' => $imageArr['mediaObj']->id
                        ];

                        FeedbackFile::create($insertArr);
                    }
                }
            }


            /* Company Feedback created mail to Company */
            //$company_mail_id = "39"; /* Mail title: Company Feedback Created */
            /* $mailArr = Custom::generate_company_user_email_arr($companyObj->company_information);
              $companyReplaceArr = [
              'company_name' => $companyObj->company_name,
              'global_domain' => ((isset($web_settings['global_domain']) && $web_settings['global_domain'] != '') ? $web_settings['global_domain'] : ''),
              'view_link' => url('feedback'),
              'phone_number' => ((isset($web_settings['phone_number']) && $web_settings['phone_number'] != '') ? $web_settings['phone_number'] : ''),
              'global_address' => ((isset($web_settings['office_address']) && $web_settings['office_address'] != '') ? $web_settings['office_address'] : ''),
              'global_email' => ((isset($web_settings['global_email']) && $web_settings['global_email'] != '') ? $web_settings['global_email'] : ''),
              'facebook_link' => ((isset($web_settings['facebook']) && $web_settings['facebook'] != '') ? $web_settings['facebook'] : ''),
              'twitter_link' => ((isset($web_settings['twitter']) && $web_settings['twitter'] != '') ? $web_settings['twitter'] : ''),
              'instagram_link' => ((isset($web_settings['instagram']) && $web_settings['instagram'] != '') ? $web_settings['instagram'] : ''),
              'youtube_link' => ((isset($web_settings['youtube']) && $web_settings['youtube'] != '') ? $web_settings['youtube'] : ''),
              'unsubscription_link' => url('unsubscribe-page/company', ['company_slug' => $companyObj->slug]),
              'request_generate_link' => $itemObj->customer_email,
              'date' => $itemObj->created_at->format(env('DATE_FORMAT')),
              'url' => url('/', ['company_slug' => $companyObj->slug]),
              'email_footer' => $itemObj->customer_email,
              //'main_service_category' => '',
              ];

              $messageArr = [
              'company_id' => $companyObj->id,
              'message_type' => 'info',
              'link' => url('feedback')
              ];
              Custom::companyMailMessageCreate($messageArr, $company_mail_id, $companyReplaceArr);
              if (!is_null($mailArr) && count($mailArr) > 0) {
              foreach ($mailArr AS $mail_item) {
              Mail::to($mail_item)->send(new CompanyMail($company_mail_id, $companyReplaceArr));
              }
              } */

            /* Company Feedback created mail to Consumer */
            $consumer_mail_id = "40"; /* Mail title: Company Feedback Created - Consumer */
            $consumerReplaceArr = [
                'customer_name' => $itemObj->customer_name,
                'company_name' => $companyObj->company_name,
                'confirmation_link' => url('/confirm-review/' . $itemObj->activation_key),
                'global_address' => ((isset($web_settings['office_address']) && $web_settings['office_address'] != '') ? $web_settings['office_address'] : ''),
                'phone_number' => ((isset($web_settings['phone_number']) && $web_settings['phone_number'] != '') ? $web_settings['phone_number'] : ''),
                'global_email' => ((isset($web_settings['global_email']) && $web_settings['global_email'] != '') ? $web_settings['global_email'] : ''),
                'facebook_link' => ((isset($web_settings['facebook']) && $web_settings['facebook'] != '') ? $web_settings['facebook'] : ''),
                'twitter_link' => ((isset($web_settings['twitter']) && $web_settings['twitter'] != '') ? $web_settings['twitter'] : ''),
                'instagram_link' => ((isset($web_settings['instagram']) && $web_settings['instagram'] != '') ? $web_settings['instagram'] : ''),
                'rumble_link' => ((isset($web_settings['rumble']) && $web_settings['rumble'] != '') ? $web_settings['rumble'] : ''),
                'youtube_link' => ((isset($web_settings['youtube']) && $web_settings['youtube'] != '') ? $web_settings['youtube'] : ''),
                'unsubscription_link' => '',
                'request_generate_link' => $itemObj->customer_email,
                'date' => $itemObj->created_at->format(env('DATE_FORMAT')),
                'url' => url('/', ['company_slug' => $companyObj->slug]),
                'email_footer' => $itemObj->customer_email,
                'copyright_year' => date('Y'),
                    //'main_service_category' => '',
            ];

            Mail::to($itemObj->customer_email)->send(new ConsumerMail($consumer_mail_id, $consumerReplaceArr));

            if (isset($this->web_settings['global_email']) && $this->web_settings['global_email'] != '') {
                /* Company Feedback created mail to Admin */
                $admin_mail_id = "41"; /* Mail title: Company Feedback Created - Admin */
                $adminReplaceArr = [
                    'company_name' => $companyObj->company_name,
                    'feedback_number' => $itemObj->feedback_id,
                    'customer_name' => $itemObj->customer_name,
                    'customer_phone' => $itemObj->customer_phone,
                    'customer_email' => $itemObj->customer_email,
                    'rating' => $itemObj->ratings,
                    'review' => $itemObj->content,
                ];

                Mail::to($this->web_settings['global_email'])->send(new AdminMail($admin_mail_id, $adminReplaceArr));
            }

            return [
                'success' => 0,
                'title' => 'Success',
                'type' => 'success',
                'message' => 'Your feedback submitted successfully. Please Check Your Email.'
            ];
        }
    }

    public function submit_complaint(Request $request) {
        $validator = Validator::make($request->all(), [
                    'company_id' => 'required',
                    'first_last_name' => 'required',
                    'customer_name' => 'required',
                    'customer_email' => 'required',
                    'customer_phone' => 'required',
                    'zipcode' => 'required',
                    'have_contract_agreement' => 'required',
                    'terms' => 'required',
        ]);

        if ($validator->fails()) {
            $errorMessage = "";
            foreach ($validator->messages()->getMessages() as $field_name => $messages) {
                foreach ($messages AS $message_item) {
                    $errorMessage .= $message_item . '<br />';
                }
            }

            return [
                'success' => 0,
                'title' => 'Warning',
                'type' => 'warning',
                'message' => $errorMessage
            ];
        } else {
            $web_settings = $this->web_settings;

            $requestArr = $request->all();
            $requestArr['complaint_id'] = Complaint::getComplaintNumber();
            $requestArr['complaint_status'] = 'Submitted';
            $itemObj = Complaint::create($requestArr);

            $companyObj = Company::find($requestArr['company_id']);
            if ($request->hasFile('media')) {
                $images = $request->file('media');
                if (count($images) > 0) {
                    foreach ($images as $file) {
                        $imageArr = Custom::uploadFile($file, 'complaint');
                        $insertArr = [
                            'company_id' => $itemObj->company_id,
                            'complaint_id' => $itemObj->id,
                            'media_id' => $imageArr['mediaObj']->id
                        ];

                        ComplaintFile::create($insertArr);
                    }
                }
            }


            if ($requestArr['have_contract_agreement'] == 'yes' && $request->hasFile('contract_agreement_file')) {
                $contract_file = $request->file('contract_agreement_file');
                $fileArr = Custom::uploadFile($contract_file, 'complaint');
                $itemObj->contract_agreement_file_id = $fileArr['mediaObj']->id;
                $itemObj->save();
            }

            /* Company Complaint created mail to Consumer */
            $consumer_mail_id = "46"; /* Mail title: Company Complaint Created - Consumer */
            $consumerReplaceArr = [
                'customer_name' => $itemObj->customer_name,
                'company_name' => $companyObj->company_name,
                'complaint_number' => $itemObj->complaint_id,
                'phone_number' => ((isset($web_settings['phone_number']) && $web_settings['phone_number'] != '') ? $web_settings['phone_number'] : ''),
                'global_address' => ((isset($web_settings['office_address']) && $web_settings['office_address'] != '') ? $web_settings['office_address'] : ''),
                'global_email' => ((isset($web_settings['global_email']) && $web_settings['global_email'] != '') ? $web_settings['global_email'] : ''),
                'facebook_link' => ((isset($web_settings['facebook']) && $web_settings['facebook'] != '') ? $web_settings['facebook'] : ''),
                'twitter_link' => ((isset($web_settings['twitter']) && $web_settings['twitter'] != '') ? $web_settings['twitter'] : ''),
                'instagram_link' => ((isset($web_settings['instagram']) && $web_settings['instagram'] != '') ? $web_settings['instagram'] : ''),
                'rumble_link' => ((isset($web_settings['rumble']) && $web_settings['rumble'] != '') ? $web_settings['rumble'] : ''),
                'youtube_link' => ((isset($web_settings['youtube']) && $web_settings['youtube'] != '') ? $web_settings['youtube'] : ''),
                'unsubscription_link' => '',
                'request_generate_link' => $itemObj->customer_email,
                'date' => $itemObj->created_at->format(env('DATE_FORMAT')),
                'url' => url('/', ['company_slug' => $companyObj->slug]),
                'email_footer' => $itemObj->customer_email,
                'copyright_year' => date('Y'),
                    //'main_service_category' => '',
            ];

            Mail::to($itemObj->customer_email)->send(new ConsumerMail($consumer_mail_id, $consumerReplaceArr));

            if (isset($this->web_settings['global_email']) && $this->web_settings['global_email'] != '') {
                /* Company Complaint created mail to Admin */
                $admin_mail_id = "47"; /* Mail title: Company Complaint Created - Admin */
                $adminReplaceArr = [
                    'company_name' => $companyObj->company_name,
                    'complaint_number' => $itemObj->complaint_id,
                    'customer_name' => $itemObj->customer_name,
                    'customer_phone' => $itemObj->customer_phone,
                    'customer_email' => $itemObj->customer_email,
                ];

                Mail::to($this->web_settings['global_email'])->send(new AdminMail($admin_mail_id, $adminReplaceArr));
            }


            return [
                'success' => 0,
                'title' => 'Success',
                'type' => 'success',
                'message' => 'Your complaint submitted successfully.'
            ];
        }
    }

    public function view_contact_information_session(Request $request) {
        $validator = Validator::make($request->all(), [
                    'company_id' => 'required',
        ]);

        if ($validator->fails()) {
            return [
                'success' => 0,
                'title' => 'Error',
                'type' => 'error',
                'message' => 'Company not found.'
            ];
        } else {
            $requestArr = $request->all();

            CompanyContactView::firstOrCreate([
                'company_id' => $requestArr['company_id'],
                'session_id' => Session::getId()
            ]);

            return [
                'success' => 1,
                'title' => 'Success',
                'type' => 'success',
                'message' => 'Company Contact info displayed.'
            ];
        }
    }

    /* Ajax calls end */
}

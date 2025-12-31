<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Mail\Company\CompanyMail;
use Illuminate\Support\Facades\Mail;
use Auth;
use Session;
use View;
use Image;
use ImageOptimizer;
use Validator;
use Str;
use PDF;
use Log;
// Models [start]
use App\Models\Custom;
use App\Models\Media;
use App\Models\Company;
use App\Models\CompanyUser;
use App\Models\MembershipLevel;
use App\Models\MembershipStatus;
use App\Models\CompanyZipcode;
use App\Models\CompanyServiceCategory;
use App\Models\CompanyLeadNotification;
use App\Models\CompanyNote;
use App\Models\CompanyFaqQuestion;
use App\Models\CompanyInformation;
use App\Models\CompanyInvoice;
use App\Models\CompanyApprovalStatus;
use App\Models\CompanyLicensing;
use App\Models\CompanyDocument;
use App\Models\CompanyCustomerReference;
use App\Models\CompanyInsurance;
use App\Models\CompanyMembershipActivityLog;
use App\Models\CompanyLead;
use App\Models\CompanyGallery;
use App\Models\Complaint;
use App\Models\Feedback;
use App\Models\Lead;
use App\Models\MainCategoryTopLevelCategory;
use App\Models\NonMember;
use App\Models\State;
use App\Models\User;
use App\Models\TopLevelCategory;
use App\Models\TopLevelCategoryTrade;
use App\Models\MainCategory;
use App\Models\ServiceCategory;
use App\Models\ServiceCategoryType;
use App\Models\ProfessionalAffiliation;
use App\Models\ZipcodeDetail;
use Rap2hpoutre\FastExcel\FastExcel;

class CompanyController extends Controller {

    public function __construct() {
        $segment = \Request::segment(2);
        if ($segment == 're-order') {
            $segment = \Request::segment(3);
        }

        $url_key = $segment;
        $module_display_name = Str::singular(ucwords(str_replace('_', ' ', $segment)));

        // Links
        $this->urls = Custom::getModuleUrls($url_key);

        //Import members
        //$this->urls += ["import" => url("admin/" . $url_key . "/import")];

        // Common Model
        if ($module_display_name != '') {
            $model_name = '\\App\\Models\\' . str_replace(' ', '', $module_display_name);
            $this->modelObj = new $model_name;
        }

        // Post type
        $this->post_type = $url_key;

        // Module Message
        $this->module_messages = Custom::getModuleFlashMessages($module_display_name);

        // Singular and Plural Name of Module
        $this->singular_display_name = Str::singular($module_display_name);
        $this->module_plural_name = Str::plural($module_display_name);

        $this->common_data = [
            'module_singular_name' => $this->singular_display_name,
            'module_plural_name' => $this->module_plural_name,
            'url_key' => $url_key,
            'module_urls' => $this->urls,
            'membership_levels' => MembershipLevel::active()->order()->pluck('title', 'id'),
            'membership_statuses' => MembershipStatus::active()->order()->pluck('title', 'title'),
            //'users' => User::where('role_id', '7')->active()->order()->get()->pluck('full_name', 'id'),
            'users' => User::active()->order()->get()->pluck('full_name', 'id'),
            'top_level_categories' => TopLevelCategory::orderBy('title', 'ASC')->pluck('title', 'id'),
            'service_category_types' => ServiceCategoryType::pluck('title', 'id'),
        ];

        View::share($this->common_data);

        // View
        $this->view_base = 'admin.' . $url_key;
    }

    public function index(Request $request, $membership_level = null) {
        $list_params = Custom::getListParams($request);
        $admin_page_title = 'Manage ' . $this->module_plural_name;

        if (!is_null($membership_level) && $membership_level != 'all-registered-members' && $membership_level != 'all-official-members') {
            $membership_level_obj = MembershipLevel::whereSlug($membership_level)->firstOrFail();
            $list_params['membership_level_id'] = $membership_level_obj->id;
            $admin_page_title .= ' [' . $membership_level_obj->title . ']';

            $membership_levels = MembershipLevel::whereSlug($membership_level)->pluck('title', 'id');
            $membership_level_status = MembershipStatus::leftJoin('membership_level_statuses', 'membership_statuses.id', 'membership_level_statuses.membership_status_id')
                    ->where('membership_level_statuses.membership_level_id', $membership_level_obj->id)
                    ->active()
                    ->order()
                    ->pluck('membership_statuses.title', 'membership_statuses.title');
        } else if (!is_null($membership_level) && $membership_level == 'all-registered-members') {
            $list_params['membership_level_id'] = 'unpaid_members';
            $admin_page_title .= ' [All Registered Members]';

            $membership_levels = MembershipLevel::whereIn('id', ['1', '2', '3'])
                    ->active()
                    ->order()
                    ->pluck('title', 'id');

            $membership_level_status = MembershipStatus::leftJoin('membership_level_statuses', 'membership_statuses.id', 'membership_level_statuses.membership_status_id')
                    ->whereIn('membership_level_statuses.membership_level_id', ['1', '2', '3'])
                    ->active()
                    ->order()
                    ->pluck('membership_statuses.title', 'membership_statuses.title');
        } else if (!is_null($membership_level) && $membership_level == 'all-official-members') {
            $list_params['membership_level_id'] = 'paid_members';
            $admin_page_title .= ' [All Official Members]';

            $membership_levels = MembershipLevel::whereIn('id', ['4', '5', '6', '7'])
                    ->active()
                    ->order()
                    ->pluck('title', 'id');

            $membership_level_status = MembershipStatus::leftJoin('membership_level_statuses', 'membership_statuses.id', 'membership_level_statuses.membership_status_id')
                    ->whereIn('membership_level_statuses.membership_level_id', ['4', '5', '6', '7'])
                    ->active()
                    ->order()
                    ->pluck('membership_statuses.title', 'membership_statuses.title');
        } else {
            $list_params['membership_level_id'] = '';
            $admin_page_title .= ' [All Members]';

            $membership_levels = MembershipLevel::active()->order()->pluck('title', 'id');

            $membership_level_status = MembershipStatus::active()
                    ->order()
                    ->pluck('title', 'title');
        }
        
        $rows = $this->modelObj->getAdminList($list_params);


        $requestArr = $request->all();
        $main_categories = [];
        $service_categories = [];

        if (isset($requestArr['search']['company_service_categories.top_level_category_id']) && $requestArr['search']['company_service_categories.top_level_category_id'] > 0) {
            //dd($requestArr);

            $main_categories = MainCategory::leftJoin('main_category_top_level_categories', 'main_category_top_level_categories.main_category_id', '=', 'main_categories.id')
                    ->where('main_category_top_level_categories.top_level_category_id', $requestArr['search']['company_service_categories.top_level_category_id'])
                    ->pluck('main_categories.title', 'main_categories.id');
            //dd($main_categories);
        }

        if (isset($requestArr['search']['company_service_categories.main_category_id']) && $requestArr['search']['company_service_categories.main_category_id'] > 0) {
            //dd($requestArr);

            $service_categories = ServiceCategory::where([
                        ['top_level_category_id', $requestArr['search']['company_service_categories.top_level_category_id']],
                        ['service_category_type_id', $requestArr['search']['company_service_categories.service_category_type_id']],
                        ['main_category_id', $requestArr['search']['company_service_categories.main_category_id']],
                    ])
                    ->orderBy('title', 'ASC')
                    ->pluck('title', 'id');
            //dd($main_categories);
        }


        //dd($rows->toArray());

        if (count($rows) <= 0 && $request->has('page') && $request->get('page') > 1) {
            $list_params['page'] = $rows->lastPage();
            return redirect($this->urls['list'] . http_build_query($list_params));
        }

        $data = [
            'admin_page_title' => $admin_page_title,
            'rows' => $rows,
            'list_params' => $list_params,
            'searchColumns' => $this->modelObj->searchColumns,
            'with_date' => 0,
            'action_arr' => Custom::getActionArr($this->common_data['url_key']),
            'membership_level_obj' => isset($membership_level_obj) ? $membership_level_obj : null,
            'main_categories' => $main_categories,
            'search' => [
                'company_service_categories.top_level_category_id' => [
                    'title' => 'Top Level Category',
                    'options' => $this->common_data['top_level_categories'],
                    'id' => 'top_level_category_id'
                ],
                'company_service_categories.main_category_id' => [
                    'title' => 'Main Category',
                    'options' => $main_categories,
                    'id' => 'main_category_id'
                ],
                'company_service_categories.service_category_type_id' => [
                    'title' => 'Service Category type',
                    'options' => $this->common_data['service_category_types'],
                    'id' => 'service_category_type_id'
                ],
                'company_service_categories.service_category_id' => [
                    'title' => 'Service Category',
                    'options' => $service_categories,
                    'id' => 'service_category_id'
                ],
                'companies.membership_level_id' => [
                    'title' => 'Membership Level',
                    'options' => $membership_levels,
                    'id' => 'membership_level_id',
                ],
                'companies.status' => [
                    'title' => 'Membership Status',
                    'options' => $membership_level_status,
                    'id' => 'membership_status',
                ],
                'companies.sales_representative_id' => [
                    'title' => 'Sales Representative',
                    'options' => $this->common_data['users'],
                ]
            ],
            'import_url' => url("admin/" . $this->common_data['url_key'] . "/import").'/'.$membership_level
        ];

        return view($this->view_base . '.index', $data);
    }

    public function edit($id) {
        $formObj = $this->modelObj->findOrFail($id);

        $data['admin_page_title'] = 'Edit ' . $this->singular_display_name . ' [' . $formObj->company_name . ']';
        $data['formObj'] = $formObj;
        $data['user_item'] = CompanyUser::where([
                    ['company_id', $formObj->id],
                    ['company_user_type', 'company_super_admin']
                ])
                ->active()
                ->latest()
                ->first();
        $data['states'] = State::order()->pluck('name', 'id');

        if (!is_null($formObj->approval_date)) {
            $data['formObj']->approval_date = Custom::date_formats($formObj->approval_date, env('DB_DATE_FORMAT'), env('DATE_FORMAT'));
        }

        if (!is_null($formObj->renewal_date)) {
            $data['formObj']->renewal_date = Custom::date_formats($formObj->renewal_date, env('DB_DATE_FORMAT'), env('DATE_FORMAT'));
        }

        $category_service_list = Custom::company_service_category_list($formObj->id);

        $data['company_service_category_list'] = $category_service_list['company_service_category_list'];
        $data['removed_company_service_category_list'] = $category_service_list['removed_company_service_category_list'];


        $data['company_zip_codes'] = CompanyZipcode::where('company_id', $formObj->id)->get();
        $data['company_lead_notifications'] = CompanyLeadNotification::where('company_id', $formObj->id)->latest()->first();
        $data['company_customer_references'] = CompanyCustomerReference::where('company_id', $formObj->id)->latest()->first();
        $data['professional_affiliations'] = ProfessionalAffiliation::where('trade_id', $formObj->trade_id)->active()->order()->pluck('title', 'title');
        $data['company_notes'] = CompanyNote::with('user')->where('company_id', $formObj->id)->get();
        $data['company_faqs'] = CompanyFaqQuestion::with('company_user')->where('company_id', $formObj->id)->get();

        $data['membership_status'] = self::get_membership_status($formObj->membership_level_id);


        $data['feedback'] = Feedback::with('feedback_files')->where('company_id', $formObj->id)->order()->paginate(env('APP_RECORDS_PER_PAGE'), ['*'], 'feedback');
        $data['complaints'] = Complaint::where('company_id', $formObj->id)->order()->paginate(env('APP_RECORDS_PER_PAGE'), ['*'], 'complaints');
        $data['company_invoices'] = CompanyInvoice::where('company_id', $formObj->id)->order()->paginate(env('APP_RECORDS_PER_PAGE'), ['*'], 'company_invoices');
        $data['company_galleries'] = CompanyGallery::where('company_id', $formObj->id)->order()->get();

        $data['company_application_file'] = CompanyDocument::with('media')
                ->where([
                    ['company_id', $formObj->id],
                    ['document_type', 'application_file'],
                    ['status', 'completed']
                ])
                ->latest()
                ->first();

        //dd($data['company_application_file']);
        $data['company_status_history'] = CompanyMembershipActivityLog::where('company_id', $formObj->id)->order()->get();

        return view($this->view_base . '.edit', $data);
    }

    public function destroy(Request $request, $id) {
        $modelObj = $this->modelObj->findOrFail($id);
        $modelObjTemp = $modelObj;
        try {
            $modelObj->delete();
            flash($this->module_messages['delete'])->warning();
            return back();
        } catch (Exception $e) {
            flash($this->module_messages['delete_error'])->danger();
            return back();
        }
    }

    public function assign_sales_representative(Request $request) {
        $validator = Validator::make($request->all(), [
                    'company_id' => 'required',
                    'sales_representative_id' => 'required',
        ]);

        if (isset($validator) && $validator->fails()) {
            return back()->withErrors($validator)->withInput();
        } else {
            $requestArr = $request->all();

            $companyObj = $this->modelObj->findOrFail($requestArr['company_id']);
            $companyObj->update($requestArr);

            $user_detail = User::where([
                        ['role_id', '7'],
                        ['id', $requestArr['sales_representative_id']]
                    ])->active()->first();

            flash('Sales Representative assigned to Company successfully')->success();
            return back();
        }
    }

    /* Company Owners information */

    public function company_owners($company_id) {
        $formObj = $this->modelObj->with('company_information')->findOrFail($company_id);

        $data['admin_page_title'] = $formObj->company_name . "'s Owners";
        $data['formObj'] = $formObj;

        return view($this->view_base . '.company_owners', $data);
    }

    public function make_owner_super_admin(Request $request) {
        $requestArr = $request->all();

        Session::put('active_accordion', 'company_owners');
        if (isset($requestArr['company_id']) && $requestArr['company_id'] != '' && isset($requestArr['user_id']) && $requestArr['user_id'] != '') {
            $company_user = CompanyUser::where([
                        ['id', $requestArr['user_id']],
                        ['company_id', $requestArr['company_id']],
                        ['company_user_type', 'company_admin']
                    ])->active()->first();

            if (is_null($company_user)) {
                return [
                    'success' => 0,
                    'title' => 'Error',
                    'type' => 'error',
                    'message' => 'Company Owner not found.'
                ];
            } else {
                CompanyUser::where('company_id', $requestArr['company_id'])->update(['company_user_type' => 'company_admin']);

                $company_user->company_user_type = 'company_super_admin';
                $company_user->save();

                return [
                    'success' => 1,
                    'title' => 'Success',
                    'type' => 'success',
                    'message' => 'Company Owner super admin applied successfully.'
                ];
            }
        } else {
            return [
                'success' => 0,
                'title' => 'Error',
                'type' => 'error',
                'message' => 'Company/Company Owner not found.'
            ];
        }
    }

    public function update_company_profile(Request $request) {
        $requestArr = $request->all();

        if ($requestArr['update_type'] == 'company_contact_info') {
            $validator = Validator::make($request->all(), [
                        'company_id' => 'required',
                        //'company_website' => 'required',
                        'main_company_telephone' => 'required',
                        'company_mailing_address' => 'required',
                        //'suite' => 'required',
                        'city' => 'required',
                        'state_id' => 'required',
                        'zipcode' => 'required',
            ]);
        } else if ($requestArr['update_type'] == 'company_owner_info') {
            $validator = Validator::make($request->all(), [
                        'company_id' => 'required',
            ]);
        } else if ($requestArr['update_type'] == 'internal_contact_info') {
            $validator = Validator::make($request->all(), [
                        'company_id' => 'required',
                        'internal_contact_name' => 'required',
                        'internal_contact_email' => 'required|email',
                        'internal_contact_phone' => 'required',
            ]);
        } else if ($requestArr['update_type'] == 'company_logo') {
            $validator = Validator::make($request->all(), [
                        'company_id' => 'required',
                        'company_logo' => 'required|mimes:jpg,jpeg,png,gif',
            ]);
        } else if ($requestArr['update_type'] == 'company_bio') {
            $validator = Validator::make($request->all(), [
                        'company_id' => 'required',
            ]);
        } else if ($requestArr['update_type'] == 'company_member_status_info') {
            $validator = Validator::make($request->all(), [
                        'company_id' => 'required',
                        'membership_level_id' => 'required',
                        'status' => 'required',
            ]);
        }

        if (isset($validator) && $validator->fails()) {
            return back()->withErrors($validator)->withInput();
        } else {
            $companyObj = $this->modelObj->findOrFail($requestArr['company_id']);

            if (isset($requestArr['approval_date'])) {
                $requestArr['approval_date'] = Custom::date_formats($requestArr['approval_date'], env('DATE_FORMAT'), env('DB_DATE_FORMAT'));
            }


            if (isset($requestArr['renewal_date'])) {
                $requestArr['renewal_date'] = Custom::date_formats($requestArr['renewal_date'], env('DATE_FORMAT'), env('DB_DATE_FORMAT'));
            }

            $old_membership_level_id = $companyObj->membership_level_id;
            $old_membership_status = MembershipStatus::where('title', $companyObj->status)->active()->first();

            $companyObj->update($requestArr);


            $company_information = CompanyInformation::firstOrCreate(['company_id' => $companyObj->id]);
            $company_approval_status = CompanyApprovalStatus::firstOrCreate(['company_id' => $companyObj->id]);
            Session::put('active_accordion', 'company_profile');

            if ($requestArr['update_type'] == 'company_bio') {
                $company_approval_status->company_bio = "completed";
                $company_approval_status->save();
            } else if ($requestArr['update_type'] == 'company_logo') {
                $company_approval_status->company_logo = "completed";
                $company_approval_status->save();
            } else if ($requestArr['update_type'] == 'company_owner_info') {
                Session::put('active_accordion', 'company_owners');

                $company_information->update($requestArr);

                if (isset($requestArr['company_owner_1_full_name']) && $requestArr['company_owner_1_full_name'] != '' && isset($requestArr['company_owner_1_email']) && $requestArr['company_owner_1_email'] != '') {
                    $company_update_arr = [
                        'owner_name' => isset($requestArr['company_owner_1_full_name']) ? $requestArr['company_owner_1_full_name'] : null,
                        'owner_email' => isset($requestArr['company_owner_1_email']) ? $requestArr['company_owner_1_email'] : null,
                    ];
                    $companyObj->update($company_update_arr);
                }


                if (isset($requestArr['owner_id']) && $requestArr['owner_id'] != '') {
                    for ($i = 1; $i < $companyObj->number_of_owners; $i++) {
                        $name_field = 'company_owner_' . $i . '_full_name';
                        $email_field = 'company_owner_' . $i . '_email';
                        $phone_field = 'company_owner_' . $i . '_phone';
                        $user_id_field = 'company_owner_' . $i . '_user_id';

                        if ($requestArr['owner_id'] == $i && !is_null($company_information->$user_id_field)) {
                            $company_user = CompanyUser::active()->find($company_information->$user_id_field);
                            if (!is_null($company_user)) {
                                $first_name = $last_name = null;
                                $name = explode(' ', $requestArr[$name_field]);
                                if (is_array($name) && count($name) > 1) {
                                    $first_name = $name['0'];
                                    $last_name = $name['1'];
                                } else {
                                    $first_name = $requestArr[$name_field];
                                }


                                $company_user->first_name = $first_name;
                                $company_user->last_name = $last_name;
                                $company_user->email = $requestArr[$email_field];
                                $company_user->user_telephone = $requestArr[$phone_field];
                                $company_user->save();
                            }
                        }
                    }
                }
            } else if ($requestArr['update_type'] == 'company_contact_info') {
                $company_information->update($requestArr);
            } else if ($requestArr['update_type'] == 'internal_contact_info') {
                $company_information_update_arr = [
                    'internal_contact_fullname' => $requestArr['internal_contact_name'],
                    'internal_contact_phone' => $requestArr['internal_contact_phone'],
                    'internal_contact_email' => $requestArr['internal_contact_email'],
                ];
                $company_information->update($company_information_update_arr);
            } else if ($requestArr['update_type'] == 'company_member_status_info') {
                //$requestArr['company_start_date'] = $requestArr['registered_date'];
                $company_information->update($requestArr);


                if (isset($requestArr['bg_check_date']) && $requestArr['bg_check_date'] != '') {
                    $companyObj->bg_check_date = \Carbon\Carbon::createFromFormat(env('DATE_FORMAT', 'm/d/Y'), $requestArr['bg_check_date'])->format(env('DB_DATE_FORMAT', 'Y-m-d'));
                }

                //$company_information->created_at = \Carbon\Carbon::createFromFormat('m/d/Y', $requestArr['created_at'])->format('Y-m-d');
                //$company_information->save(['timestamps' => false]);
                //get membership status id
                $membership_status = MembershipStatus::where('title', $requestArr['status'])->active()->first();
                $insertArr = [
                    'company_id' => $companyObj->id,
                    'couser_id' => Auth::id(),
                    'from_membership_level_id' => $old_membership_level_id,
                    'membership_level_id' => $requestArr['membership_level_id'],
                    'from_membership_status_id' => $old_membership_status->id,
                    'membership_status_id' => $membership_status->id,
                    'ip_address' => $request->ip(),
                ];

                CompanyMembershipActivityLog::create($insertArr);

                /* Create company page screen shot start */
                Custom::createCompanyPageScreenShot($companyObj);
                /* Create company page screen shot end */

                /* update company lead informations */
                $membership_level = $companyObj->membership_level;
                if ($membership_level->hide_leads == 'yes') {
                    CompanyLead::where('company_id', $companyObj->id)->update(['is_hidden' => 'yes']);
                } else if ($membership_level->hide_leads == 'no') {
                    CompanyLead::where('company_id', $companyObj->id)->update(['is_hidden' => 'no']);
                }

                if ($requestArr['status'] == 'Approved') {
                    $companyObj->bg_check_date = $requestArr['approval_date'];

                    /* Enable Second Invoice for payment [Start] */
                    CompanyInvoice::where([
                        'company_id' => $companyObj->id,
                        'invoice_type' => 'Referral List',
                        'status' => 'waiting',
                    ])->update(['status' => 'pending']);

                    /* Enable Second Invoice for payment [End] */


                    /* Company status changed mail to Company */
                    $web_settings = Custom::getSettings();
                    $companyUserObj = CompanyUser::where([
                                ['company_id', $companyObj->id],
                                ['company_user_type', 'company_super_admin']
                            ])
                            ->first();

                    $mail_id = "26"; /* Mail title: Company Status Change - Approved */
                    $mailArr = Custom::generate_company_user_email_arr($companyObj->company_information);
                    $replaceArr = [
                        'company_name' => $companyObj->company_name,
                        'global_address' => ((isset($web_settings['office_address']) && $web_settings['office_address'] != '') ? $web_settings['office_address'] : ''),
                        'phone_number' => ((isset($web_settings['phone_number']) && $web_settings['phone_number'] != '') ? $web_settings['phone_number'] : ''),
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
                        'link' => url('dashboard')
                    ];
                    Custom::companyMailMessageCreate($messageArr, $mail_id, $replaceArr);

                    if (!is_null($mailArr) && count($mailArr) > 0) {                     
                        foreach ($mailArr AS $mail_item) {
                            Mail::to($mail_item)->send(new CompanyMail($mail_id, $replaceArr));
                        }
                    }
                } else if ($requestArr['status'] == 'Subscribed') {
                    $companyObj->regarding_your_request = "subscribe";
                    $companyObj->special_offers = "subscribe";
                    $companyObj->scams_updates = "subscribe";
                    $companyObj->general_updates = "subscribe";
                    $companyObj->why_unsubscribe = null;
                    $companyObj->unsubscribe_reason = null;
                }

                $companyObj->save();
                Session::put('active_accordion', 'member_status');
            }

            if ($request->hasFile('company_logo')) {
                $imageArr = Custom::uploadFile($request->file('company_logo'), 'company_logo');
                $companyObj->company_logo_id = $imageArr['mediaObj']->id;
                $companyObj->save();
            }

            /* Create company page screen shot start */
            Custom::createCompanyPageScreenShot($companyObj);
            /* Create company page screen shot end */

            flash('Company profile has been updated successfully')->success();
            //return redirect('company-profile');
            return back();
        }
    }

    public function update_affiliations(Request $request) {
        $validator = Validator::make($request->all(), [
                    'company_id' => 'required',
                    'professional_affiliations' => 'required',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        } else {
            $requestArr = $request->all();
            $requestArr['professional_affiliations'] = json_encode($requestArr['professional_affiliations']);
            $company_customer_references = CompanyCustomerReference::where('company_id', $requestArr['company_id'])->first();
            $company_customer_references->update($requestArr);

            Session::put('active_accordion', 'company_profile');

            flash('Company Professional Affiliations has been updated successfully')->success();
            return back();
        }
    }

    public function upload_company_documents(Request $request) {
        if ($request->has('expiry_type') && $request->get('expiry_type') == 'yes') {
            $validator = Validator::make($request->all(), [
                        'company_id' => 'required',
                        'document_type' => 'required',
                        'media' => 'required|mimes:jpg,jpeg,png,pdf|max:25000',
                        'expiration_date' => 'required'
            ]);
        } else if ($request->has('expiry_type') && $request->get('expiry_type') == 'no') {
            $validator = Validator::make($request->all(), [
                        'company_id' => 'required',
                        'document_type' => 'required',
                        'media' => 'required|mimes:jpg,jpeg,png,pdf|max:25000',
            ]);
        } else {
            return [
                'success' => 0,
                'title' => 'Error',
                'type' => 'error',
                'message' => 'Select Document type which you want to upload.',
            ];
        }


        if ($validator->fails()) {
            $messageArr = [];
            foreach ($validator->messages()->getMessages() AS $key => $value) {
                $messageArr[$key] = $value[0];
            }

            return [
                'success' => 0,
                'title' => 'Error',
                'type' => 'error',
                'message' => implode("<br />", $messageArr),
            ];
        } else {
            $requestArr = $request->all();

            if (isset($requestArr['expiration_date']) && $requestArr['expiration_date'] != '') {
                //check data is proper or not
                $expiration_date_arr = explode('/', $requestArr['expiration_date']);

                if ($expiration_date_arr['0'] > 12 || $expiration_date_arr['0'] > 31) {
                    return [
                        'success' => 0,
                        'type' => 'error',
                        'title' => 'Error',
                        'message' => 'Enter valid expiration date.',
                    ];
                }

                $selected_date = \Carbon\Carbon::parse($requestArr['expiration_date']);
                $today_date = \Carbon\Carbon::now();

                if ($today_date > $selected_date) {
                    return [
                        'success' => 0,
                        'type' => 'error',
                        'title' => 'Error',
                        'message' => 'Expiration date must be future date!',
                    ];
                }
            }

            $companyObj = $this->modelObj->find($requestArr['company_id']);

            $title_arr = [
                'credit_check_report_file' => 'Credit Check Report File',
                'online_reputation_report_file' => 'Online Reputation Report File',
                'owner_1_bg_check_file' => 'Owner 1 Background Check Report File',
                'owner_2_bg_check_file' => 'Owner 2 Background Check Report File',
                'owner_3_bg_check_file' => 'Owner 3 Background Check Report File',
                'owner_4_bg_check_file' => 'Owner 4 Background Check Report File',
                'registered_legally_to_state' => 'State Business Registration',
                'proof_of_ownership' => 'Proof Of Ownership',
                'state_licensing' => 'State Licensing',
                'country_licensing' => 'Country Licensing',
                'city_licensing' => 'City Licensing',
                'written_warrenty' => 'Work Agreements Warranty',
                'general_liablity_insurance_file' => 'General Liablity Insurance Document',
                'worker_comsensation_insurance_file' => 'Worker Complen Insurance Document',
                'articles_of_incorporation_file' => 'Income Tax Filling Document',
                'pre_screening_report_file' => 'Pre Screening Report File',
                'customer_references' => 'Customer References File',
                'subcontractor_agreement_file' => 'Subcontractor Agreement',
            ];

            $field_name = $requestArr['field_name'];
            $document_type = $requestArr['document_type'];


            /* Document type Arr */
            $documentTypeArr = [
                'credit_check_report_file',
                'online_reputation_report_file',
                'owner_1_bg_check_file',
                'owner_2_bg_check_file',
                'owner_3_bg_check_file',
                'owner_4_bg_check_file',
            ];


            //dd($document_type);
            if (in_array($document_type, $documentTypeArr)) {
                if ($request->hasFile('media')) {
                    $imageArr = Custom::uploadFile($request->file('media'), 'media', [],true);

                    $documentArr = [
                        'company_id' => $requestArr['company_id'],
                        'document_type' => $document_type,
                        'file_id' => $imageArr['mediaObj']->id,
                        'status' => 'completed',
                        'upload_by' => 'Admin',
                        'admin_id' => Auth::id(),
                    ];

                    $company_document = CompanyDocument::create($documentArr);

                    $company_approval_status = CompanyApprovalStatus::firstOrCreate([
                                'company_id' => $requestArr['company_id']
                    ]);

                    if ($document_type != 'credit_check_report_file' && $document_type != 'online_reputation_report_file') {
                        for ($owners = 1; $owners <= $companyObj->number_of_owners; $owners++) {
                            $file_type = 'owner_' . $owners . '_bg_check_file';
                            $company_owner = 'company_owner' . $owners;
                            $bg_check_status = 'owner_' . $owners . '_bg_check_document_status';
                            if ($file_type == $document_type) {
                                $company_owner_information = $companyObj->company_information->$company_owner;
                                $company_owner_information->bg_check_document_id = $company_document->id;
                                $company_owner_information->save();


                                $company_approval_status->$bg_check_status = 'completed';
                                $company_approval_status->save();
                            }
                        }
                    } else {
                        $companyObj->$field_name = $company_document->id;
                        $companyObj->save();

                        if ($document_type == 'credit_check_report_file') {
                            $company_approval_status->credit_check_report_status = 'completed';
                            $company_approval_status->save();
                        } else if ($document_type == 'online_reputation_report_file') {
                            $company_approval_status->online_reputation_report_status = 'completed';
                            $company_approval_status->save();
                        }
                    }
                } else {
                    flash('Please select file first.')->error();
                    return back();
                }
            } else if ($document_type == 'insurance_documents') {
                $company_insurance = CompanyInsurance::firstOrCreate(['company_id' => $requestArr['company_id']]);

                $insurance_type = $company_insurance->general_liability_insurance_and_worker_compensation_insurance;
                if ($insurance_type == 'Yes') {
                    $liability_insurance_document = CompanyDocument::create($requestArr);
                    if (isset($requestArr['expiration_date']) && $requestArr['expiration_date'] != '') {
                        $expiration_date1 = Custom::date_formats($requestArr['expiration_date'], env('DATE_FORMAT'), env('DB_DATE_FORMAT'));
                    }

                    $first_media = '';
                    if ($request->hasFile('media')) {
                        $imageArr = Custom::uploadFile($request->file('media'), 'media',true, true);
                        $first_media = $imageArr['mediaObj']->id;
                        $liability_insurance_document->file_id = $first_media;
                        $liability_insurance_document->expiration_date = $expiration_date1;
                        $liability_insurance_document->document_type = "general_liablity_insurance_file";
                        $liability_insurance_document->save();
                    }


                    $compensation_insurance_document = CompanyDocument::create($requestArr);
                    if (isset($requestArr['expiration_date2']) && $requestArr['expiration_date2'] != '') {
                        $expiration_date2 = Custom::date_formats($requestArr['expiration_date2'], env('DATE_FORMAT'), env('DB_DATE_FORMAT'));
                    }

                    $compensation_insurance_document->file_id = $first_media;
                    $compensation_insurance_document->expiration_date = $expiration_date2;
                    $compensation_insurance_document->document_type = "worker_comsensation_insurance_file";
                    $compensation_insurance_document->save();



                    $company_insurance->gen_lia_ins_file_id = $liability_insurance_document->id;
                    $company_insurance->general_liability_insurance_expiry_date = $expiration_date1;
                    $company_insurance->work_com_ins_file_id = $compensation_insurance_document->id;
                    $company_insurance->workers_compensation_insurance_expiry_date = $expiration_date2;
                    $company_insurance->save();


                    $company_approval_status = CompanyApprovalStatus::firstOrCreate(['company_id' => $requestArr['company_id']]);
                    $company_approval_status->$document_type = 'completed';
                    $company_approval_status->save();
                } else {
                    if ($field_name == "gen_lia_ins_file_id") {
                        $liability_insurance_document = CompanyDocument::create($requestArr);
                        if (isset($requestArr['expiration_date']) && $requestArr['expiration_date'] != '') {
                            $expiration_date = Custom::date_formats($requestArr['expiration_date'], env('DATE_FORMAT'), env('DB_DATE_FORMAT'));
                        }

                        if ($request->hasFile('media')) {
                            $imageArr = Custom::uploadFile($request->file('media'), 'media',true,true);
                            $liability_insurance_document->file_id = $imageArr['mediaObj']->id;
                            $liability_insurance_document->expiration_date = $expiration_date;
                            $liability_insurance_document->document_type = "general_liablity_insurance_file";
                            $liability_insurance_document->save();
                        }

                        $company_insurance->gen_lia_ins_file_id = $liability_insurance_document->id;
                        $company_insurance->general_liability_insurance_expiry_date = $expiration_date;
                        $company_insurance->save();
                    } else if ($field_name == "work_com_ins_file_id") {
                        $compensation_insurance_document = CompanyDocument::create($requestArr);
                        if (isset($requestArr['expiration_date']) && $requestArr['expiration_date'] != '') {
                            $expiration_date = Custom::date_formats($requestArr['expiration_date'], env('DATE_FORMAT'), env('DB_DATE_FORMAT'));
                        }

                        if ($request->hasFile('media')) {
                            $imageArr = Custom::uploadFile($request->file('media'), 'media',true,true);
                            $compensation_insurance_document->file_id = $imageArr['mediaObj']->id;
                            $compensation_insurance_document->expiration_date = $expiration_date;
                            $compensation_insurance_document->document_type = "worker_comsensation_insurance_file";
                            $compensation_insurance_document->save();
                        }

                        $company_insurance->work_com_ins_file_id = $compensation_insurance_document->id;
                        $company_insurance->workers_compensation_insurance_expiry_date = $expiration_date;
                        $company_insurance->save();
                    }


                    if (!is_null($company_insurance->gen_lia_ins_file_id) && !is_null($company_insurance->work_com_ins_file_id)) {
                        $company_approval_status = CompanyApprovalStatus::firstOrCreate(['company_id' => $requestArr['company_id']]);
                        $company_approval_status->$document_type = 'completed';
                        $company_approval_status->save();
                    }
                }
            } else {
                if (isset($requestArr['expiration_date']) && $requestArr['expiration_date'] != '') {
                    $requestArr['expiration_date'] = Custom::date_formats($requestArr['expiration_date'], env('DATE_FORMAT'), env('DB_DATE_FORMAT'));
                }

                $requestArr['upload_by'] = 'Admin';
                $requestArr['admin_id'] = Auth::id();

                $company_document = CompanyDocument::create($requestArr);
                if ($request->hasFile('media')) {
                    $imageArr = Custom::uploadFile($request->file('media'), 'media',true, true);
                    $company_document->file_id = $imageArr['mediaObj']->id;
                    $company_document->save();
                }


                if ($document_type != 'articles_of_incorporation_file') {
                    $company_approval_status = CompanyApprovalStatus::firstOrCreate(['company_id' => $requestArr['company_id']]);
                    if ($document_type == 'pre_screening_report_file') {
                        $company_approval_status->pre_screening_process = 'completed';
                    } else if ($document_type == 'written_warrenty') {
                        $company_approval_status->work_agreements_warranty = 'completed';
                    } else if ($document_type == 'subcontractor_agreement_file') {
                        $company_approval_status->subcontractor_agreement = 'completed';
                    } else {
                        $company_approval_status->$document_type = 'completed';
                    }

                    $company_approval_status->save();
                }


                if ($requestArr['document_type'] == 'general_liablity_insurance_file') {
                    $company_insurance_file = CompanyInsurance::firstOrCreate(['company_id' => $requestArr['company_id']]);
                    $company_insurance_file->$field_name = $company_document->id;
                    $company_insurance_file->general_liability_insurance_expiry_date = $requestArr['expiration_date'];
                    $company_insurance_file->save();
                } else if ($requestArr['document_type'] == 'worker_comsensation_insurance_file') {
                    $company_insurance_file = CompanyInsurance::firstOrCreate(['company_id' => $requestArr['company_id']]);
                    $company_insurance_file->$field_name = $company_document->id;
                    $company_insurance_file->workers_compensation_insurance_expiry_date = $requestArr['expiration_date'];
                    $company_insurance_file->save();
                } else if ($requestArr['document_type'] == 'customer_references') {
                    $company_document->document_type = 'references_form_file';
                    $company_document->save();


                    $company_customer_references = CompanyCustomerReference::firstOrCreate(['company_id' => $requestArr['company_id']]);
                    $company_customer_references->$field_name = $company_document->id;
                    $company_customer_references->save();
                } else {
                    $company_licensing = CompanyLicensing::firstOrCreate(['company_id' => $requestArr['company_id']]);
                    $company_licensing->$field_name = $company_document->id;
                    $company_licensing->save();
                }
            }

            Custom::company_approval_status($companyObj->id);

            $data = [
                'company_item' => $companyObj,
                'company_licensing' => $companyObj->company_licensing,
                'company_approval_status' => $companyObj->company_approval_status,
                'company_customer_references' => $companyObj->company_customer_references,
                'company_insurances' => $companyObj->company_insurance,
                'company_information' => $companyObj->company_information,
                'company_application_file' => CompanyDocument::with('media')->where([
                            ['company_id', $companyObj->id],
                            ['document_type', 'application_file'],
                            ['status', 'completed']
                        ])
                        ->latest()
                        ->first()
            ];

            return view($this->view_base . '.ajax_refreshed._company_documents', $data);
        }
    }

    public function change_company_document_status(Request $request) {
        $validator = Validator::make($request->all(), [
                    'company_id' => 'required',
                    'document_type' => 'required',
                    'document_id' => 'required',
                    'approval_status' => 'required',
        ]);

        if ($validator->fails()) {
            $messageArr = [];
            foreach ($validator->messages()->getMessages() AS $key => $value) {
                $messageArr[$key] = $value[0];
            }

            return [
                'success' => 0,
                'title' => 'Error',
                'type' => 'error',
                'message' => implode("<br />", $messageArr),
            ];
        } else {
            $title_arr = [
                'registered_legally_to_state' => 'State Business Registration',
                'proof_of_ownership' => 'Proof Of Ownership',
                'state_licensing' => 'State Licensing',
                'country_licensing' => 'Country Licensing',
                'city_licensing' => 'City Licensing',
                'written_warrenty' => 'Work Agreements Warranty',
                'subcontractor_agreement_file' => 'Subcontractor Agreement',
                'customer_references' => 'Customer References File',
            ];

            $requestArr = $request->all();


            if (isset($requestArr['expiration_date']) && $requestArr['expiration_date'] != '') {
                //check data is proper or not
                $expiration_date_arr = explode('/', $requestArr['expiration_date']);

                if ($expiration_date_arr['0'] > 12 || $expiration_date_arr['0'] > 31) {
                    return [
                        'success' => 0,
                        'type' => 'error',
                        'title' => 'Error',
                        'message' => 'Enter valid expiration date.',
                    ];
                }

                $selected_date = \Carbon\Carbon::parse($requestArr['expiration_date']);
                $today_date = \Carbon\Carbon::now();

                if ($today_date > $selected_date) {
                    return [
                        'success' => 0,
                        'type' => 'error',
                        'title' => 'Error',
                        'message' => 'Expiration date must be future date!',
                    ];
                }
            }

            $document_type = $requestArr['document_type'];
            $companyObj = $this->modelObj->find($requestArr['company_id']);

            $company_approval_status = CompanyApprovalStatus::firstOrCreate(['company_id' => $requestArr['company_id']]);
            $company_approval_status_type = $requestArr['approval_status'];
            if ($requestArr['approval_status'] == 'rejected') {
                $company_approval_status_type = 'pending';
            }


            if ($document_type == 'written_warrenty') {
                $company_approval_status->work_agreements_warranty = $company_approval_status_type;
            } else if ($document_type == 'pre_screening_report_file') {
                $company_approval_status->pre_screening_process = $company_approval_status_type;
            } else if ($document_type == 'subcontractor_agreement_file') {
                $company_approval_status->subcontractor_agreement = $company_approval_status_type;
            } else {
                $company_approval_status->$document_type = $company_approval_status_type;
            }
            $company_approval_status->save();


            if ($document_type == 'customer_references') {
                $company_document_type = 'references_form_file';
            } else {
                $company_document_type = $document_type;
            }

            $company_document = CompanyDocument::where([
                        ['company_id', $requestArr['company_id']],
                        ['document_type', $company_document_type]
                    ])->first();

            $updateArr = ['status' => $requestArr['approval_status']];
            if (isset($requestArr['expiration_date']) && $requestArr['expiration_date'] != '') {
                $updateArr['expiration_date'] = Custom::date_formats($requestArr['expiration_date'], env('DATE_FORMAT'), env('DB_DATE_FORMAT'));
            }

            if ($request->has('reject_note')) {
                $updateArr['reject_note'] = $requestArr['reject_note'];
            }
            $company_document->update($updateArr);

            if ($requestArr['approval_status'] == 'rejected') {
                /* Company Document rejected by Admin mail to Company */
                $web_settings = Custom::getSettings();
                $companyUserObj = CompanyUser::where([
                            ['company_id', $companyObj->id],
                            ['company_user_type', 'company_super_admin']
                        ])
                        ->first();
                $mail_id = "22"; /* Mail title: Company Document Rejected */
                $replaceWithArr = [
                    'company_name' => $companyObj->company_name,
                    'document_type' => $title_arr[$document_type],
                    'reject_reason' => $requestArr['reject_note'],
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
                    'url' => url('company-documents'),
                    'email_footer' => $companyUserObj->email,
                    'copyright_year' => date('Y'),
                        //'main_service_category' => '',
                ];

                $messageArr = [
                    'company_id' => $companyObj->id,
                    'message_type' => 'info',
                    'link' => url('company-documents'),
                ];
                Custom::companyMailMessageCreate($messageArr, $mail_id, $replaceWithArr);
            }


            if (isset($mail_id) && $mail_id != '') {
                /* $companyObj->company_email */
                $emailIdArr = Custom::generate_company_user_email_arr($companyObj->company_information);
                if (!is_null($emailIdArr) && count($emailIdArr) > 0) {
                    foreach ($emailIdArr AS $mail_item) {
                        //Mail::to('ajay.makwana87@gmail.com')->send(new CompanyMail($mail_id, $replaceWithArr));
                        Mail::to($mail_item)->send(new CompanyMail($mail_id, $replaceWithArr));
                    }
                }
            }


            Custom::company_approval_status($companyObj->id);
            $data = [
                'company_item' => $companyObj,
                'company_licensing' => $companyObj->company_licensing,
                'company_approval_status' => $companyObj->company_approval_status,
                'company_customer_references' => $companyObj->company_customer_references,
                'company_insurances' => $companyObj->company_insurance,
                'company_information' => $companyObj->company_information,
                'company_application_file' => CompanyDocument::with('media')->where([
                            ['company_id', $companyObj->id],
                            ['document_type', 'application_file'],
                            ['status', 'completed']
                        ])
                        ->latest()
                        ->first()
            ];

            return view($this->view_base . '.ajax_refreshed._company_documents', $data);
        }
    }

    public function remove_company_documents(Request $request) {
        $validator = Validator::make($request->all(), [
                    'company_id' => 'required',
                    'document_type' => 'required',
                    'field_name' => 'required',
                    'document_id' => 'required',
        ]);

        if ($validator->fails()) {
            $messageArr = [];
            foreach ($validator->messages()->getMessages() AS $key => $value) {
                $messageArr[$key] = $value[0];
            }

            return [
                'success' => 0,
                'title' => 'Error',
                'type' => 'error',
                'message' => implode("<br />", $messageArr),
            ];
        } else {
            $title_arr = [
                'credit_check_report_file' => 'Credit Check Report File',
                'online_reputation_report_file' => 'Online Reputation Report File',
                'owner_1_bg_check_file' => 'Owner 1 Background Check Report File',
                'owner_2_bg_check_file' => 'Owner 2 Background Check Report File',
                'owner_3_bg_check_file' => 'Owner 3 Background Check Report File',
                'owner_4_bg_check_file' => 'Owner 4 Background Check Report File',
                'registered_legally_to_state' => 'State Business Registration',
                'proof_of_ownership' => 'Proof Of Ownership',
                'state_licensing' => 'State Licensing',
                'country_licensing' => 'Country Licensing',
                'city_licensing' => 'City Licensing',
                'written_warrenty' => 'Work Agreements Warranty',
                'articles_of_incorporation_file' => 'Articles Of Incorporation',
                'general_liablity_insurance_file' => 'General Liablity Insurance Document',
                'worker_comsensation_insurance_file' => 'Worker Complen Insurance Document',
                //'insurance_documents' => 'Insurance Documents',
                'pre_screening_report_file' => 'Pre Screening Report',
                'customer_references' => 'Customer Reference Report',
                'subcontractor_agreement_file' => 'Subcontractor Agreement',
            ];

            $requestArr = $request->all();
            $companyObj = $this->modelObj->find($requestArr['company_id']);

            $company_document = CompanyDocument::where([
                        ['company_id', $requestArr['company_id']],
                        ['id', $requestArr['document_id']]
                    ])->first();

            $get_media = Media::find($company_document->file_id);
            if (!is_null($get_media) && file_exists($get_media->file_name)) {
                unlink($get_media->file_name);
            }
            $get_media->delete();
            $company_document->delete();

            $field_name = $requestArr['field_name'];
            $document_type = $requestArr['document_type'];

            /* Document type Arr */
            $documentTypeArr = [
                'owner_1_bg_check_file',
                'owner_2_bg_check_file',
                'owner_3_bg_check_file',
                'owner_4_bg_check_file',
            ];

            if ($document_type != 'articles_of_incorporation_file') {
                $company_approval_status = CompanyApprovalStatus::where('company_id', $requestArr['company_id'])->first();

                if (in_array($document_type, $documentTypeArr)) {
                    for ($owners = 1; $owners <= $companyObj->number_of_owners; $owners++) {
                        $file_type = 'owner_' . $owners . '_bg_check_file';
                        $bg_check_status = 'owner_' . $owners . '_bg_check_document_status';
                        if ($file_type == $document_type) {
                            $company_approval_status->$bg_check_status = 'pending';
                            $company_approval_status->background_check_process = 'in process';
                            $company_approval_status->background_check_submittal = 'in process';
                        }
                    }
                } else if ($document_type == 'written_warrenty') {
                    $company_approval_status->work_agreements_warranty = 'pending';
                } else if ($document_type == 'subcontractor_agreement_file') {
                    $company_approval_status->subcontractor_agreement = 'pending';
                } else if ($document_type == 'pre_screening_report_file') {
                    $company_approval_status->pre_screening_process = 'pending';
                } else if ($document_type == 'credit_check_report_file') {
                    $company_approval_status->credit_check_report_status = 'pending';
                } else if ($document_type == 'online_reputation_report_file') {
                    $company_approval_status->online_reputation_report_status = 'pending';
                } else {
                    $company_approval_status->$document_type = 'pending';
                }
                $company_approval_status->save();
            }


            if ($document_type == 'insurance_documents') {
                $company_insurance = CompanyInsurance::firstOrCreate(['company_id' => $requestArr['company_id']]);

                $insurance_type = $company_insurance->general_liability_insurance_and_worker_compensation_insurance;
                if ($insurance_type == 'Yes') {
                    $company_insurance->gen_lia_ins_file_id = null;
                    $company_insurance->general_liability_insurance_expiry_date = null;
                    $company_insurance->work_com_ins_file_id = null;
                    $company_insurance->workers_compensation_insurance_expiry_date = null;
                } else {
                    if ($field_name == "gen_lia_ins_file_id") {
                        $company_insurance->gen_lia_ins_file_id = null;
                        $company_insurance->general_liability_insurance_expiry_date = null;
                    } else if ($field_name == "work_com_ins_file_id") {
                        $company_insurance->work_com_ins_file_id = null;
                        $company_insurance->workers_compensation_insurance_expiry_date = null;
                    }
                }
                $company_insurance->save();
            } else if ($document_type == 'general_liablity_insurance_file') {
                $company_insurance = CompanyInsurance::firstOrCreate(['company_id' => $requestArr['company_id']]);
                $company_insurance->$field_name = null;
                $company_insurance->general_liability_insurance_expiry_date = null;
                $company_insurance->save();
            } else if ($document_type == 'worker_comsensation_insurance_file') {
                $company_insurance = CompanyInsurance::firstOrCreate(['company_id' => $requestArr['company_id']]);
                $company_insurance->$field_name = null;
                $company_insurance->workers_compensation_insurance_expiry_date = null;
                $company_insurance->save();
            } else if ($document_type == 'customer_references') {
                $company_customer_references = CompanyCustomerReference::firstOrCreate(['company_id' => $requestArr['company_id']]);
                $company_customer_references->$field_name = null;
                $company_customer_references->save();
            } else if ($document_type == 'credit_check_report_file' || $document_type == 'online_reputation_report_file' || in_array($document_type, $documentTypeArr)) {
                if ($document_type == 'credit_check_report_file') {
                    $companyObj->credit_check_report_id = null;
                    $companyObj->save();
                } else if ($document_type == 'online_reputation_report_file') {
                    $companyObj->online_reputation_report_id = null;
                    $companyObj->save();
                }
            } else {
                $company_licensing = CompanyLicensing::where('company_id', $requestArr['company_id'])->first();
                $company_licensing->$field_name = null;
                $company_licensing->save();
            }

            Custom::company_approval_status($requestArr['company_id']);
            $data = [
                'company_item' => $companyObj,
                'company_licensing' => $companyObj->company_licensing,
                'company_approval_status' => $companyObj->company_approval_status,
                'company_customer_references' => $companyObj->company_customer_references,
                'company_insurances' => $companyObj->company_insurance,
                'company_information' => $companyObj->company_information,
                'company_application_file' => CompanyDocument::with('media')->where([
                            ['company_id', $companyObj->id],
                            ['document_type', 'application_file'],
                            ['status', 'completed']
                        ])
                        ->latest()
                        ->first()
            ];

            return view($this->view_base . '.ajax_refreshed._company_documents', $data);
        }
    }

    public function update_service_category(Request $request) {
        $requestArr = $request->all();
        //dd($requestArr);
        $validator = Validator::make($requestArr, [
            'company_id' => 'required',
            'item_id' => 'required',
            'item_type' => 'required',
            'item_category_type' => 'required',
        ]);
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $returnStr = Custom::custom_update_service_category($requestArr);

        if ($returnStr) {
            Session::put('active_accordion', 'service_categories');

            flash("Service category list updated successfully")->success();
            return back();
        } else {
            return back()->withErrors($validator)->withInput();
        }
    }

    public function update_service_category_price(Request $request) {
        $validation_arr = [
            'company_id' => 'required',
            'service_category_id' => 'required',
            'fee' => 'required',
        ];

        $validator = Validator::make($request->all(), $validation_arr);

        if ($validator->fails()) {
            return [
                'status' => 0,
                'title' => 'Error',
                'type' => 'error',
                'message' => implode("<br/>", $validator->messages()->all())
            ];
        } else {
            $requestArr = $request->all();

            $company_service_category = CompanyServiceCategory::where([
                        ['company_id', $requestArr['company_id']],
                        ['service_category_id', $requestArr['service_category_id']]
                    ])->first();

            if (!is_null($company_service_category)) {
                $company_service_category->fee = $requestArr['fee'];
                $company_service_category->save();

                return [
                    'status' => 1,
                    'title' => 'Success',
                    'type' => 'success',
                    'message' => 'Service Category price updated successfully.',
                    'fee' => $requestArr['fee'],
                ];
            } else {
                return [
                    'status' => 0,
                    'title' => 'Error',
                    'type' => 'error',
                    'message' => 'Service Category not found.'
                ];
            }
        }
    }

    public function update_company_zipcode_list(Request $request) {
        $validator = Validator::make($request->all(), [
                    'company_id' => 'required',
                    'main_zipcode' => 'required',
                    'mile_range' => 'required'
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        } else {
            $requestArr = $request->all();
            $companyObj = $this->modelObj->findOrFail($requestArr['company_id']);

            if ($companyObj->main_zipcode != $requestArr['main_zipcode']) {
                try {
                    $mainZipcodeCity = Custom::getZipcodeDetail($requestArr['main_zipcode']);
                    if (count($mainZipcodeCity) > 0) {
                        $requestArr['main_zipcode_city'] = $mainZipcodeCity['city'];
                    }
                } catch (Exception $e) {
                    return 'fail';
                }
            }
            $companyObj->update($requestArr);

            try {
                $zipCodes = Custom::getZipCodeRange($companyObj->main_zipcode, $requestArr['mile_range']);
                if (count($zipCodes) > 0) {
                    CompanyZipcode::where('company_id', $companyObj->id)->delete();

                    foreach ($zipCodes as $zipcode_item) {
                        $stateObj = State::where('short_name', $zipcode_item['state'])->first();

                        $insertZipcodeArr = [
                            'company_id' => $companyObj->id,
                            'zip_code' => $zipcode_item['zip_code'],
                            'distance' => $zipcode_item['distance'],
                            'city' => $zipcode_item['city'],
                            'state' => $zipcode_item['state'],
                            'state_id' => ((!is_null($stateObj)) ? $stateObj->id : null),
                        ];

                        if (isset($requestArr['zipcode_item']) && count($requestArr['zipcode_item']) > 0 && in_array($zipcode_item['zip_code'], $requestArr['zipcode_item'])) {
                            $insertZipcodeArr['status'] = 'active';
                        } else {
                            $insertZipcodeArr['status'] = 'inactive';
                        }

                        CompanyZipcode::create($insertZipcodeArr);
                    }
                }
            } catch (Exception $e) {
                return 'fail';
            }
            Session::put('active_accordion', 'zipcodes');

            flash("Zipcode list updated successfully")->success();
            return back();
        }
    }

    public function update_company_application_leads_notifications(Request $request) {
        $requestArr = $request->all();
        //dd($requestArr);
        $companyObj = $this->modelObj->findOrFail($requestArr['company_id']);
        $company_lead_notification_obj = CompanyLeadNotification::firstOrCreate(['company_id' => $companyObj->id]);

        $updateArr = [
            'main_email_address' => $requestArr['main_email_address'],
            'owner_2' => '',
            'owner_2_name' => '',
            'owner_2_email' => '',
            'office_manager' => '',
            'office_manager_name' => '',
            'office_manager_email' => '',
            'sales_manager' => '',
            'sales_manager_name' => '',
            'sales_manager_email' => '',
            'estimators_sales_1' => '',
            'estimators_sales_1_name' => '',
            'estimators_sales_1_email' => '',
            'estimators_sales_2' => '',
            'estimators_sales_2_name' => '',
            'estimators_sales_2_email' => '',
        ];

        if ($request->has('owner_2')) {
            $updateArr['owner_2'] = $requestArr['owner_2'];
            $updateArr['owner_2_name'] = $requestArr['owner_2_name'];
            $updateArr['owner_2_email'] = $requestArr['owner_2_email'];
        }

        if ($request->has('office_manager')) {
            $updateArr['office_manager'] = $requestArr['office_manager'];
            $updateArr['office_manager_name'] = $requestArr['office_manager_name'];
            $updateArr['office_manager_email'] = $requestArr['office_manager_email'];
        }

        if ($request->has('sales_manager')) {
            $updateArr['sales_manager'] = $requestArr['sales_manager'];
            $updateArr['sales_manager_name'] = $requestArr['sales_manager_name'];
            $updateArr['sales_manager_email'] = $requestArr['sales_manager_email'];
        }

        if ($request->has('estimators_sales_1')) {
            $updateArr['estimators_sales_1'] = $requestArr['estimators_sales_1'];
            $updateArr['estimators_sales_1_name'] = $requestArr['estimators_sales_1_name'];
            $updateArr['estimators_sales_1_email'] = $requestArr['estimators_sales_1_email'];
        }

        if ($request->has('estimators_sales_2')) {
            $updateArr['estimators_sales_2'] = $requestArr['estimators_sales_2'];
            $updateArr['estimators_sales_2_name'] = $requestArr['estimators_sales_2_name'];
            $updateArr['estimators_sales_2_email'] = $requestArr['estimators_sales_2_email'];
        }

        $company_lead_notification_obj->update($updateArr);
        Session::put('active_accordion', 'lead_management');

        flash('Company lead notifications updated successfully')->success();
        return back();
    }

    public function view_invoice($invoice_id) {
        $data['admin_page_title'] = $this->singular_display_name . ' Invoice Detail';
        $data['company_invoice'] = CompanyInvoice::where('invoice_id', $invoice_id)->first();

        return view($this->view_base . '.company_invoice_detail', $data);
    }

    public function download_invoice($invoice_id) {
        $data['company_invoice'] = CompanyInvoice::where('invoice_id', $invoice_id)->first();
        //return view('company.invoices.pdf', $data);

        $pdf = PDF::loadView('company.invoices.pdf', $data);
        if ($data['company_invoice']->status == 'paid') {
            $pdf->mpdf->setWatermarkText('PAID');
            $pdf->mpdf->showWatermarkText = true;
            //$pdf->setWatermarkImage(env('APP_URL') . 'images/paid.png');
        }
        $uploadsPath = storage_path('app' . DIRECTORY_SEPARATOR . 'private' . DIRECTORY_SEPARATOR . 'company-invoices'. DIRECTORY_SEPARATOR. $data['company_invoice']->invoice_id . '.pdf');
        $pdf->save($uploadsPath);
        $pdf->download('invoice-' . $data['company_invoice']->invoice_id . '.pdf');
    }

    public function mark_invoice_paid(Request $request) {
        $validator = Validator::make($request->all(), [
                    'invoice_id' => 'required',
                    'invoice_paid_date' => 'required',
                    'note' => 'required'
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        } else {
            $fileAttachments = [];
            $requestArr = $request->all();

            $company_invoice = CompanyInvoice::where([
                        ['invoice_id', $requestArr['invoice_id']],
                        ['status', 'pending']
                    ])->first();

            if (is_null($company_invoice)) {
                flash("Invoice not found you want to mark as paid.")->error();
                return back();
            }

            $companyObj = $this->modelObj->with('membership_level')->find($company_invoice->company_id);

            $requestArr['invoice_paid_date'] = $requestArr['invoice_paid_date'];
            $requestArr['status'] = 'paid';
            $requestArr['payment_type'] = 'check';
            $company_invoice->update($requestArr);

            if ($company_invoice->invoice_type == 'One Time Setup Fee & Prescreen/Background Check Fees') {
                $company_approval_status = CompanyApprovalStatus::firstOrCreate(['company_id' => $companyObj->id]);
                $company_approval_status->one_time_setup_fee = 'completed';
                $company_approval_status->background_check_pre_screen_fees = 'completed';
                $company_approval_status->save();
            } else if ($company_invoice->invoice_type == 'Referral List') {
                $daysToAdd = $companyObj->membership_level->number_of_days;
                $approval_date = Custom::date_formats($requestArr['invoice_paid_date'], env('DATE_FORMAT'), env('DB_DATE_FORMAT'));
                $renewal_date = \Carbon\Carbon::createFromFormat(env('DB_DATE_FORMAT'), $approval_date)->addDays($daysToAdd);

                $companyObj->approval_date = $approval_date;
                $companyObj->renewal_date = $renewal_date;
                $companyObj->status = "Active";
                $companyObj->leads_status = "active";
                $companyObj->save();
            }

            //Custom::company_approval_status($companyObj->id);

            /* Check Payment mail to Company Mail */
            $mail_id = '125';
            $data['company_invoice'] = $company_invoice;
            $pdf = PDF::loadView('company.invoices.pdf', $data);
            $pdf->mpdf->setWatermarkText('PAID');
            $pdf->mpdf->showWatermarkText = true;
            //$pdf->setWatermarkImage(env('APP_URL') . 'images/paid.png');
            $uploadsPath = storage_path('app' . DIRECTORY_SEPARATOR . 'private' . DIRECTORY_SEPARATOR . 'company-invoices'. DIRECTORY_SEPARATOR. $data['company_invoice']->invoice_id . '.pdf');
            $pdf->save($uploadsPath);
            $fileAttachments[] = $uploadsPath;

            /* Mail send to Company */
            $web_settings = Custom::getSettings();
            $companyUserObj = CompanyUser::where([
                        ['company_id', $companyObj->id],
                        ['company_user_type', 'company_super_admin']
                    ])
                    ->first();

            $replaceWithArr = [
                'company_name' => $companyObj->company_name,
                'invoice_number' => $company_invoice->invoice_id,
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
                'date' => $company_invoice->created_at->format(env('DATE_FORMAT')),
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
            Custom::companyMailMessageCreate($messageArr, $mail_id, $replaceWithArr);

            /* $companyObj->company_email */
            $mailArr = Custom::generate_company_user_email_arr($companyObj->company_information);
            if (!is_null($mailArr) && count($mailArr) > 0) {
                foreach ($mailArr AS $mail_item) {
                    Mail::to($mail_item)->send(new CompanyMail($mail_id, $replaceWithArr, $fileAttachments));
                }
            }

            Session::put('active_accordion', 'payment_details');
            flash("Invoice status updated successfully")->success();
            return back();
        }
    }

    public function delete_invoice(Request $request) {
        $company_invoice = CompanyInvoice::findOrFail($request->get('invoice_id'));

        try {
            $company_invoice->delete();
            flash("Company Invoice deleted successfully")->success();
            return back();
        } catch (Exception $e) {
            flash("Company Invoice can not be deleted!")->danger();
            return back();
        }
    }

    public function add_company_note(Request $request) {
        $validator = Validator::make($request->all(), [
                    'company_id' => 'required',
                    'notes' => 'required',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        } else {
            $requestArr = $request->all();
            $requestArr['created_by'] = Auth::id();

            $messageArr = [
                'company_id' => $requestArr['company_id'],
                'message_type' => 'info'
            ];

            $insert = false;
            if ($request->has('company_note_id')) {
                $company_note = CompanyNote::find($request->get('company_note_id'));

                if (is_null($company_note)) {
                    $insert = true;
                } else {
                    $company_note->update($requestArr);
                    flash('Company note updated successfully')->success();
                }
            } else {
                $insert = true;
            }

            if ($insert) {
                CompanyNote::create($requestArr);
                flash('Company note added successfully')->success();
            }

            Session::put('active_accordion', 'notes');

            return back();
        }
    }

    public function pendingApproval(Request $request) {
        $list_params = Custom::getListParams($request);
        $rows = $this->modelObj->getPendingApprovalAdminList($list_params);

        if (count($rows) <= 0 && $request->has('page') && $request->get('page') > 1) {
            $list_params['page'] = $rows->lastPage();
            return redirect('admin/companies/pending-approval' . http_build_query($list_params));
        }

        $data = [
            'admin_page_title' => $this->singular_display_name . ' [Pending Approval]',
            'module_plural_name' => $this->singular_display_name . ' [Pending Approval]',
            'rows' => $rows,
            'list_params' => $list_params,
            'url_key' => 'pending-approval',
            'module_urls' => [
                'list' => 'pending-approval',
                'url_key' => 'companies',
                'url_key_singular' => Str::singular('companies'),
                'add' => route('companies.create'),
                'store' => route('companies.store'),
                'edit' => 'companies.edit',
                'update' => 'companies.update',
                'delete' => 'companies.destroy',
            ],
        ];

        return view($this->view_base . '.company_pending_approval', $data);
    }

    public function paidPending(Request $request) {
        $list_params = Custom::getListParams($request);
        $rows = $this->modelObj->getPaidPendingAdminList($list_params);

        if (count($rows) <= 0 && $request->has('page') && $request->get('page') > 1) {
            $list_params['page'] = $rows->lastPage();
            return redirect('admin/companies/paid-pending' . http_build_query($list_params));
        }

        $data = [
            'admin_page_title' => $this->singular_display_name . ' [Paid Pending]',
            'module_plural_name' => $this->singular_display_name . ' [Paid Pending]',
            'rows' => $rows,
            'list_params' => $list_params,
            'url_key' => 'paid-pending',
            'module_urls' => [
                'list' => 'paid-pending',
                'url_key' => 'companies',
                'url_key_singular' => Str::singular('companies'),
                'add' => route('companies.create'),
                'store' => route('companies.store'),
                'edit' => 'companies.edit',
                'update' => 'companies.update',
                'delete' => 'companies.destroy',
            ],
        ];

        return view($this->view_base . '.company_paid_pending', $data);
    }

    /* Ajax Call methods */

    public function zipcode_list_display(Request $request) {
        if (
                ($request->has('zipcode') && $request->get('zipcode') != '') && ($request->has('mile_range') && $request->get('mile_range') != '')
        ) {
            try {
                $requestArr = $request->all();
                $zipCodes = Custom::getZipCodeRange($requestArr['zipcode'], $requestArr['mile_range']);

                $data['zipcode'] = $zipCodes;
                return view($this->view_base . '._zipcode_list_display', $data);
            } catch (Exception $e) {
                return 'fail';
            }
        } else {
            return [
                'success' => 0,
                'message' => 'Select mile range first.'
            ];
        }
    }

    public function get_membership_status_from_level(Request $request) {
        if ($request->has('membership_level_id') && $request->get('membership_level_id') != '') {
            $data['membership_status'] = self::get_membership_status($request->get('membership_level_id'));
            return view($this->view_base . '._membership_level_status_selection', $data);
        } else {
            return [
                'success' => 0,
                'message' => 'Select Level first.'
            ];
        }
    }

    public function change_company_approval_status(Request $request) {
        $validator = Validator::make($request->all(), [
                    'company_id' => 'required',
                    'approval_status_type' => 'required',
        ]);

        if (isset($validator) && $validator->fails()) {
            return [
                'success' => 0,
                'title' => 'Error',
                'type' => 'error',
                'message' => 'Company ID not found.'
            ];
        } else {
            $web_settings = Custom::getSettings();

            $requestArr = $request->all();
            $companyObj = $this->modelObj->find($requestArr['company_id']);
            $companyUserObj = CompanyUser::where([
                        ['company_id', $companyObj->id],
                        ['company_user_type', 'company_super_admin']
                    ])
                    ->first();

            $messageArr = [
                'company_id' => $companyObj->id,
                'message_type' => 'info',
                'link' => url('company-profile')
            ];

            $company_approval_status = CompanyApprovalStatus::firstOrCreate(['company_id' => $requestArr['company_id']]);

            if ($requestArr['approval_status_type'] == 'company_bio') {
                $company_approval_status->company_bio = $requestArr['approval_status'];

                if ($requestArr['approval_status'] == 'pending') {
                    $company_approval_status->company_bio_reject_note = $requestArr['reject_note'];

                    /* Company Bio Rejected mail to Company */
                    $mail_id = "98"; /* Mail title: Company Bio Rejected */
                    $replaceArr = [
                        'company_name' => $companyObj->company_name,
                        'rejected_reason' => $requestArr['reject_note'],
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
                        'url' => url('company-profile'),
                        'email_footer' => $companyUserObj->email,
                        'copyright_year' => date('Y'),
                            //'main_service_category' => '',
                    ];
                } else if ($requestArr['approval_status'] == 'remove') {
                    $company_approval_status->company_bio = 'pending';
                    $company_approval_status->company_bio_reject_note = null;

                    $companyObj->company_bio = null;
                    $companyObj->save();
                } else if ($requestArr['approval_status'] == 'completed') {
                    /* Company Bio Approved mail to Company */
                    $mail_id = "90"; // Mail title: Company Bio Approved
                    $replaceArr = [
                        'company_name' => $companyObj->company_name,
                        'global_address' => ((isset($web_settings['office_address']) && $web_settings['office_address'] != '') ? $web_settings['office_address'] : ''),
                        'phone_number' => ((isset($web_settings['phone_number']) && $web_settings['phone_number'] != '') ? $web_settings['phone_number'] : ''),
                        'global_email' => ((isset($web_settings['global_email']) && $web_settings['global_email'] != '') ? $web_settings['global_email'] : ''),
                        'facebook_link' => ((isset($web_settings['facebook']) && $web_settings['facebook'] != '') ? $web_settings['facebook'] : ''),
                        'twitter_link' => ((isset($web_settings['twitter']) && $web_settings['twitter'] != '') ? $web_settings['twitter'] : ''),
                        'instagram_link' => ((isset($web_settings['instagram']) && $web_settings['instagram'] != '') ? $web_settings['instagram'] : ''),
                        'rumble_link' => ((isset($web_settings['rumble']) && $web_settings['rumble'] != '') ? $web_settings['rumble'] : ''),
                        'youtube_link' => ((isset($web_settings['youtube']) && $web_settings['youtube'] != '') ? $web_settings['youtube'] : ''),
                        'unsubscription_link' => url('unsubscribe-page/company', ['company_slug' => $companyObj->slug]),
                        'request_generate_link' => $companyUserObj->email,
                        'date' => $companyObj->created_at->format(env('DATE_FORMAT')),
                        'url' => url('company-profile'),
                        'email_footer' => $companyUserObj->email,
                        'copyright_year' => date('Y'),
                            //'main_service_category' => '',
                    ];
                }
            } else if ($requestArr['approval_status_type'] == 'company_logo') {
                $company_approval_status->company_logo = $requestArr['approval_status'];

                if ($requestArr['approval_status'] == 'pending') {
                    $company_approval_status->company_logo_reject_note = $requestArr['reject_note'];

                    Media::where('id', $companyObj->company_logo_id)->delete();
                    $companyObj->company_logo_id = null;
                    $companyObj->save();

                    /* Company Logo Rejected mail to Company */
                    $mail_id = "99"; /* Mail title: Company Logo Status Change */
                    $replaceArr = [
                        'company_name' => $companyObj->company_name,
                        'rejected_reason' => $requestArr['reject_note'],
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
                        'date' => $companyUserObj->created_at->format(env('DATE_FORMAT')),
                        'url' => url('company-profile'),
                        'email_footer' => $companyUserObj->email,
                        'copyright_year' => date('Y'),
                            //'main_service_category' => '',
                    ];
                } else if ($requestArr['approval_status'] == 'remove') {
                    $company_approval_status->company_logo = 'pending';
                    $company_approval_status->company_logo_reject_note = null;

                    Media::where('id', $companyObj->company_logo_id)->delete();
                    $companyObj->company_logo_id = null;
                    $companyObj->save();
                } else if ($requestArr['approval_status'] == 'completed') {
                    /* Company Logo Approved mail to Company */
                    $mail_id = "92"; // Mail title: Company Logo Approved
                    $replaceArr = [
                        'company_name' => $companyObj->company_name,
                        'global_address' => ((isset($web_settings['office_address']) && $web_settings['office_address'] != '') ? $web_settings['office_address'] : ''),
                        'phone_number' => ((isset($web_settings['phone_number']) && $web_settings['phone_number'] != '') ? $web_settings['phone_number'] : ''),
                        'global_email' => ((isset($web_settings['global_email']) && $web_settings['global_email'] != '') ? $web_settings['global_email'] : ''),
                        'facebook_link' => ((isset($web_settings['facebook']) && $web_settings['facebook'] != '') ? $web_settings['facebook'] : ''),
                        'twitter_link' => ((isset($web_settings['twitter']) && $web_settings['twitter'] != '') ? $web_settings['twitter'] : ''),
                        'instagram_link' => ((isset($web_settings['instagram']) && $web_settings['instagram'] != '') ? $web_settings['instagram'] : ''),
                        'rumble_link' => ((isset($web_settings['rumble']) && $web_settings['rumble'] != '') ? $web_settings['rumble'] : ''),
                        'youtube_link' => ((isset($web_settings['youtube']) && $web_settings['youtube'] != '') ? $web_settings['youtube'] : ''),
                        'unsubscription_link' => url('unsubscribe-page/company', ['company_slug' => $companyObj->slug]),
                        'request_generate_link' => $companyUserObj->email,
                        'date' => $companyObj->created_at->format(env('DATE_FORMAT')),
                        'url' => url('company-profile'),
                        'email_footer' => $companyUserObj->email,
                        'copyright_year' => date('Y'),
                            //'main_service_category' => '',
                    ];
                }
            }

            $company_approval_status->save();

            Custom::company_approval_status($companyObj->id);

            /* Create company page screen shot start */
            Custom::createCompanyPageScreenShot($companyObj);
            /* Create company page screen shot end */

            if (isset($mail_id) && $mail_id != '') {
                Custom::companyMailMessageCreate($messageArr, $mail_id, $replaceArr);
                $mailArr = Custom::generate_company_user_email_arr($companyObj->company_information);
                if (!is_null($mailArr) && count($mailArr) > 0) {
                    foreach ($mailArr AS $mail_item) {
                        //Mail::to('ajay.makwana87@gmail.com')->send(new CompanyMail($mail_id, $replaceArr));
                        Mail::to($mail_item)->send(new CompanyMail($mail_id, $replaceArr));
                    }
                }
            }

            $data = [
                'company_item' => $companyObj,
                'company_approval_status' => $companyObj->company_approval_status,
                'admin_form' => true
            ];


            if ($requestArr['approval_status_type'] == 'company_logo') {
                return [
                    'type' => 'company_logo',
                    'data' => view($this->view_base . '.ajax_refreshed._company_logo', $data)->render(),
                ];
            } else if ($requestArr['approval_status_type'] == 'company_bio') {
                return [
                    'type' => 'company_bio',
                    'data' => view($this->view_base . '.ajax_refreshed._company_bio', $data)->render(),
                ];

                return view($this->view_base . '.ajax_refreshed._company_bio', $data);
            }
        }
    }

    public function change_company_user_approval_status(Request $request) {
        $validator = Validator::make($request->all(), [
                    'company_id' => 'required',
                    'company_user_id' => 'required',
                    'approval_status_type' => 'required',
                    'approval_status' => 'required'
        ]);

        if (isset($validator) && $validator->fails()) {
            return [
                'success' => 0,
                'title' => 'Error',
                'type' => 'error',
                'message' => 'Company ID not found.'
            ];
        } else {
            $web_settings = Custom::getSettings();

            $requestArr = $request->all();
            $companyObj = $this->modelObj->find($requestArr['company_id']);
            $userObj = CompanyUser::where([
                        ['company_id', $requestArr['company_id']],
                        ['id', $requestArr['company_user_id']]
                    ])->first();

            $messageArr = [
                'company_id' => $companyObj->id,
                'message_type' => 'info',
                'link' => url('profile')
            ];

            if ($requestArr['approval_status_type'] == 'user_bio') {
                if ($requestArr['approval_status'] == 'pending') {
                    $userObj->user_bio_status = $requestArr['approval_status'];
                    $userObj->user_bio_reject_note = $requestArr['reject_note'];

                    /* Company User Bio Rejected mail to Company */
                    $mail_id = "100"; /* Mail title: Company User Bio Rejected */
                    $replaceArr = [
                        'first_name' => $userObj->first_name,
                        'last_name' => $userObj->last_name,
                        'rejected_reason' => $requestArr['reject_note'],
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
                        'request_generate_link' => $userObj->email,
                        'date' => $userObj->created_at->format(env('DATE_FORMAT')),
                        'url' => url('profile'),
                        'email_footer' => $userObj->email,
                        'copyright_year' => date('Y'),
                            //'main_service_category' => '',
                    ];
                } else if ($requestArr['approval_status'] == 'remove') {
                    $userObj->user_bio_status = "pending";
                    $userObj->user_bio_reject_note = null;
                } else if ($requestArr['approval_status'] == 'completed') {
                    $userObj->user_bio_status = $requestArr['approval_status'];

                    /* Company User Bio Approved mail to Company */
                    $mail_id = "94"; /* Mail title: Company User Bio Approved */
                    $replaceArr = [
                        'first_name' => $userObj->first_name,
                        'last_name' => $userObj->last_name,
                        'global_address' => ((isset($web_settings['office_address']) && $web_settings['office_address'] != '') ? $web_settings['office_address'] : ''),
                        'phone_number' => ((isset($web_settings['phone_number']) && $web_settings['phone_number'] != '') ? $web_settings['phone_number'] : ''),
                        'global_email' => ((isset($web_settings['global_email']) && $web_settings['global_email'] != '') ? $web_settings['global_email'] : ''),
                        'facebook_link' => ((isset($web_settings['facebook']) && $web_settings['facebook'] != '') ? $web_settings['facebook'] : ''),
                        'twitter_link' => ((isset($web_settings['twitter']) && $web_settings['twitter'] != '') ? $web_settings['twitter'] : ''),
                        'instagram_link' => ((isset($web_settings['instagram']) && $web_settings['instagram'] != '') ? $web_settings['instagram'] : ''),
                        'rumble_link' => ((isset($web_settings['rumble']) && $web_settings['rumble'] != '') ? $web_settings['rumble'] : ''),
                        'youtube_link' => ((isset($web_settings['youtube']) && $web_settings['youtube'] != '') ? $web_settings['youtube'] : ''),
                        'unsubscription_link' => url('unsubscribe-page/company', ['company_slug' => $companyObj->slug]),
                        'request_generate_link' => $userObj->email,
                        'date' => $userObj->created_at->format(env('DATE_FORMAT')),
                        'url' => url('profile'),
                        'email_footer' => $userObj->email,
                        'copyright_year' => date('Y'),
                            //'main_service_category' => '',
                    ];
                }
            } else if ($requestArr['approval_status_type'] == 'user_profile_picture') {
                if ($requestArr['approval_status'] == 'pending') {
                    $userObj->user_image_status = $requestArr['approval_status'];
                    $userObj->user_image_reject_note = $requestArr['reject_note'];

                    /* Company User Profile Picture Rejected mail to Company */
                    $mail_id = "101"; /* Mail title: Company User Profile Picture Rejected */
                    $replaceArr = [
                        'first_name' => $userObj->first_name,
                        'last_name' => $userObj->last_name,
                        'rejected_reason' => $requestArr['reject_note'],
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
                        'request_generate_link' => $userObj->email,
                        'date' => $userObj->created_at->format(env('DATE_FORMAT')),
                        'url' => url('profile'),
                        'email_footer' => $userObj->email,
                        'copyright_year' => date('Y'),
                            //'main_service_category' => '',
                    ];
                } else if ($requestArr['approval_status'] == 'remove') {
                    $userObj->user_image_status = "pending";
                    $userObj->user_image_reject_note = null;
                } else if ($requestArr['approval_status'] == 'completed') {
                    $userObj->user_image_status = $requestArr['approval_status'];

                    /* Company User Profile Picture Approved mail to Company */
                    $mail_id = "96"; /* Mail title: Company User Profile Picture Approved */
                    $replaceArr = [
                        'first_name' => $userObj->first_name,
                        'last_name' => $userObj->last_name,
                        'global_address' => ((isset($web_settings['office_address']) && $web_settings['office_address'] != '') ? $web_settings['office_address'] : ''),
                        'phone_number' => ((isset($web_settings['phone_number']) && $web_settings['phone_number'] != '') ? $web_settings['phone_number'] : ''),
                        'global_email' => ((isset($web_settings['global_email']) && $web_settings['global_email'] != '') ? $web_settings['global_email'] : ''),
                        'facebook_link' => ((isset($web_settings['facebook']) && $web_settings['facebook'] != '') ? $web_settings['facebook'] : ''),
                        'twitter_link' => ((isset($web_settings['twitter']) && $web_settings['twitter'] != '') ? $web_settings['twitter'] : ''),
                        'instagram_link' => ((isset($web_settings['instagram']) && $web_settings['instagram'] != '') ? $web_settings['instagram'] : ''),
                        'rumble_link' => ((isset($web_settings['rumble']) && $web_settings['rumble'] != '') ? $web_settings['rumble'] : ''),
                        'youtube_link' => ((isset($web_settings['youtube']) && $web_settings['youtube'] != '') ? $web_settings['youtube'] : ''),
                        'unsubscription_link' => url('unsubscribe-page/company', ['company_slug' => $companyObj->slug]),
                        'request_generate_link' => $userObj->email,
                        'date' => $userObj->created_at->format(env('DATE_FORMAT')),
                        'url' => url('profile'),
                        'email_footer' => $userObj->email,
                        'copyright_year' => date('Y'),
                            //'main_service_category' => '',
                    ];
                }
            }

            $userObj->save();

            if (isset($mail_id) && $mail_id != '') {
                Custom::companyMailMessageCreate($messageArr, $mail_id, $replaceArr);

                Mail::to($userObj->email)->send(new CompanyMail($mail_id, $replaceArr));
            }

            return [
                'success' => 1,
                'title' => 'Success',
                'type' => 'success',
                'message' => 'Company User Approval Status Updated successfully.'
            ];
        }
    }

    /* Static methods */

    public static function get_membership_status($membership_level_id) {
        $membership_status = MembershipStatus::leftJoin('membership_level_statuses', 'membership_statuses.id', 'membership_level_statuses.membership_status_id')
                ->where('membership_level_statuses.membership_level_id', $membership_level_id)
                ->active()
                ->orderBy('membership_statuses.title', 'ASC')
                ->pluck('membership_statuses.title', 'membership_statuses.title');

        return $membership_status;
    }

    /* Get Sign In As Company From Admin Side [Start] */

    public function signInCompany($id, Request $request) {

        // Find company super admin 
        $company_user_item = CompanyUser::where([
                    ['company_user_type', 'company_super_admin'],
                    ['company_id', $id]
                ])->firstOrFail();
        //dd($company_user_item);

        Session::put('company_mask', true);

        Auth::guard('company_user')->login($company_user_item);
        return redirect(route('company-dashboard'));
    }

    /* Get Sign In As Company From Admin Side [End] */


    /* upload company logo start */

    public function uploadCompanyLogo(Request $request) {
        //file_put_contents('test.png', $request->get('file'));
        //dd($request->all());

        $companyObj = $this->modelObj->find($request->get('company_id'));


        $file_name = 'abc_company_logo_' . $companyObj->id . '.png';
        $file_path = 'uploads/media/' . $file_name;

        Image::make($request->get('file'))->save($file_path);

        $mediaObj = Media::create([
                    'file_name' => $file_name,
                    'original_file_name' => $file_name,
                    'file_type' => 'image/png',
                    'file_extension' => 'png',
        ]);

        // Optimize Image
        ImageOptimizer::optimize($file_path, $file_path);

        // Generate Thumbs
        $image_obj = Image::make($file_path);

        if (env('FIT_THUMBS') != '') {
            Custom::createThumbnails($file_path, 'fit_thumbs', $file_name, env('FIT_THUMBS'));
        }
        if (env('HEIGHT_THUMBS') != '') {
            Custom::createThumbnails($file_path, 'height_thumbs', $file_name, env('HEIGHT_THUMBS'));
        }
        if (env('WIDTH_THUMBS') != '') {
            Custom::createThumbnails($file_path, 'width_thumbs', $file_name, env('WIDTH_THUMBS'));
        }


        $companyObj->company_logo_id = $mediaObj->id;
        $companyObj->save();


        $company_approval_status_obj = CompanyApprovalStatus::where('company_id', $companyObj->id)->first();
        $company_approval_status_obj->company_logo = 'completed';
        $company_approval_status_obj->save();

        /* Create company page screen shot start */
        Custom::createCompanyPageScreenShot($companyObj);
        /* Create company page screen shot end */

        $data = [
            'company_item' => $companyObj,
            'company_approval_status' => $companyObj->company_approval_status,
            'admin_form' => true
        ];

        return view($this->view_base . '.ajax_refreshed._company_logo', $data);
    }

    /* upload company logo end */

    /* upload company bio start */

    public function update_company_bio(Request $request) {
        $requestArr = $request->all();

        $validator = Validator::make($request->all(), [
                    'company_id' => 'required',
                    'company_bio' => 'required',
        ]);

        if (isset($validator) && $validator->fails()) {
            $messageArr = [];
            foreach ($validator->messages()->getMessages() AS $key => $value) {
                $messageArr[$key] = $value[0];
            }

            return [
                'success' => 0,
                'title' => 'Error',
                'type' => 'error',
                'message' => implode("<br />", $messageArr),
            ];
        } else {
            $companyObj = $this->modelObj->findOrFail($requestArr['company_id']);
            $companyObj->update($requestArr);

            $company_approval_status = CompanyApprovalStatus::firstOrCreate(['company_id' => $companyObj->id]);
            $company_approval_status->company_bio = "completed";
            $company_approval_status->save();

            /* Create company page screen shot start */
            Custom::createCompanyPageScreenShot($companyObj);
            /* Create company page screen shot end */

            $data = [
                'company_item' => $companyObj,
                'company_approval_status' => $companyObj->company_approval_status,
                'admin_form' => true
            ];

            return view($this->view_base . '.ajax_refreshed._company_bio', $data);
        }
    }

    /* upload company bio end */


    /* Update Company Document list start */

    public function update_company_document_list(Request $request) {
        $requestArr = $request->all();
        $validator = Validator::make($request->all(), [
                    'company_id' => 'required',
        ]);

        if (isset($validator) && $validator->fails()) {
            return back()->withErrors($validator)->withInput();
        } else {
            $statusColumnsArr = [
                'registered_legally_to_state',
                'proof_of_ownership',
                'state_licensing',
                'country_licensing',
                'city_licensing',
                'work_agreements_warranty',
                'subcontractor_agreement',
                'general_liablity_insurance_file',
                'worker_comsensation_insurance_file',
                'customer_references',
            ];

            $company_approval_status = CompanyApprovalStatus::firstOrCreate(['company_id' => $requestArr['company_id']]);
            $company_licensing = CompanyLicensing::firstOrCreate(['company_id' => $requestArr['company_id']]);
            $company_insurance = CompanyInsurance::firstOrCreate(['company_id' => $requestArr['company_id']]);

            /* State business registration [Start] */
            if (isset($requestArr['registered_legally_to_state']) && $requestArr['registered_legally_to_state'] == 'yes') {
                if ($company_approval_status->registered_legally_to_state == 'not required') {
                    $company_approval_status->registered_legally_to_state = 'pending';
                }
                $company_approval_status->proof_of_ownership = 'not required';
                $company_approval_status->save();

                $company_licensing->legally_registered_within_state = 'yes';
                $company_licensing->proof_of_ownership = 'no';
                $company_licensing->save();
            }
            /* State business registration [End] */


            /* Proof Of Ownership [Start] */
            if (isset($requestArr['proof_of_ownership']) && $requestArr['proof_of_ownership'] == 'yes' && !isset($requestArr['registered_legally_to_state'])) {
                if ($company_approval_status->proof_of_ownership == 'not required') {
                    $company_approval_status->proof_of_ownership = 'pending';
                }
                $company_approval_status->registered_legally_to_state = 'not required';
                $company_approval_status->save();

                $company_licensing->legally_registered_within_state = 'no';
                $company_licensing->proof_of_ownership = 'yes';
                $company_licensing->save();
            }
            /* Proof Of Ownership [End] */


            /* Country/State/City Licensing [Start] */
            if ((isset($requestArr['country_licensing']) && $requestArr['country_licensing'] == 'yes') && (isset($requestArr['state_licensing']) && $requestArr['state_licensing'] == 'yes') && (isset($requestArr['city_licensing']) && $requestArr['city_licensing'] == 'yes')) {
                if ($company_approval_status->state_licensing == 'not required') {
                    $company_approval_status->state_licensing = 'pending';
                }

                if ($company_approval_status->country_licensing == 'not required') {
                    $company_approval_status->country_licensing = 'pending';
                }

                if ($company_approval_status->city_licensing == 'not required') {
                    $company_approval_status->city_licensing = 'pending';
                }
                $company_approval_status->save();


                $licensing_required = ["State licensing is required", "Country licensing is required", "City licensing is required"];
                $company_licensing->licensing_required = json_encode($licensing_required);
                $company_licensing->state_licensed = 'yes';
                $company_licensing->country_licensed = 'yes';
                $company_licensing->city_licensed = 'yes';
                $company_licensing->save();
            } else {
                if (isset($requestArr['country_licensing']) && $requestArr['country_licensing'] == 'yes') {
                    if ($company_approval_status->country_licensing == 'not required') {
                        $company_approval_status->country_licensing = 'pending';
                    }
                    $company_approval_status->save();

                    $licensing_required = ['Country licensing is required'];
                    if (isset($requestArr['state_licensing']) && $requestArr['state_licensing'] == 'yes') {
                        $licensing_required[] = 'State licensing is required';
                    }

                    if (isset($requestArr['city_licensing']) && $requestArr['city_licensing'] == 'yes') {
                        $licensing_required[] = 'City licensing is required';
                    }


                    $company_licensing->licensing_required = json_encode($licensing_required);
                    $company_licensing->country_licensed = 'yes';
                    $company_licensing->save();
                } else {
                    $company_approval_status->country_licensing = 'not required';
                    $company_approval_status->save();

                    $remove_element = 'Country licensing is required';
                    $licensing_required = json_decode($company_licensing->licensing_required);
                    if (($key = array_search($remove_element, $licensing_required)) !== false) {
                        unset($licensing_required[$key]);
                    }
                    $licensing_required = array_values($licensing_required);

                    $company_licensing->licensing_required = json_encode($licensing_required);
                    $company_licensing->country_licensed = 'no';
                    $company_licensing->save();
                }

                if (isset($requestArr['state_licensing']) && $requestArr['state_licensing'] == 'yes') {
                    if ($company_approval_status->state_licensing == 'not required') {
                        $company_approval_status->state_licensing = 'pending';
                    }
                    $company_approval_status->save();

                    $licensing_required = ['State licensing is required'];
                    if (isset($requestArr['country_licensing']) && $requestArr['country_licensing'] == 'yes') {
                        $licensing_required[] = 'Country licensing is required';
                    }

                    if (isset($requestArr['city_licensing']) && $requestArr['city_licensing'] == 'yes') {
                        $licensing_required[] = 'City licensing is required';
                    }


                    $company_licensing->licensing_required = json_encode($licensing_required);
                    $company_licensing->state_licensed = 'yes';
                    $company_licensing->save();
                } else {
                    $company_approval_status->state_licensing = 'not required';
                    $company_approval_status->save();

                    $remove_element = 'State licensing is required';
                    $licensing_required = json_decode($company_licensing->licensing_required);
                    if (($key = array_search($remove_element, $licensing_required)) !== false) {
                        unset($licensing_required[$key]);
                    }
                    $licensing_required = array_values($licensing_required);

                    $company_licensing->licensing_required = json_encode($licensing_required);
                    $company_licensing->state_licensed = 'no';
                    $company_licensing->save();
                }

                if (isset($requestArr['city_licensing']) && $requestArr['city_licensing'] == 'yes') {
                    if ($company_approval_status->city_licensing == 'not required') {
                        $company_approval_status->city_licensing = 'pending';
                    }
                    $company_approval_status->save();

                    $licensing_required = ['City licensing is required'];
                    if (isset($requestArr['state_licensing']) && $requestArr['state_licensing'] == 'yes') {
                        $licensing_required[] = 'State licensing is required';
                    }

                    if (isset($requestArr['country_licensing']) && $requestArr['country_licensing'] == 'yes') {
                        $licensing_required[] = 'Country licensing is required';
                    }


                    $company_licensing->licensing_required = json_encode($licensing_required);
                    $company_licensing->city_licensed = 'yes';
                    $company_licensing->save();
                } else {
                    $company_approval_status->city_licensing = 'not required';
                    $company_approval_status->save();

                    $remove_element = 'City licensing is required';
                    $licensing_required = json_decode($company_licensing->licensing_required);
                    if (($key = array_search($remove_element, $licensing_required)) !== false) {
                        unset($licensing_required[$key]);
                    }
                    $licensing_required = array_values($licensing_required);

                    $company_licensing->licensing_required = json_encode($licensing_required);
                    $company_licensing->city_licensed = 'no';
                    $company_licensing->save();
                }
            }
            /* Country/State/City Licensing [End] */


            /* Work Agreement Warranty [Start] */
            if (isset($requestArr['work_agreements_warranty']) && $requestArr['work_agreements_warranty'] == 'yes') {
                if ($company_approval_status->work_agreements_warranty == 'not required') {
                    $company_approval_status->work_agreements_warranty = 'pending';
                }
                $company_approval_status->save();

                $company_licensing->provide_written_warrenty = 'yes';
                $company_licensing->save();
            } else {
                $company_approval_status->work_agreements_warranty = 'not required';
                $company_approval_status->save();

                $company_licensing->provide_written_warrenty = 'no';
                $company_licensing->save();
            }
            /* Work Agreement Warranty [End] */

            /* Sub Contractor Agreement [Start] */
            if (isset($requestArr['subcontractor_agreement']) && $requestArr['subcontractor_agreement'] == 'yes') {
                if ($company_approval_status->subcontractor_agreement == 'not required') {
                    $company_approval_status->subcontractor_agreement = 'pending';
                }
                $company_approval_status->save();

                $company_licensing->subcontract_with_other_companies = 'yes';
                $company_licensing->save();
            } else {
                $company_approval_status->subcontractor_agreement = 'not required';
                $company_approval_status->save();

                $company_licensing->subcontract_with_other_companies = 'no';
                $company_licensing->save();
            }
            /* Sub Contractor Agreement [End] */

            /* Insurance Documents [Start] */
            if (isset($requestArr['general_liablity_insurance_file']) && $requestArr['general_liablity_insurance_file'] == 'yes') {
                if ($company_approval_status->general_liablity_insurance_file == 'not required') {
                    $company_approval_status->general_liablity_insurance_file = 'pending';
                }
                $company_approval_status->save();

                if (isset($requestArr['worker_comsensation_insurance_file']) && $requestArr['worker_comsensation_insurance_file'] == 'yes') {
                    $company_insurance->general_liability_insurance_and_worker_compensation_insurance = 'Yes';
                    $company_insurance->save();
                }
            } else {
                $company_approval_status->general_liablity_insurance_file = 'not required';
                $company_approval_status->save();
            }

            if (isset($requestArr['worker_comsensation_insurance_file']) && $requestArr['worker_comsensation_insurance_file'] == 'yes') {
                if ($company_approval_status->worker_comsensation_insurance_file == 'not required') {
                    $company_approval_status->worker_comsensation_insurance_file = 'pending';
                }
                $company_approval_status->save();


                if (isset($requestArr['general_liablity_insurance_file']) && $requestArr['general_liablity_insurance_file'] == 'yes') {
                    $company_insurance->general_liability_insurance_and_worker_compensation_insurance = 'Yes';
                    $company_insurance->save();
                }
            } else {
                $company_approval_status->worker_comsensation_insurance_file = 'not required';
                $company_approval_status->save();
            }
            /* Insurance Documents [End] */

            /* Customer References [Start] */
            if (isset($requestArr['customer_references']) && $requestArr['customer_references'] == 'yes') {
                if ($company_approval_status->customer_references == 'not required') {
                    $company_approval_status->customer_references = 'pending';
                }
                $company_approval_status->save();
            } else {
                $company_approval_status->customer_references = 'not required';
                $company_approval_status->save();
            }
            /* Customer References [End] */

            flash('Company document list updated successfully.')->success();
            return back();
        }
    }

    /* Update Company Document list end */

    public function import_registered_members(Request $request) {
        if ($request->hasFile('memberfile')) {
            $companyList=array();
            $filename = $request->file('memberfile')->getRealPath(); 
            //dd($filename);
            $importedRecords = (new FastExcel)->import($filename, function ($line) {                
                $zip = substr($line['ZIP'], 0, 5);  
                $companyName = $line['NAME'];   
                $companyShortName = strtolower(preg_replace('/[^a-zA-Z0-9_.]/', '', $companyName)).$zip;             
                $email = $line['LOGINEMAIL']; // $companyShortName.'@'.env('EMAIL_DOMAIN');                 
                $passString = Str::random(10);
                $phone = Custom::formatPhoneNumber($line['PHONE']);
                $status_id = $line['STATUS'];
                $membership_status = MembershipStatus::where('id', $status_id)->first();
                $status = 'Subscribed';
                if (isset($membership_status)) {
                    $status = $membership_status->title;
                }
                else
                {
                    Log::error("invalid membership status for ".$companyName.", data:".$line['STATUS']);
                }

                $insertArr = [
                    'firstname' => $line['FIRSTNAME'],
                    'lastname' => $line['LASTNAME'],
                    'companyname' =>  $companyName,
                    'email' => $email,
                    'lead_dest_email' => $line['LEADSDESTINATIONEMAIL'],
                    'phone' => $phone,
                    'address' => $line['ADDRESS'],
                    'city' => $line['CITY'],
                    'state' =>  $line['STATE'],
                    'zipcode' => $zip,
                    'website' => $line['WEBSITE'],
                    'trade_id' => '1',
                    'how_did_you_hear_about_us' => '',
                    'comments' => '',
                    'activation_date' => \Carbon\Carbon::now()->format(env('DB_DATETIME_FORMAT')),
                    'status' => $status,
                    'username' => $companyShortName,
                    'membership_level_id' => $line['LEVEL'],                    
                    'county' => $line['COUNTY'],
                    'scid' => $line['SCID'],
                    'passstring' => $passString,
                    'created_by' => 'import_orphan',
                    'slug' => Str::slug($companyName),
                    'login_email_original' => $line['LOGINEMAIL_Original'],
                    'lead_destination_email_original' => $line['LEADSDESTINATIONEMAIL_Original']
                ]; 
                return $insertArr;
            });

            if(!empty($importedRecords))
            {
                $totalCompaniesToBeImported = count($importedRecords);
                Log::info("Total companies to be imported: ".$totalCompaniesToBeImported);
                $companyIndex = 0;
                foreach ($importedRecords as &$value) {
                    try
                    {
                        $companyIndex++;
                        Log::info("********************************STARTING IMPORT FOR COMPANY".$companyIndex."*************************************");
                        Log::info("Importing ".$companyIndex." of ".$totalCompaniesToBeImported);
                        $saveResult = $this->SaveCompany($value); 
                        if($saveResult['status'] == 0)
                        {
                            Log::info("Company saved successfully: ".json_encode($value));
                        }
                        else
                        {
                            Log::error("Company save failed: ".$saveResult['message'].",data:".json_encode($value));
                        }
                        
                    }
                    catch(Exception $e)
                    {
                        Log::error($e->getMessage());
                    }
                   
                }
            }
        }        
        return redirect($this->urls['list']);
    }

    public function import_official_members(Request $request) {
        if ($request->hasFile('memberfile')) {
            $companyList=array();
            $filename = $request->file('memberfile')->getRealPath(); 
            $json = file_get_contents($filename);
            $data = json_decode($json, true);
            foreach ($data as $item) {
                $requestData = [];
                $serviceCategory = ServiceCategory::where('sc_code', $item['sc_code'])->first();
                $mainCategory = MainCategory::where('id', $serviceCategory->main_category_id)->first();
                $mainTlc = MainCategoryTopLevelCategory::where('main_category_id', $mainCategory->id)->first();
                $tradeType = TopLevelCategoryTrade::select('trade_id')->where('top_level_category_id', $mainTlc->top_level_category_id)->first();
                $zipcodeDetails = ZipCodeDetail::where('zip_code', $item['zipcode'])->first();
                                
                $requestData['full_name'] = $item['first_name'].' '.$item['last_name'];
                $requestData['project_address'] = $item['project_address'];
                $requestData['timeframe'] = $item['timeframe'];
                $requestData['phone'] = $item['phone'];
                $requestData['email'] = $item['email'];
                $requestData['content'] = $item['content'];
                $requestData['signup_url'] = $item['signup_url'];
                $requestData['affiliate_id'] = $item['affiliate_id'];
                $requestData['service_category_id'] = $serviceCategory->id;
                $requestData['service_category_type_id'] = $serviceCategory->service_category_type_id;
                $requestData['main_category_id'] = $mainCategory->id;
                $requestData['top_level_category_id'] = $mainTlc->top_level_category_id;
                $requestData['trade_id'] = $tradeType->trade_id;
                $requestData['ip_address'] = $item['ip_address'];
                $requestData['lead_activation_key'] = Custom::getRandomString(50);
                $requestData['affiliate_id'] = $item['affiliate_id'];
                $requestData['additional_notes'] = 'lead_import_tp';
                if (!isset($zipcodeDetails))
                {
                    $APIkey = env('ZIPCODE_API_KEY');
                    $zipJson = @file_get_contents('https://www.zipcodeapi.com/rest/' . $APIkey . '/info.json/' . $item['zipcode'] . '/radians');
                    if ($zipJson != '') {
                        $zipcodeArr = json_decode($zipJson);
                        $stateObj = State::where('short_name', $zipcodeArr->state)->first();
                        $requestData['zipcode'] = $item['zipcode'];
                        $requestData['city'] = $zipcodeArr->city;
                        $requestData['state_id'] = ((!is_null($stateObj)) ? $stateObj->id : null);
                    } else {
                        Log::error("Ivalid zipcode. zipcode:". $item['zipcode']." item:".json_encode($item));
                    }
                }
                else
                {
                    $requestData['zipcode'] = $item['zipcode'];
                    $requestData['city'] = $zipcodeDetails->city;
                    $requestData['state_id'] = $zipcodeDetails->state_id;
                }
                Log::info("Creating lead");
                Log::info(json_encode($requestData));
                $lead = Lead::create($requestData);
                Log::info("Created lead");
                Log::info(json_encode($lead));

                foreach ($item['companies'] as $companyInfo) {
                    Log::info("Company Info Name:".$companyInfo['name']);
                    Log::info("Company Info Phone:".$companyInfo['phone']);
                  
                    $company = Company::select(
                                    'companies.id',
                                    'companies.membership_level_id',
                                    'companies.created_by')
                                ->leftJoin('membership_levels AS ml', 'companies.membership_level_id', 'ml.id')
                                ->leftJoin('states as s', 'companies.state_id', 's.id')
                                ->leftJoin('media as m', 'companies.company_logo_id', 'm.id')
                                ->where(
                                    function($query) use ($companyInfo) 
                                    {
                                        $query->where('companies.main_company_telephone', $companyInfo['phone'])
                                            ->orWhere('companies.company_name', $companyInfo['name']);
                                    })
                                ->orderByRaw("CASE WHEN companies.created_by = 'import_official' THEN 0 ELSE 1 END")
                                ->first();
                    if(isset($company))
                    {
                        $company_lead_request = [
                            'company_id' => $company->id,
                            'lead_id' => $lead->id,
                            'is_hidden' => $company->membership_level->hide_leads,
                            'priority' => '1'
                        ];
                        Log::info("Creating company lead");
                        Log::info(json_encode($company_lead_request));
        
                        $company_lead = CompanyLead::create($company_lead_request);
                        Log::info("Created company lead");
                        Log::info(json_encode($company_lead));
                    }
                    else
                    {
                        Log::info("Company not found");
                    }
                    
                }
                
                Log::info("Processed json file: item:".json_encode($item));
            }
        }        
        return redirect($this->urls['list']);
    }

    private function SaveCompany($data)
    {
        // check for unique Email
        if (CompanyUser::where('email', $data['email'])->count() > 0) {
            return ['status' => 1, 'message' => 'Email already exists'];
        }
        // check for unique Username
        if (CompanyUser::where('username',  $data['username'])->count() > 0) {
            return ['status' => 1, 'message' => 'Username already exists'];
        }        

        $stateObj = State::where('name', $data['state'])->first();
        // check for valid state
        if (!isset($stateObj)) {
            return ['status' => 1, 'message' => 'Invalid state'];
        }

        try {
            $mainZipcodeCity = Custom::getZipcodeDetail( $data['zipcode']);
            if (count($mainZipcodeCity) > 0) {
                $requestArr['main_zipcode_city'] = $mainZipcodeCity['city'];
            }
        } catch (Exception $e) {
            return ['status' => 1, 'message' => 'Error getting city for zipcode:'.$data['zipcode']];
        }

        $requestArr = [            
            'membership_level_id' => $data['membership_level_id'],            
            'company_name' => $data['companyname'],
            'company_website' => empty($data['website']) ? null : $data['website'],
            'main_company_telephone' => $data['phone'],
            'secondary_telephone' => '',
            'company_mailing_address' => $data['address'],
            'suite' => '',
            'city' => $data['city'],
            'state_id' => $stateObj->id,
            'zipcode' => $data['zipcode'],
            'trade_id' => $data['trade_id'],
            'main_zipcode' => $data['zipcode'],
            'mile_range' => 50,            
            'status' => $data['status'],
            'activation_key' => null,
            'activated_at' => $data['activation_date'],
            'created_by' => $data['created_by'],
            'slug' => $data['slug'],
            'login_email_original' => $data['login_email_original'],
            'lead_destination_email_original' => $data['lead_destination_email_original']
        ];        

        // Add Company
        Log::info("Saving company, request: ".json_encode($requestArr));
        $companyObj = Company::create($requestArr);
        

        // Add Company User
        $companyUserRequest = ['company_id' => $companyObj->id,
                                'first_name' => $data['firstname'],
                                'last_name' => $data['lastname'],
                                'email' => strtolower($data['email']),
                                'username' => $data['username'],
                                'password' => bcrypt($data['passstring'])];
        Log::info("Saving company user: ".json_encode($companyUserRequest));
        $company_user = CompanyUser::create($companyUserRequest);
        
        // Add Company Lead Notification
        if (CompanyLeadNotification::where('main_email_address', $data['lead_dest_email'])->count() == 0) {
            $companyLeadNotificationRequest = [
                    'company_id' => $companyObj->id,
                    'main_email_address' => strtolower($data['lead_dest_email']),
            ];
            Log::info("Saving company lead notification, request: ".json_encode($companyLeadNotificationRequest));
            CompanyLeadNotification::create($companyLeadNotificationRequest);
        }
        else
        {
            Log::error("CompanyLeadNotification error, ".$data['lead_dest_email']." already exists in the system");            
        }
                

        // Add Company Information
        $company_information_obj = CompanyInformation::firstOrCreate(['company_id' => $companyObj->id]);

        //Update Company Information
        $companyInformationRequest = [
                'legal_company_name' => $requestArr['company_name'],
                'main_company_telephone' => $requestArr['main_company_telephone'],
                'website' => $requestArr['company_website'],
                'mailing_address' => $requestArr['company_mailing_address'],
                'suite' => $requestArr['suite'],
                'city' => $requestArr['city'],
                'state_id' => $requestArr['state_id'],
                //'county' => $requestArr['county'],
                'zipcode' => $requestArr['zipcode'],
                //
                'company_owner_1_full_name' => $company_user->first_name . ' ' . $company_user->last_name,
                'company_owner_1_email' => $company_user->email,
                'company_owner_1_user_id' => $company_user->id,
                'company_owner_1_status' => 'registered',
        ];
        Log::info("Saving company information, request: ".json_encode($companyInformationRequest));
        $company_information_obj->update($companyInformationRequest);
        
        // Add Company ServiceCategory
        $scIds = explode(',', $data['scid']);
        foreach ($scIds as $scid)
        {
            $service_category_item = ServiceCategory::where('sc_code', $scid)->first();
            if(isset( $service_category_item))
            {
                $insertScArr = [
                    'company_id' => $companyObj->id,
                    'top_level_category_id' => $service_category_item->top_level_category_id,
                    'main_category_id' => $service_category_item->main_category_id,
                    'service_category_id' => $service_category_item->id,
                    'service_category_type_id' => $service_category_item->service_category_type_id,
                    'category_type' => 'main'
                ];
                Log::info("Saving company service category, request: ".json_encode($insertScArr));
                CompanyServiceCategory::create($insertScArr);
            }
            else
            {
                Log::error("CompanyServiceCategory error, missing service category for sc_code:".$scid);
            }
            
        }
        
        //Add Company zipcodes
        try {
            $zipCodes = Custom::getZipCodeRange($data['zipcode'], 50); //get zips within 50 mile range

            if (count($zipCodes) > 0) {
                foreach ($zipCodes as $zipcode_item) {
                    $stateObj = State::where('short_name', $zipcode_item['state'])->first();

                    $insertZipcodeArr = [
                        'company_id' => $companyObj->id,
                        'zip_code' => $zipcode_item['zip_code'],
                        'distance' => $zipcode_item['distance'],
                        'city' => $zipcode_item['city'],
                        'state' => $zipcode_item['state'],
                        'state_id' => ((!is_null($stateObj)) ? $stateObj->id : null),
                    ];
                    Log::info("Saving company zipcodes, request: ".json_encode($insertZipcodeArr));
                    CompanyZipcode::create($insertZipcodeArr);
                }
            }
        } catch (Exception $e) {
            Log::warning("Error geting zipcode data: ".json_encode($data));
        }

        return ['status' => 0, 'message' => 'Success'];
    }
}

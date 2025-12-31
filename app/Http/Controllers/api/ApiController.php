<?php

namespace App\Http\Controllers\api;
use App\Http\Controllers\Controller;
use App\Models\Complaint;
use App\Models\Company;
use App\Models\CompanyGallery;
use App\Models\CompanyServiceCategory;
use App\Models\Custom;
use App\Models\CompanyZipcode;
use App\Models\Feedback;
use App\Models\Initials;
use App\Models\KeyValuePair;
use App\Models\Lead;
use App\Models\MainCategory;
use App\Models\MainCategoryTopLevelCategory;
use App\Models\ServiceCategory;
use App\Models\ServiceCategoryType;
use App\Models\State;
use App\Models\TopLevelCategory;
use App\Models\TopLevelCategoryTrade;
use App\Models\VerifyMember;
use App\Models\ZipcodeDetail;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Validator;
use Str;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\File;



class ApiController extends Controller
{

    public function get_maincategories(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'searchkey' => 'required',
        ]);

        if ($validator->fails()) {
            $returnArr = [
                'response' => 'failure',
                'responsemessage' => 'Main category not found.',
            ];
        } else {
            $requestArr = $request->all();

            //leftJoin('service_categories', 'main_categories.id', 'service_categories.main_category_id')

            $main_categories = MainCategory::active()
                ->groupBy('main_categories.id')
                ->order();
            if (isset($requestArr['searchkey']) && $requestArr['searchkey'] != '') {
                $main_categories->where(function ($query) use ($requestArr) {
                    $query->where('main_categories.title', 'like', '%' . $requestArr['searchkey'] . '%');
                    $query->orWhere('main_categories.tags', 'like', '%' . $requestArr['searchkey'] . '%');
                    //$query->orWhere('service_categories.title', 'like', '%' . $requestArr['searchkey'] . '%');
                    //$query->orWhere('service_categories.tags', 'like', '%' . $requestArr['searchkey'] . '%');
                });
            }

            $main_categories = $main_categories->get();

            if (count($main_categories) > 0) {
                $mainCategoryArr = [];
                $i = 0;
                foreach ($main_categories as $key => $category_item) {
                    $mainCategoryArr[$i]['slug'] = $category_item->slug;
                    $mainCategoryArr[$i]['name'] = $category_item->title;
                    $mainCategoryArr[$i]['id'] = $category_item->id;

                    /* if (!is_null($category_item->media)) {
                    $mainCategoryArr[$i]['image'] = rtrim(url('/'), '/index.php') . '/uploads/media/' . $category_item->media->file_name;
                    } */

                    if (!is_null($category_item->image_link)) {
                        $mainCategoryArr[$i]['image'] = $category_item->image_link;
                    }
                    $i++;
                }

                $returnArr = ['maincategories' => $mainCategoryArr];
            } else {
                $returnArr = [
                    'response' => 'failure',
                    'responsemessage' => 'Main category not found.',
                ];
            }
        }

        return $returnArr;
    }

    public function get_servicecategorytypes(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'maincategory' => 'required',
        ]);

        if ($validator->fails()) {
            $returnArr = [
                'response' => 'failure',
                'responsemessage' => 'Main category not found.',
            ];
        } else {
            $requestArr = $request->all();

            $top_level_category = MainCategoryTopLevelCategory::select('main_category_top_level_categories.top_level_category_id')
            //->join('service_categories', 'main_category_top_level_categories.main_category_id', 'service_categories.main_category_id')
                ->where('main_category_top_level_categories.main_category_id', $requestArr['maincategory'])
                ->first();

            if (is_null($top_level_category)) {
                $returnArr = [
                    'response' => 'failure',
                    'responsemessage' => 'Top level category not found.',
                ];
            } else {
                $trade_type = TopLevelCategoryTrade::select('top_level_category_trades.trade_id')
                //->join('service_categories', 'top_level_category_trades.top_level_category_id', 'service_categories.top_level_category_id')
                    ->where('top_level_category_trades.top_level_category_id', $top_level_category->top_level_category_id)
                    ->first();

                if (is_null($trade_type)) {
                    $returnArr = [
                        'response' => 'failure',
                        'responsemessage' => 'Trade not found.',
                    ];
                } else {
                    if ($trade_type->trade_id == 1) {
                        $service_category_types = ServiceCategoryType::select('service_category_types.*')->leftJoin('service_categories', 'service_category_types.id', 'service_categories.service_category_type_id')
                            ->where([
                                ['service_category_types.trade_id', $trade_type->trade_id],
                                ['service_categories.main_category_id', $requestArr['maincategory']],
                            ])
                            ->active()
                            ->order()
                            ->groupBy('service_category_types.id')
                            ->get();

                        $serviceCategoryTypeArr = [];
                        if (count($service_category_types) > 0) {
                            $i = 0;
                            foreach ($service_category_types as $service_category_type_item) {
                                $serviceCategoryTypeArr[$i]['id'] = $service_category_type_item->id;
                                $serviceCategoryTypeArr[$i]['name'] = $service_category_type_item->title;

                                if (!is_null($service_category_type_item->media)) {
                                    $serviceCategoryTypeArr[$i]['image'] = rtrim(url('/'), '/index.php') . '/uploads/media/' . $service_category_type_item->media->file_name;
                                }

                                $i++;
                            }
                        }

                        $returnArr = [
                            'serviceCategoryType' => $serviceCategoryTypeArr,
                            'toplevelcategory' => $top_level_category->top_level_category_id,
                        ];
                    } else {
                        $returnArr = [
                            'response' => 'failure',
                            'responsemessage' => 'service category types not found.',
                            'toplevelcategory' => $top_level_category->top_level_category_id,
                        ];
                    }
                }
            }
        }

        return $returnArr;
    }

    public function get_servicecategories(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'toplevelcategory' => 'required',
            'maincategory' => 'required',
        ]);

        if ($validator->fails()) {
            $messageArr = [];
            foreach ($validator->messages()->getMessages() as $key => $value) {
                $messageArr[$key] = $value[0];
            }

            $returnArr = [
                'response' => 'failure',
                'responsemessage' => [$messageArr],
            ];
        } else {
            $requestArr = $request->all();

            //main category
            $main_category_detail = MainCategory::with('media')->active()->find($requestArr['maincategory']);

            $mainCategoryArr = [
                'id' => $main_category_detail->id,
                'name' => $main_category_detail->title,
            ];

            if (!is_null($main_category_detail->media)) {
                $mainCategoryArr['image'] = rtrim(url('/'), '/index.php') . '/uploads/media/' . $main_category_detail->media->file_name;
            }

            $query = ServiceCategory::where('main_category_id', $requestArr['maincategory'])
                ->where('top_level_category_id', $requestArr['toplevelcategory'])
                ->active()
                ->orderBy('sort_order', 'asc');

            if ($request->has('servicecategorytype') && $request->get('servicecategorytype') > 0) {
                $query->where('service_category_type_id', $request->get('servicecategorytype'));
            }

            $service_categories = $query->pluck('title', 'id');

            $serviceCategoryArr = [];
            if (count($service_categories) > 0) {
                $i = 0;
                foreach ($service_categories as $key => $category_item) {
                    $serviceCategoryArr[$i]['id'] = $key;
                    $serviceCategoryArr[$i]['name'] = $category_item;
                    $i++;
                }

                $returnArr = [
                    'maincategory' => $mainCategoryArr,
                    'servicecategories' => $serviceCategoryArr,
                ];
            } else {
                $returnArr = [
                    'response' => 'failure',
                    'responsemessage' => 'service categories not found.',
                    'maincategory' => $mainCategoryArr,
                ];
            }
        }

        return $returnArr;
    }

    public function get_timeframes()
    {

        $timeframeArr = [
            '1' => [
                'title' => 'Ready To Go - 0 to 2 Weeks',
                'value' => 'Ready To Go - 0 to 2 Weeks',
                'image' => 'https://find-a-pro.s3-us-west-2.amazonaws.com/asap_time.jpg',
            ],
            '2' => [
                'title' => 'No Urgency - 3 to 6 Weeks',
                'value' => 'No Urgency - 3 to 6 Weeks',
                'image' => 'https://find-a-pro.s3-us-west-2.amazonaws.com/soon_time.jpg',
            ],
            '3' => [
                'title' => 'Price Shopping - Price Comparing',
                'value' => 'Price Shopping - Price Comparing',
                'image' => 'https://find-a-pro.s3-us-west-2.amazonaws.com/price_shopper_time.jpg',
            ],
        ];

        $returnArr = ['timeframes' => $timeframeArr];
        return $returnArr;
    }

    public function find_a_pro(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'full_name' => 'required',
            'email' => 'required|email',
            'phone' => 'required',
            'main_category_id' => 'required',
            'service_category_id' => 'required',
            'timeframe' => 'required',
            'project_address' => 'required',
            //'state_id' => 'required',
            //'city' => 'required',
            'zipcode' => 'required',
            'content' => 'required',
        ]);

        if ($validator->fails()) {
            $messageArr = [];
            foreach ($validator->messages()->getMessages() as $key => $value) {
                $messageArr[$key] = $value[0];
            }

            $returnArr = [
                'response' => 'failure',
                'responsemessage' => [$messageArr],
            ];
        } else {
            $requestArr = $request->all();
            $top_level_category = MainCategoryTopLevelCategory::select('top_level_category_id')
                ->where('main_category_id', $requestArr['main_category_id'])
                ->first();

            if (is_null($top_level_category)) {
                $returnArr = [
                    'response' => 'failure',
                    'responsemessage' => 'Top level category not found.',
                ];
            } else {
                $requestArr['top_level_category_id'] = $top_level_category->top_level_category_id;

                $trade_type = TopLevelCategoryTrade::select('trade_id')->where('top_level_category_id', $top_level_category->top_level_category_id)->first();

                if (is_null($trade_type)) {
                    $returnArr = [
                        'response' => 'failure',
                        'responsemessage' => 'Trade not found.',
                    ];
                } else {
                    $requestArr['trade_id'] = $trade_type->trade_id;
                    $requestArr['ip_address'] = $request->ip();
                    $requestArr['lead_activation_key'] = Custom::getRandomString(50);
                    $lead = Lead::create($requestArr);

                    $web_settings = Custom::getSettings();

                    // Send confirmation email to consumer
                    Custom::lead_confirmation_email($lead);
                    Custom::generateCompanyLeads($lead);

                    $lead_counter = Custom::get_number_of_companies_who_get_leads($lead);
                    if (
                        (isset($web_settings['sent_to_networx']) && $web_settings['sent_to_networx'] == 'yes') &&
                        $lead->trade_id == 1 &&
                        $lead_counter <= 0
                    ) {
                        $networx_response = Custom::networxCall($lead);
                        if ($networx_response['statusCode'] == '200') {
                            $lead->networx_code = $networx_response['successCode'];
                            $lead->save();
                        }
                    }

                    Custom::lead_email_admin($lead);

                    $returnArr = [
                        'response' => 'success',
                        'responsemessage' => 'Lead generated successfully.',
                    ];
                }
            }
        }

        return $returnArr;
    }

    /* Top Search [Start] */
    public function get_quick_links($type)
    {
        $top_level_categories = TopLevelCategory::select('top_level_categories.*')
            ->where('top_level_categories.top_search_status', 'yes')
            ->active()
            ->groupBy('top_level_categories.id')
            ->orderBy('top_search_sort_order', 'ASC');

        if ($type == 'top-search') {
            $top_level_categories = $top_level_categories->whereNotNull('top_level_categories.top_search_image')
                ->get();
        } else {
            $top_level_categories = $top_level_categories
                ->leftJoin('top_level_category_trades', 'top_level_categories.id', 'top_level_category_trades.top_level_category_id')
                ->whereNull('top_level_categories.top_search_image')
                ->where(function ($q) use ($type) {
                    if (isset($type) && $type != '') {
                        if ($type == 'professional') {
                            $q->where('top_level_category_trades.trade_id', '2');
                        } else if ($type == 'contractor') {
                            $q->where('top_level_category_trades.trade_id', '1');
                        }
                    }
                })
                ->get();
        }

        if (count($top_level_categories) > 0) {
            $topLevelCategoryArr = [];
            $i = 0;
            foreach ($top_level_categories as $category_item) {
                $topLevelCategoryArr[$i]['id'] = $category_item->id;
                $topLevelCategoryArr[$i]['name'] = $category_item->title;

                if ($type == 'top-search' && !is_null($category_item->top_search_image)) {
                    $topLevelCategoryArr[$i]['image'] = $category_item->top_search_image;
                }
                $i++;
            }

            $returnArr = ['toplevelcategories' => $topLevelCategoryArr];
        } else {
            $returnArr = [
                'response' => 'failure',
                'responsemessage' => 'Top Level categories not found.',
            ];
        }

        return $returnArr;
    }

    public function get_maincategories_quick_links(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'toplevelcategory' => 'required',
        ]);

        if ($validator->fails()) {
            $returnArr = [
                'response' => 'failure',
                'responsemessage' => 'Top Level categories not found.',
            ];
        } else {
            $requestArr = $request->all();

            $main_categories = MainCategory::select('main_categories.*')
                ->leftJoin('main_category_top_level_categories', 'main_categories.id', 'main_category_top_level_categories.main_category_id')
                ->leftJoin('top_level_categories', 'main_category_top_level_categories.top_level_category_id', 'top_level_categories.id')
                ->where([
                    ['main_category_top_level_categories.top_level_category_id', $requestArr['toplevelcategory']],
                    ['top_level_categories.top_search_status', 'yes'],
                ])
                ->active()
                ->groupBy('main_categories.id')
                ->order()
                ->get();

            if (count($main_categories) > 0) {
                $mainCategoryArr = [];
                $i = 0;
                foreach ($main_categories as $category_item) {
                    $mainCategoryArr[$i]['id'] = $category_item->id;
                    $mainCategoryArr[$i]['name'] = $category_item->title;

                    /* if (!is_null($category_item->media)) {
                    $mainCategoryArr[$i]['image'] = rtrim(url('/'), '/index.php') . '/uploads/media/' . $category_item->media->file_name;
                    } */

                    if (!is_null($category_item->image_link)) {
                        $mainCategoryArr[$i]['image'] = $category_item->image_link;
                    }

                    $i++;
                }

                $returnArr = ['maincategories' => $mainCategoryArr];
            } else {
                $returnArr = [
                    'response' => 'failure',
                    'responsemessage' => 'Main category not found.',
                ];
            }
        }

        return $returnArr;
    }

    public function get_maincategories_top_search(Request $request)
    {
        $requestArr = $request->all();

        $main_categories = MainCategory::select('main_categories.*')
            ->leftJoin('main_category_top_level_categories', 'main_categories.id', 'main_category_top_level_categories.main_category_id')
            ->leftJoin('top_level_category_trades', 'main_category_top_level_categories.top_level_category_id', 'top_level_category_trades.top_level_category_id')
            ->where(function ($q) use ($requestArr) {
                $q->where('main_categories.top_search_status', 'yes');
                if (isset($requestArr['top_search_type']) && $requestArr['top_search_type'] != '') {
                    if ($requestArr['top_search_type'] == 'professional') {
                        $q->where('top_level_category_trades.trade_id', '2');
                    } else if ($requestArr['top_search_type'] == 'contractor') {
                        $q->where('top_level_category_trades.trade_id', '1');
                    }
                }
            })
            ->active()
            ->groupBy('main_categories.id')
            ->orderBy('main_categories.top_search_sort_order', 'ASC')
            ->get();

        if (count($main_categories) > 0) {
            $mainCategoryArr = [];
            $i = 0;
            foreach ($main_categories as $category_item) {
                $mainCategoryArr[$i]['id'] = $category_item->id;
                $mainCategoryArr[$i]['name'] = $category_item->title;

                if (!is_null($category_item->image_link)) {
                    $mainCategoryArr[$i]['image'] = $category_item->image_link;
                }

                $i++;
            }

            $returnArr = ['maincategories' => $mainCategoryArr];
        } else {
            $returnArr = [
                'response' => 'failure',
                'responsemessage' => 'Main category not found.',
            ];
        }

        return $returnArr;
    }
    /* Top Search [End] */

    /* Verify Member [Start] */
    public function verify_a_member(Request $request)
    {
        if ($request->has('company_telephone_number') 
        && $request->get('company_telephone_number') != '' 
        && preg_match('/^\(\d{3}\) \d{3}-\d{4}$/', $request->get('company_telephone_number'))) {
            $v = VerifyMember::create([
                'phone_number' => $request->get('company_telephone_number'),
            ]);

            //dd($v->toArray());

            $companyObj = Company::where('main_company_telephone', $request->get('company_telephone_number'))
            ->orWhere('secondary_telephone', $request->get('company_telephone_number'))->first();

            if (!is_null($companyObj)) {
                $v->company_id = $companyObj->id;
                $v->save();
                $officialMemberMembershipLevels = [2, 3, 4, 5, 6, 7];
                $returnArr = [
                    'response' => 'success',
                    'uniqueId' => $companyObj->id,
                    'company_slug' => $companyObj->slug,
                    'official_member' => in_array($companyObj->membership_level_id, $officialMemberMembershipLevels)
                    //'member_url' => route('company-page', ['company_slug' => $companyObj->slug]),
                ];
            } else {
                $returnArr = [
                    'uniqueId' => '',
                    'response' => 'failure',
                    'responsemessage' => 'No Member found.',
                    'query' => $request->get('company_telephone_number'),
                ];
            }
        } else {
            $returnArr = [
                'uniqueId' => '',
                'response' => 'failure',
                'responsemessage' => 'Company telephone number not found.',
            ];
        }

        return $returnArr;
    }

    public function featured_company_count(Request $request)
    {
        $zipcodes = $request->get('zip_codes');       
        $service_category_codes = $request->get('service_category_codes');        
        $company_list = Company::select(
            'companies.id'
        )
        ->distinct()
        ->leftJoin('membership_levels AS ml', 'companies.membership_level_id', 'ml.id')
        ->leftJoin('company_service_categories AS csc', 'companies.id', 'csc.company_id')
        ->join('service_categories as sc', 'csc.service_category_id', 'sc.id')
        ->leftJoin('company_zipcodes AS cz', 'companies.id', 'cz.company_id')
        ->leftJoin('media as m', 'companies.company_logo_id', 'm.id')
        ->leftJoin('states as s', 'companies.state_id', 's.id')      
        ->leftJoin('membership_statuses as ms', 'companies.status', 'ms.title')      
        ->where([
            ['ml.paid_members', 'yes'],
            ['ml.lead_access', 'yes'],
            ['ml.slug', '!=', 'accredited-member'],
            ['companies.leads_status', '=', 'active'],
            ['ml.status', 'active'],
            ['cz.status', 'active'],
            ['csc.status', 'active']
        ])
        ->whereIn('cz.zip_code', $zipcodes)       
        ->whereIn('sc.sc_code', $service_category_codes)
        ->whereIn('ms.id',[8]) /**Membership status active */
        ->whereIn('companies.membership_level_id', [2,3,4,5,6,7])
        //->orderBy('companies.approval_date', 'ASC')
        ->orderBy('companies.activated_at', 'ASC')
        ->orderBy('companies.lead_resume_date', 'ASC')
        ->orderBy('csc.service_category_type_id', 'ASC')
        ->orderBy('csc.main_category_id', 'ASC')
        ->get();  
        //Take first 6, first 3 will displayed in the featured experts section, rest will be displayed within the additional contractors
       return count($company_list) > 6 ? 6 : count($company_list);
    }

    /*Company data functions starts*/
    /**
     * Function to retrieve featured experts by service category and zip
     * Official members priority 1, 2 and 3 ( or 4, 5 or 6th depending on if 1, 2 or 3 have paused leads)
     * Take first 6, first 3 will displayed in the featured experts section, 
     * rest will be displayed within the additional contractors
     */
    public function featured_experts(Request $request)
    {
        $output = [];
        $zipcodes = $request->get('zip_codes');       
        $service_category_codes = $request->get('service_category_codes');        
        $company_list = Company::select(
            'companies.id',
            'companies.company_name',
            'companies.slug',
            'companies.company_mailing_address',
            'companies.city',
            'companies.zipcode',
            'companies.bg_check_date',
            'm.file_name as logo',
            's.short_name as state',
            'companies.leads_status',
            'companies.approval_date',
        )
        ->distinct()
        ->leftJoin('membership_levels AS ml', 'companies.membership_level_id', 'ml.id')
        ->leftJoin('company_service_categories AS csc', 'companies.id', 'csc.company_id')
        ->join('service_categories as sc', 'csc.service_category_id', 'sc.id')
        ->leftJoin('company_zipcodes AS cz', 'companies.id', 'cz.company_id')
        ->leftJoin('media as m', 'companies.company_logo_id', 'm.id')
        ->leftJoin('states as s', 'companies.state_id', 's.id')      
        ->leftJoin('membership_statuses as ms', 'companies.status', 'ms.title')      
        ->where([
            ['ml.paid_members', 'yes'],
            ['ml.lead_access', 'yes'],
            ['ml.slug', '!=', 'accredited-member'],
            ['ml.status', 'active'],
            ['cz.status', 'active'],
            ['csc.status', 'active']
        ])
        ->whereIn('cz.zip_code', $zipcodes)       
        ->whereIn('sc.sc_code', $service_category_codes)
        ->whereIn('ms.id',[8]) /**Membership status active */
        ->whereIn('companies.membership_level_id', [2,3,4,5,6])
        //->orderBy('companies.approval_date', 'ASC')
        ->orderBy('companies.activated_at', 'ASC')
        ->orderBy('companies.lead_resume_date', 'ASC')
        ->orderBy('csc.service_category_type_id', 'ASC')
        ->orderBy('csc.main_category_id', 'ASC')
        ->get();       
        $company_details = [];
        if (count($company_list) > 0) {
            foreach ($company_list as $key => $company_item) {     
                if(strtolower($company_item->leads_status) != 'active')
                {
                    continue;
                }     
                /**Ratings*/
                $average_ratings = Feedback::select(DB::raw('AVG(ratings) AS average_ratings'), DB::raw('COUNT(id) AS total_reviews'))
                    ->where('company_id', $company_item->id)
                    ->first();

                /**Reviews*/
                $latest_reviews = Feedback::select(
                    'customer_name',
                    'ratings',
                    'content'
                )->where([
                    ['company_id', $company_item->id],
                    ['feedback_status', 'Posted'],
                ])
                    ->orderBy('feedback_id', 'desc')
                    ->first();

                /**Background Check*/
                $background_check_date = null;
                if (!is_null($company_item->bg_check_date)) {
                    $background_check_date = \App\Models\Custom::date_formats($company_item->bg_check_date, env('DB_DATE_FORMAT'), env('BG_DATE_FORMAT'));
                } elseif (!is_null($company_item->approval_date)) {
                    $background_check_date = \App\Models\Custom::date_formats($company_item->approval_date, env('DB_DATE_FORMAT'), env('BG_DATE_FORMAT'));
                }
                 /**Service Offered*/
                $services_offered = CompanyServiceCategory::select(
                'company_service_categories.service_category_type_id',
                'sct.title as service_category_type',
                'company_service_categories.main_category_id',
                'mc.title as main_category',
                'company_service_categories.service_category_id',
                'sc.title  as service_category'
                )
                ->join('service_category_types as sct', 'sct.id','company_service_categories.service_category_type_id')
                ->join('main_categories as mc', 'mc.id', 'company_service_categories.main_category_id')
                ->join('service_categories as sc', 'sc.id', 'company_service_categories.service_category_id')
                ->where
                ([
                    ['company_service_categories.company_id', $company_item->id],
                    ['company_service_categories.status', 'active'],
                ])
                ->orderBy('sct.sort_order', 'asc')
                ->orderBy('mc.title', 'asc')
                ->orderBy('sc.title', 'asc')   
                ->get();
                
                $servicesOfferedCollection = new Collection();
        
                foreach($services_offered as $key => $service_offered)
                {
                    $servicesOfferedCollection->push($service_offered);     
                }
                
                $serviceCategoryTypeCollection = $servicesOfferedCollection->groupBy('service_category_type_id')
                ->map(function ($items) {
                    return [
                        'key' => $items[0]['service_category_type_id'],
                        'value' => $items[0]['service_category_type'],
                    ];
                })
                ->values();

                $mainCategoryCollection = $servicesOfferedCollection->groupBy('service_category_type_id')
                         ->map(function ($items) {
                             return [
                                 'type_id' => $items[0]['service_category_type_id'],
                                 'categories' => $items->unique('main_category_id')
                                                      ->map(function ($item) {
                                                          return [
                                                              'key' => $item['main_category_id'],
                                                              'value' => $item['main_category'],
                                                          ];
                                                      })->unique()
                                                      ->values(),
                             ];
                         })
                         ->values();
                
                $serviceCategoriesCollection = $servicesOfferedCollection->map(function ($item) {
                    return [
                        'type_id' => $item['service_category_type_id'],
                        'main_category_id' => $item['main_category_id'],
                        'key' => $item['service_category_id'],
                        'value' => $item['service_category'],
                    ];
                })->values();              

                $company_details[] = (object) array(
                    'id' => $company_item->id,
                    'slug' => $company_item->slug,
                    'name' => $company_item->company_name, 
                    'logo' => $company_item->logo == "" ? self::getTempLogo($company_item->company_name,$company_item->slug) : env('APP_URL') . "/uploads/media/{$company_item->logo}",
                    'telephone' => $company_item->main_company_telephone,
                    'address' => $company_item->company_mailing_address,
                    'city' => $company_item->city,
                    'state' => $company_item->state,
                    'zipcode' => $company_item->zipcode,
                    'rating' => $average_ratings,
                    'review' => $latest_reviews,
                    'background_check_date' => $background_check_date,
                    'priority' => $key + 1,
                    'initials' => Initials::generate($company_item->company_name),
                    'recent_screening_date' => isset($company_item->approval_date) ?  \App\Models\Custom::date_formats($company_item->approval_date, env('DB_DATE_FORMAT'), env('BG_DATE_FORMAT')) : "",
                    'service_category_types' => $serviceCategoryTypeCollection,
                    'main_categories' => $mainCategoryCollection,
                    'service_categories' => $serviceCategoriesCollection
                );
            }
        }
        //Take first 6, first 3 will displayed in the featured experts section, rest will be displayed within the additional contractors
        $output = $company_details;
        if(count($company_details) > 6)
        {
            $output = array_slice($company_details, 0, 6); 
        }

        $returnArr = [
            /*'zip_code' => json_encode($zipcodes),
            'service_category_codes' => json_encode($service_category_codes),*/
            'totalcount' => count($output),
            'company_details' => $output,
        ];

        return $returnArr;
    }

    /**
     * Function to retrieve additional pros by service category and zip
     * All Registered Companies that match the service category and zip codes
     */
    public function additional_contractors(Request $request)
    {
        $zipcodes = $request->get('zip_codes');       
        $service_category_codes = $request->get('service_category_codes');  
        $company_list = Company::select(
            'companies.id',
            'companies.company_name',
            'companies.company_mailing_address',
            'companies.city',
            's.short_name as state',
            'cz.zip_code as zipcode'
        )
            ->distinct()
            ->leftJoin('membership_levels AS ml', 'companies.membership_level_id', 'ml.id')
            ->leftJoin('company_service_categories AS csc', 'companies.id', 'csc.company_id')
            ->join('service_categories as sc', 'csc.service_category_id', 'sc.id')
            ->leftJoin('company_zipcodes AS cz', 'companies.id', 'cz.company_id')
            ->leftJoin('states as s', 'companies.state_id', 's.id')
            ->leftJoin('membership_statuses as ms', 'companies.status', 'ms.title') 
           /* ->where([
                ['ml.paid_members', 'no'],
                ['ml.status', 'active'],
                ['companies.status', 'active'],
                ['cz.status', 'active'],
                ['csc.status', 'active']
            ])*/
            ->whereIn('cz.zip_code', $zipcodes)       
            ->whereIn('sc.sc_code', $service_category_codes)            
            ->whereIn('companies.membership_level_id', [1])
            /*->whereIn('ms.id',[8])*/
            ->orderBy('companies.id', 'ASC')            
            ->get();
        $company_details = [];
        $output = [];
        if (count($company_list) > 0) {
            $company_id_temp = 0;
            foreach ($company_list as $key => $company_item) {
                //fetch only unique companies
                if ($company_id_temp == 0 || $company_id_temp != $company_item->id) {
                    $company_details[] = (object) array(
                        'id' => $company_item->id,
                        'name' => $company_item->company_name,
                        'address' => $company_item->company_mailing_address,
                        'city' => $company_item->city,
                        'state' => $company_item->state,
                        'zipcode' => $company_item->zipcode,
                    );
                    $company_id_temp = $company_item->id;
                }
            }
        }

        //Take first 6
        if(count($company_details) > 6)
        {
            $output = array_slice($company_details, 0, 6); 
        }
        $returnArr = [
            'company_details' => $output,
        ];

        return $returnArr;
    }

    /**
     * Function to retrieve the lead information by location
     * It's requests submitted by users
     */
    public function projects_by_location(Request $request)
    {
        $zipcodes = $request->get('zip_codes');       
        $service_category_codes = $request->get('service_category_codes'); 
        $leads = Lead::select(
            'leads.timeframe',
            'leads.content',
            'leads.zipcode',
            'leads.created_at as project_date',
            'sc.title as service_category',
            'leads.city as city',
            's.short_name as state',
            'mc.title as main_category',
            'tlc.title as top_level_category'
        )
            ->leftJoin('service_categories AS sc', 'leads.service_category_id', 'sc.id')
            ->leftJoin('states as s', 'leads.state_id', 's.id')
            ->leftJoin('top_level_categories as tlc', 'leads.top_level_category_id', 'tlc.id')
            ->leftJoin('main_categories as mc', 'sc.main_category_id', 'mc.id')
            ->whereIn('leads.zipcode', $zipcodes)
            ->whereIn('sc.sc_code', $service_category_codes) 
            ->orderBy('lead_active_date', 'DESC')->limit(6)->get();
        $lead_details = [];
        $output = [];
        if (count($leads) > 0) {
            foreach ($leads as $key => $lead_item) {
                $lead_details[] = (object) array(
                    'service_category' => $lead_item->service_category,
                    'title' => "{$lead_item->top_level_category} Project",
                    'city' => $lead_item->city,
                    'state' => $lead_item->state,
                    'zipcode' => $lead_item->zipcode,
                    'project_date' =>  \Carbon\Carbon::parse($lead_item->project_date)->format(env('DATE_FORMAT')),  
                    'timeframe' =>  $lead_item->timeframe,
                    'project_details' => $lead_item->content
                );
            }
        }

        //Take first 6
        if(count($lead_details) > 6)
        {
            $output = array_slice($lead_details, 0, 6); 
        }
        else
        {
            $output = $lead_details;
        }

        $returnArr = [            
            'projects' => $output,
        ];
        return $returnArr;
    }        
    /*Company data functions ends*/   

    /**
     * Lightweight function to get basic company details
     */
    public function get_basic_company_details_by_slug(Request $request)
    {
        $company_slug = $request->get('company_slug');
        $company_slug = isset($company_slug) ? trim($company_slug) : false;
        if(empty($company_slug)){
            return [            
                'error' => 1,
                'error_message' => 'Missing required parameter company_slug'
            ];
        } 
        $company = Company::select
                    (                       
                        'companies.company_name',
                        'companies.slug'
                    )
                    ->where('companies.slug', $company_slug)
                    ->first();   

        if(!isset($company)){
            return [            
                'error' => 1,
                'error_message' => 'Company not found'
            ];
        }         
        
        return [
            'error' => 0,
            'company' => $company
        ];        
    }

    public function get_company_by_slug(Request $request)
    {
        $company_slug = $request->get('company_slug');
        $company_slug = isset($company_slug) ? trim($company_slug) : false;
        if(empty($company_slug)){
            return [            
                'error' => 1,
                'error_message' => 'Missing required parameter company_slug'
            ];
        } 
        $company = Company::select
                    (
                        'companies.id',
                        'companies.company_name',
                        'companies.slug',
                        'companies.main_company_telephone',
                        'companies.company_mailing_address',
                        'companies.city',
                        'companies.zipcode',
                        'companies.company_bio',
                        'companies.membership_level_id',
                        'companies.company_website',
                        'companies.status',
                        DB::raw('IF(m.file_name != "", CONCAT("' . env('APP_URL') . '/uploads/media/", m.file_name), "") AS logo'),
                        //'m.file_name as logo', //$company_item->logo == "" ? "" : env('APP_URL') . "/uploads/media/{$company_item->logo}"
                        's.short_name as state_code',                       
                        's.name as state_name' 
                    )
                    ->leftJoin('membership_levels AS ml', 'companies.membership_level_id', 'ml.id')
                    ->leftJoin('states as s', 'companies.state_id', 's.id')
                    ->leftJoin('media as m', 'companies.company_logo_id', 'm.id')
                    ->where('companies.slug', $company_slug)
                    ->first();   

        if(!isset($company)){
            return [            
                'error' => 1,
                'error_message' => 'Company not found'
            ];
        } 

         /**Ratings*/
         $average_ratings = Feedback::select(DB::raw('AVG(ratings) AS rating'), DB::raw('COUNT(id) AS feedback_count'))
         ->where('company_id', $company->id)
         ->first();

        /**Reviews*/
        $reviews = Feedback::select
                        (
                            'customer_name',
                            'ratings',
                            'content'
                        )
                        ->where
                        ([
                            ['company_id', $company->id],
                            ['feedback_status', 'Posted'],
                        ])
                        ->orderBy('created_at', 'desc')
                        ->get();
        
        /**Complaints*/
        $complaints = Complaint::select
                        (
                            'customer_name',
                            'content'
                        )
                        ->where
                        ([
                            ['company_id', $company->id],
                            ['complaint_status', 'Posted'],
                        ])
                        ->order()
                        ->get();

         /**Service Area's*/
        $service_areas = CompanyZipcode::select
        (
            'zip_code',
            'city',
            'state'
        )
        ->where([['company_id', $company->id], ['status', 'active']])
        ->orderBy('distance', 'ASC')
        ->get();
        
         /**Service Offered*/
         $services_offered= CompanyServiceCategory::select(
            'company_service_categories.service_category_type_id',
            'sct.title as service_category_type',
            'company_service_categories.main_category_id',
            'mc.title as main_category',
            'company_service_categories.service_category_id',
            'sc.title  as service_category'
            )
            ->join('service_category_types as sct', 'sct.id','company_service_categories.service_category_type_id')
            ->join('main_categories as mc', 'mc.id', 'company_service_categories.main_category_id')
            ->join('service_categories as sc', 'sc.id', 'company_service_categories.service_category_id')
            ->where
            ([
                ['company_service_categories.company_id', $company->id],
                ['company_service_categories.status', 'active'],
            ])
            ->orderBy('sct.sort_order', 'asc')
            ->orderBy('mc.title', 'asc')
            ->orderBy('sc.title', 'asc')   
            ->get();
        
            $servicesOfferedCollection = new Collection();
        
            foreach($services_offered as $key => $service_offered)
            {
                $servicesOfferedCollection->push($service_offered);     
            }
            
            $serviceCategoryTypeCollection = $servicesOfferedCollection->groupBy('service_category_type_id')
            ->map(function ($items) {
                return [
                    'key' => $items[0]['service_category_type_id'],
                    'value' => $items[0]['service_category_type'],
                ];
            })
            ->values();

            $mainCategoryCollection = $servicesOfferedCollection->groupBy('service_category_type_id')
                     ->map(function ($items) {
                         return [
                             'type_id' => $items[0]['service_category_type_id'],
                             'categories' => $items->unique('main_category_id')
                                                  ->map(function ($item) {
                                                      return [
                                                          'key' => $item['main_category_id'],
                                                          'value' => $item['main_category'],
                                                      ];
                                                  })->unique()
                                                  ->values(),
                         ];
                     })
                     ->values();
            
            $serviceCategoriesCollection = $servicesOfferedCollection->map(function ($item) {
                return [
                    'type_id' => $item['service_category_type_id'],
                    'main_category_id' => $item['main_category_id'],
                    'key' => $item['service_category_id'],
                    'value' => $item['service_category'],
                ];
            })->values();       

        /*Gallery*/
        $gallery = CompanyGallery::select
        (
            'm.file_name'            
        )
        ->join('media as m','company_galleries.media_id','m.id')
        ->where('company_id', $company->id)
        ->status('approved')
        ->order()
        ->get();

        foreach ($gallery as &$value) {
            $value["file_name_thumb"] = asset('/uploads/media/'.$value["file_name"]); //asset('uploads/media/fit_thumbs/100x100/'.$value["file_name"]);
            $value["file_name"] = asset('/uploads/media/'.$value["file_name"]);
        }
        $company['logo'] =  $company['logo'] == "" ? self::getTempLogo( $company['company_name'], $company['slug']) : $company['logo'];
        $company['initials'] = Initials::generate($company['company_name']);
        $company['reviews'] = $reviews;        
        $company['complaints'] = $complaints;
        $company['service_areas'] = $service_areas;
        $company['services'] = $services_offered;
        $company['company_website'] =  substr($company['company_website'],0,4) !== "www."?"www.".$company['company_website']:$company['company_website'];
        $company['averageratings'] = $average_ratings;
        $company['gallery'] = $gallery;     
        $company['service_category_types'] = $serviceCategoryTypeCollection;
        $company['main_categories' ] = $mainCategoryCollection;
        $company['service_categories'] = $serviceCategoriesCollection;
        
        return [
            'error' => 0,
            'company' => $company
        ];        
    }
    public function get_services_by_sc_code(Request $request)
    {
        $service_category_codes = $request->get('service_category_codes'); 
        if(empty($service_category_codes)){
            return [            
                'error' => 1,
                'error_message' => 'Missing required parameter service_category_codes'
            ];
        } 

        /**Service Offered*/
        $services_offered= ServiceCategory::select(
            'service_categories.service_category_type_id',
            'sct.title as service_category_type',
            'service_categories.main_category_id',
            'mc.title as main_category',
            'service_categories.id  as service_category_id',
            'service_categories.title  as service_category'
            )
            ->join('service_category_types as sct', 'sct.id','service_categories.service_category_type_id')
            ->join('main_categories as mc', 'mc.id', 'service_categories.main_category_id')            
            ->whereIn('service_categories.sc_code', $service_category_codes) 
            ->orderBy('sct.sort_order', 'asc')
            ->orderBy('mc.title', 'asc')
            ->orderBy('service_categories.title', 'asc')   
            ->get();
        
        $serviceCategoryTypeCollection = $services_offered->groupBy('service_category_type_id')
        ->map(function ($items) {
            return [
                'key' => $items[0]['service_category_type_id'],
                'value' => $items[0]['service_category_type'],
            ];
        })
        ->values();

        $mainCategoryCollection = $services_offered->groupBy('service_category_type_id')
                    ->map(function ($items) {
                        return [
                            'type_id' => $items[0]['service_category_type_id'],
                            'categories' => $items->unique('main_category_id')
                                                ->map(function ($item) {
                                                    return [
                                                        'key' => $item['main_category_id'],
                                                        'value' => $item['main_category'],
                                                    ];
                                                })->unique()
                                                ->values(),
                        ];
                    })
                    ->values();
        
        $serviceCategoriesCollection = $services_offered->map(function ($item) {
            return [
                'type_id' => $item['service_category_type_id'],
                'main_category_id' => $item['main_category_id'],
                'key' => $item['service_category_id'],
                'value' => $item['service_category'],
            ];
        })->values();

        return [
            'error' => 0,
            'service_category_types' => $serviceCategoryTypeCollection,
            'main_categories' => $mainCategoryCollection,
            'service_categories' =>  $serviceCategoriesCollection
        ];        
    }

    private function ItemNotExistsInArray($sourceArray, $item)
    {
        foreach ($sourceArray as $sourceItem) {
            if ($item['key'] == $sourceItem['key']) {
                return true;
            }
        }
        return false;
    }

    function getTempLogo($name, $slug)
    {
        $path = 'uploads/media/' . $slug . '_temp_logo.png';
        
        if (File::exists(public_path($path))) {
            return env('APP_URL') . '/' . $path;
        }
        
        $sizeOfFont = strlen($name) > 25 ? 12 : 14;

        $logo = Image::canvas(200, 100, '#FFFFFF');
        $logo->text($name, 100, 50, function($font) use ($sizeOfFont) {
            $font->file(public_path('fonts/arial.ttf'));
            $font->size($sizeOfFont);
            $font->color('#334a6c');
            $font->align('center');
            $font->valign('middle');
        });
        $logo->resizeCanvas(220, 120, 'center', false, '#334a6c');
        $logo->resize(200, 100);
        $logo->save(public_path($path));
        return env('APP_URL') . '/' . $path;
    }

    public function populatezipcodes()
    {     
        try {           
            //get company id which has not got zipcodes
            $companiesWithoutZipCodesPopulatedBefore = Company::select('zipcode')
            ->leftJoin('zipcode_details AS z','companies.zipcode', 'z.parent_zip_code')            
            ->distinct()
            ->whereNull('z.parent_zip_code')
            ->get();

            for ($x = 0; $x <= 199; $x++) {                
                $zipCodeToBeFetched = $companiesWithoutZipCodesPopulatedBefore[$x];               
                $zipCodes = Custom::getZipCodeRange($zipCodeToBeFetched->zipcode, 50); //get zips within 50 mile range
                
                if (count($zipCodes) > 0) {
                    foreach ($zipCodes as $zipcode_item) {
                        $stateObj = State::where('short_name', $zipcode_item['state'])->first();
                        $insertZipcodeArr = [
                            'parent_zip_code' =>  $zipCodeToBeFetched->zipcode,
                            'zip_code' => $zipcode_item['zip_code'],
                            'distance' => $zipcode_item['distance'],
                            'city' => $zipcode_item['city'],
                            'state' => $zipcode_item['state'],
                            'state_id' => ((!is_null($stateObj)) ? $stateObj->id : null),
                            'status' => 'active'
                        ];

                        ZipCodeDetail::create($insertZipcodeArr);
                    }
                }
            }

            $companiesWithoutZipCodesPopulatedAfter = Company::select('zipcode')
            ->leftJoin('zipcode_details AS z','companies.zipcode', 'z.parent_zip_code')            
            ->distinct()
            ->whereNull('z.parent_zip_code')
            ->get();
            
            return json_encode(
                ['status' => 0, 
                'message' => "Success", 
                'totalcompanies_without_zip_before' => count($companiesWithoutZipCodesPopulatedBefore),
                'totalcompanies_without_zip_after' => count($companiesWithoutZipCodesPopulatedAfter),
            ]);
            
        } catch (Exception $e) {
            Log::warning("Error geting zipcode data: ".$e->getMessage());
            return json_encode(
                ['status' => 1, 
                'message' => $e->getMessage()
            ]);
        }
        return json_encode(
            ['status' => 1, 
            'message' => 'Failure'
        ]);
    }
}

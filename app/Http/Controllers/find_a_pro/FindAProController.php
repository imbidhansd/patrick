<?php

namespace App\Http\Controllers\find_a_pro;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Rules\ValidRecaptcha;
use Validator;
// Model [Start]
use App\Models\Page;
use App\Models\TopLevelCategory;
use App\Models\TopLevelCategoryTrade;
use App\Models\MainCategory;
use App\Models\MainCategoryTopLevelCategory;
use App\Models\ServiceCategoryType;
use App\Models\ServiceCategory;
use App\Models\State;
use App\Models\Lead;
use App\Models\Testimonial;
use App\Models\Custom;
use Str;

class FindAProController extends Controller {

    public function __construct() {
        $this->web_settings = Custom::getSettings();
        $this->view_base = 'find_a_pro.';
    }

    public function index($main_category_slug = null) {
        $top_search_top_level_category_list = TopLevelCategory::select('top_level_categories.*')
                ->whereNotNull('top_level_categories.top_search_image')
                ->where('top_level_categories.top_search_status', 'yes')
                ->active()
                ->groupBy('top_level_categories.id')
                ->orderBy('top_search_sort_order', 'ASC')
                ->get();

        $professional_top_level_category_list = TopLevelCategory::select('top_level_categories.*')
                ->leftJoin('top_level_category_trades', 'top_level_categories.id', 'top_level_category_trades.top_level_category_id')
                ->whereNull('top_level_categories.top_search_image')
                ->where([
                    ['top_level_categories.top_search_status', 'yes'],
                    ['top_level_category_trades.trade_id', '2']
                ])
                ->active()
                ->groupBy('top_level_categories.id')
                //->orderBy('top_search_sort_order', 'ASC')
                ->orderBy('top_level_categories.title', 'ASC')
                ->get();

        $contractor_top_level_category_list = TopLevelCategory::select('top_level_categories.*')
                ->leftJoin('top_level_category_trades', 'top_level_categories.id', 'top_level_category_trades.top_level_category_id')
                ->whereNull('top_level_categories.top_search_image')
                ->where([
                    ['top_level_categories.top_search_status', 'yes'],
                    ['top_level_category_trades.trade_id', '1']
                ])
                ->active()
                ->groupBy('top_level_categories.id')
                //->orderBy('top_search_sort_order', 'ASC')
                ->orderBy('top_level_categories.title', 'ASC')
                ->get();

        $testimonial_list = Testimonial::with('media')->active()->order()->get();

        $main_category_item = null;
        if (!is_null($main_category_slug)) {
            $main_category_item = MainCategory::whereSlug($main_category_slug)->active()->first();
        }

        $timeframeArr = config('config.timeframe');
        $data = [
            'web_settings' => $this->web_settings,
            'terms_page' => Page::find(13),
            'top_search_top_level_category_list' => $top_search_top_level_category_list,
            'professional_top_level_category_list' => $professional_top_level_category_list,
            'contractor_top_level_category_list' => $contractor_top_level_category_list,
            'testimonial_list' => $testimonial_list,
            'main_category_item' => $main_category_item,
            'timeframe' => $timeframeArr,
        ];

        return view($this->view_base . 'index', $data);
    }

    /* Ajax method [Start] */

    public function get_maincategories(Request $request) {
        $validator = Validator::make($request->all(), [
                    'search' => 'required',
        ]);

        if ($validator->fails()) {
            $returnArr = [
                'response' => 'Category not found.',
            ];
        } else {
            $requestArr = $request->all();

            //leftJoin('service_categories', 'main_categories.id', 'service_categories.main_category_id')

            $main_categories = MainCategory::active()
                    ->groupBy('main_categories.id')
                    ->orderBy('main_categories.title', 'ASC')
                    //->order()
                    ->where(function ($query) use ($requestArr) {
                        $query->where('main_categories.title', 'like', '%' . $requestArr['search'] . '%');
                        $query->orWhere('main_categories.tags', 'like', '%' . $requestArr['search'] . '%');
                        //$query->orWhere('service_categories.title', 'like', '%' . $requestArr['searchkey'] . '%');
                        //$query->orWhere('service_categories.tags', 'like', '%' . $requestArr['searchkey'] . '%');
                    })
                    ->limit('10')
                    ->pluck('title', 'id');

            if (count($main_categories) > 0) {
                $mainCategoryArr = [];
                $i = 0;
                foreach ($main_categories AS $key => $category_item) {
                    $mainCategoryArr[$i]['value'] = $key;
                    $mainCategoryArr[$i]['label'] = $category_item;
                    $i++;
                }

                $returnArr = $mainCategoryArr;
            } else {
                $returnArr = [
                    'response' => 'Category not found.',
                ];
            }
        }

        return $returnArr;
    }

    public function get_servicecategorytypes(Request $request) {
        $validator = Validator::make($request->all(), [
                    'maincategory' => 'required',
        ]);

        if ($validator->fails()) {
            $returnArr = [
                'success' => 0,
                'title' => 'Error',
                'type' => 'error',
                'message' => 'Main category not found.',
            ];
        } else {
            $requestArr = $request->all();

            $top_level_category = MainCategoryTopLevelCategory::select('main_category_top_level_categories.top_level_category_id')
                    //->join('service_categories', 'main_category_top_level_categories.main_category_id', 'service_categories.main_category_id')
                    ->where('main_category_top_level_categories.main_category_id', $requestArr['maincategory'])
                    ->first();

            if (is_null($top_level_category)) {
                $returnArr = [
                    'success' => 0,
                    'title' => 'Error',
                    'type' => 'error',
                    'message' => 'Top level category not found.',
                ];
            } else {
                $trade_type = TopLevelCategoryTrade::select('top_level_category_trades.trade_id')
                        //->join('service_categories', 'top_level_category_trades.top_level_category_id', 'service_categories.top_level_category_id')
                        ->where('top_level_category_trades.top_level_category_id', $top_level_category->top_level_category_id)
                        ->first();

                if (is_null($trade_type)) {
                    $returnArr = [
                        'success' => 0,
                        'title' => 'Error',
                        'type' => 'error',
                        'message' => 'Trade not found.',
                    ];
                } else {
                    if ($trade_type->trade_id == 1) {
                        $service_category_types_list = ServiceCategoryType::select('service_category_types.*')
                                ->leftJoin('service_categories', 'service_category_types.id', 'service_categories.service_category_type_id')
                                ->where([
                                    ['service_category_types.trade_id', $trade_type->trade_id],
                                    ['service_categories.main_category_id', $requestArr['maincategory']]
                                ])
                                ->active()
                                ->order()
                                ->groupBy('service_category_types.id')
                                ->get();

                        if (count($service_category_types_list) == 1) {
                            foreach ($service_category_types_list AS $service_category_type_item) {
                                $requestArr['servicecategorytype'] = $service_category_type_item->id;
                            }

                            /* Search service category */
                            $requestArr['toplevelcategory'] = $top_level_category->top_level_category_id;
                            $returnArr = self::service_category_selection($requestArr);
                        } else {
                            $data = [
                                'service_category_types_list' => $service_category_types_list,
                                'top_level_category_id' => $top_level_category->top_level_category_id,
                            ];

                            $returnArr = [
                                'result_type' => 'service_category_types',
                                'top_level_category_id' => $top_level_category->top_level_category_id,
                                'html' => view($this->view_base . '_service_category_types', $data)->render(),
                            ];
                        }
                    } else {
                        /* Search service category */
                        $requestArr['toplevelcategory'] = $top_level_category->top_level_category_id;
                        $returnArr = self::service_category_selection($requestArr);
                    }
                }
            }
        }

        return $returnArr;
    }

    public function get_servicecategories(Request $request) {
        $validator = Validator::make($request->all(), [
                    'toplevelcategory' => 'required',
                    'maincategory' => 'required',
        ]);

        if ($validator->fails()) {
            $returnArr = [
                'success' => 0,
                'title' => 'Error',
                'type' => 'error',
                'message' => implode("<br/>", $validator->messages()->all()),
            ];
        } else {
            $requestArr = $request->all();
            $returnArr = self::service_category_selection($requestArr);
        }

        return $returnArr;
    }

    public function get_top_search_main_categories(Request $request) {
        $validator = Validator::make($request->all(), [
                    'top_level_category_id' => 'required',
        ]);

        if ($validator->fails()) {
            $returnArr = [
                'success' => 0,
                'title' => 'Error',
                'type' => 'error',
                'message' => 'Top Level Category not found.',
            ];
        } else {
            $requestArr = $request->all();

            $main_category_list = MainCategory::select('main_categories.*')
                    ->leftJoin('main_category_top_level_categories', 'main_categories.id', 'main_category_top_level_categories.main_category_id')
                    ->where('main_category_top_level_categories.top_level_category_id', $requestArr['top_level_category_id'])
                    ->active()
                    //->order()
                    ->orderBy('main_categories.title', 'ASC')
                    ->groupBy('main_categories.id')
                    ->get();

            if (count($main_category_list) > 0) {
                $top_level_category_item = TopLevelCategory::active()->find($requestArr['top_level_category_id']);
                $data = [
                    'main_category_list' => $main_category_list,
                    'top_level_category_item' => $top_level_category_item,
                ];

                $returnArr = [
                    'result_type' => 'main_categories',
                    'html' => view($this->view_base . '_main_categories', $data)->render(),
                ];
            } else {
                $returnArr = [
                    'success' => 0,
                    'title' => 'Error',
                    'type' => 'error',
                    'message' => 'Main Categories not found.',
                ];
            }
        }

        return $returnArr;
    }

    public function find_a_pro(Request $request) {
        
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
                    'g-recaptcha-response' => ['required', new ValidRecaptcha]
        ]);

        if ($validator->fails()) {
            $returnArr = [
                'success' => 0,
                'title' => 'Error',
                'type' => 'error',
                'message' => implode("<br/>", $validator->messages()->all()),
            ];
        } else {
            $requestArr = $request->all();
            $top_level_category = MainCategoryTopLevelCategory::select('top_level_category_id')
                    ->where('main_category_id', $requestArr['main_category_id'])
                    ->first();

            if (is_null($top_level_category)) {
                $returnArr = [
                    'success' => 0,
                    'title' => 'Error',
                    'type' => 'error',
                    'message' => 'Top level category not found.',
                ];
            } else {
                $requestArr['top_level_category_id'] = $top_level_category->top_level_category_id;

                $trade_type = TopLevelCategoryTrade::select('trade_id')->where('top_level_category_id', $top_level_category->top_level_category_id)->first();

                if (is_null($trade_type)) {
                    $returnArr = [
                        'success' => 0,
                        'title' => 'Error',
                        'type' => 'error',
                        'message' => 'Trade not found.',
                    ];
                } else {

                    $testValuesToBeProcessed = explode(',', env('TEST_ZIPCODES_TOBEPROCESSED'));
                    $testValuesToBeProcessedExceptNetworx = explode(',', env('TEST_ZIPCODES_TOBEPROCESSED_EXCEPT_NETWORX'));
                    if (in_array($requestArr["zipcode"], $testValuesToBeProcessed) || in_array($requestArr["zipcode"], $testValuesToBeProcessedExceptNetworx)){
                        //some default city and state
                        $requestArr['city'] = "NA";
                        $requestArr['state_id'] = 1;
                    }
                    else{
                        // check zipcode is valid or not
                        $APIkey = env('ZIPCODE_API_KEY');
                        $json = @file_get_contents('https://www.zipcodeapi.com/rest/' . $APIkey . '/info.json/' . $requestArr['zipcode'] . '/radians');
                        if ($json != '') {
                            $zipcodeArr = json_decode($json);
                            $stateObj = State::where('short_name', $zipcodeArr->state)->first();
                            $requestArr['city'] = $zipcodeArr->city;
                            $requestArr['state_id'] = ((!is_null($stateObj)) ? $stateObj->id : null);
                        } 
                        else {
                            $returnArr = [
                                'success' => 0,
                                'title' => 'Error',
                                'type' => 'error',
                                'message' => 'Zipcode is not valid.',
                            ];

                            return $returnArr;
                        }
                    }
                    

                    $requestArr['trade_id'] = $trade_type->trade_id;
                    $requestArr['ip_address'] = $request->ip();
                    $requestArr['lead_activation_key'] = Custom::getRandomString(50);
                    $correlation_id = Str::uuid()->toString();
                    $requestArr['correlation_id'] = $correlation_id;
                    $lead = Lead::create($requestArr);

                    $web_settings = Custom::getSettings();                    
                    
                    $recommended_companies = Custom::get_companies_who_get_leads_v1($lead);
                    $companiesCount = $recommended_companies->count();
                    $successMsg = '';
                    $networx_processed = false;
                    if (
                            (isset($web_settings['sent_to_networx']) && $web_settings['sent_to_networx'] == 'yes') &&
                            $lead->trade_id == 1 &&
                            $companiesCount <= 0
                    ) {
                        Custom::lead_confirmation_email_for_find_a_pro($lead);
                        Custom::generateCompanyLeads($lead);
                        Custom::lead_email_admin($lead);

                        $networx_response = Custom::networxCall($lead);
                        if ($networx_response['statusCode'] == '200') {
                            $networx_processed = true;
                            $lead->networx_code = $networx_response['successCode'];
                            $networx_redirect_url = $networx_response['redirectUrl'];
                            $lead->networx_redirect_url = $networx_redirect_url; 
                            $successMsg = '<h4>Our Partner Netowrk Is Here To Help.</h4>
                            <h4>While we don\'t currently have any contractors to serve you for your project, our partner network does!</h4>
                            <h2>Get connected with the pros below!</h2>
                            <iframe id="networx-frame" src='.urldecode($networx_redirect_url).' style="border: none; width: 100%; height: 100%; min-height:650px;" scrolling="no"></iframe>';                            
                            $lead->save();
                        }
                    }
                    else
                    {
                        $successMsg = '<div class="rec-comp-header">
                                            Get connected with one or more of the pros below!
                                        </div>
                                        <div class="rec-comp-sub-header">
                                            Select up to three of our pre screened pros
                                        </div>
                                        ';                                             
                       
                        $successMsg .= '<div class="rec-comp-companies">
                                            <form method="post" url="find-a-pro/generate-lead-by-recommened-members">
                                                <input type="hidden" name="correlation_id" value="'.$correlation_id.'" />'; 
                        foreach ($recommended_companies as $company) {
                            $successMsg .= '    <div class="rec-comp-company-card">                                                   
                                                    <div class="rec-comp-company-info">
                                                        <div class="rec-comp-company-name">
                                                            ' . htmlspecialchars($company->company_name, ENT_QUOTES, 'UTF-8') . '
                                                        </div>
                                                        <div class="rec-comp-ratings">
                                                            <div class="rec-comp-star-rating">★★★★★</div>
                                                            <div class="rec-comp-rating-value">5.0</div>
                                                        </div>
                                                    </div>
                                                    <div class="rec-comp-company-logo">
                                                        <img src="https://pros.trustpatrick.com/uploads/media/certified_pro_selection.webp" width="200px" height="100px" alt="'.htmlspecialchars($company->company_name, ENT_QUOTES, 'UTF-8').'">
                                                    </div>
                                                    <div class="rec-comp-company-selection">
                                                     <input type="checkbox" class="rec-comp-checkbox" value="' . htmlspecialchars($company->slug, ENT_QUOTES, 'UTF-8') . '">
                                                    </div>
                                                </div>';
                        }      
                        $successMsg .= '    </form>
                                        </div>'; 
                    }
                   
                    $returnArr = [
                        'success' => 1,
                        'title' => "You're Almost Finished!",
                        'message' => $successMsg,
                        'networx_processed' => $networx_processed,
                        'recommended_companies' => $recommended_companies
                    ];
                }
            }
        }

        return $returnArr;
    }

    public function find_a_pro_recommended_members_submit(Request $request) {
        
        $validator = Validator::make($request->all(), [
                    'correlation_id' => 'required',
                    'recommended_companies' => 'required'
        ]);

        if ($validator->fails()) {
            $returnArr = [
                'success' => 0,
                'title' => 'Error',
                'type' => 'error',
                'message' => implode("<br/>", $validator->messages()->all()),
            ];
        } else {
            $requestArr = $request->all();
            $recommended_companies = $requestArr['recommended_companies'];
            $lead = Lead::where('correlation_id', $request->correlation_id)->first();
            Custom::lead_confirmation_email_for_find_a_pro($lead);
            Custom::generateCompanyLeads($lead);
            Custom::lead_email_admin($lead);
            $successMsg = '<p>Important Please check your email inbox <br /><br />We just sent an email confirmation to <a href="mailto:' . $lead->email . '">' . $lead->email . '</a>. If you entered the wrong email address, please close this window and resubmit your request <br /><br />Thank you for visiting TrustPatrick.com</p>';   
            $returnArr = [
                'success' => 1,
                'title' => "You're Almost Finished!",
                'type' => 'success',                
                'message' => $successMsg,
                // 'networx_processed' => $networx_processed,
                // 'recommended_companies' => $duplicatedCompanies
            ];
        }              

        return $returnArr;
    }

    /* Ajax method [End] */


    /* Static method [Start] */

    private function service_category_selection($requestArr) {
        $query = ServiceCategory::where('main_category_id', $requestArr['maincategory'])
                ->where('top_level_category_id', $requestArr['toplevelcategory'])
                ->active()
                ->orderBy('sort_order', 'asc');

        if (isset($requestArr['servicecategorytype']) && $requestArr['servicecategorytype'] != '') {
            $query->where('service_category_type_id', $requestArr['servicecategorytype']);
        }

        $service_category_list = $query->get();

        if (count($service_category_list) > 0) {
            $data = [
                'service_category_list' => $service_category_list,
            ];

            $returnArr = [
                'result_type' => 'service_categories',
                'top_level_category_id' => $requestArr['toplevelcategory'],
                'html' => view($this->view_base . '_service_categories', $data)->render(),
            ];
        } else {
            $returnArr = [
                'success' => 0,
                'title' => 'Error',
                'type' => 'error',
                'message' => 'service categories not found.',
            ];
        }
        return $returnArr;
        //return $service_categories;
    }

    /* Static method [End] */
}

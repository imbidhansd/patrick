<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Jobs\ProcessGeneralRequestJob;
use App\Jobs\ProcessGeneralRequestJobv1;
use App\Jobs\ProcessAffliateGeneralRequestByCompanySlugJob;
use App\Models\Affiliate;
use App\Models\AffiliateMainCategory;
use App\Models\Aweber;
use App\Models\AweberSubscriberRequest;
use App\Models\Company;
use App\Models\CompanyLead;
use App\Models\CompanyServiceCategory;
use App\Models\CompanyZipcode;
use App\Models\Custom;
use App\Models\Lead;
use App\Models\MainCategory;
use App\Models\MainCategoryTopLevelCategory;
use App\Models\ServiceCategory;
use App\Models\State;
use App\Models\TopLevelCategory;
use App\Models\TopLevelCategoryTrade;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Validator;
use Str;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use App\Models\KeyIdentifierType;

class AffiliateLeadController extends Controller
{
    public function ProcessAffiliateMemberRequest(Request $request)
    {
        $correlationId = Str::uuid()->toString();
        try
        {
            Log::channel('custom_db')->info('Processing started for member lead', [
                'data' => $request->json()->all(),
                'key_identifier' =>  $correlationId,
                'key_identifier_type' => KeyIdentifierType::MemberLead
            ]);
           // return $request->json()->all();
            $data = [];
            $data['correlation_id'] = $correlationId;
            $data['ip_address'] = $request->ip();
            $data["member_slug"]  = $request->member_slug;
            $data["first_name"]  = $request->first_name;
            $data["last_name"] = $request->last_name;
            $data["full_name"] = $data["first_name"]." ". $data["last_name"] ;
            $data["email"] = $request->email;
            $data["city"] = $request->city;
            $data["state"] = $request->state;
            $data["phone"] = $request->phone;
            $data["service_category_type_id"] = isset($request->service_type_id) && isset($request->service_type_id[0])  ? $request->service_type_id[0] : null;
            $data["main_category_id"] = isset($request->main_category_id) && isset($request->main_category_id[0])  ? $request->main_category_id[0] : null;
            $data["service_category_id"] = isset($request->category_id) && isset($request->category_id[0])  ? $request->category_id[0] : null;
            $data["project_address"] = $request->address;
            $data["timeframe"] = isset($request->timeframe) && isset($request->timeframe[0])  ? self::GetTimeframe($request->timeframe[0]) : null;
            $data["zipcode"] = $request->zip;
            $data["content"] = $request->project_info;
            $data["signup_url"] = $request->signup_url;
            $data["cert_url"] =  $request->cert_url;
            $data["api_key"] = $request->header('apikey');
            Log::channel('custom_db')->info('Lead data mapped', [
                'data' => $data,
                'key_identifier' =>  $correlationId,
                'key_identifier_type' => KeyIdentifierType::MemberLead
            ]);

            $validator = Validator::make($data, [
                'member_slug' => 'required',
                'first_name' => 'required',
                'last_name' => 'required',
                'email' => 'required',
                'phone' => 'required',
                'service_category_type_id' => 'required',
                'main_category_id' => 'required',
                'service_category_id' => 'required',
                'project_address' => 'required',
                'timeframe' => 'required',
                'zipcode' => 'required',
                'content' => 'required',
                'api_key' => 'required'
                //'lead_terms' => 'required',
                //'g-recaptcha-response' => ['required', new ValidRecaptcha]
            ]);

            if (isset($validator) && $validator->fails()) {
                $validation_message = $validator->messages()->getMessages();
                Log::channel('custom_db')->warning("Request validation failed", [
                    'data' =>  $validation_message,
                    'key_identifier' =>  $correlationId,
                    'key_identifier_type' => KeyIdentifierType::MemberLead
                ]);

                return [
                    'success' => 0,
                    'message' => $validation_message,
                    'correlationid' => $correlationId
                ];
            }

            $affiliate = Affiliate::where('api_key', '=',  $data["api_key"] )->first();

            Log::channel('custom_db')->info('Affiliate Information', [
                'data' => $affiliate,
                'key_identifier' =>  $correlationId,
                'key_identifier_type' => KeyIdentifierType::MemberLead
            ]);

            if (is_null($affiliate)) {
                Log::channel('custom_db')->warning('Invalid API Key', [
                    'key_identifier' =>  $correlationId,
                    'key_identifier_type' => KeyIdentifierType::MemberLead
                ]);
                return [
                    'success' => 0,
                    'message' => 'Invalid API Key.',
                    'correlationid' => $correlationId
                ];
            }

            $data["affiliate_id"] = $affiliate->id;
            $mainTlc = MainCategoryTopLevelCategory::where('main_category_id', $data["main_category_id"])->first();
            $data['top_level_category_id'] = $mainTlc->top_level_category_id;
            $topLevelCategoryTrade = TopLevelCategoryTrade::where('top_level_category_id', $data['top_level_category_id'])->first();
            $data['trade_id'] = $topLevelCategoryTrade->trade_id;

            if (is_null($mainTlc) || is_null($topLevelCategoryTrade)) {
                Log::channel('custom_db')->warning('Bad request[Service]', [
                    'key_identifier' =>  $correlationId,
                    'key_identifier_type' => KeyIdentifierType::MemberLead
                ]);
                return [
                    'success' => 0,
                    'message' => 'Bad request[Service].',
                    'correlationid' => $correlationId
                ];
            }

            $data['lead_activation_key'] = Custom::getRandomString(50);
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
                            'm.file_name as logo',
                            's.short_name as state_code',
                            's.name as state_name'
                        )
                        ->leftJoin('membership_levels AS ml', 'companies.membership_level_id', 'ml.id')
                        ->leftJoin('states as s', 'companies.state_id', 's.id')
                        ->leftJoin('media as m', 'companies.company_logo_id', 'm.id')
                        ->where('companies.slug', $data["member_slug"])
                        ->first();
            Log::channel('custom_db')->info('Company Information', [
                'data' => $company,
                'key_identifier' =>  $correlationId,
                'key_identifier_type' => KeyIdentifierType::MemberLead
            ]);

            if (is_null($company)) {
                Log::channel('custom_db')->warning('Bad request[Member]', [
                    'key_identifier' =>  $correlationId,
                    'key_identifier_type' => KeyIdentifierType::MemberLead
                ]);

                return [
                    'success' => 0,
                    'message' => 'Bad request[Member].',
                    'correlationid' => $correlationId
                ];
            }
            $data["company"] = $company->company_name;

            $company_service_category = CompanyServiceCategory::where([
                ['company_id', $company->id],
                ['main_category_id',$data["main_category_id"]],
                ['top_level_category_id', $data['top_level_category_id']],
                ['service_category_type_id', $data["service_category_type_id"]],
                ['service_category_id', $data["service_category_id"]],
            ])->active()->first();
            Log::channel('custom_db')->info('Company Service Category Information', [
                'data' => $company_service_category,
                'key_identifier' =>  $correlationId,
                'key_identifier_type' => KeyIdentifierType::MemberLead
            ]);

            if (is_null($company_service_category)) {
                Log::channel('custom_db')->warning('Company not working in the service category you selected', [
                    'key_identifier' =>  $correlationId,
                    'key_identifier_type' => KeyIdentifierType::MemberLead
                ]);
                return [
                    'success' => 0,
                    'message' => 'Company not working in the service category you selected.',
                    'correlationid' => $correlationId
                ];
            }

            //check for test zipcodes
            $testValuesToBeProcessed = explode(',', env('TEST_ZIPCODES_TOBEPROCESSED'));
            $testValuesToBeProcessedExceptNetworx = explode(',', env('TEST_ZIPCODES_TOBEPROCESSED_EXCEPT_NETWORX'));
            if (in_array($data["zipcode"], $testValuesToBeProcessed) || in_array($data["zipcode"], $testValuesToBeProcessedExceptNetworx))
            if (in_array($data["zipcode"], $testValuesToBeProcessed) || in_array($data["zipcode"], $testValuesToBeProcessedExceptNetworx))
            {
                //some default city and state
                $data['city'] = "NA";
                $data['state_id'] = 1;
                Log::channel('custom_db')->info('Processing the request with test zipcode', [
                    'key_identifier' =>  $correlationId,
                    'key_identifier_type' => KeyIdentifierType::GeneralLead
                ]);
            }
            else
            {
                $company_zipcodes = CompanyZipcode::where([
                    ['company_id', $company->id],
                    ['zip_code', $data["zipcode"]]
                ])->active()->first();

                Log::channel('custom_db')->info('Company Zipcode Information', [
                    'data' => $company_zipcodes,
                    'key_identifier' =>  $correlationId,
                    'key_identifier_type' => KeyIdentifierType::MemberLead
                ]);

                if (is_null($company_zipcodes)) {
                    Log::channel('custom_db')->info('Company not working in the selected service area.', [
                        'key_identifier' =>  $correlationId,
                        'key_identifier_type' => KeyIdentifierType::MemberLead
                    ]);

                    return [
                        'success' => 0,
                        'message' => 'Company not working in the selected service area.',
                        'correlationid' => $correlationId
                    ];
                }
                $data["state_id"] =  $company_zipcodes->state_id;
                $data["city"] = $company_zipcodes->city;
            }


            $lead = Lead::create($data);
            Log::channel('custom_db')->info('Lead created', [
                'data' => $lead,
                'key_identifier' =>  $correlationId,
                'key_identifier_type' => KeyIdentifierType::MemberLead
            ]);

            Custom::lead_confirmation_email($lead);

            //Parse aweber
            if (isset($affiliate) && $affiliate->aweber_enabled) {
                $mainCategory = MainCategory::where('id', $data['main_category_id'])->first();
                $company_logo = empty($company->logo) ? self::getTempLogo($company->company_name,$company->slug) :  env('APP_URL') . "/uploads/media/{$company->logo}";
                $customFields = [
                    'regionname' => $lead->city,
                    'signupurl' => $lead->signup_url,
                    'company_logo_1' => $company_logo,
                    'company_profile_url_1' => $affiliate->member_base_url.$company->slug,
                    'service_category' => $mainCategory->title,
                    'company_1_display' => '',
                    'company_phone_1' => $company->main_company_telephone,
                    'company_name_1' => $company->company_name
                ];

                $aweberSubscribeListRequest = [
                    "name" => $lead->full_name,
                    "email" => $lead->email,
                    "custom_fields" => $customFields
                ];

                $domainAbbr = strtolower($affiliate->domain_abbr);
                $listname = $affiliate->aweber_member_list;
                Log::channel('custom_db')->info('Aweber subscribe request', [
                    'data' => $aweberSubscribeListRequest,
                    'listname' => $listname,
                    'key_identifier' =>  $correlationId,
                    'key_identifier_type' => KeyIdentifierType::MemberLead
                ]);

                $subscribeListResponse = Aweber::SubscribeToList($affiliate->aweber_account_id,
                    $listname,
                    $affiliate->aweber_refresh_token,
                    $aweberSubscribeListRequest);
                Log::channel('custom_db')->info('Aweber subscribe response', [
                    'data' => $subscribeListResponse,
                    'listname' => $listname,
                    'key_identifier' =>  $correlationId,
                    'key_identifier_type' => KeyIdentifierType::MemberLead
                ]);
            }

            $company_lead_request = [
                'company_id' => $company->id,
                'lead_id' => $lead->id,
                'is_hidden' => $company->membership_level->hide_leads,
                'priority' => '1'
            ];

            $company_lead = CompanyLead::create($company_lead_request);
            Log::debug("{$correlationId} Company lead created");
            Log::debug(json_encode($company_lead));
            Log::channel('custom_db')->info('Company lead created', [
                'data' => $company_lead,
                'request' => $company_lead_request,
                'key_identifier' =>  $correlationId,
                'key_identifier_type' => KeyIdentifierType::MemberLead
            ]);

            $company_lead_ids[] = $company_lead->id;
            Custom::lead_generation_email_to_company($company_lead_ids);
            Custom::lead_email_admin($lead);
            Log::channel('custom_db')->info('Lead processed sucessfully',[
                'key_identifier' =>  $correlationId,
                'key_identifier_type' => KeyIdentifierType::MemberLead
            ]);
            return [
                'success' => 1,
                'message' => "Lead processed sucessfully",
                'correlationid' => $correlationId
            ];
        }
        catch (Exception $e)
        {
            Log::channel('custom_db')->error('Error processing member lead request',[
                'data' => $request->json()->all(),
                'exception_message' => $e->getMessage(),
                'exception_trace' => $e->getTraceAsString(),
                'key_identifier' =>  $correlationId,
                'key_identifier_type' => KeyIdentifierType::MemberLead
            ]);

            return [
                'success' => 0,
                'message' => "Exception while processing lead".$e->getMessage()."Trace:".$e->getTraceAsString(),
                'correlationid' => $correlationId
            ];
        }
    }

    public function ProcessAffiliateGeneralRequest(Request $request)
    {
        $correlationId = Str::uuid()->toString();
        Log::channel('custom_db')->info('Job creation started for general lead', [
            'data' => $request->json()->all(),
            'key_identifier' =>  $correlationId,
            'key_identifier_type' => KeyIdentifierType::GeneralLead
        ]);

        $data = [];
        $data['correlation_id'] =  $correlationId;
        $data['ip_address'] = $request->ip();
        $data["first_name"]  = $request->first_name;
        $data["last_name"] = $request->last_name;
        $data["full_name"] = $data["first_name"]." ". $data["last_name"] ;
        $data["email"] = $request->email;
        $data["phone"] = $request->phone;
        $data["service_category_type_id"] = isset($request->service_type_id) ? $request->service_type_id : null;
        $data["main_category_id"] = isset($request->main_category_id) ? $request->main_category_id : null;
        $data["service_category_id"] = isset($request->category_id) ? $request->category_id : null;
        $data["project_address"] = $request->address;
        $data["timeframe"] = isset($request->timeframe)  ? self::GetTimeframe($request->timeframe) : null;
        $data["zipcode"] = $request->zip;
        $data["content"] = $request->project_info;
        $data["signup_url"] = $request->signup_url;
        $data["api_key"] = $request->header('apikey');
        $data["cert_url"] =  $request->cert_url;

        Log::channel('custom_db')->info('Lead data mapped', [
            'data' => $data,
            'key_identifier' =>  $correlationId,
            'key_identifier_type' => KeyIdentifierType::GeneralLead
        ]);

        $validator = Validator::make($data, [
            'full_name' => 'required',
            'email' => 'required',
            'phone' => 'required',
            'service_category_type_id' => 'required',
            'main_category_id' => 'required',
            'service_category_id' => 'required',
            'project_address' => 'required',
            'timeframe' => 'required',
            'zipcode' => 'required',
            'content' => 'required',
            'api_key' => 'required'
        ]);

        if (isset($validator) && $validator->fails()) {
            $validation_message = $validator->messages()->getMessages();
                Log::channel('custom_db')->warning("Request validation failed", [
                    'validation_data' =>  $validation_message,
                    'key_identifier' =>  $correlationId,
                    'key_identifier_type' => KeyIdentifierType::GeneralLead
                ]);

            return [
                'success' => 0,
                'message' =>$validation_message,
                'correlationid' => $correlationId
            ];
        }
        Log::channel('custom_db')->warning("Creating general request job", [
            'data' => $data,
            'key_identifier' =>  $correlationId,
            'key_identifier_type' => KeyIdentifierType::GeneralLead
        ]);
        ProcessGeneralRequestJob::dispatch($data);
        return [
            'success' =>1,
            'message' => 'ProcessGeneralRequestJob job created.',
            'correlationid' => $correlationId
        ];
    }

    /**
     * Method checks for company match and if not found parse the netwrox
     * Returns correlationid - for auditing
     *         networxresponseurl - if networx parsed, response url received in networx response
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function ProcessAffiliateGeneralRequestv1(Request $request)
    {
        $correlationId = Str::uuid()->toString();
        Log::channel('custom_db')->info('Job creation started for general lead', [
            'data' => $request->json()->all(),
            'key_identifier' =>  $correlationId,
            'key_identifier_type' => KeyIdentifierType::GeneralLead
        ]);

        $data = [];
        $data['correlation_id'] =  $correlationId;
        $data['ip_address'] = $request->ip();
        $data["first_name"]  = $request->first_name;
        $data["last_name"] = $request->last_name;
        $data["full_name"] = $request->first_name." ". $request->last_name ;
        $data["email"] = $request->email;
        $data["phone"] = $request->phone;
        $data["service_category_type_id"] = isset($request->service_type_id) ? $request->service_type_id : null;
        $data["main_category_id"] = isset($request->main_category_id) ? $request->main_category_id : null;
        $data["service_category_id"] = isset($request->category_id) ? $request->category_id : null;
        $data["project_address"] = $request->address;
        $data["timeframe"] = isset($request->timeframe)  ? self::GetTimeframe($request->timeframe) : null;
        $data["zipcode"] = $request->zip;
        $data["content"] = $request->project_info;
        $data["signup_url"] = $request->signup_url;
        $data["homeowner_id"] =  $request->homeowner_id;
        $data["api_key"] = $request->header('apikey');
        $data["cert_url"] =  $request->cert_url;

        Log::channel('custom_db')->info('Lead data mapped', [
            'data' => $data,
            'key_identifier' =>  $correlationId,
            'key_identifier_type' => KeyIdentifierType::GeneralLead
        ]);

        $validator = Validator::make($data, [
            'full_name' => 'required',
            'email' => 'required',
            'phone' => 'required',
            'service_category_type_id' => 'required',
            'main_category_id' => 'required',
            'service_category_id' => 'required',
            'project_address' => 'required',
            'timeframe' => 'required',
            'zipcode' => 'required',
            'content' => 'required',
            'api_key' => 'required'
        ]);

        if (isset($validator) && $validator->fails()) {
            $validation_message = $validator->messages()->getMessages();
                Log::channel('custom_db')->warning("Request validation failed", [
                    'validation_data' =>  $validation_message,
                    'key_identifier' =>  $correlationId,
                    'key_identifier_type' => KeyIdentifierType::GeneralLead
                ]);

            return [
                'success' => 0,
                'message' =>$validation_message,
                'correlationid' => $correlationId
            ];
        }

        /*API key check - START*/
        $affiliate = Affiliate::where('api_key', '=',  $data["api_key"] )->first();
        Log::channel('custom_db')->info('Affiliate Information', [
            'data' => $affiliate,
            'key_identifier' =>  $correlationId,
            'key_identifier_type' => KeyIdentifierType::GeneralLead
        ]);

        if (is_null($affiliate)) {
            Log::channel('custom_db')->warning('Invalid API Key', [
                'key_identifier' =>  $correlationId,
                'key_identifier_type' => KeyIdentifierType::GeneralLead
            ]);

            return [
                'success' => 0,
                'message' => 'Invalid API Key.',
                'correlationid' => $correlationId
            ];
        }
        $data["affiliate_id"] = $affiliate->id;
        /*API key check - END*/

        /**Main Category Id check - START*/
        $mainTlc = MainCategoryTopLevelCategory::where('main_category_id', $data["main_category_id"])->first();

        if (is_null($mainTlc)) {
            Log::channel('custom_db')->warning('Bad request[Service main_category_id]', [
                'key_identifier' =>  $correlationId,
                'key_identifier_type' => KeyIdentifierType::GeneralLead
            ]);

            return [
                'success' => 0,
                'message' => 'Bad request[main_category_id].',
                'correlationid' => $correlationId
            ];
        }
        /**Main Category Id check - END*/

        /**Top Level Category Id check - START*/
        $data['top_level_category_id'] = $mainTlc->top_level_category_id;
        $topLevelCategoryTrade = TopLevelCategoryTrade::where('top_level_category_id', $data['top_level_category_id'])->first();

        if (is_null($topLevelCategoryTrade)) {
            Log::channel('custom_db')->warning('Bad request[top_level_category_id]', [
                'key_identifier' =>  $correlationId,
                'key_identifier_type' => KeyIdentifierType::GeneralLead
            ]);

            return [
                'success' => 0,
                'message' => 'Bad request[top_level_category_id].',
                'correlationid' => $correlationId
            ];
        }
        /**Top Level Category Id check - END*/
        $data['trade_id'] = $topLevelCategoryTrade->trade_id;
        /**ZipCode validation - START */
        $testValuesToBeProcessed = explode(',', env('TEST_ZIPCODES_TOBEPROCESSED'));
        $testValuesToBeProcessedExceptNetworx = explode(',', env('TEST_ZIPCODES_TOBEPROCESSED_EXCEPT_NETWORX'));
        if (in_array($data["zipcode"], $testValuesToBeProcessed) || in_array($data["zipcode"], $testValuesToBeProcessedExceptNetworx))
        {
            //some default city and state
            $data['city'] = "NA";
            $data['state_id'] = 1;
            Log::channel('custom_db')->info('Processing the request with test zipcode', [
                'key_identifier' =>  $correlationId,
                'key_identifier_type' => KeyIdentifierType::GeneralLead
            ]);
        }
        else
        {
            //get state info by zipcode
            $APIkey = env('ZIPCODE_API_KEY');
            $json = @file_get_contents('https://www.zipcodeapi.com/rest/' . $APIkey . '/info.json/' . $data["zipcode"] . '/radians');

            if ($json != '') {
                $zipcodeArr = json_decode($json);
                $stateObj = State::where('short_name', $zipcodeArr->state)->first();
                $data['city'] = $zipcodeArr->city;
                $data['state_id'] = ((!is_null($stateObj)) ? $stateObj->id : null);
            } else {
                Log::channel('custom_db')->warning('Bad request[Zipcode]', [
                    'key_identifier' =>  $correlationId,
                    'key_identifier_type' => KeyIdentifierType::GeneralLead
                ]);

                return [
                    'success' => 0,
                    'message' => 'Bad request[Zipcode]',
                    'correlationid' => $correlationId
                ];
            }
        }
        /**ZipCode validation - END */

        /**Generate Lead - START */
        $lead = Lead::create($data);
            Log::channel('custom_db')->info('Lead created', [
                'data' => $lead,
                'key_identifier' =>  $correlationId,
                'key_identifier_type' => KeyIdentifierType::GeneralLead
            ]);
        /**Generate Lead - END */

        $companies_who_gets_leads = Custom::get_companies_who_get_leads($lead);
            Log::channel('custom_db')->info('Companies who gets leads', [
                'data' => $companies_who_gets_leads,
                'key_identifier' =>  $correlationId,
                'key_identifier_type' => KeyIdentifierType::GeneralLead
            ]);

        $lead_counter = $companies_who_gets_leads->count();
        Log::channel('custom_db')->info('Lead counter', [
            'data' => $lead_counter,
            'key_identifier' =>  $correlationId,
            'key_identifier_type' => KeyIdentifierType::GeneralLead
        ]);

        $web_settings = Custom::getSettings();
        $networxEnabled = isset($web_settings['sent_to_networx']) && $web_settings['sent_to_networx'] == 'yes';
        $membership_level_ids = [2, 4, 5,6];
        // Use the filter method to keep only the records with the specified charge types
        $filteredOfficialCompanies = $companies_who_gets_leads->filter(function ($company) use ($membership_level_ids) {
            return in_array($company->membership_level_id, $membership_level_ids);
        });

        $filteredOfficialCompaniesCount = isset($filteredOfficialCompanies) ? $filteredOfficialCompanies->count() : 0;
        $generalData = [];
        $generalData['network_enabled'] = $networxEnabled;
        $generalData['trade_id'] = $lead->trade_id;
        $generalData['network_enabled'] = $lead_counter;
        $generalData['official_company_count'] = $filteredOfficialCompaniesCount;
        Log::channel('custom_db')->info('Networx info', [
            'data' => $generalData,
            'key_identifier' =>  $correlationId,
            'key_identifier_type' => KeyIdentifierType::GeneralLead
        ]);
        $networx_processed = false;
        $networx_status_code =  null;
        $networx_redirect_url =  null;
        if ($networxEnabled &&
            $lead->trade_id == 1 &&
            $filteredOfficialCompaniesCount <= 0
        ) {
            $testValuesToBeProcessedExceptNetworx = explode(',', env('TEST_ZIPCODES_TOBEPROCESSED_EXCEPT_NETWORX'));
            if (in_array($data["zipcode"], $testValuesToBeProcessedExceptNetworx))
            {
                Log::channel('custom_db')->info('Skipping networx with test zipcode', [
                    'key_identifier' =>  $correlationId,
                    'key_identifier_type' => KeyIdentifierType::GeneralLead
                ]);
            }
            else
            {
                $networx_response = Custom::networxCall($lead);
                $networx_processed = true;
                Log::channel('custom_db')->info('Networx response', [
                    'data' => $networx_response,
                    'key_identifier' =>  $correlationId,
                    'key_identifier_type' => KeyIdentifierType::GeneralLead
                ]);
                $networx_status_code = $networx_response['statusCode'];
                if ($networx_response['statusCode'] == '200') {
                    $networx_redirect_url = urldecode((string)$networx_response['redirectUrl']);
                    $lead->networx_redirect_url = $networx_redirect_url;
                }
                else if(in_array($data["zipcode"], $testValuesToBeProcessed) && $networx_response['statusCode'] != '200'){ //override networx dummy response if requested with 00001
                    $networx_status_code = 200;
                    $networx_redirect_url = urldecode("https%3A%2F%2Fwww.networx.com%2Flead-confirmation%3Fid%3D123465%26aff_token%3D91jcbjwdvchjr5%26utm_source%3D12345%26utm_medium%3Daffiliate");
                    $lead->networx_redirect_url = $networx_redirect_url;
                }

                $lead->networx_code = $networx_status_code;
                $lead->save();
            }
        }

        Log::channel('custom_db')->warning("Creating general request - v1 job", [
            'data' => $lead,
            'key_identifier' =>  $correlationId,
            'key_identifier_type' => KeyIdentifierType::GeneralLead
        ]);

        ProcessGeneralRequestJobv1::dispatch($lead);
        $v1_response = [
            'success' =>1,
            'message' => 'ProcessGeneralRequestJobV1 job created.',
            'networx_processed' => $networx_processed,
            'networx_status_code' => $networx_status_code,
            'networx_redirect_url' => $networx_redirect_url,
            'correlationid' => $correlationId
        ];

        Log::channel('custom_db')->warning("V1 response", [
            'data' => $v1_response,
            'key_identifier' =>  $correlationId,
            'key_identifier_type' => KeyIdentifierType::GeneralLead
        ]);

        return  $v1_response;
    }
    public function ProcessAffliateGeneralRequestByCompanySlug(Request $request){
        $correlationId = Str::uuid()->toString();
        Log::channel('custom_db')->info('Job creation started for general lead by member slug', [
            'data' => $request->json()->all(),
            'key_identifier' =>  $correlationId,
            'key_identifier_type' => KeyIdentifierType::GeneralLead
        ]);

        $memberSlugsString = $request->input('member_slugs', '');
        // Parse member slugs string into array
        $memberSlugs = array_map('trim', explode(',', $memberSlugsString));
        $memberSlugs = array_filter($memberSlugs); // Remove empty entries

        if (empty($memberSlugs)) {
            return response()->json([
                'success' => 0,
                'message' => 'No valid member slugs provided'
            ], 400);
        }

        $data = [];
        $data['correlation_id'] =  $correlationId;
        $data['ip_address'] = $request->ip();
        $data["first_name"]  = $request->first_name;
        $data["last_name"] = $request->last_name;
        $data["full_name"] = $request->first_name." ". $request->last_name ;
        $data["email"] = $request->email;
        $data["phone"] = $request->phone;
        $data["service_category_type_id"] = isset($request->service_type_id) ? $request->service_type_id : null;
        $data["main_category_id"] = isset($request->main_category_id) ? $request->main_category_id : null;
        $data["service_category_id"] = isset($request->category_id) ? $request->category_id : null;
        $data["project_address"] = $request->address;
        $data["timeframe"] = isset($request->timeframe)  ? self::GetTimeframe($request->timeframe) : null;
        $data["zipcode"] = $request->zip;
        $data["content"] = $request->project_info;
        $data["signup_url"] = $request->signup_url;
        $data["api_key"] = $request->header('apikey');
        $data["cert_url"] =  $request->cert_url;
        $data["homeowner_id"] =  $request->homeowner_id;
        $data['company_slugs_csv'] = $request->input('member_slugs', '');
        Log::channel('custom_db')->info('Lead data mapped', [
            'data' => $data,
            'key_identifier' =>  $correlationId,
            'key_identifier_type' => KeyIdentifierType::GeneralLead
        ]);

        $validator = Validator::make($data, [
            'full_name' => 'required',
            'email' => 'required',
            'phone' => 'required',
            'service_category_type_id' => 'required',
            'main_category_id' => 'required',
            'service_category_id' => 'required',
            'project_address' => 'required',
            'timeframe' => 'required',
            'zipcode' => 'required',
            'content' => 'required',
            'api_key' => 'required'
        ]);

        if (isset($validator) && $validator->fails()) {
            $validation_message = $validator->messages()->getMessages();
                Log::channel('custom_db')->warning("Request validation failed", [
                    'validation_data' =>  $validation_message,
                    'key_identifier' =>  $correlationId,
                    'key_identifier_type' => KeyIdentifierType::GeneralLead
                ]);

            return [
                'success' => 0,
                'message' =>$validation_message,
                'correlationid' => $correlationId
            ];
        }

        /*API key check - START*/
        $affiliate = Affiliate::where('api_key', '=',  $data["api_key"] )->first();
        Log::channel('custom_db')->info('Affiliate Information', [
            'data' => $affiliate,
            'key_identifier' =>  $correlationId,
            'key_identifier_type' => KeyIdentifierType::GeneralLead
        ]);

        if (is_null($affiliate)) {
            Log::channel('custom_db')->warning('Invalid API Key', [
                'key_identifier' =>  $correlationId,
                'key_identifier_type' => KeyIdentifierType::GeneralLead
            ]);

            return [
                'success' => 0,
                'message' => 'Invalid API Key.',
                'correlationid' => $correlationId
            ];
        }
        $data["affiliate_id"] = $affiliate->id;
        /*API key check - END*/

        /**Main Category Id check - START*/
        $mainTlc = MainCategoryTopLevelCategory::where('main_category_id', $data["main_category_id"])->first();

        if (is_null($mainTlc)) {
            Log::channel('custom_db')->warning('Bad request[Service main_category_id]', [
                'key_identifier' =>  $correlationId,
                'key_identifier_type' => KeyIdentifierType::GeneralLead
            ]);

            return [
                'success' => 0,
                'message' => 'Bad request[main_category_id].',
                'correlationid' => $correlationId
            ];
        }
        /**Main Category Id check - END*/

        /**Top Level Category Id check - START*/
        $data['top_level_category_id'] = $mainTlc->top_level_category_id;
        $topLevelCategoryTrade = TopLevelCategoryTrade::where('top_level_category_id', $data['top_level_category_id'])->first();

        if (is_null($topLevelCategoryTrade)) {
            Log::channel('custom_db')->warning('Bad request[top_level_category_id]', [
                'key_identifier' =>  $correlationId,
                'key_identifier_type' => KeyIdentifierType::GeneralLead
            ]);

            return [
                'success' => 0,
                'message' => 'Bad request[top_level_category_id].',
                'correlationid' => $correlationId
            ];
        }
        /**Top Level Category Id check - END*/
        $data['trade_id'] = $topLevelCategoryTrade->trade_id;
        /**ZipCode validation - START */
        $testValuesToBeProcessed = explode(',', env('TEST_ZIPCODES_TOBEPROCESSED'));
        $testValuesToBeProcessedExceptNetworx = explode(',', env('TEST_ZIPCODES_TOBEPROCESSED_EXCEPT_NETWORX'));
        if (in_array($data["zipcode"], $testValuesToBeProcessed) || in_array($data["zipcode"], $testValuesToBeProcessedExceptNetworx))
        {
            //some default city and state
            $data['city'] = "NA";
            $data['state_id'] = 1;
            Log::channel('custom_db')->info('Processing the request with test zipcode', [
                'key_identifier' =>  $correlationId,
                'key_identifier_type' => KeyIdentifierType::GeneralLead
            ]);
        }
        else
        {
            //get state info by zipcode
            $APIkey = env('ZIPCODE_API_KEY');
            $json = @file_get_contents('https://www.zipcodeapi.com/rest/' . $APIkey . '/info.json/' . $data["zipcode"] . '/radians');

            if ($json != '') {
                $zipcodeArr = json_decode($json);
                $stateObj = State::where('short_name', $zipcodeArr->state)->first();
                $data['city'] = $zipcodeArr->city;
                $data['state_id'] = ((!is_null($stateObj)) ? $stateObj->id : null);
            } else {
                Log::channel('custom_db')->warning('Bad request[Zipcode]', [
                    'key_identifier' =>  $correlationId,
                    'key_identifier_type' => KeyIdentifierType::GeneralLead
                ]);

                return [
                    'success' => 0,
                    'message' => 'Bad request[Zipcode]',
                    'correlationid' => $correlationId
                ];
            }
        }
        /**ZipCode validation - END */

        /**Generate Lead - START */
        $lead = Lead::create($data);
        Log::channel('custom_db')->info('Lead created', [
            'data' => $lead,
            'key_identifier' =>  $correlationId,
            'key_identifier_type' => KeyIdentifierType::GeneralLead
        ]);

        Log::channel('custom_db')->warning("Creating general request by company slug job", [
            'data' => $lead,
            'key_identifier' =>  $correlationId,
            'key_identifier_type' => KeyIdentifierType::GeneralLead
        ]);

        ProcessGeneralRequestJobv1::dispatch($lead);
        $v1_response = [
            'success' =>1,
            'message' => 'ProcessAffliateGeneralRequestByCompanySlugJob job created.',
            'correlationid' => $correlationId
        ];

        Log::channel('custom_db')->warning("ProcessAffliateGeneralRequestByCompanySlug response", [
            'data' => $v1_response,
            'key_identifier' =>  $correlationId,
            'key_identifier_type' => KeyIdentifierType::GeneralLead
        ]);

        return  $v1_response;
    }

    public function ProcessExternalRequest(Request $request)
    {
        //Add captcha validation

        // Clone existing request
        $newRequest = Request::createFrom($request);

        // Add or override the apikey header
        $newRequest->headers->set('apikey', 'ND1XFA3XFLTKAQO785HFP4FBK2BSLK0Y');

       //return $this->ProcessAffiliateGeneralRequestv1($newRequest);

       $v1_response = [
            "success" => 1,
            "message" => "ProcessGeneralRequestJobV1 job created.",
            "networx_processed" => true,
            "networx_status_code" => "200",
            "networx_redirect_url" => "https://www.networx.com/lead-confirmation?id=29536599&aff_token=958368effa2021794431089623",
            "correlationid" => "a4f6be85-f1bf-4506-83da-510446a01c74"
        ];

        return $v1_response;
    }

    public function store(Request $request)
    {
        try {
            $categoryIdCode = $request->category_id;
            $timeframe = self::GetTimeframe($request->selectTimeframe);
            $request->offsetSet('full_name', $request->txtFirstName . ' ' . $request->txtLastName);
            //$request->offsetSet('request_data_category', $request->selectServiceNeeded);
            $request->offsetSet('email', $request->txtEmail);
            $request->offsetSet('project_address', $request->txtAddress);
            $request->offsetSet('timeframe', $timeframe);
            $request->offsetSet('phone', $request->txtPhone);
            $request->offsetSet('request_data_sub_service_category', $request->category_id);
            $request->offsetSet('domain', self::get_domain($request->signupurl));
            $request->offsetSet('url_slug', $request->urlslug);
            $request->offsetSet('signup_url', $request->signupurl);
            $request->offsetSet('content', $request->txtAreaProject);
            $request->offsetSet('cert_url', $request->certurl);

            $serviceCategory = ServiceCategory::where('sc_code', $request->request_data_sub_service_category)->first();

            if (!$serviceCategory) {
                return response(['message' => 'No Category Found with category code ' . $request->request_data_sub_service_category], 200);
            }

            $mainCategory = MainCategory::where('id', $serviceCategory->main_category_id)->first();
            $mainTlc = MainCategoryTopLevelCategory::where('main_category_id', $mainCategory->id)->first();
            $tradeType = TopLevelCategoryTrade::select('trade_id')->where('top_level_category_id', $mainTlc->top_level_category_id)->first();
            $request->offsetSet('service_category_id', $serviceCategory->id);
            $request->offsetSet('service_category_type_id', $serviceCategory->service_category_type_id);
            $request->offsetSet('main_category_id', $mainCategory->id);

            //get state info by zipcode
            $APIkey = env('ZIPCODE_API_KEY');
            Log::debug('https://www.zipcodeapi.com/rest/' . $APIkey . '/info.json/' . $request->txtzip . '/radians');
            $json = @file_get_contents('https://www.zipcodeapi.com/rest/' . $APIkey . '/info.json/' . $request->txtZip . '/radians');
            Log::debug("zip json");
            Log::debug($json);
            $requestData = $request->all();
            if ($json != '') {
                $zipcodeArr = json_decode($json);
                $stateObj = State::where('short_name', $zipcodeArr->state)->first();
                $requestData['zipcode'] = $request->txtZip;
                $requestData['city'] = $zipcodeArr->city;
                $requestData['state_id'] = ((!is_null($stateObj)) ? $stateObj->id : null);
            } else {
                return response("Zip is invalid", 200);
            }

            $validator = Validator::make($requestData, [
                'full_name' => 'required',
                'email' => 'required|email',
                'project_address' => 'required',
                'zipcode' => 'required',
                'timeframe' => 'required',
                'phone' => 'required',
                'service_category_id' => 'required',
                'main_category_id' => 'required',
                'url_slug' => 'required',
                'signup_url' => 'required',
                /*'recaptcha_token' => ['required', new ValidRecaptchaAad]*/
            ]);

            if ($validator->fails()) {
                return response(implode("<br/>", $validator->messages()->all()), 200);
            }
            Log::debug("Signup Url");
            Log::debug("-" . $requestData['signup_url'] . "-");
            //$requestData['origin']
            $affiliate = Affiliate::where('domain', 'LIKE', '%' . $requestData['domain'] . '%')->first();
            Log::debug("Affiliate");
            Log::debug(json_encode($affiliate));
            Log::debug(json_encode($affiliate->id));
            Log::debug("Affiliate Isset:" . isset($affiliate));
            $requestData['top_level_category_id'] = $mainTlc->top_level_category_id;
            $requestData['trade_id'] = $tradeType->trade_id;
            $requestData['ip_address'] = $request->ip();
            $requestData['lead_activation_key'] = Custom::getRandomString(50);
            $requestData['affiliate_id'] = $affiliate->id;
            Log::debug("Request");
            Log::debug(json_encode($request));
            Log::debug("Request Data");
            Log::debug(json_encode($requestData));

            //Clean up the request
            $request->request->remove('txtAddress');
            $request->request->remove('selectServiceNeeded');
            $request->request->remove('txtFirstName');
            $request->request->remove('txtLastName');
            $request->request->remove('txtEmail');
            $request->request->remove('txtZip');
            $request->request->remove('selectTimeframe');
            $request->request->remove('txtPhone');
            $request->request->remove('recaptcha_token');
            $request->request->remove('urlSlug');
            $request->request->remove('g-recaptcha-response');
            $request->request->remove('category_id');
            $request->request->remove('txtAreaProject');
            $request->request->remove('certUrl');

            if (count($request->all())) {
                $lead = Lead::create($requestData);
                Log::debug("Lead created successfully");
                Log::debug(json_encode($lead));
                $web_settings = Custom::getSettings();
                //get the companies within requested zipcode range and service categoryid
                //send no member mail or member found emal to the lead
                Custom::lead_confirmation_email($lead);

                //get the companies to save to company leads and send lead emails
                //preview-trials
                //acredited members
                Custom::generateCompanyLeads($lead);

                //copy to admin
                Custom::lead_email_admin($lead);

                //networx parsing
                $lead_counter = Custom::get_number_of_companies_who_get_leads($lead);
                $affiliate_aweber_enabled = $affiliate->aweber_enabled;

                if (isset($affiliate) && $affiliate->aweber_enabled) {
                    $affiliateMainCategory = AffiliateMainCategory::where(['affiliate_id' => $affiliate->id, 'main_category_id' => $mainCategory->id])->first();
                    Log::debug("Affiliate Top Level Category");
                    Log::debug(json_encode($affiliateMainCategory ));
                    if(isset($affiliateMainCategory))
                    {
                        $customFields = [
                            'regionname' => $lead->city,
                            'memberurl' => '',
                            'signupurl' => $lead->signup_url,
                        ];

                        $aweberSubscribeListRequest = [
                            "name" => $lead->full_name,
                            "email" => $lead->email,
                            "custom_fields" => $customFields
                        ];
                        $listname = $lead_counter > 0 ? $affiliateMainCategory->aweber_member_listname : $affiliateMainCategory->aweber_non_member_listname;
                        $subscribeListResponse = Aweber::SubscribeToList($affiliate->aweber_account_id,
                            $listname,
                            $affiliate->aweber_refresh_token,
                            $aweberSubscribeListRequest);
                        Log::debug("Aweber subscribe response");
                        Log::debug(json_encode($subscribeListResponse ));
                    }

                }

                if (
                    (isset($web_settings['sent_to_networx']) && $web_settings['sent_to_networx'] == 'yes') &&
                    $lead->trade_id == 1 &&
                    $lead_counter <= 0
                ) {
                    Log::debug("Networx parsing");
                    // $networx_response = Custom::networxCall($lead);
                    // if ($networx_response['statusCode'] == '200') {
                    //     $lead->networx_code = $networx_response['successCode'];
                    //     $lead->save();
                    // }
                }
            }

            return response($requestData, 200);

        } catch (Exception $e) {
            return response($e, 200);
        }
    }

    private function GetTimeframe($id)
    {
        $timeframe = "";
        switch ($id) {
            case "urgent":
                $timeframe = "Ready To Go - 0 to 2 Weeks";
                break;
            case "not-urgent":
                $timeframe = "No Urgency - 3 to 6 Weeks";
                break;
            case "pre-planning-stages":
                $timeframe = "Price Shopping - Price Comparing";
                break;
        }

        return $timeframe;
    }

    private function get_domain($url)
    {
        $pieces = parse_url($url);
        $domain = isset($pieces['host']) ? $pieces['host'] : $pieces['path'];
        if (preg_match('/(?P<domain>[a-z0-9][a-z0-9\-]{1,63}\.[a-z\.]{2,6})$/i', $domain, $regs)) {
            return $regs['domain'];
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

    /**
     * @param int $homeowner_id
     * @return JsonResponse
     */
    public function getHomeownerLeads($homeowner_id)
    {
        try {
            $leads = Lead::where('homeowner_id', $homeowner_id)
                ->with([
                    'service_category_type:id,title',
                    'main_category:id,title',
                    'service_category:id,title',
                    'top_level_category:id,title'
                ])
                ->select([
                    'id',
                    'homeowner_id',
                    'service_category_type_id',
                    'main_category_id',
                    'service_category_id',
                    'company_slugs_csv',
                    'zipcode',
                    'project_address',
                    'content',
                    'created_at'
                ])
                ->orderBy('created_at', 'desc')
                ->get();

            if ($leads->isEmpty()) {
                return response()->json([
                    'success' => true,
                    'message' => 'No leads found for this homeowner.',
                    'data' => []
                ], 200);
            }

            $formattedLeads = $leads->map(function ($lead) {
                return [
                    'lead_id'            => $lead->id,
                    'service_type'       => $lead->service_type->title ?? 'N/A',
                    'main_category'      => $lead->main_category->title ?? 'N/A',
                    'category'           => $lead->service_category->title ?? 'N/A',
                    'top_level_category' => $lead->top_level_category->title ?? 'N/A',
                    'company_slugs_csv' => $lead->company_slugs_csv  ?? 'N/A',
                    'address'            => $lead->project_address,
                    'zip'                => $lead->zipcode,
                    'description'        => $lead->content,
                    'date'               => $lead->created_at->format('M d, Y')
                ];
            });

            return response()->json([
                'success' => true,
                'count'   => $formattedLeads->count(),
                'data'    => $formattedLeads
            ], 200);

        } catch (\Exception $e) {
            \Log::error("Get Homeowner Leads Error: " . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong while fetching leads.'
            ], 500);
        }
    }

}

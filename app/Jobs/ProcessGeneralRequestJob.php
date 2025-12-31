<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

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
use Validator;
use Str;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use App\Models\KeyIdentifierType;

class ProcessGeneralRequestJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $data;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {       
        $data = $this->data;
        $correlationId =  $data['correlation_id'];
        Log::channel('custom_db')->info('Job started for general lead', [
            'data' => $data,
            'key_identifier' =>  $correlationId,
            'key_identifier_type' => KeyIdentifierType::GeneralLead
        ]);
        
        try 
        {  
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
                
                return;
            }

            $data["affiliate_id"] = $affiliate->id;

            $mainTlc = MainCategoryTopLevelCategory::where('main_category_id', $data["main_category_id"])->first();
            
            if (is_null($mainTlc)) {
                Log::channel('custom_db')->warning('Bad request[Service]', [
                    'key_identifier' =>  $correlationId,
                    'key_identifier_type' => KeyIdentifierType::GeneralLead
                ]);

                return;
            }

            $data['top_level_category_id'] = $mainTlc->top_level_category_id;   
            $topLevelCategoryTrade = TopLevelCategoryTrade::where('top_level_category_id', $data['top_level_category_id'])->first();
            
            if (is_null($topLevelCategoryTrade)) {
                Log::channel('custom_db')->warning('Bad request[TLCTrade]', [
                    'key_identifier' =>  $correlationId,
                    'key_identifier_type' => KeyIdentifierType::GeneralLead
                ]);
                
                return;
            }     

            $data['trade_id'] = $topLevelCategoryTrade->trade_id;   

            //check for test zipcodes
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

                    return;
                }
            }           

            $lead = Lead::create($data);
            Log::channel('custom_db')->info('Lead created', [
                'data' => $lead,
                'key_identifier' =>  $correlationId,
                'key_identifier_type' => KeyIdentifierType::GeneralLead
            ]);
            
            $web_settings = Custom::getSettings();
                          
            Custom::lead_confirmation_email($lead);
            Log::channel('custom_db')->info('Lead confirmation email sent', [
                'data' => $lead,
                'key_identifier' =>  $correlationId,
                'key_identifier_type' => KeyIdentifierType::GeneralLead
            ]);

            //get the companies to save to company leads and send lead emails
            //preview-trials
            //acredited members
            Custom::generateCompanyLeads($lead);
            Log::channel('custom_db')->info('Generated company leads', [
                'data' => $lead,
                'key_identifier' =>  $correlationId,
                'key_identifier_type' => KeyIdentifierType::GeneralLead
            ]);

            //copy to admin
            Custom::lead_email_admin($lead);
            Log::channel('custom_db')->info('Lead admin email sent', [
                'data' => $lead,
                'key_identifier' =>  $correlationId,
                'key_identifier_type' => KeyIdentifierType::GeneralLead
            ]);

            //networx parsing
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

            $affiliate_aweber_enabled = $affiliate->aweber_enabled;
            Log::channel('custom_db')->info('Aweber enabled', [
                'data' => $affiliate_aweber_enabled,
                'key_identifier' =>  $correlationId,
                'key_identifier_type' => KeyIdentifierType::GeneralLead
            ]);
           
            if (isset($affiliate) && $affiliate->aweber_enabled) {
                $affiliateMainCategory = AffiliateMainCategory::where(
                    ['affiliate_id' => $affiliate->id, 
                    'main_category_id' => $data["main_category_id"] , 
                    'service_category_type_id' => $data["service_category_type_id"]])->first();                    
                    Log::channel('custom_db')->info('Affiliate Main Category', [
                        'data' => $affiliateMainCategory,
                        'key_identifier' =>  $correlationId,
                        'key_identifier_type' => KeyIdentifierType::GeneralLead
                    ]);

                if(isset($affiliateMainCategory))
                {
                    $topLevelCategory = TopLevelCategory::where('id', $data['top_level_category_id'])->first();
                    $data["top_level_category"] =  $topLevelCategory->title;
                    $mainCategory = MainCategory::where('id', $data['main_category_id'])->first();
                    $data["main_category"] =  $mainCategory->title;
                    //set up the company custom fields
                    $company_logo_1 = "";
                    $company_1_display = "display:none !important;";
                    $company_profile_url_1 = "";
                    $company_logo_2 = "";
                    $company_2_display = "display:none !important;";
                    $company_profile_url_2 = "";
                    $company_logo_3 = "";
                    $company_3_display = "display:none !important;";
                    $company_profile_url_3 = "";
                    $company_phone_1 = "";
                    $company_phone_2 = "";
                    $company_phone_3 = "";

                    if($lead_counter >= 1)
                    {                       
                        $company_logo_1 = empty( $companies_who_gets_leads[0]["logo"]) ? self::getTempLogo($companies_who_gets_leads[0]["company_name"],$companies_who_gets_leads[0]["slug"]) :  $companies_who_gets_leads[0]["logo"];                        
                        $company_profile_url_1 =  $affiliate->member_base_url.$companies_who_gets_leads[0]["slug"];
                        $company_phone_1 = $companies_who_gets_leads[0]["main_company_telephone"];
                        if(!empty($company_logo_1) || !empty($company_profile_url_1))
                        {
                            $company_1_display = "";
                        }
                    }

                    if($lead_counter >= 2)
                    {
                        $company_logo_2 = empty( $companies_who_gets_leads[1]["logo"]) ? self::getTempLogo($companies_who_gets_leads[1]["company_name"],$companies_who_gets_leads[1]["slug"]) :  $companies_who_gets_leads[1]["logo"];
                        $company_profile_url_2 = $affiliate->member_base_url.$companies_who_gets_leads[1]["slug"];
                        $company_phone_2 = $companies_who_gets_leads[1]["main_company_telephone"];
                        if(!empty($company_logo_2) || !empty($company_profile_url_2))
                        {
                            $company_2_display = "";
                        }
                    }

                    if($lead_counter >= 3)
                    {
                        $company_logo_3 = empty( $companies_who_gets_leads[2]["logo"]) ? self::getTempLogo($companies_who_gets_leads[2]["company_name"],$companies_who_gets_leads[2]["slug"]) :  $companies_who_gets_leads[2]["logo"];
                        $company_profile_url_3 = $affiliate->member_base_url.$companies_who_gets_leads[2]["slug"];
                        $company_phone_3 = $companies_who_gets_leads[2]["main_company_telephone"];
                        if(!empty($company_logo_3) || !empty($company_profile_url_3))
                        {
                            $company_3_display = "";
                        }
                    }
                    
                    $customFields = [
                        'regionname' => $lead->city,
                        'memberurl' => '',
                        'signupurl' => $lead->signup_url,
                        'service_category' => $data["main_category"],
                        'top_level_category' => $data["top_level_category"],
                        'company_logo_1' => $company_logo_1,
                        'company_profile_url_1' => $company_profile_url_1,
                        'company_logo_2' => $company_logo_2,
                        'company_profile_url_2' => $company_profile_url_2,
                        'company_logo_3' => $company_logo_3,
                        'company_profile_url_3' => $company_profile_url_3,
                        'company_1_display'=> $company_1_display,
                        'company_2_display' => $company_2_display,
                        'company_3_display' => $company_3_display,
                        'company_phone_1' => $company_phone_1,
                        'company_phone_2' => $company_phone_2,
                        'company_phone_3' => $company_phone_3 
                    ];
                    
                    $aweberSubscribeListRequest = [ 
                        "name" => $lead->full_name,
                        "email" => $lead->email, 
                        "custom_fields" => $customFields
                    ];

                    Log::channel('custom_db')->info('Aweber subscribe request', [
                        'data' => $aweberSubscribeListRequest,
                        'key_identifier' =>  $correlationId,
                        'key_identifier_type' => KeyIdentifierType::GeneralLead
                    ]);
                  
                    $listname = $lead_counter > 0 ? $affiliateMainCategory->aweber_member_listname : $affiliateMainCategory->aweber_non_member_listname;
                    Log::channel('custom_db')->info('Aweber listname', [
                        'data' => $listname,
                        'key_identifier' =>  $correlationId,
                        'key_identifier_type' => KeyIdentifierType::GeneralLead
                    ]);
                   
                    $subscribeListResponse = Aweber::SubscribeToList($affiliate->aweber_account_id,
                        $listname,
                        $affiliate->aweber_refresh_token,
                        $aweberSubscribeListRequest);
                    Log::channel('custom_db')->info('Aweber subscribe response', [
                        'data' => $subscribeListResponse,
                        'key_identifier' =>  $correlationId,
                        'key_identifier_type' => KeyIdentifierType::GeneralLead
                    ]);
                }                
            }           

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
                    Log::channel('custom_db')->info('Networx response', [
                        'data' => $networx_response,
                        'key_identifier' =>  $correlationId,
                        'key_identifier_type' => KeyIdentifierType::GeneralLead
                    ]);

                    if ($networx_response['statusCode'] == '200') {
                        $networxRedirectUrl = $networx_response['redirectUrl'];
                        $lead->networx_code = $networx_response['successCode'];
                        $lead->save();                        
                    }
                }
            }            
            
            Log::channel('custom_db')->info('ProcessJob completed', [
                'data' => $data,
                'key_identifier' =>  $correlationId,
                'key_identifier_type' => KeyIdentifierType::GeneralLead
            ]);
        } catch (Exception $e) {
            Log::channel('custom_db')->error('Error processing general request',[
                'data' => $data,
                'exception_message' => $e->getMessage(),
                'exception_trace' => $e->getTraceAsString(),
                'key_identifier' =>  $correlationId,
                'key_identifier_type' => KeyIdentifierType::GeneralLead
            ]);
           
            Log::error("Error processing general lead request. CorrelationId:".$correlationId." Message:".$e->getMessage()."Trace:".$e->getTraceAsString());           
        }
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
}

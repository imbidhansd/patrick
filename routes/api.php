<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group([
    'namespace' => 'api'
], function () {
    Route::group([
        'prefix' => 'find-a-pro'
    ], function () {
        Route::post('/maincategories', 'ApiController@get_maincategories');
        Route::post('/servicecategorytypes', 'ApiController@get_servicecategorytypes');
        Route::post('/servicecategories', 'ApiController@get_servicecategories');
        Route::post('/timeframes', 'ApiController@get_timeframes');
        Route::post('/quick_links/maincategories', 'ApiController@get_maincategories_quick_links');
        Route::post('/quick_links/{type}', 'ApiController@get_quick_links');
        Route::post('/', 'ApiController@find_a_pro');
        Route::post('/submit', 'ApiController@find_a_pro');        
    });

    Route::post('/verify-a-member', 'ApiController@verify_a_member');
    Route::get('/featured_experts', 'ApiController@featured_experts');
    Route::get('/featured_company_count', 'ApiController@featured_company_count');
    Route::get('/additional_pros', 'ApiController@additional_pros');
    Route::get('/additional_contractors', 'ApiController@additional_contractors');
    Route::get('/projects_by_location', 'ApiController@projects_by_location');
    Route::get('/company_by_slug', 'ApiController@get_company_by_slug');
    Route::get('/basic_company_details_by_slug', 'ApiController@get_basic_company_details_by_slug');
    Route::get('/general_services', 'ApiController@get_services_by_sc_code');
    //Affiliate leads
    Route::post('/affiliate/memberlead', 'AffiliateLeadController@ProcessAffiliateMemberRequest'); 
    Route::post('/affiliate/memberleadbyslug', 'AffiliateLeadController@ProcessAffliateGeneralRequestByCompanySlug'); 
    Route::post('/affiliate/generallead', 'AffiliateLeadController@ProcessAffiliateGeneralRequest');  
    Route::post('/affiliate/generalleadv1', 'AffiliateLeadController@ProcessAffiliateGeneralRequestv1');  
    Route::post('/affiliate/generalexternalleadv1', 'AffiliateLeadController@ProcessExternalRequest');  #endpoint to be used by external system like app.unbounce.com
    Route::post('/affiliate/lead', 'AffiliateLeadController@store');    
    Route::post('/populatezipcodes', 'ApiController@populatezipcodes');
    
    // Homeowner Authentication & Management
    Route::post('/homeowners/create', 'HomeownerController@store');
    Route::post('/homeowners/login', 'HomeownerController@login');
    Route::post('/homeowners/send-email-otp', 'HomeownerController@sendEmailOTP');
    Route::post('/homeowners/verify-email-otp', 'HomeownerController@verifyEmailOTP');
    Route::post('/homeowners/send-phone-otp', 'HomeownerController@sendPhoneOTP');
    Route::post('/homeowners/verify-phone-otp', 'HomeownerController@verifyPhoneOTP');
    Route::post('/homeowners/update-profile', 'HomeownerController@updateProfile');
    Route::post('/homeowners/change-password', 'HomeownerController@changePassword');
    Route::post('/homeowners/forgot-password', 'HomeownerController@forgotPassword');
    Route::post('/homeowners/verify-reset-otp', 'HomeownerController@verifyResetOTP');
    Route::post('/homeowners/reset-password', 'HomeownerController@resetPassword');
    
    // Homeowner Service Search
    Route::get('/homeowners/search-services', 'HomeownerController@searchServices');
});

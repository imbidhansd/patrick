<?php

/*
  |--------------------------------------------------------------------------
  | Web Routes
  |--------------------------------------------------------------------------
  |
  | Here is where you can register web routes for your application. These
  | routes are loaded by the RouteServiceProvider within a group which
  | contains the "web" middleware group. Now create something great!
  |
 */
Route::get('/company_who_didnt_get_lead', 'TestEmailController@company_who_didnt_get_lead');
Route::get('/test_ppl_invoice', 'company\TestController@test_ppl_invoice');


Route::get('/email_content_html', 'TestEmailController@email_content_html');
Route::get('/email_content_html_latest', 'TestEmailController@email_content_html_latest');


Route::get('/followup_email_send', 'TestEmailController@followup_email_send');

Route::get('/registered_member_emails', 'TestEmailController@registered_member_emails');


Route::get('/test_followup_email_send', 'TestEmailController@test_follow_up_email');
Route::get('/non_member_email', 'TestEmailController@non_member_email');


Route::get('/admin_registration_email', 'TestEmailController@admin_registration_email');
/* new emails send */
Route::get('/company_custom_email_send', 'TestEmailController@company_custom_email_send');


Route::get('/update-service-category', 'ServiceCategoryController@update_service_category');
Route::get('/networx_api', 'NetworxController@index');

Route::get('/send-lead-admin', 'TestEmailController@send_lead_admin');
//Route::get('/send-broadcast-email', 'TestEmailController@broadcast_mail_send');
Route::get('/send-mail', 'CustomEmailController@send_mail');
Route::get('/email_variables', 'EmailVariableController@email_variables');
Route::get('/followup_variables', 'EmailVariableController@followup_variables');

Route::get('/custom_email_update', 'CustomEmailController@custom_email_update');
Route::get('/custom_emails', 'CustomEmailController@custom_emails');
Route::get('/followup_emails', 'CustomEmailController@followup_emails');

Route::get('/email_list', 'CustomEmailController@email_list');
Route::get('/followup_email_list', 'CustomEmailController@followup_email_list');

Route::get('/custom_email_variable_change', 'CustomEmailController@custom_email_variable_change');
Route::get('/followup_email_variable_change', 'CustomEmailController@followup_email_variable_change');

Route::get('/test-email', 'TestEmailController@test_email_send');
Route::get('/warrenty_email', 'TestEmailController@warrenty_email');


/* Test pdf start */
Route::get('/generate-pdf', 'PDFController@generate_pdf');
Route::get('/generate-test-pdf', 'PDFController@generate_test_pdf');
Route::get('/generate-invoice-pdf', 'PDFController@generate_invoice_pdf');
Route::get('/insurance-pdf', 'PDFController@insurance_pdf');
Route::get('/customer-reference-pdf', 'PDFController@customer_reference_pdf');
Route::get('/customer-required-document-pdf', 'PDFController@customer_required_document_pdf');
/* Test pdf end */






Route::group(['namespace' => 'crons'], function () {
    /* Company leads generation and email start */
    Route::get('/send-leads', 'LeadController@send_leads');
    Route::get('/send-lead-emails', 'LeadController@send_lead_emails');
    /* Company leads generation and email end */

    /* Payment Subscription update start */
    Route::get('/subscription-process-check', 'PaymentController@subscription_process_check');
    /* Payment Subscription update end */

    /* Payment invoice generate start */
    Route::get('/generate-ppl-invoice', 'PaymentController@generate_ppl_invoice');
    Route::get('/check-ppl-invoice', 'PaymentController@check_ppl_invoice');
    /* Payment invoice generate end */

    /* pause leads for ppl memberships */
    Route::get('/pause-leads', 'CompanyLeadStatusController@pauseleads');
    /* start leads for ppl memberships */
    Route::get('/start-leads', 'CompanyLeadStatusController@startleads');

    /* update monthly budget */
    Route::get('/update-monthly-budget', 'UpdateMonthlyBudgetController@updateMonthlyBudget');

    /* Background check process */
    Route::get('/background-check-process', 'BackgroundCheckController@background_check_process');

    /* Send broadcast emails */
    Route::get('/send-broadcast-emails', 'BroadcastEmailController@broadcast_mail_send');

    /* Send followup emails */
    Route::get('/send-followup-emails', 'LeadFollowUpEmailController@index');


    /* Send followup email to non members */
    Route::get('/send-non-member-followup-emails', 'NonMemberFollowUpEmailController@index');

    /* Send followup email to registered members */
    Route::get('/send-registered-member-followup-emails', 'RegisteredMemberFollowUpEmailController@index');
});


/* Find A Pro [Start] */
Route::group(['namespace' => 'find_a_pro', 'prefix' => 'find-a-pro'], function () {
    Route::post('/get-maincategories', 'FindAProController@get_maincategories');
    Route::post('/get-servicecategorytypes', 'FindAProController@get_servicecategorytypes');
    Route::post('/get-servicecategories', 'FindAProController@get_servicecategories');

    Route::post('/get-top-search-main-categories', 'FindAProController@get_top_search_main_categories');

    Route::post('/generate-lead', 'FindAProController@find_a_pro');
    Route::post('/generate-lead-by-recommened-members', 'FindAProController@find_a_pro_recommended_members_submit');
    //Route::get('/', 'FindAProController@index');
    Route::get('/{main_category_slug?}', 'FindAProController@index');
});
/* Find A Pro [End] */

Route::group(['namespace' => 'leads'], function () {
        /* Testing purpose leads form start */
    //Route::get('/', [\App\Http\Controllers\find_a_pro\FindAProController::class, 'index']);
    //Route::get('/', 'find-a-pro/FindAProController@index');
    Route::redirect('/', '/find-a-pro');
    Route::get('/find-a-pro', 'LeadController@find_a_pro');

    Route::post('/get-service-category-type', 'LeadController@get_service_category_types');
    Route::post('/get-top-level-categories', 'LeadController@get_top_level_categories');
    Route::post('/get-category-selection', 'LeadController@get_category_selection');
    Route::post('/get-service-categories', 'LeadController@get_service_categories');
    /* Testing purpose leads form end */

    /* Leads generation and activation start */
    Route::post('/generate-lead', 'LeadController@generate_lead');
    Route::get('/activate-lead/{activation_key}', 'LeadController@activate_lead');
    Route::get('/lead-activated', 'LeadController@lead_activated');
    /* Leads generation and activation end */

    Route::get('/unsubscribe-page/lead/{lead_id}', 'LeadController@lead_unsubscribe');
    Route::post('/lead-unsubscribe-first-step', 'LeadController@lead_unsubscribe_first_step');
    Route::post('/lead-unsubscribe-second-step', 'LeadController@post_lead_unsubscribe');
    Route::get('/leads/unsubscribe-success', 'LeadController@unsubscribe_success');
});


/* [Tazworks API Start ] */
Route::get('/background_check', 'TazworksApiController@background_check');
Route::get('/background_check_status', 'TazworksApiController@background_check_status');
Route::get('/clients', 'TazworksApiController@getClients');
Route::post('/clients/guid', 'TazworksApiController@getClientGuidByName');
Route::post('/clients/products', 'TazworksApiController@getClientProducts');
Route::get('/clients/product', 'TazworksApiController@getClientProductByName');
Route::post('/clients/create_applicant', 'TazworksApiController@createApplicant');
/* [Tazworks API End ] */


/*      [Admin Start]       */

Route::group(['namespace' => 'admin', 'prefix' => 'admin'], function () {
    /* Excel Imoprt [Start] */
    Route::get('/networx_import', 'ExcelImportController@networx_import');
    /* Excel Imoprt [End] */

    // Login & Other Routes
    Route::get('/', 'LoginController@login')->name('admin-login');
    Route::get('/login', 'LoginController@login');
    Route::post('/', 'LoginController@postLogin');
    Route::post('/login', 'LoginController@postLogin');

    // Show 2FA QR-Code Page
    Route::get('/2fa-qr-code/{qr_code_key}', 'CommonController@showQrCode')->name('show-qr-code');
    Route::post('/2fa-qr-code/{qr_code_key}', 'CommonController@postShowQrCode');

    // Admin Middleware [start]
    Route::group(['middleware' => ['App\Http\Middleware\AdminMiddleware']], function () {

        // Logout
        Route::get('/logout', 'LoginController@getLogout');

        Route::group(['middleware' => ['App\Http\Middleware\AdminCommonMiddleware']], function () {
            Route::group(['middleware' => ['2fa']], function () {

            });

            // Dashboard
            Route::get('/dashboard', 'DashboardController@index')->name('dashboard');

            // profile and change password routes
            Route::get('/profile', 'UserController@profile');
            Route::post('/update-profile', 'UserController@updateProfile');

            Route::get('/change-password', 'UserController@changePassword');
            Route::post('/change-password', 'UserController@updatePassword');

            // Common Update Status
            Route::post('/update-status', 'CommonController@updateStatus')->name('update-status');

            // top_level_categories reorder
            Route::post('/top_level_categories/get_options', 'TopLevelCategoryController@getOptions');
            Route::get('/top_level_categories/re-order', 'TopLevelCategoryController@reorder');
            Route::get('/top_level_categories/re-order/{trade_id}', 'TopLevelCategoryController@reorder');
            Route::post('/top_level_categories/re-order', 'TopLevelCategoryController@updateOrder');

            // Main Category
            Route::post('/main_categories/get_options', 'MainCategoryController@getOptions');
            Route::post('/main_categories/get_ppl_price', 'MainCategoryController@getPplPrice');


            // Service Category
            Route::post('/service_categories/get_options', 'ServiceCategoryController@getOptions');
            Route::post('/service_categories/get_service_options', 'ServiceCategoryController@getServiceOptions');

            // main_categories reorder
            Route::get('/main_categories/re-order', 'MainCategoryController@reorder');
            Route::get('/main_categories/re-order/{top_level_category_id}', 'MainCategoryController@reorder');
            Route::post('/main_categories/re-order', 'MainCategoryController@updateOrder');

            // service_categories reorder
            Route::get('/service_categories/re-order', 'ServiceCategoryController@reorder');
            Route::get('/service_categories/re-order/{top_level_category_id}', 'ServiceCategoryController@reorder');
            Route::get('/service_categories/re-order/{top_level_category_id}/{main_category_id}', 'ServiceCategoryController@reorder');
            Route::get('/service_categories/re-order/{top_level_category_id}/{main_category_id}/{service_category_type_id}', 'ServiceCategoryController@reorder');
            Route::post('/service_categories/re-order', 'ServiceCategoryController@updateOrder');

            Route::post('/service_categories/update-service-category-id', 'ServiceCategoryController@update_service_category_id');

            Route::post('/networx_tasks/get-task-detail', 'NetworxTaskController@get_task_detail');
            Route::post('/service_categories/update-networx-details', 'ServiceCategoryController@update_networx_details');


            Route::get('/service_categories/networx_task_list', 'ServiceCategoryController@networx_task_list');

            /* // Service category types reorder
              Route::get('/service_category_types/re-order', 'ServiceCategoryTypeController@reorder');
              Route::get('/service_category_types/re-order/{trade_id}', 'ServiceCategoryTypeController@reorder');
              Route::post('/service_category_types/re-order', 'ServiceCategoryTypeController@updateOrder'); */

            // Site Logo Reorder
            Route::get('/site_logos/re-order', 'SiteLogoController@reorder');
            Route::post('/site_logos/re-order', 'SiteLogoController@updateOrder');

            Route::get('/modules/re-order', 'ModuleController@reorder');
            Route::get('/modules/re-order/{module_category_id}', 'ModuleController@reorder');
            Route::post('/modules/re-order', 'ModuleController@updateOrder');


            /* Professional Affiliations reorder start */
            Route::get('/professional_affiliations/re-order', 'ProfessionalAffiliationController@reorder');
            Route::get('/professional_affiliations/re-order/{trade_id}', 'ProfessionalAffiliationController@reorder');
            Route::post('/professional_affiliations/re-order', 'ProfessionalAffiliationController@updateOrder');
            /* Professional Affiliations reorder end */


            /* Reorder follow up emails start */
            Route::get('/follow_up_emails/re-order', 'FollowUpEmailController@reorder');
            Route::post('/follow_up_emails/re-order', 'FollowUpEmailController@updateOrder');
            /* Reorder follow up emails end */

            /* Reorder artwork start */
            Route::get('/artworks/re-order', 'ArtworkController@reorder');
            Route::get('/artworks/re-order/{artwork_type}', 'ArtworkController@reorder');
            Route::post('/artworks/re-order', 'ArtworkController@updateOrder');
            /* Reorder artwork end */


            // Common reorder
            Route::get('/{table_name}/re-order', 'CommonController@reorder')->name('common-reorder');
            Route::post('/{table_name}/re-order', 'CommonController@updateOrder');

            // Media
            Route::get('/media/resize', 'MediaController@resizeImages');
            Route::post('/media/editorupload', 'MediaController@editorUpload');
            Route::post('/media/delete', 'MediaController@deleteMedia')->name('delete-media');

            // Site Settings
            Route::get('/site-settings', 'SettingController@settings')->name('site-settings');
            Route::post('/site-settings', 'SettingController@updateSettings');

            // Admin Users
            Route::post('/users/change-password', 'UserController@changeUserPassword');
            Route::get('/users/{user}/send-2FA-auth-link', 'UserController@send2faAuthLink')->name('send-2FA-auth-link');
            Route::get('/roles/{role}/permissions', 'RoleController@getPermissions')->name('role-permissions');
            Route::post('/roles/{role}/permissions', 'RoleController@postPermissions');

            // Copy Items
            Route::get('/{url_key}/{id}/copy', 'BasicController@copy')->name('copy-item');

            /* Company Edit form routes start */
            Route::get('/company_users/company/{company_id}', 'CompanyOwnerController@index');
            Route::post('/company_users/change-password', 'CompanyOwnerController@changeUserPassword');

            Route::post('/companies/assign-sales-representative', 'CompanyController@assign_sales_representative');
            Route::post('/companies/update-company-profile', 'CompanyController@update_company_profile');
            Route::post('/companies/update-company-bio', 'CompanyController@update_company_bio');
            Route::post('/companies/update-affiliations', 'CompanyController@update_affiliations');
            Route::post('/companies/update-service-category', 'CompanyController@update_service_category');
            Route::post('/companies/update-service-category-price', 'CompanyController@update_service_category_price');


            Route::post('/companies/zipcode-list-display', 'CompanyController@zipcode_list_display');
            Route::post('/companies/update-company-zipcode-list', 'CompanyController@update_company_zipcode_list');
            Route::post('/companies/update-company-application-leads-notification', 'CompanyController@update_company_application_leads_notifications');

            Route::post('/companies/add-company-note', 'CompanyController@add_company_note');

            Route::post('/companies/get-membership-status-from-level', 'CompanyController@get_membership_status_from_level');

            Route::get('/companies/download-invoice/{invoice_id}', 'CompanyController@download_invoice');
            Route::post('/companies/mark-invoice-paid', 'CompanyController@mark_invoice_paid');
            Route::post('/companies/delete-invoice', 'CompanyController@delete_invoice');
            Route::get('/companies/view-invoice/{invoice_id}', 'CompanyController@view_invoice');

            Route::post('/companies/upload-company-documents', 'CompanyController@upload_company_documents');
            Route::post('/companies/change-company-document-status', 'CompanyController@change_company_document_status');
            Route::post('/companies/remove-company-documents', 'CompanyController@remove_company_documents');
            Route::post('/companies/update-company-document-list', 'CompanyController@update_company_document_list');

            Route::get('/companies/pending-approval', 'CompanyController@pendingApproval');
            Route::get('/companies/paid-pending', 'CompanyController@paidPending');

            Route::post('/companies/change-company-approval-status', 'CompanyController@change_company_approval_status');
            Route::post('/companies/change-company-user-approval-status', 'CompanyController@change_company_user_approval_status');

            Route::get('/companies/company-owners/{company_id}', 'CompanyController@company_owners');
            Route::post('/companies/make-owner-super-admin', 'CompanyController@make_owner_super_admin');

            Route::get('/companies/sign-in-company/{company}', 'CompanyController@signInCompany')->name('sign-in-company');
            Route::post('/companies/upload-company-logo', 'CompanyController@uploadCompanyLogo');

            /* Company Gallery start */
            Route::get('/companies/company-galleries', 'CompanyGalleryController@index');
            Route::get('/companies/manage-gallery-requests/{company_id}', 'CompanyGalleryController@manage_gallery_requests')->name('manage-gallery-requests');
            Route::post('/companies/change-company-gallery-status', 'CompanyGalleryController@change_company_gallery_status');
            /* Company Gallery end */

            /* Company Edit form routes end */

            Route::get('/companies/{membership_level}', 'CompanyController@index')->where('membership_level', '[A-Za-z-]+')->name('company_by_membership_level');

            /* Package create/edit */
            Route::post('/packages/get-company-owner-email', 'PackageController@get_company_owner_email');
            Route::post('/packages/get-top-level-category-list', 'PackageController@getTopLevelCategoryList');
            Route::post('/packages/get-main-category-list', 'PackageController@getMainCategoryList');
            Route::post('/packages/get-service-category-list', 'PackageController@getServiceCategoryList');
            Route::post('/packages/get-rest-category-list', 'PackageController@getRestCategoryList');

            Route::post('/packages/get-fees', 'PackageController@get_fees');
            Route::get('/packages/service-categories/{slug}', 'PackageController@package_service_categories')->name('package-service-categories');
            Route::post('/packages/service-categories', 'PackageController@postServiceCategories')->name('pacakge-service-categories');

            Route::get('/packages/send-email/{package}', 'PackageController@sendPackageEmail')->name('send-package-email');

            /* Product create/edit */
            Route::post('/products/get-category-list', 'ProductController@get_category_list');

            /* Dummy invoice generate */
            Route::get('/generate-invoice', 'InvoiceController@generate_invoice');


            /* Feedback routes start */
            Route::post('/feedback/change_status', 'FeedbackController@change_status');
            /* Feedback routes start end */

            /* Complaints routes start */
            Route::post('/complaints/change_status', 'ComplaintController@change_status');
            Route::get('/complaints/complaint-responses/{complaint_id}', 'ComplaintController@complaint_responses')->name('complaint-responses');
            Route::post('/complaints/add-complaint-response', 'ComplaintController@add_complaint_response');
            /* Complaints routes start end */

            /* Leads routes start */
            Route::post('/leads/get-service-categories', 'LeadController@get_service_categories');
            /* Leads routes end */

            /* Affiliates start */
            Route::get('/affiliates/{affiliate}/configure', 'AffiliateController@configure')->name('affiliates.configure');
            Route::put('/affiliates/{affiliate}/configure', 'AffiliateController@store_configuration')->name('affiliates.configure');
            /* Affiliates end */

            /* Email variables start */
            Route::post('/emails/get-email-variables', 'CommonController@get_email_variables');
            /* Email variables end */


            /* Faqs routes start */
            Route::post('/faqs/get-membership-status-from-level', 'FaqController@get_membership_status_from_level');
            /* Faqs routes end */


            /* Broadcast emails start */
            Route::post('/broadcast_emails/get-top-level-categories', 'BroadcastEmailController@get_top_level_categories');
            Route::post('/broadcast_emails/get-main-categories', 'BroadcastEmailController@get_main_categories');
            Route::post('/broadcast_emails/get-service-categories', 'BroadcastEmailController@get_service_categories');
            /* Broadcast emails end */


            /* Leads start */
            Route::get('/leads/open-disputes', 'LeadController@open_disputes');
            Route::get('/leads/closed-disputes', 'LeadController@closed_disputes');
            Route::post('/leads/change-dispute-status', 'LeadController@change_dispute_status');
            Route::get('/leads/get-logs', 'LeadController@get_logs');
            /* Leads end */

            /* company priority start */
            Route::get('/company_priority', 'CompanyPriorityController@index');
            Route::post('/company_priority', 'CompanyPriorityController@search');
            /* company priority end */


            /* Membership Level status update */
            Route::post('/membership_level_statuses/update-status', 'MembershipLevelStatusController@updateStatus');


            /* get default email header/footer templates start */
            Route::post('/default_email_header_footers/get_header_template', 'DefaultHeaderFooterController@get_header_template');
            Route::post('/default_email_header_footers/get_footer_template', 'DefaultHeaderFooterController@get_footer_template');
            /* get default email header/footer templates end */


            /* Manage Subscribers start */
            Route::get('/manage_subscribers', 'LeadSubscriberController@index');
            Route::post('/manage_subscribers/update-basic-info', 'LeadSubscriberController@update_basic_info');
            Route::post('/manage_subscribers/send_confirmation_email', 'LeadSubscriberController@send_confirmation_email');
            Route::post('/manage_subscribers/send_followup_email', 'LeadSubscriberController@send_followup_email');
            /* Manage Subscribers end */


            Route::post('/send_test_emails', 'TestEmailSendController@send_test_emails');
            Route::put('non_members/import', 'NonMemberController@import');
            Route::put('companies/import/all-registered-members', 'CompanyController@import_registered_members');
            Route::put('companies/import/all-official-members', 'CompanyController@import_official_members');
            // All Resources
            Route::resources([
                // Modules
                'module_categories' => 'BasicController',
                'modules' => 'ModuleController',
                //'states' => 'BasicController',
                'media' => 'MediaController',
                //site content
                'pages' => 'BasicController',
                'post_categories' => 'BasicController',
                'posts' => 'PostController',
                'testimonials' => 'TestimonialController',
                'faqs' => 'FaqController',
                'company_faq_questions' => 'CompanyFaqQuestionController',
                'news' => 'NewsController',
                'default_emails' => 'DefaultEmailController',
                'default_email_header_footers' => 'DefaultHeaderFooterController',
                'follow_up_header_footer_templates' => 'FollowUpHeaderFooterController',
                'emails' => 'EmailController',
                'follow_up_mail_categories' => 'FollowUpEmailCategoryController',
                'follow_up_emails' => 'FollowUpEmailController',
                'non_member_emails' => 'NonMemberEmailController',
                'registered_member_emails' => 'RegisteredMemberEmailController',
                'new_emails' => 'NewEmailController',
                'broadcast_emails' => 'BroadcastEmailController',
                'mail_templates' => 'MailTemplateController',
                'networx_tasks' => 'NetworxTaskController',
                // non members
                'non_members' => 'NonMemberController',
                //companies
                'companies' => 'CompanyController',
                'company_users' => 'CompanyOwnerController',
                'site_logos' => 'SiteLogoController',
                'artworks' => 'ArtworkController',
                'partner_links' => 'PartnerLinkController',
                // lead categories
                'trades' => 'BasicController',
                'top_level_categories' => 'TopLevelCategoryController',
                'main_categories' => 'MainCategoryController',
                //'service_category_types' => 'ServiceCategoryTypeController',
                'service_category_types' => 'BasicController',
                'service_categories' => 'ServiceCategoryController',
                // Leads
                'leads' => 'LeadController',
                // Users
                'roles' => 'RoleController',
                'users' => 'UserController',
                // feedback & complaints
                'feedback' => 'FeedbackController',
                'complaints' => 'ComplaintController',
                //video podcasts
                'video_podcasts' => 'VideoPodcastController',
                // shopping carts
                'packages' => 'PackageController',
                'products' => 'ProductController',
                'membership_levels' => 'MembershipLevelController',
                'membership_statuses' => 'BasicController',
                'membership_level_statuses' => 'MembershipLevelStatusController',
                'pre_screen_settings' => 'PreScreenSettingController',
                'professional_affiliations' => 'ProfessionalAffiliationController',
                // settings
                'settings' => 'BasicController',
                'company_charge_settings' => 'BasicController',
                //
                'membership_types' => 'BasicController',
                'coupon_types' => 'BasicController',
                'coupons' => 'CouponController',
                'affiliates' => 'AffiliateController',
                'oauth/redirect' => 'OAuthController',
                'verify_members' => 'VerifyMemberController'
            ]);

            Route::get('import-data', 'ImportController@import');
             Route::get('/secure-file/{path}', [App\Http\Controllers\Admin\SecureFileController::class, 'show'])
            ->where('path', '.*')
            ->name('secure.file');
        });
        // Admin Common Middleware [end]
    });
    // Admin Middleware [end]
});

/*      [Admin End]     */



/*      [Front Start]       */

Route::group(['namespace' => 'company'], function () {
    Route::get('/check-company', 'CompanyController@checkCompany');

    Route::get('/test', 'TestController@index');
    Route::get('/test-shot', 'TestController@shot');
    Route::get('/subscription', 'TestController@subscription');
    Route::get('/check-subscription', 'TestController@check_subscription');
    Route::get('/get-subscription', 'TestController@get_subscription');
    Route::get('/cancel-subscription', 'TestController@cancel_subscription');
    Route::get('/zipcode-api-check', 'TestController@zipcode_api_check');

    Route::get('/lead', 'TestController@lead');
    Route::get('/lead_view', 'TestController@lead_view');

    Route::get('/insert_zipcodes', 'TestController@insert_zipcodes');


    /* Get Listed (Non Members) start */
    Route::get('/get-listed', 'NonMemberController@index');
    Route::post('/get-listed/get-top-level-categories', 'NonMemberController@get_top_level_categories');
    Route::post('/get-listed/get-main-categories', 'NonMemberController@get_main_categories');
    Route::post('/get-listed', 'NonMemberController@postGetListed');
    Route::get('/get-listed/activation/{activation_key}', 'NonMemberController@activateAccount');


    Route::get('/get-listed/unsubscribe-page/{company_id}', 'NonMemberController@company_unsubscribe');
    Route::post('/get-listed/unsubscribe-page/post-unsubscribe', 'NonMemberController@post_company_unsubscribe');
    Route::get('/get-listed/unsubscribe-success', 'NonMemberController@company_unsubscribe_success');
    /* Get Listed (Non Members) end */




    /* Register Company [Start] */

    Route::post('/check-available-email', 'RegisterController@checkAvailableEmail');
    Route::post('/check-available-username', 'RegisterController@checkAvailableUsername');

    Route::post('/get-top-level-category-list', 'RegisterController@getTopLevelCategoryList');
    Route::post('/get-main-category-list', 'RegisterController@getMainCategoryList');
    Route::post('/get-rest-category-list', 'RegisterController@getRestCategoryList');
    Route::post('/get-service-category-list', 'RegisterController@getServiceCategoryList');

    Route::get('/preview-trial', 'RegisterController@index');
    Route::get('/accreditation', 'RegisterController@accreditation');
    Route::get('/full-listing', 'RegisterController@full_listing');
    Route::post('/phone-in-lead/get-maincategories', 'RegisterController@get_maincategories');
    Route::post('/phone-in-lead/get-servicecategories', 'RegisterController@get_servicecategories');
    Route::get('/phone-in-lead', 'RegisterController@get_phone_in_lead');
    Route::post('/phone-in-lead', 'RegisterController@post_phone_in_lead');


    Route::post('/register/step1', 'RegisterController@postStep1');
    Route::post('/register/step2', 'RegisterController@postStep2');
    Route::post('/register/step3', 'RegisterController@postStep3');
    Route::post('/register/step4', 'RegisterController@postStep4');
    Route::post('/register/step5', 'RegisterController@postStep5');
    Route::post('/register/step6', 'RegisterController@postStep6');
    Route::post('/register/step7', 'RegisterController@postStep7');

    Route::post('/free-preview-trial', 'RegisterController@postFreePreviewTrial');
    Route::get('/thankyou', 'RegisterController@thankyou');

    /* Register Company [End] */

    // Login
    //Route::get('/', 'LoginController@getLogin');
    Route::get('/login', 'LoginController@getLogin');
    Route::post('/login', 'LoginController@postLogin');
    Route::get('/logout', 'LoginController@getLogout');

    Route::post('/resend-activation-link/{company_id}', 'LoginController@resendActivationLink')->name('resend-activation-link');

    Route::get('/forgot-password', 'LoginController@forgot_password');
    Route::post('/forgot-password', 'LoginController@postForgotPassword');

    Route::get('/reset-password/{forgot_password_key}', 'LoginController@reset_password')->name('reset-password');
    Route::post('/reset-password', 'LoginController@post_reset_password');

    Route::get('/forgot-username', 'LoginController@forgot_username');
    Route::post('/forgot-username', 'LoginController@postForgotUsername');

    // Activate Company Account
    Route::get('/activate/{activation_key}', 'RegisterController@activateAccount')->name('company-activation-link');
    Route::get('/activation-complete', 'RegisterController@activationComplete');

    // Company Owner Invitation
    Route::get('register/company-owner/{invitation_key}', 'CompanyOwnerController@register_other_owner');
    Route::post('register/company-owner/{invitation_key}', 'CompanyOwnerController@postRegister');



    // Company Middleware [start]
    Route::group(['middleware' => ['App\Http\Middleware\CompanyMiddleware']], function () {
        Route::group(['middleware' => ['App\Http\Middleware\CompanyCommonMiddleware']], function () {
            Route::get('/dashboard', 'DashboardController@index')->name('company-dashboard');
            Route::post('/dismiss-dashboard-video', 'DashboardController@dismiss_dashboard_video');

            Route::get('/faq', 'PageController@faq');
            Route::post('/submit-faq', 'PageController@submit_faq')->name('submit-faq');

            Route::get('company-owners', 'CompanyOwnerController@index');
            Route::post('invite-company-owner', 'CompanyOwnerController@invite');

            Route::post('/account/upgrade/promotional-code', 'UpgradeController@accountUpgradePromocode');

            Route::get('/referral-list/full-listing-more', 'UpgradeController@full_listing_more');
            Route::get('/referral-list/credibility', 'UpgradeController@credibility');

            Route::get('/referral-list/application-process', 'UpgradeController@index');
            Route::post('/account/upgrade/terms-check', 'UpgradeController@terms_check');
            Route::get('/account/upgrade', 'UpgradeController@step1');
            Route::post('/account/upgrade/step1', 'UpgradeController@postStep1');
            Route::post('/account/upgrade/step2', 'UpgradeController@postStep2');
            Route::post('/account/upgrade/step3', 'UpgradeController@postStep3');
            Route::post('/account/upgrade/step4', 'UpgradeController@postStep4');
            Route::post('/account/upgrade/step5', 'UpgradeController@postStep5');
            Route::post('/account/upgrade/step6', 'UpgradeController@postStep6');
            Route::post('/account/upgrade/step7', 'UpgradeController@postStep7');

            Route::get('/account/upgrade/review', 'UpgradeController@accountUpgradeReview')->name('account-upgrade-review');
            Route::post('/account/upgrade/review', 'UpgradeController@postAccountUpgradeReview');

            Route::post('/remove-category-from-cart', 'UpgradeController@remove_category_from_cart');
            Route::post('/update-cart', 'UpgradeController@update_cart');
            Route::post('/update-review', 'UpgradeController@update_review');

            Route::get('/account/upgrade/suggested-products', 'UpgradeController@suggested_products');
            Route::get('/account/upgrade/checkout', 'UpgradeController@checkout');
            Route::post('/account/upgrade/checkout', 'UpgradeController@postCheckout');
            Route::get('/account/upgrade/payment/success', 'UpgradeController@payment_success');
            Route::get('/account/upgrade/payment/checkout-success', 'UpgradeController@checkout_success')->name('checkout-success');
            Route::get('/account/upgrade/payment/checkout-cancel', 'UpgradeController@checkout_cancel')->name('checkout-cancel');

            Route::get('/account/application', 'CompanyApplicationController@index');
            Route::post('/account/application/company-information', 'CompanyApplicationController@postCompanyInformation');
            Route::post('/account/application/company-licensing', 'CompanyApplicationController@postCompanyLicensing');
            Route::post('/account/application/company-insurance', 'CompanyApplicationController@postCompanyInsurance');
            Route::post('/account/application/customer-references', 'CompanyApplicationController@postCustomerReferences');
            Route::post('/account/application/lead-notifications', 'CompanyApplicationController@postLeadNotification');
            Route::post('/account/application/listing-agreement', 'CompanyApplicationController@postListingAgreement');

            Route::post('/account/application', 'CompanyController@postAccountApplication');


            Route::post('/account/application/insurance-mark-as-completed', 'CompanyInsuranceController@mark_insurance_completed');
            Route::get('/account/application/liability-insurance-download', 'CompanyInsuranceController@liability_insurance_download');
            Route::get('/account/application/liability-insurance-view', 'CompanyInsuranceController@liability_insurance_view');
            Route::get('/account/application/worker-compensation-insurance-download', 'CompanyInsuranceController@worker_compensation_insurance_download');
            Route::get('/account/application/worker-compensation-insurance-view', 'CompanyInsuranceController@worker_compensation_insurance_view');
            Route::get('/account/application/customer-references-download', 'CompanyApplicationController@customer_references_download');


            Route::get('/profile', 'CompanyController@profile');
            Route::post('/update-profile', 'CompanyController@updateProfile');

            Route::get('/company-profile', 'CompanyController@company_profile');
            Route::post('/update-company-profile', 'CompanyController@update_company_profile');
            Route::post('/update-affiliations', 'CompanyController@update_affiliations');

            /* Billing */
            Route::get('/billing', 'CompanyInvoiceController@billing');
            Route::get('/billing/download-invoice/{invoice_id}', 'CompanyInvoiceController@download_invoice');
            Route::get('/billing/invoice-payment/{invoice_id}', 'CompanyInvoiceController@invoice_payment');
            Route::post('/billing/invoice-payment', 'CompanyInvoiceController@postInvoicePayment');
            Route::get('/billing/invoice-payment/{invoice_id}/checkout-success', 'CompanyInvoiceController@checkout_success')->name('checkout-success');
            Route::get('/billing/invoice-payment/{invoice_id}/checkout-cancel', 'CompanyInvoiceController@checkout_cancel')->name('checkout-cancel');
            Route::get('/billing/view-invoice/{invoice_id}', 'CompanyInvoiceController@view_invoice');
            Route::post('/billing/cancel-subscription', 'CompanyInvoiceController@cancel_subscription');

            /* Lead Management */
            Route::get('/lead-management', 'CompanyController@lead_management');
            Route::post('/update-company-application-leads-notification', 'CompanyController@update_company_application_leads_notifications');

            /* Service Categories */
            Route::get('/service-category', 'CompanyController@service_categories');
            Route::post('/update-service-category', 'CompanyController@update_service_category');

            /* Zipcodes */
            Route::get('/zip-codes', 'CompanyController@zip_codes');
            Route::post('/zipcode-list-display', 'CompanyController@zipcode_list_display');
            Route::post('/update-company-zipcode-list', 'CompanyController@update_company_zipcode_list');

            /* Company Documents */
            Route::get('/company-documents', 'CompanyDocumentController@company_documents');
            // Upload Company Document
            Route::post('/upload-company-document', 'CompanyController@uploadCompanyDocument');


            /* Company Galler routes start */
            Route::get('/company_galleries/re-order', 'CompanyGalleryController@reorder');
            Route::post('/company_galleries/re-order', 'CompanyGalleryController@updateOrder');

            Route::post('/company_galleries/update-status', 'CompanyGalleryController@update_status');
            Route::resource('/company_galleries', 'CompanyGalleryController');
            /* Company Galler routes end */

            Route::get('/change-password', 'CompanyController@changePassword');
            Route::post('/change-password', 'CompanyController@postChangePassword');

            /* Feedback & Complaints */
            Route::get('/feedback', 'ComplaintController@feedback');
            Route::get('/feedback/complaint-responses/{complaint_id}', 'ComplaintController@complaint_responses');
            Route::post('/feedback/complaints/add-complaint-response', 'ComplaintController@add_complaint_response');

            /* Company Message routes start */
            Route::post('/company_messages/update-status', 'CompanyMessageController@update_status');
            Route::resource('/company_messages', 'CompanyMessageController');
            /* Company Message routes end */


            /* Company Leads start */
            Route::get('/leads-archive-inbox', 'CompanyLeadController@index');
            Route::post('/update-monthly-budget', 'CompanyLeadController@update_monthly_budget');
            Route::post('/leads-archive-inbox/generate-lead-dispute', 'CompanyLeadController@generate_lead_dispute');
            Route::post('/leads-archive-inbox/cancel-lead-dispute', 'CompanyLeadController@cancel_lead_dispute');
            Route::post('/leads-archive-inbox/delete-lead/{company_lead_id}', 'CompanyLeadController@delete_lead');
            Route::post('/leads-archive-inbox/mark-lead-as-read', 'CompanyLeadController@mark_lead_as_read');
            /* Company Leads end */

            Route::post('/update-company-subscription', 'CompanyController@update_company_subscription');
            Route::post('/update-company-lead-status', 'CompanyController@update_company_lead_status');


            Route::get('/background-check', 'PreScreenController@pre_screen_questions');
            Route::post('/background-check/step1', 'PreScreenController@postStep1')->name('post-background-check-step1');
            Route::post('/background-check/step2', 'PreScreenController@postStep2')->name('post-background-check-step2');
            Route::post('/background-check/step3', 'PreScreenController@postStep3')->name('post-background-check-step3');
            Route::post('/background-check/step4', 'PreScreenController@postStep4')->name('post-background-check-step4');
            Route::post('/background-check/step5', 'PreScreenController@postStep5')->name('post-background-check-step5');
            Route::post('/background-check/step6', 'PreScreenController@postStep6')->name('post-background-check-step6');
            Route::post('/background-check', 'PreScreenController@postPreScreenQuestions'); // Backup Line

            /* Members Resources Start */
            Route::get('/member-resources', 'CompanyPageController@member_resources');
            Route::get('/social-media-artwork', 'CompanyPageController@social_media_artwork');
            Route::get('/print-ready-artwork', 'CompanyPageController@print_ready_artwork');
            Route::post('/member-resources/set-company-logo-banner', 'CompanyPageController@set_company_logo_banner');

            /* Members Resources End  */

            /* Partner Links Start */
            Route::get('/partner-links', 'CompanyPageController@partner_links');
            /* Partner Links End */

            Route::get('/news', 'NewsController@index');

            Route::get('/contact-us', 'CompanyController@contact_us');
            Route::get('/secure-file/{path}', [App\Http\Controllers\company\SecureFileController::class, 'show'])
            ->where('path', '.*')
            ->name('secure.file.company');
        });
    });


    /* Company unsubscription form start */
    Route::get('/unsubscribe-page/company/{company_slug}', 'CompanyController@company_unsubscribe');
    Route::post('/unsubscribe-page/company/post-unsubscribe', 'CompanyController@post_company_unsubscribe');
    Route::get('/unsubscribe-success', 'CompanyController@company_unsubscribe_success');
    /* Company unsubscription form end */


    /* Company Profile Page start */
    Route::get('/{company_slug}', 'CompanyProfileController@index')->name('company-page');
    Route::post('/company-profile-page/check-company-zipcode', 'CompanyProfileController@check_company_zipcode');
    Route::post('/company-profile-page/get-main-categories', 'CompanyProfileController@get_main_categories');
    Route::post('/company-profile-page/get-service-categories', 'CompanyProfileController@get_service_categories');
    Route::post('/company-profile-page/generate-lead', 'CompanyProfileController@generate_lead');
    Route::post('/company-profile-page/submit-review', 'CompanyProfileController@submit_review');
    Route::post('/company-profile-page/submit-complaint', 'CompanyProfileController@submit_complaint');
    Route::post('/company-profile/view-contact-information-session', 'CompanyProfileController@view_contact_information_session');

    Route::post('/company-profile/upload-company-logo', 'CompanyController@uploadCompanyLogo')->name('upload-company-logo');


    Route::get('/review/{company_slug}', 'CompanyProfileController@reviews');
    Route::get('/complaints/{company_slug}', 'CompanyProfileController@complaints');

    /* Confirm review user */
    Route::get('/confirm-review/success', 'CompanyProfileController@confirm_review_success');
    Route::get('/confirm-review/error', 'CompanyProfileController@confirm_review_success');
    Route::get('/confirm-review/{activation_key}', 'CompanyProfileController@confirm_review');

    /* Company Profile Page end */
});

/*      [Front End]       */


/* 2FA [post - Start] */
Route::post('/2fa', function () {
    // check for admin or siteuser
    //$user = Auth::user();
    return redirect(URL()->previous());
})->name('2fa')->middleware('2fa');
/* 2FA [post - End] */

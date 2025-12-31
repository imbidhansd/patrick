<?php

return [
    /* Company Status change email to Admin */
    'Company Status Change - Admin' => [
        'company_name' => '{{ COMPANY_NAME }}',
        'company_status' => '{{ COMPANY_STATUS }}',
    ],
    'Monthly Listing Company Status Change - Admin' => [
        'company_name' => '{{ COMPANY_NAME }}',
    ],
    'PPL Company Status Change - Admin' => [
        'company_name' => '{{ COMPANY_NAME }}',
    ],
    /* Registration Emails */
    'Register Confirmation Email' => [
        'company_name' => '{{ COMPANY_NAME }}',
        'account_type' => '{{ ACCOUNT_TYPE }}',
        'confirmation_link' => '{{ CONFIRMATION_LINK }}',
        'global_address' => '{{ GLOBAL_ADDRESS }}',
    ],
    'Register Confirmation Success Email' => [
        //'company_name' => '{{ COMPANY_NAME }}',
        'first_name' => '{{ FIRST_NAME }}',
        'account_type' => '{{ ACCOUNT_TYPE }}',
        'user_name' => '{{ USER_NAME }}',
        'user_email' => '{{ USER_EMAIL }}',
        'login-link' => '{{ LOGIN_LINK }}',
        'global_address' => '{{ GLOBAL_ADDRESS }}',
    ],
    'Register Success Email - Admin' => [
        'account_type' => '{{ ACCOUNT_TYPE }}',
        'company_name' => '{{ COMPANY_NAME }}',
        'first_name' => '{{ FIRST_NAME }}',
        'last_name' => '{{ LAST_NAME }}',
        'company_phone' => '{{ COMPANY_PHONE }}',
        'company_user_email' => '{{ COMPANY_USER_EMAIL }}',
        'company_address' => '{{ COMPANY_ADDRESS }}',
        'state' => '{{ STATE }}',
        'zipcode' => '{{ ZIPCODE }}',
        'trade' => '{{ TRADE }}',
        'top_level_category' => '{{ TOP_LEVEL_CATEGORY }}',
        'service_categories' => '{{ SERVICE_CATEGORIES }}',
        'region' => '{{ REGION }}',
    ],
    'Activate Company Registration Email - Admin' => [
        'company_name' => '{{ COMPANY_NAME }}',
        'account_type' => '{{ ACCOUNT_TYPE }}',
    ],
    'Forgot Password Email' => [
        'first_name' => '{{ FIRST_NAME }}',
        'last_name' => '{{ LAST_NAME }}',
        'username' => '{{ USERNAME }}',
        'email' => '{{ EMAIL }}',
        'change_password_link' => '{{ CHANGE_PASSWORD_LINK }}',
        'login-link' => '{{ LOGIN_LINK }}',
        'global_address' => '{{ GLOBAL_ADDRESS }}',
    ],
    'Company User password changed' => [
        'first_name' => '{{ FIRST_NAME }}',
        'last_name' => '{{ LAST_NAME }}',
        'global_address' => '{{ GLOBAL_ADDRESS }}',
    ],
    /* Company Upgrade Process Emails */
    'Company Upgrade Check Payment Email' => [
        'company_name' => '{{ COMPANY_NAME }}',
        'submit_application_link' => '{{ SUBMIT_APPLICATION_LINK }}',
        'phone_number' => '{{ PHONE_NUMBER }}',
        'global_domain' => '{{ GLOBAL_DOMAIN }}',
        'global_address' => '{{ GLOBAL_ADDRESS }}',
    ],
    'Company Upgrade Check Payment Email - Admin' => [
        'company_name' => '{{ COMPANY_NAME }}',
        'account_type' => '{{ ACCOUNT_TYPE }}',
    ],
    'Company Upgrade Credit Card Payment Email' => [
        'company_name' => '{{ COMPANY_NAME }}',
        'submit_application_link' => '{{ SUBMIT_APPLICATION_LINK }}',
        'phone_number' => '{{ PHONE_NUMBER }}',
        'global_domain' => '{{ GLOBAL_DOMAIN }}',
        'global_address' => '{{ GLOBAL_ADDRESS }}',
    ],
    'Company Upgrade Credit Card Payment Email - Admin' => [
        'company_name' => '{{ COMPANY_NAME }}',
        'account_type' => '{{ ACCOUNT_TYPE }}',
    ],
    /* Company Application Process Emails */
    'Company Online Application Submitted' => [
        'company_name' => '{{ COMPANY_NAME }}',
        'dashboard_link' => '{{ DASHBOARD_LINK }}',
        'phone_number' => '{{ PHONE_NUMBER }}',
        'global_address' => '{{ GLOBAL_ADDRESS }}',
    ],
    'Company Online Application Submitted - Admin' => [
        'company_name' => '{{ COMPANY_NAME }}',
    ],
    'Company User Bio Received' => [
        'first_name' => '{{ FIRST_NAME }}',
        'last_name' => '{{ LAST_NAME }}',
        'global_address' => '{{ GLOBAL_ADDRESS }}',
    ],
    'Company User Profile Picture Received' => [
        'first_name' => '{{ FIRST_NAME }}',
        'last_name' => '{{ LAST_NAME }}',
        'global_address' => '{{ GLOBAL_ADDRESS }}',
    ],
    'Company User Bio Uploaded - Admin' => [
        'first_name' => '{{ FIRST_NAME }}',
        'last_name' => '{{ LAST_NAME }}',
        'profile_url' => '{{ MEMBER_PROFILE_URL }}',
    ],
    'Company User Profile Picture Uploaded - Admin' => [
        'first_name' => '{{ FIRST_NAME }}',
        'last_name' => '{{ LAST_NAME }}',
        'profile_url' => '{{ MEMBER_PROFILE_URL }}',
    ],
    'Company Bio Received' => [
        'company_name' => '{{ COMPANY_NAME }}',
        'global_address' => '{{ GLOBAL_ADDRESS }}',
    ],
    'Company Bio Uploaded - Admin' => [
        'company_name' => '{{ COMPANY_NAME }}',
        'profile_url' => '{{ MEMBER_PROFILE_URL }}',
    ],
    'Company Logo received' => [
        'company_name' => '{{ COMPANY_NAME }}',
        'phone_number' => '{{ PHONE_NUMBER }}',
        'global_domain' => '{{ GLOBAL_DOMAIN }}',
        'global_address' => '{{ GLOBAL_ADDRESS }}',
    ],
    'Company Logo Uploaded - Admin' => [
        'company_name' => '{{ COMPANY_NAME }}',
        'profile_url' => '{{ MEMBER_PROFILE_URL }}',
    ],
    'Company Document Received' => [
        'company_name' => '{{ COMPANY_NAME }}',
        'document_type' => '{{ DOCUMENT_TYPE }}',
        'global_address' => '{{ GLOBAL_ADDRESS }}',
    ],
    'Company Document Uploaded - Admin' => [
        'company_name' => '{{ COMPANY_NAME }}',
        'document_type' => '{{ DOCUMENT_TYPE }}',
        'profile_url' => '{{ MEMBER_PROFILE_URL }}',
    ],
    'Company Document Rejected' => [
        'company_name' => '{{ COMPANY_NAME }}',
        'document_type' => '{{ DOCUMENT_TYPE }}',
        'reject_reason' => '{{ REJECTED_REASON }}',
        'phone_number' => '{{ PHONE_NUMBER }}',
        'global_domain' => '{{ GLOBAL_DOMAIN }}',
        'global_address' => '{{ GLOBAL_ADDRESS }}',
    ],
    'Company Document Accepted' => [
        'company_name' => '{{ COMPANY_NAME }}',
        'document_type' => '{{ DOCUMENT_TYPE }}',
        'global_address' => '{{ GLOBAL_ADDRESS }}',
    ],
    'Company Document Removed' => [
        'company_name' => '{{ COMPANY_NAME }}',
        'document_type' => '{{ DOCUMENT_TYPE }}',
        'phone_number' => '{{ PHONE_NUMBER }}',
        'global_domain' => '{{ GLOBAL_DOMAIN }}',
        'global_address' => '{{ GLOBAL_ADDRESS }}',
    ],
    'Company Status Change - Final Review' => [
        'company_name' => '{{ COMPANY_NAME }}',
        'global_address' => '{{ GLOBAL_ADDRESS }}',
    ],
    'Company Status Change - Approved' => [
        'company_name' => '{{ COMPANY_NAME }}',
        'global_address' => '{{ GLOBAL_ADDRESS }}',
    ],
    'Company Approved Check Payment Email' => [
        'company_name' => '{{ COMPANY_NAME }}',
        'phone_number' => '{{ PHONE_NUMBER }}',
        'global_domain' => '{{ GLOBAL_DOMAIN }}',
        'global_address' => '{{ GLOBAL_ADDRESS }}',
    ],
    'Company Approved Check Payment Email - Admin' => [
        'company_name' => '{{ COMPANY_NAME }}',
    ],
    'Company Approved Credit Card Payment Email' => [
        'company_name' => '{{ COMPANY_NAME }}',
        'phone_number' => '{{ PHONE_NUMBER }}',
        'global_domain' => '{{ GLOBAL_DOMAIN }}',
        'global_address' => '{{ GLOBAL_ADDRESS }}',
    ],
    'Company Approved Credit Card Payment Email - Admin' => [
        'company_name' => '{{ COMPANY_NAME }}',
    ],
    'Company Membership Activated' => [
        'company_name' => '{{ COMPANY_NAME }}',
        'account_type' => '{{ ACCOUNT_TYPE }}',
        'phone_number' => '{{ PHONE_NUMBER }}',
        'global_address' => '{{ GLOBAL_ADDRESS }}',
    ],
    'Company Membership Activated - Admin' => [
        'company_name' => '{{ COMPANY_NAME }}',
    ],
    /* Company Lead paused Emails */
    'Company Lead Paused' => [
        'company_name' => '{{ COMPANY_NAME }}',
        'account_type' => '{{ ACCOUNT_TYPE }}',
        'lead_pause_date' => '{{ LEAD_PAUSE_DATE }}',
        'lead_resume_date' => '{{ LEAD_RESUME_DATE }}',
        'phone_number' => '{{ PHONE_NUMBER }}',
        //'global_domain' => '{{ GLOBAL_DOMAIN }}',
        'global_address' => '{{ GLOBAL_ADDRESS }}',
    ],
    'Company Lead Paused - Admin' => [
        'account_type' => '{{ ACCOUNT_TYPE }}',
        'company_name' => '{{ COMPANY_NAME }}',
        'lead_pause_date' => '{{ LEAD_PAUSE_DATE }}',
        'lead_resume_date' => '{{ LEAD_RESUME_DATE }}',
        'internal_contact_name' => '{{ INTERNAL_CONTACT_NAME }}',
        'internal_contact_phone' => '{{ INTERNAL_CONTACT_PHONE }}',
        'main_company_telephone' => '{{ MAIN_COMPANY_TELEPHONE }}',
        'address' => '{{ ADDRESS }}',
        'city' => '{{ CITY }}',
        'state' => '{{ STATE }}',
        'zipcode' => '{{ ZIPCODE }}',
        'main_service_category' => '{{ MAIN_SERVICE_CATEGORY }}',
        'service_category' => '{{ SERVICE_CATEGORY }}',
    ],
    'Company Lead Unpaused - Admin' => [
        'company_name' => '{{ COMPANY_NAME }}',
    ],
    'Company Lead Unpaused' => [
        'company_name' => '{{ COMPANY_NAME }}',
        'account_type' => '{{ ACCOUNT_TYPE }}',
        'phone_number' => '{{ PHONE_NUMBER }}',
        //'global_domain' => '{{ GLOBAL_DOMAIN }}',
        'global_address' => '{{ GLOBAL_ADDRESS }}',
    ],
    /* Company Subscription Status Emails */
    'Company Subscription Status Change' => [
        'company_name' => '{{ COMPANY_NAME }}',
        'subscription_status' => '{{ SUBSCRIPTION_STATUS }}',
        'phone_number' => '{{ PHONE_NUMBER }}',
        'global_address' => '{{ GLOBAL_ADDRESS }}',
    ],
    'Company Subscription Status Change - Admin' => [
        'company_name' => '{{ COMPANY_NAME }}',
        'subscription_status' => '{{ SUBSCRIPTION_STATUS }}',
    ],
    /* Company FAQ Emails */
    'Company FAQ Question Created' => [
        'company_name' => '{{ COMPANY_NAME }}',
        'faq_question' => '{{ FAQ_QUESTION }}',
        'faq_question_description' => '{{ FAQ_QUESTION_DESCRIPTION }}',
        'phone_number' => '{{ PHONE_NUMBER }}',
        'global_address' => '{{ GLOBAL_ADDRESS }}',
    ],
    'Company FAQ Question Created - Admin' => [
        'company_name' => '{{ COMPANY_NAME }}',
        'faq_question' => '{{ FAQ_QUESTION }}',
        'faq_question_description' => '{{ FAQ_QUESTION_DESCRIPTION }}',
    ],
    /* Company Feedback/Complaint Emails */
    'Company Feedback Created' => [
        'company_name' => '{{ COMPANY_NAME }}',
        'global_domain' => '{{ GLOBAL_DOMAIN }}',
        'view_link' => '{{ VIEW_LINK }}',
        'phone_number' => '{{ PHONE_NUMBER }}',
        'global_address' => '{{ GLOBAL_ADDRESS }}',
    ],
    'Company Feedback Created - Consumer' => [
        'customer_name' => '{{ CUSTOMER_NAME }}',
        'company_name' => '{{ COMPANY_NAME }}',
        'confirmation_link' => '{{ CONFIRMATION_LINK }}',
        'global_address' => '{{ GLOBAL_ADDRESS }}',
    ],
    'Company Feedback Created - Admin' => [
        'company_name' => '{{ COMPANY_NAME }}',
        'feedback_number' => '{{ FEEDBACK_NUMBER }}',
        'customer_name' => '{{ CUSTOMER_NAME }}',
        'customer_phone' => '{{ CUSTOMER_PHONE }}',
        'customer_email' => '{{ CUSTOMER_EMAIL }}',
        'rating' => '{{ RATING }}',
        'review' => '{{ REVIEW }}',
    ],
    'Company Feedback Status Change' => [
        'company_name' => '{{ COMPANY_NAME }}',
        'feedback_number' => '{{ FEEDBACK_NUMBER }}',
        'feedback_status' => '{{ FEEDBACK_STATUS }}',
        'global_address' => '{{ GLOBAL_ADDRESS }}',
    ],
    'Company Feedback Status Change - Consumer' => [
        'change_by' => '{{ CHANGE_BY }}',
        'customer_name' => '{{ CUSTOMER_NAME }}',
        'company_name' => '{{ COMPANY_NAME }}',
        'feedback_number' => '{{ FEEDBACK_NUMBER }}',
        'feedback_status' => '{{ FEEDBACK_STATUS }}',
        'global_address' => '{{ GLOBAL_ADDRESS }}',
    ],
    'Company Feedback Status Change - Admin' => [
        'company_name' => '{{ COMPANY_NAME }}',
        'feedback_number' => '{{ FEEDBACK_NUMBER }}',
        'feedback_status' => '{{ FEEDBACK_STATUS }}',
    ],
    'Company Complaint Created' => [
        'complaint_number' => '{{ COMPLAINT_NUMBER }}',
        'company_name' => '{{ COMPANY_NAME }}',
        'complaint_date' => '{{ COMPLAINT_DATE }}',
        'view_link' => '{{ VIEW_LINK }}',
        'phone_number' => '{{ PHONE_NUMBER }}',
        'global_domain' => '{{ GLOBAL_DOMAIN }}',
        'global_address' => '{{ GLOBAL_ADDRESS }}',
    ],
    'Company Complaint Created - Consumer' => [
        'customer_name' => '{{ CUSTOMER_NAME }}',
        'company_name' => '{{ COMPANY_NAME }}',
        'complaint_number' => '{{ COMPLAINT_NUMBER }}',
        'phone_number' => '{{ PHONE_NUMBER }}',
        'global_address' => '{{ GLOBAL_ADDRESS }}',
    ],
    'Company Complaint Created - Admin' => [
        'company_name' => '{{ COMPANY_NAME }}',
        'complaint_number' => '{{ COMPLAINT_NUMBER }}',
        'customer_name' => '{{ CUSTOMER_NAME }}',
        'customer_phone' => '{{ CUSTOMER_PHONE }}',
        'customer_email' => '{{ CUSTOMER_EMAIL }}',
    ],
    'Company Complaint Response' => [
        'company_name' => '{{ COMPANY_NAME }}',
        'complaint_date' => '{{ COMPLAINT_DATE }}',
        'comment_text' => '{{ COMMENT_TEXT }}',
        'global_address' => '{{ GLOBAL_ADDRESS }}',
    ],
    'Company Complaint Response - Consumer' => [
        'customer_name' => '{{ CUSTOMER_NAME }}',
        'change_by' => '{{ CHANGE_BY }}',
        'complaint_date' => '{{ COMPLAINT_DATE }}',
        'comment_text' => '{{ COMMENT_TEXT }}',
        'submit_a_response' => '{{ SUBMIT_A_RESPONSE }}',
        'phone_number' => '{{ PHONE_NUMBER }}',
        'global_domain' => '{{ GLOBAL_DOMAIN }}',
        'global_address' => '{{ GLOBAL_ADDRESS }}',
    ],
    'Company Complaint Response - Admin' => [
        'company_name' => '{{ COMPANY_NAME }}',
        'complaint_date' => '{{ COMPLAINT_DATE }}',
        'comment_text' => '{{ COMMENT_TEXT }}',
    ],
    /* Company Leads Emails */
    'Find A Pro Confirmation' => [
        'customer_name' => '{{ CUSTOMER_NAME }}',
        'service_category' => '{{ SERVICE_CATEGORY }}',
        'confirm_request' => '{{ CONFIRM_REQUEST }}',
        'global_address' => '{{ GLOBAL_ADDRESS }}',
    ],
    'Find A Pro Activation' => [
        'customer_name' => '{{ CUSTOMER_NAME }}',
        'service_category' => '{{ SERVICE_CATEGORY }}',
        'global_address' => '{{ GLOBAL_ADDRESS }}',
    ],
    'Find A Pro Activation - Admin' => [
        'customer_name' => '{{ CUSTOMER_NAME }}',
        'customer_phone' => '{{ CUSTOMER_PHONE }}',
        'customer_email' => '{{ CUSTOMER_EMAIL }}',
        'street' => '{{ STREET }}',
        'zipcode' => '{{ ZIPCODE }}',
        'main_service_category' => '{{ MAIN_SERVICE_CATEGORY }}',
        'service_category' => '{{ SERVICE_CATEGORY }}',
        'project_info' => '{{ PROJECT_INFO }}',
        'company_list' => '{{ COMPANY_LIST }}',
        'not_get_lead_company_list' => '{{ NOT_GET_LEAD_COMPANY_LIST }}',
        'city' => '{{ CITY }}',
        'state' => '{{ STATE }}',
    ],
    'Company Get Lead' => [
        'company_name' => '{{ COMPANY_NAME }}',
        'customer_name' => '{{ CUSTOMER_NAME }}',
        'customer_phone' => '{{ CUSTOMER_PHONE }}',
        'customer_email' => '{{ CUSTOMER_EMAIL }}',
        'street' => '{{ STREET }}',
        'zipcode' => '{{ ZIPCODE }}',
        'main_service_category' => '{{ MAIN_SERVICE_CATEGORY }}',
        'service_category' => '{{ SERVICE_CATEGORY }}',
        'project_info' => '{{ PROJECT_INFO }}',
        'city' => '{{ CITY }}',
        'state' => '{{ STATE }}',
        'global_address' => '{{ GLOBAL_ADDRESS }}',
        'account_type' => '{{ ACCOUNT_TYPE }}',
        'lead_domain' => '{{ LEAD_DOMAIN }}',
    ],
    'Lead Dispute' => [
        'company_name' => '{{ COMPANY_NAME }}',
        'service_category' => '{{ SERVICE_CATEGORY }}',
        'phone_number' => '{{ PHONE_NUMBER }}',
        'global_address' => '{{ GLOBAL_ADDRESS }}',
    ],
    'Lead Dispute - Consumer' => [
        'service_category' => '{{ SERVICE_CATEGORY }}',
        'customer_name' => '{{ CUSTOMER_NAME }}',
        'company_name' => '{{ COMPANY_NAME }}',
        'global_address' => '{{ GLOBAL_ADDRESS }}',
    ],
    'Lead Dispute - Admin' => [
        'service_category' => '{{ SERVICE_CATEGORY }}',
        'company_name' => '{{ COMPANY_NAME }}',
    ],
    'Lead Dispute Status Change - Consumer' => [
        'customer_name' => '{{ CUSTOMER_NAME }}',
        'dispute_status' => '{{ DISPUTE_STATUS }}',
        'service_category' => '{{ SERVICE_CATEGORY }}',
        'global_address' => '{{ GLOBAL_ADDRESS }}',
    ],
    'Lead Dispute Status Change' => [
        'company_name' => '{{ COMPANY_NAME }}',
        'dispute_status' => '{{ DISPUTE_STATUS }}',
        'service_category' => '{{ SERVICE_CATEGORY }}',
        'global_address' => '{{ GLOBAL_ADDRESS }}',
    ],
    'Lead Dispute Declined' => [
        'company_name' => '{{ COMPANY_NAME }}',
        'service_category' => '{{ SERVICE_CATEGORY }}',
        'reason' => '{{ REASON }}',
        'global_address' => '{{ GLOBAL_ADDRESS }}',
    ],
    /* Company Monthly Subscription Emails */
    'Company Monthly Subscription Failed' => [
        'company_name' => '{{ COMPANY_NAME }}',
        'global_address' => '{{ GLOBAL_ADDRESS }}',
    ],
    'Company Monthly Subscription Failed - Admin' => [
        'company_name' => '{{ COMPANY_NAME }}',
    ],
    'Company Monthly Subscription' => [
        'company_name' => '{{ COMPANY_NAME }}',
        'phone_number' => '{{ PHONE_NUMBER }}',
        'global_domain' => '{{ GLOBAL_DOMAIN }}',
        'global_address' => '{{ GLOBAL_ADDRESS }}',
    ],
    'Company Monthly Subscription - Admin' => [
        'company_name' => '{{ COMPANY_NAME }}',
    ],
    'Company Monthly Subscription Reminder' => [
        'company_name' => '{{ COMPANY_NAME }}',
        'days' => '{{ DAYS }}',
        'global_address' => '{{ GLOBAL_ADDRESS }}',
    ],
    'Company Monthly Subscription Cancelled' => [
        'company_name' => '{{ COMPANY_NAME }}',
        'phone_number' => '{{ PHONE_NUMBER }}',
        'global_address' => '{{ GLOBAL_ADDRESS }}',
    ],
    'Company Monthly Subscription Cancelled - Admin' => [
        'company_name' => '{{ COMPANY_NAME }}',
    ],
    /* Company Pay Per Lead Listing Invoice Emails */
    'Company Pay Per Lead Listing Invoice' => [
        'company_name' => '{{ COMPANY_NAME }}',
        'pay_now' => '{{ PAY_NOW }}',
        'phone_number' => '{{ PHONE_NUMBER }}',
        'global_domain' => '{{ GLOBAL_DOMAIN }}',
        'global_address' => '{{ GLOBAL_ADDRESS }}',
    ],
    'Company Pay Per Lead Listing Invoice - Admin' => [
        'company_name' => '{{ COMPANY_NAME }}',
    ],
    'Company Pay Per Lead Listing Invoice Paid' => [
        'company_name' => '{{ COMPANY_NAME }}',
        'phone_number' => '{{ PHONE_NUMBER }}',
        'global_domain' => '{{ GLOBAL_DOMAIN }}',
        'global_address' => '{{ GLOBAL_ADDRESS }}',
    ],
    'Company Pay Per Lead Listing Invoice Paid - Admin' => [
        'company_name' => '{{ COMPANY_NAME }}',
    ],
    'Company Status Change - Unpaid Invoice' => [
        'company_name' => '{{ COMPANY_NAME }}',
        'global_address' => '{{ GLOBAL_ADDRESS }}',
    ],
    'PPL Company Status Change - Unpaid Invoice' => [
        'company_name' => '{{ COMPANY_NAME }}',
        'global_address' => '{{ GLOBAL_ADDRESS }}',
    ],
    'Monthly Listing Company Status Change - Unpaid Invoice' => [
        'company_name' => '{{ COMPANY_NAME }}',
        'global_address' => '{{ GLOBAL_ADDRESS }}',
    ],
    'Company Pay Per Lead Listing Invoice - Reminder' => [
        'company_name' => '{{ COMPANY_NAME }}',
        'days' => '{{ DAYS }}',
        'pay_now' => '{{ PAY_NOW }}',
        'phone_number' => '{{ PHONE_NUMBER }}',
        'global_domain' => '{{ GLOBAL_DOMAIN }}',
        'global_address' => '{{ GLOBAL_ADDRESS }}',
    ],
    'Company Monthly Budget' => [
        'company_name' => '{{ COMPANY_NAME }}',
        'monthly_budget' => '{{ MONTHLY_BUDGET }}',
        'phone_number' => '{{ PHONE_NUMBER }}',
        'global_address' => '{{ GLOBAL_ADDRESS }}',
    ],
    'Company Monthly Budget - Admin' => [
        'company_name' => '{{ COMPANY_NAME }}',
        'monthly_budget' => '{{ MONTHLY_BUDGET }}',
    ],
    'Company Low Monthly Budget - Reminder' => [
        'company_name' => '{{ COMPANY_NAME }}',
        'monthly_budget' => '{{ MONTHLY_BUDGET }}',
        'phone_number' => '{{ PHONE_NUMBER }}',
        'global_domain' => '{{ GLOBAL_DOMAIN }}',
        'global_address' => '{{ GLOBAL_ADDRESS }}',
    ],
    /* Company Member Resource Emails */
    'Company Update Member Resource' => [
        'company_name' => '{{ COMPANY_NAME }}',
        'phone_number' => '{{ PHONE_NUMBER }}',
        'global_domain' => '{{ GLOBAL_DOMAIN }}',
        'global_address' => '{{ GLOBAL_ADDRESS }}',
    ],
    'Company Update Member Resource - Admin' => [
        'company_name' => '{{ COMPANY_NAME }}',
    ],
    /* Company Package Generated Email */
    'Company Package Generated' => [
        'company_name' => '{{ COMPANY_NAME }}',
        'package_code' => '{{ PACKAGE_CODE }}',
        'upgrade_link' => '{{ UPGRADE_LINK }}',
        'phone_number' => '{{ PHONE_NUMBER }}',
        'global_domain' => '{{ GLOBAL_DOMAIN }}',
        'global_address' => '{{ GLOBAL_ADDRESS }}',
    ],
    /* Company Submitted upgrade/review page and change emails */
    'Company Submitted Upgrade Page' => [
        'company_name' => '{{ COMPANY_NAME }}',
    ],
    'Company Change Membership In Upgrade Review Page' => [
        'company_name' => '{{ COMPANY_NAME }}',
    ],
    'Company Change Service Categories In Upgrade Review Page' => [
        'company_name' => '{{ COMPANY_NAME }}',
    ],
    'Company Change Zipcode List In Upgrade Review Page' => [
        'company_name' => '{{ COMPANY_NAME }}',
    ],
    'Company Change Monthly Budget In Upgrade Review Page' => [
        'company_name' => '{{ COMPANY_NAME }}',
    ],
    'Company Submitted Upgrade Review Page' => [
        'company_name' => '{{ COMPANY_NAME }}',
    ],
    /* Company Document upload by Admin */
    'Company Document Upload By Admin' => [
        'company_name' => '{{ COMPANY_NAME }}',
        'document_type' => '{{ DOCUMENT_TYPE }}',
        'phone_number' => '{{ PHONE_NUMBER }}',
        'global_domain' => '{{ GLOBAL_DOMAIN }}',
        'global_address' => '{{ GLOBAL_ADDRESS }}',
    ],
    /* Company Complaint status change mails */
    'Company Complaint Status Change' => [
        'company_name' => '{{ COMPANY_NAME }}',
        'complaint_number' => '{{ COMPLAINT_NUMBER }}',
        'complaint_status' => '{{ COMPLAINT_STATUS }}',
        'global_address' => '{{ GLOBAL_ADDRESS }}',
    ],
    'Company Complaint Status Change - Consumer' => [
        'change_by' => '{{ CHANGE_BY }}',
        'customer_name' => '{{ CUSTOMER_NAME }}',
        'company_name' => '{{ COMPANY_NAME }}',
        'complaint_number' => '{{ COMPLAINT_NUMBER }}',
        'complaint_status' => '{{ COMPLAINT_STATUS }}',
        'global_address' => '{{ GLOBAL_ADDRESS }}',
    ],
    'Company Complaint Status Change - Admin' => [
        'company_name' => '{{ COMPANY_NAME }}',
        'complaint_number' => '{{ COMPLAINT_NUMBER }}',
        'complaint_status' => '{{ COMPLAINT_STATUS }}',
    ],
    /* Company Bio/Logo Status change/Remove Mails */
    'Company Bio Approved' => [
        'company_name' => '{{ COMPANY_NAME }}',
        'global_address' => '{{ GLOBAL_ADDRESS }}',
    ],
    'Company Bio Rejected' => [
        'company_name' => '{{ COMPANY_NAME }}',
        'rejected_reason' => '{{ REJECTED_REASON }}',
        'phone_number' => '{{ PHONE_NUMBER }}',
        'global_domain' => '{{ GLOBAL_DOMAIN }}',
        'global_address' => '{{ GLOBAL_ADDRESS }}',
    ],
    'Company Bio Removed' => [
        'company_name' => '{{ COMPANY_NAME }}',
        'phone_number' => '{{ PHONE_NUMBER }}',
        'global_domain' => '{{ GLOBAL_DOMAIN }}',
        'global_address' => '{{ GLOBAL_ADDRESS }}',
    ],
    'Company Logo Approved' => [
        'company_name' => '{{ COMPANY_NAME }}',
        'global_address' => '{{ GLOBAL_ADDRESS }}',
    ],
    'Company Logo Rejected' => [
        'company_name' => '{{ COMPANY_NAME }}',
        'rejected_reason' => '{{ REJECTED_REASON }}',
        'phone_number' => '{{ PHONE_NUMBER }}',
        'global_domain' => '{{ GLOBAL_DOMAIN }}',
        'global_address' => '{{ GLOBAL_ADDRESS }}',
    ],
    'Company Logo Removed' => [
        'company_name' => '{{ COMPANY_NAME }}',
        'phone_number' => '{{ PHONE_NUMBER }}',
        'global_domain' => '{{ GLOBAL_DOMAIN }}',
        'global_address' => '{{ GLOBAL_ADDRESS }}',
    ],
    /* Company User Bio/Profile Picture Status change/Remove Mails */
    'Company User Bio Approved' => [
        'first_name' => '{{ FIRST_NAME }}',
        'last_name' => '{{ LAST_NAME }}',
        'global_address' => '{{ GLOBAL_ADDRESS }}',
    ],
    'Company User Bio Rejected' => [
        'first_name' => '{{ FIRST_NAME }}',
        'last_name' => '{{ LAST_NAME }}',
        'rejected_reason' => '{{ REJECTED_REASON }}',
        'phone_number' => '{{ PHONE_NUMBER }}',
        'global_domain' => '{{ GLOBAL_DOMAIN }}',
        'global_address' => '{{ GLOBAL_ADDRESS }}',
    ],
    'Company User Bio Removed' => [
        'first_name' => '{{ FIRST_NAME }}',
        'last_name' => '{{ LAST_NAME }}',
        'phone_number' => '{{ PHONE_NUMBER }}',
        'global_domain' => '{{ GLOBAL_DOMAIN }}',
        'global_address' => '{{ GLOBAL_ADDRESS }}',
    ],
    'Company User Profile Picture Approved' => [
        'first_name' => '{{ FIRST_NAME }}',
        'last_name' => '{{ LAST_NAME }}',
        'global_address' => '{{ GLOBAL_ADDRESS }}',
    ],
    'Company User Profile Picture Rejected' => [
        'first_name' => '{{ FIRST_NAME }}',
        'last_name' => '{{ LAST_NAME }}',
        'rejected_reason' => '{{ REJECTED_REASON }}',
        'phone_number' => '{{ PHONE_NUMBER }}',
        'global_domain' => '{{ GLOBAL_DOMAIN }}',
        'global_address' => '{{ GLOBAL_ADDRESS }}',
    ],
    'Company User Profile Picture Removed' => [
        'first_name' => '{{ FIRST_NAME }}',
        'last_name' => '{{ LAST_NAME }}',
        'phone_number' => '{{ PHONE_NUMBER }}',
        'global_domain' => '{{ GLOBAL_DOMAIN }}',
        'global_address' => '{{ GLOBAL_ADDRESS }}',
    ],
    /* Background check process mails */
    'Company User Submitted Background Check Process' => [
        'first_name' => '{{ FIRST_NAME }}',
        'last_name' => '{{ LAST_NAME }}',
        'phone_number' => '{{ PHONE_NUMBER }}',
        'global_domain' => '{{ GLOBAL_DOMAIN }}',
        'global_address' => '{{ GLOBAL_ADDRESS }}',
    ],
    'Company User Submitted Background Check Process - Admin' => [
        'company_name' => '{{ COMPANY_NAME }}',
        'first_name' => '{{ FIRST_NAME }}',
        'last_name' => '{{ LAST_NAME }}',
    ],
    'Company User Background Check Process Completed' => [
        'first_name' => '{{ FIRST_NAME }}',
        'last_name' => '{{ LAST_NAME }}',
        'phone_number' => '{{ PHONE_NUMBER }}',
        'global_domain' => '{{ GLOBAL_DOMAIN }}',
        'global_address' => '{{ GLOBAL_ADDRESS }}',
    ],
    'Company User Background Check Process Completed - Admin' => [
        'company_name' => '{{ COMPANY_NAME }}',
        'first_name' => '{{ FIRST_NAME }}',
        'last_name' => '{{ LAST_NAME }}',
    ],
    /* Company User Invitation emails */
    'Company User Invitation' => [
        'owner_name' => '{{ OWNER_NAME }}',
        'company_name' => '{{ COMPANY_NAME }}',
        'invitation_link' => '{{ INVITATION_LINK }}',
        'phone_number' => '{{ PHONE_NUMBER }}',
        'global_domain' => '{{ GLOBAL_DOMAIN }}',
        'global_address' => '{{ GLOBAL_ADDRESS }}',
    ],
    'Company User Accepted Invitation' => [
        'first_name' => '{{ FIRST_NAME }}',
        'account_type' => '{{ ACCOUNT_TYPE }}',
        'user_name' => '{{ USER_NAME }}',
        'user_email' => '{{ USER_EMAIL }}',
        'login-link' => '{{ LOGIN_LINK }}',
    /* 'global_address' => '{{ GLOBAL_ADDRESS }}',
      'owner_name' => '{{ OWNER_NAME }}',
      'company_name' => '{{ COMPANY_NAME }}',
      'phone_number' => '{{ PHONE_NUMBER }}',
      'global_domain' => '{{ GLOBAL_DOMAIN }}',
      'global_address' => '{{ GLOBAL_ADDRESS }}', */
    ],
    'Company User Accepted Invitation - Admin' => [
        'owner_name' => '{{ OWNER_NAME }}',
        'company_name' => '{{ COMPANY_NAME }}',
    ],
    /* Lead Dispute cancel emails */
    'Lead Dispute Cancel' => [
        'service_category' => '{{ SERVICE_CATEGORY }}',
        'company_name' => '{{ COMPANY_NAME }}',
        'phone_number' => '{{ PHONE_NUMBER }}',
        'global_address' => '{{ GLOBAL_ADDRESS }}',
    ],
    'Lead Dispute Cancel - Consumer' => [
        'service_category' => '{{ SERVICE_CATEGORY }}',
        'customer_name' => '{{ CUSTOMER_NAME }}',
        'company_name' => '{{ COMPANY_NAME }}',
        'phone_number' => '{{ PHONE_NUMBER }}',
        'global_address' => '{{ GLOBAL_ADDRESS }}',
    ],
    'Lead Dispute Cancel - Admin' => [
        'company_name' => '{{ COMPANY_NAME }}',
        'service_category' => '{{ SERVICE_CATEGORY }}',
    ],
    /* Company Gallery Updated mails */
    'Company Gallery Update' => [
        'company_name' => '{{ COMPANY_NAME }}',
        'phone_number' => '{{ PHONE_NUMBER }}',
        'global_domain' => '{{ GLOBAL_DOMAIN }}',
        'global_address' => '{{ GLOBAL_ADDRESS }}',
    ],
    'Company Gallery Update - Admin' => [
        'company_name' => '{{ COMPANY_NAME }}',
    ],
    'Company Gallery Status Update' => [
        'company_name' => '{{ COMPANY_NAME }}',
        'status' => '{{ STATUS }}',
        'phone_number' => '{{ PHONE_NUMBER }}',
        'global_domain' => '{{ GLOBAL_DOMAIN }}',
        'global_address' => '{{ GLOBAL_ADDRESS }}',
    ],
    'Company Gallery Rejected' => [
        'company_name' => '{{ COMPANY_NAME }}',
        'reject_reason' => '{{ REJECT_REASON }}',
        'phone_number' => '{{ PHONE_NUMBER }}',
        'global_domain' => '{{ GLOBAL_DOMAIN }}',
        'global_address' => '{{ GLOBAL_ADDRESS }}',
    ],
    'Company Gallery Deleted' => [
        'company_name' => '{{ COMPANY_NAME }}',
        'phone_number' => '{{ PHONE_NUMBER }}',
        'global_domain' => '{{ GLOBAL_DOMAIN }}',
        'global_address' => '{{ GLOBAL_ADDRESS }}',
    ],
    /* Get Listed emails */
    'Non Member Activation Email' => [
        'company_name' => '{{ COMPANY_NAME }}',
        'confirmation_link' => '{{ CONFIRMATION_LINK }}',
        'global_address' => '{{ GLOBAL_ADDRESS }}',
    ],
    'Non Member Register Confirmation Success Email' => [
        'company_name' => '{{ COMPANY_NAME }}',
        'global_address' => '{{ GLOBAL_ADDRESS }}',
    ],
    'Non Member Register Confirmation Success Email - Admin' => [
        'company_name' => '{{ COMPANY_NAME }}',
        'first_name' => '{{ FIRST_NAME }}',
        'last_name' => '{{ LAST_NAME }}',
        'email' => '{{ EMAIL }}',
        'phone' => '{{ PHONE }}',
        'address' => '{{ ADDRESS }}',
        'city' => '{{ CITY }}',
        'state' => '{{ STATE }}',
        'zipcode' => '{{ ZIPCODE }}',
        'zipcode_range' => '{{ ZIPCODE_RANGE }}',
        'service_provider' => '{{ SERVICE_PROVIDER }}',
        'service_offered' => '{{ SERVICE_OFFERED }}',
        'service_type' => '{{ SERVICE_TYPE }}',
        'how_did_you_hear_about_us' => '{{ HOW_DID_YOU_HEAR_ABOUT_US }}',
        'comment' => '{{ COMMENT }}',
    ],
    /* preview tiral subscription email to company start */
    'Preview Trial Unsubscribed Email - Company' => [
        'company_name' => '{{ COMPANY_NAME }}',
        'upgrade_link' => '{{ UPGRADE_LINK }}',
        'phone_number' => '{{ PHONE_NUMBER }}',
        'global_domain' => '{{ GLOBAL_DOMAIN }}',
        'global_address' => '{{ GLOBAL_ADDRESS }}',
    ],
    'Preview Trial Subscribed Email - Company' => [
        'company_name' => '{{ COMPANY_NAME }}',
        'phone_number' => '{{ PHONE_NUMBER }}',
        'global_address' => '{{ GLOBAL_ADDRESS }}',
    ],
    /* preview tiral subscription email to company end */
    'Lead Unsubscribe step1 Email - Admin' => [
        'customer_name' => '{{ CUSTOMER_NAME }}',
        'customer_email' => '{{ CUSTOMER_EMAIL }}',
        //'unsubscribe_reason' => '{{ UNSUBSCRIBE_REASON }}',
        'unsubscribe_from' => '{{ UNSUBSCRIBE_FROM }}',
    ],
    'Lead Unsubscribe step2 Email - Admin' => [
        'customer_name' => '{{ CUSTOMER_NAME }}',
        'customer_email' => '{{ CUSTOMER_EMAIL }}',
        'unsubscribe_reason' => '{{ UNSUBSCRIBE_REASON }}',
    //'unsubscribe_from' => '{{ UNSUBSCRIBE_FROM }}',
    ],
    'Company Unsubscribe Email - Admin' => [
        'company_name' => '{{ COMPANY_NAME }}',
        'unsubscribe_reason' => '{{ UNSUBSCRIBE_REASON }}',
        'unsubscribe_from' => '{{ UNSUBSCRIBE_FROM }}',
    ],
    /* check payment clear by admin mail to company start */
    'Check payment Cleared Mail - Company' => [
        'company_name' => '{{ COMPANY_NAME }}',
        'invoice_number' => '{{ INVOICE_NUMBER }}',
        'phone_number' => '{{ PHONE_NUMBER }}',
        'global_domain' => '{{ GLOBAL_DOMAIN }}',
        'global_address' => '{{ GLOBAL_ADDRESS }}',
    ],
    /* check payment clear by admin mail to company end */

    /* Credit card payment Another invoice emails start */
    'Company PPL Lead Invoice Credit Card Payment Email' => [
        'company_name' => '{{ COMPANY_NAME }}',
        'phone_number' => '{{ PHONE_NUMBER }}',
        'global_domain' => '{{ GLOBAL_DOMAIN }}',
        'global_address' => '{{ GLOBAL_ADDRESS }}',
    ],
    'Company Monthly subscription Invoice Credit Card Payment Email' => [
        'company_name' => '{{ COMPANY_NAME }}',
        'phone_number' => '{{ PHONE_NUMBER }}',
        'global_domain' => '{{ GLOBAL_DOMAIN }}',
        'global_address' => '{{ GLOBAL_ADDRESS }}',
    ],
    'Company PPL Lead Invoice Credit Card Payment Email - Admin' => [
        'company_name' => '{{ COMPANY_NAME }}',
    ],
    'Company Monthly subscription Invoice Credit Card Payment Email - Admin' => [
        'company_name' => '{{ COMPANY_NAME }}',
    ],
    /* Credit card payment Another invoice emails end */

    /* Check payment Another invoice emails start */
    'Company PPL Lead Invoice Check Payment Email' => [
        'company_name' => '{{ COMPANY_NAME }}',
        'phone_number' => '{{ PHONE_NUMBER }}',
        'global_domain' => '{{ GLOBAL_DOMAIN }}',
        'global_address' => '{{ GLOBAL_ADDRESS }}',
    ],
    'Company Monthly subscription Check Payment Email' => [
        'company_name' => '{{ COMPANY_NAME }}',
        'phone_number' => '{{ PHONE_NUMBER }}',
        'global_domain' => '{{ GLOBAL_DOMAIN }}',
        'global_address' => '{{ GLOBAL_ADDRESS }}',
    ],
    'Company PPL Lead Invoice Check Payment Email - Admin' => [
        'company_name' => '{{ COMPANY_NAME }}',
    ],
    'Company Monthly subscription Check Payment Email - Admin' => [
        'company_name' => '{{ COMPANY_NAME }}',
    ],
    /* Credit card payment Another invoice emails end */

    /* non members follow up emails */
    'non_members' => [
        'confirmation_email' => [
            'company_name' => '{{ COMPANY_NAME }}',
            'first_name' => '{{ FIRST_NAME }}',
            'confirmation_link' => '{{ CONFIRMATION_LINK }}',
        /* 'global_address' => '{{ GLOBAL_ADDRESS }}',
          'phone_number' => '{{ PHONE_NUMBER }}',
          'global_domain' => '{{ GLOBAL_DOMAIN }}', */
        ],
        'followup_email' => [
            'company_name' => '{{ COMPANY_NAME }}',
            'global_address' => '{{ GLOBAL_ADDRESS }}',
         /* 'phone_number' => '{{ PHONE_NUMBER }}',
          'global_domain' => '{{ GLOBAL_DOMAIN }}', */
        ],
    ],
    /* registered members follow up emails */
    'registered_members' => [
        'confirmation_email' => [
            'company_name' => '{{ COMPANY_NAME }}',
            'account_type' => '{{ ACCOUNT_TYPE }}',
            'confirmation_link' => '{{ CONFIRMATION_LINK }}',
        /* 'global_address' => '{{ GLOBAL_ADDRESS }}',
          'phone_number' => '{{ PHONE_NUMBER }}',
          'global_domain' => '{{ GLOBAL_DOMAIN }}', */
        ],
        'followup_email' => [
            'account_type' => '{{ ACCOUNT_TYPE }}',
            'company_name' => '{{ COMPANY_NAME }}',
            'user_name' => '{{ USER_NAME }}',
            'user_email' => '{{ USER_EMAIL }}',
            'login_link' => '{{ LOGIN_LINK }}',
        /* 'global_address' => '{{ GLOBAL_ADDRESS }}',
          'phone_number' => '{{ PHONE_NUMBER }}',
          'global_domain' => '{{ GLOBAL_DOMAIN }}', */
        ],
    ],
];

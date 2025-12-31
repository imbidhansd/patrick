<?php

namespace App\Http\Controllers\company;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Mail\Company\CompanyMail;
use App\Mail\Admin\AdminMail;
use Illuminate\Support\Facades\Mail;
use PDF;
use Auth;
use Validator;
// Models
use App\Models\Page;
use App\Models\Media;
use App\Models\State;
use App\Models\Company;
use App\Models\CompanyUser;
use App\Models\CompanyInformation;
use App\Models\CompanyLicensing;
use App\Models\CompanyInsurance;
use App\Models\CompanyCustomerReference;
use App\Models\CompanyLeadNotification;
use App\Models\CompanyListingAgreement;
use App\Models\CompanyApprovalStatus;
use App\Models\CompanyDocument;
use App\Models\ProfessionalAffiliation;
use App\Models\Custom;

class CompanyApplicationController extends Controller {

    public function __construct() {
        $this->web_settings = Custom::getSettings();
        $this->view_base = 'company.profile.application.';
    }

    public function index() {
        $company_id = Auth::guard('company_user')->user()->company_id;
        $companyObj = Company::find(Auth::guard('company_user')->user()->company_id);

        if ($companyObj->status != 'Paid Pending') {
            flash('Account application already submitted.')->warning();
            return redirect('dashboard');
        }

        $data = [
            'terms_page' => Page::find('7'),
            'company' => $companyObj,
            'company_information' => CompanyInformation::where('company_id', $company_id)->first(),
            'company_licensing' => CompanyLicensing::where('company_id', $company_id)->first(),
            'company_insurance' => CompanyInsurance::where('company_id', $company_id)->first(),
            'company_customer_reference' => CompanyCustomerReference::where('company_id', $company_id)->first(),
            'company_lead_notifications' => CompanyLeadNotification::where('company_id', $company_id)->first(),
            'company_listing_agreement' => CompanyListingAgreement::where('company_id', $company_id)->first(),
            'states' => State::order()->pluck('name', 'id'),
            'professional_affiliations' => ProfessionalAffiliation::where('trade_id', $companyObj->trade_id)->active()->order()->pluck('title', 'title')
        ];

        $category_service_list = Custom::company_service_category_list($company_id);
        $data['company_service_category_list'] = $category_service_list['company_service_category_list'];
        //dd($data);
        return view($this->view_base . 'index', $data);
    }

    public function postCompanyInformation(Request $request) {
        $otherEmailArr = [];
        $requestArr = $request->all();
        $company_user_id = Auth::guard('company_user')->user()->id;
        $company_id = Auth::guard('company_user')->user()->company_id;
        $companyObj = Company::find($company_id);

        $validation_arr = [
            'company_owner_1_full_name' => 'required',
            'company_owner_1_email' => 'required',
            'legal_company_name' => 'required',
            'ein' => 'required',
            'company_start_date' => 'required',
            'main_company_telephone' => 'required',
            //'website' => 'required',
            'mailing_address' => 'required',
            'city' => 'required',
            'state_id' => 'required',
            'county' => 'required',
            'zipcode' => 'required',
            'internal_contact_fullname' => 'required',
            'internal_contact_phone' => 'required',
            'internal_contact_email' => 'required',
                //
                //'registered_date' => 'required'
        ];

        /* Company owner validation process start */
        if ($companyObj->number_of_owners > 1) {
            for ($i = 2; $i <= $companyObj->number_of_owners; $i++) {
                $owner_name = "company_owner_" . $i . "_full_name";
                $owner_email = "company_owner_" . $i . "_email";

                $company_user_check = CompanyUser::where('email', $requestArr[$owner_email])->first();
                if (!is_null($company_user_check)) {
                    return response()->json([
                                'status' => '0',
                                'type' => 'warning',
                                'title' => 'Warning',
                                'text' => 'The email address for ' . $requestArr[$owner_name] . ' (' . $requestArr[$owner_email] . ') is already in our system. Please add a unique email for ' . $requestArr[$owner_name],
                                'field_id' => 'owner_email_' . $i
                    ]);
                }

                $validation_arr[$owner_name] = "required";
                $validation_arr[$owner_email] = "required";
            }


            for ($i = 1; $i <= $companyObj->number_of_owners; $i++) {
                $owner_email = "company_owner_" . $i . "_email";
                if (in_array($requestArr[$owner_email], $otherEmailArr)) {
                    return response()->json([
                                'status' => '0',
                                'type' => 'warning',
                                'title' => 'Warning',
                                'text' => 'Other Owner Email must be unique.'
                    ]);
                }
                $otherEmailArr[] = $requestArr[$owner_email];
            }
        }
        /* Company owner validation process end */


        /* Company start date validation process start */
        if (isset($requestArr['company_start_date']) && $requestArr['company_start_date'] != '') {
            $start_date_arr = explode("/", $requestArr['company_start_date']);

            if ($start_date_arr[0] > 12) {
                return response()->json([
                            'status' => '0',
                            'type' => 'warning',
                            'title' => 'Warning',
                            'text' => 'Please enter valid company start date.'
                ]);
            }

            $company_start_date = '01/' . $requestArr['company_start_date'];
            $parsed_company_start_date = \Carbon\Carbon::createFromFormat('d/m/Y', $company_start_date);

            $today_date = now();
            if ($today_date->diffInDays($parsed_company_start_date) < 365) {
                return response()->json([
                            'status' => '0',
                            'type' => 'warning',
                            'title' => 'Warning',
                            'text' => 'Please enter a valid company start date'
                ]);
            }
        } else {
            return response()->json([
                        'status' => '0',
                        'type' => 'warning',
                        'title' => 'Warning',
                        'text' => 'Please enter a valid company start date'
            ]);
        }
        /* Company start date validation process end */

        $validator = Validator::make($requestArr, $validation_arr);
        if ($validator->fails()) {
            return response()->json([
                        'status' => '0',
                        'type' => 'warning',
                        'title' => 'Warning',
                        'text' => 'Please fill all required fields'
            ]);
        }

        $company_information_obj = CompanyInformation::firstOrCreate(['company_id' => $company_id]);
        $new_temp_obj = CompanyInformation::firstOrCreate(['company_id' => $company_id]);

        // Check for data update
        $new_temp_obj->fill($requestArr);
        $result = array_diff_assoc($new_temp_obj->toArray(), $company_information_obj->toArray());

        if (count($result) > 0) {
            $company_information_obj->update($requestArr);
        }

        $companyObj->internal_contact_name = $requestArr['internal_contact_fullname'];
        $companyObj->internal_contact_email = $requestArr['internal_contact_email'];
        $companyObj->internal_contact_phone = $requestArr['internal_contact_phone'];
        $companyObj->registered_date = $requestArr['company_start_date'];
        $companyObj->save();

        CompanyApprovalStatus::changeCompanyApprovalStatus($company_id, [
            'owner_1_bg_check_document_status' => 'not required',
            'owner_2_bg_check_document_status' => 'not required',
            'owner_3_bg_check_document_status' => 'not required',
            'owner_4_bg_check_document_status' => 'not required'
        ]);

        for ($i = 1; $i <= $companyObj->number_of_owners; $i++) {
            CompanyApprovalStatus::changeCompanyApprovalStatus($company_id, ['owner_' . $i . '_bg_check_document_status' => 'pending']);
        }

        return response()->json(['status' => '1']);
    }

    public function postCompanyLicensing(Request $request) {
        $requestArr = $request->all();
        //dd($requestArr);
        $companyUserObj = Auth::guard('company_user')->user();
        $company_id = $companyUserObj->company_id;

        $company_licensing_obj = CompanyLicensing::firstOrCreate(['company_id' => $company_id]);
        $new_temp_obj = CompanyLicensing::firstOrCreate(['company_id' => $company_id]);

        unset($requestArr['state_business_registeration_file_id']);
        unset($requestArr['proof_of_ownership_file_id']);
        unset($requestArr['articles_of_incorporation_file_id']);
        unset($requestArr['state_licensed_file_id']);
        unset($requestArr['country_licensed_file_id']);
        unset($requestArr['city_licensed_file_id']);
        unset($requestArr['written_warrenty_file_id']);
        unset($requestArr['subcontractor_agreement_file_id']);

        // Check for data update
        if (isset($requestArr['licensing_required'])) {
            $requestArr['licensing_required'] = json_encode($requestArr['licensing_required']);
        }
        $new_temp_obj->fill($requestArr);
        $result = array_diff_assoc($new_temp_obj->toArray(), $company_licensing_obj->toArray());

        if (count($result) > 0) {
            $company_licensing_obj->update($requestArr);
            // Code For Company Approval Status Update [Start]
            // 1. bussiness state registration
            if (isset($result['legally_registered_within_state'])) {
                if (isset($result['state_business_registeration'])) {
                    if ($requestArr['legally_registered_within_state'] == 'yes' && $requestArr['state_business_registeration'] == 'yes') {
                        CompanyApprovalStatus::changeCompanyApprovalStatus($company_id, ['registered_legally_to_state' => 'in process', 'proof_of_ownership' => 'not required']);
                    } elseif ($requestArr['legally_registered_within_state'] == 'yes' && $requestArr['state_business_registeration'] == 'no') {
                        CompanyApprovalStatus::changeCompanyApprovalStatus($company_id, ['registered_legally_to_state' => 'pending', 'proof_of_ownership' => 'not required']);
                    }
                } elseif (isset($requestArr['proof_of_ownership'])) {
                    if ($requestArr['legally_registered_within_state'] == 'no' && $requestArr['copy_proof_of_ownership'] == 'yes') {
                        CompanyApprovalStatus::changeCompanyApprovalStatus($company_id, ['proof_of_ownership' => 'in process', 'registered_legally_to_state' => 'not required']);
                    } elseif ($requestArr['legally_registered_within_state'] == 'no' && $requestArr['copy_proof_of_ownership'] == 'no') {
                        CompanyApprovalStatus::changeCompanyApprovalStatus($company_id, ['proof_of_ownership' => 'pending', 'registered_legally_to_state' => 'not required']);
                    }
                }
            }


            if (isset($requestArr['licensing_required'])) {
                $licensing_required = json_decode($requestArr['licensing_required']);

                if (is_array($licensing_required) && in_array('No licensing is required', $licensing_required)) {
                    CompanyApprovalStatus::changeCompanyApprovalStatus($company_id, ['state_licensing' => 'not required', 'country_licensing' => 'not required', 'city_licensing' => 'not required']);
                } else {

                    // 3. state licencing
                    if (isset($result['state_licensed']) || isset($result['copy_state_licensed'])) {
                        if ($requestArr['state_licensed'] == 'yes' && $requestArr['copy_state_licensed'] == 'yes') {
                            CompanyApprovalStatus::changeCompanyApprovalStatus($company_id, ['state_licensing' => 'in process']);
                        } elseif ($requestArr['state_licensed'] == 'yes' && $requestArr['copy_state_licensed'] == 'no') {
                            CompanyApprovalStatus::changeCompanyApprovalStatus($company_id, ['state_licensing' => 'pending']);
                        } elseif ($requestArr['state_licensed'] == 'no') {
                            CompanyApprovalStatus::changeCompanyApprovalStatus($company_id, ['state_licensing' => 'not required']);
                        }
                    }

                    // 4. country licencing
                    if (isset($result['country_licensed']) || isset($result['copy_country_licensed'])) {
                        if ($requestArr['country_licensed'] == 'yes' && $requestArr['copy_country_licensed'] == 'yes') {
                            CompanyApprovalStatus::changeCompanyApprovalStatus($company_id, ['country_licensing' => 'in process']);
                        } elseif ($requestArr['country_licensed'] == 'yes' && $requestArr['copy_country_licensed'] == 'no') {
                            CompanyApprovalStatus::changeCompanyApprovalStatus($company_id, ['country_licensing' => 'pending']);
                        } elseif ($requestArr['country_licensed'] == 'no') {
                            CompanyApprovalStatus::changeCompanyApprovalStatus($company_id, ['country_licensing' => 'not required']);
                        }
                    }

                    // 5. city licencing
                    if (isset($result['city_licensed']) || isset($result['copy_city_licensed'])) {
                        if ($requestArr['city_licensed'] == 'yes' && $requestArr['copy_city_licensed'] == 'yes') {
                            CompanyApprovalStatus::changeCompanyApprovalStatus($company_id, ['city_licensing' => 'in process']);
                        } elseif ($requestArr['city_licensed'] == 'yes' && $requestArr['copy_city_licensed'] == 'no') {
                            CompanyApprovalStatus::changeCompanyApprovalStatus($company_id, ['city_licensing' => 'pending']);
                        } elseif ($requestArr['city_licensed'] == 'no') {
                            CompanyApprovalStatus::changeCompanyApprovalStatus($company_id, ['city_licensing' => 'not required']);
                        }
                    }
                }
            }


            // 6. Work Agreements Warranty //work_agreements_warranty

            if (isset($result['provide_written_warrenty']) || isset($result['written_warrenty'])) {
                if ($requestArr['provide_written_warrenty'] == 'yes' && $requestArr['written_warrenty'] == 'yes') {
                    CompanyApprovalStatus::changeCompanyApprovalStatus($company_id, ['work_agreements_warranty' => 'in process']);
                } elseif ($requestArr['provide_written_warrenty'] == 'yes' && $requestArr['written_warrenty'] == 'no') {
                    CompanyApprovalStatus::changeCompanyApprovalStatus($company_id, ['work_agreements_warranty' => 'pending']);
                } elseif ($requestArr['provide_written_warrenty'] == 'no') {
                    CompanyApprovalStatus::changeCompanyApprovalStatus($company_id, ['work_agreements_warranty' => 'not required']);
                }
            }


            // 7. Subcontractor Agreement
            if (isset($requestArr['subcontract_with_other_companies']) && $requestArr['subcontract_with_other_companies'] == 'yes') {
                if (isset($requestArr['subcontractor_to_work_with_other_companies']) && $requestArr['subcontractor_to_work_with_other_companies'] == 'yes') {
                    if (isset($requestArr['copy_of_subcontractor_agreement']) && $requestArr['copy_of_subcontractor_agreement'] == 'yes') {
                        CompanyApprovalStatus::changeCompanyApprovalStatus($company_id, ['subcontractor_agreement' => 'in process']);
                    } else {
                        CompanyApprovalStatus::changeCompanyApprovalStatus($company_id, ['subcontractor_agreement' => 'pending']);
                    }
                } else {
                    CompanyApprovalStatus::changeCompanyApprovalStatus($company_id, ['subcontractor_agreement' => 'pending']);
                }
            } else {
                CompanyApprovalStatus::changeCompanyApprovalStatus($company_id, ['subcontractor_agreement' => 'not required']);
            }
            // Code For Company Approval Status Update [End]
        }

        foreach ($request->allFiles() as $key => $file) {
            //$mediaObj = \App\Models\Custom::uploadFile($file);
            $mediaObj = \App\Models\Custom::uploadFile($file, 'media', [],true);

            if ($key == 'state_business_registeration_file_id') {
                $document_type = 'registered_legally_to_state';
            } else if ($key == 'proof_of_ownership_file_id') {
                $document_type = 'proof_of_ownership';
            } else if ($key == 'articles_of_incorporation_file_id') {
                $document_type = 'articles_of_incorporation_file';
            } else if ($key == 'state_licensed_file_id') {
                $document_type = 'state_licensing';
            } else if ($key == 'country_licensed_file_id') {
                $document_type = 'country_licensing';
            } else if ($key == 'city_licensed_file_id') {
                $document_type = 'city_licensing';
            } else if ($key == 'written_warrenty_file_id') {
                $document_type = 'written_warrenty';
            } else if ($key == 'subcontractor_agreement_file_id') {
                $document_type = 'subcontractor_agreement_file';
            }


            // Create Computer Document
            //$document_type = rtrim($key, '_id');
            $companyDocumentObj = CompanyDocument::create([
                        'company_id' => $company_id,
                        'document_type' => $document_type,
                        'file_id' => $mediaObj['mediaObj']->id,
                        'upload_by' => 'Company Owner',
                        'company_owner_id' => $companyUserObj->id,
            ]);

            // Assign Computer Document ID to Main Table
            $company_licensing_obj->$key = $companyDocumentObj->id;
            $company_licensing_obj->save();
        }

        return response()->json(['status' => '1']);
    }

    public function postCompanyInsurance(Request $request) {
        $requestArr = $request->all();

        if (isset($requestArr['same_insurance_agent_agency']) && $requestArr['same_insurance_agent_agency'] == 'yes') {
            $requestArr['general_liability_insurance_agent_agency_name'] = $requestArr['workers_compensation_insurance_agent_agency_name'];
            $requestArr['general_liability_insurance_agent_agency_phone_number'] = $requestArr['workers_compensation_insurance_agent_agency_phone_number'];
        }

        $company_id = Auth::guard('company_user')->user()->company_id;
        $companyObj = Company::find($company_id);

        $company_insurance_obj = CompanyInsurance::firstOrCreate(['company_id' => $company_id]);
        $new_temp_obj = CompanyInsurance::firstOrCreate(['company_id' => $company_id]);

        $new_temp_obj->fill($requestArr);
        $result = array_diff_assoc($new_temp_obj->toArray(), $company_insurance_obj->toArray());

        if (count($result) > 0) {
            $company_insurance_obj->update($requestArr);
        }


        /* 12-3-2020 Added start */
        if (isset($requestArr['general_liability_insurance_and_worker_compensation_insurance']) && $requestArr['general_liability_insurance_and_worker_compensation_insurance'] == 'Yes') {
            CompanyApprovalStatus::changeCompanyApprovalStatus($company_id, ['general_liablity_insurance_file' => 'pending']);
            if ($companyObj->trade_id == '1') {
                CompanyApprovalStatus::changeCompanyApprovalStatus($company_id, ['worker_comsensation_insurance_file' => 'pending']);
            } else {
                CompanyApprovalStatus::changeCompanyApprovalStatus($company_id, ['worker_comsensation_insurance_file' => 'not required']);
            }
        } else if (isset($requestArr['general_liability_insurance_and_worker_compensation_insurance']) && $requestArr['general_liability_insurance_and_worker_compensation_insurance'] != 'Yes') {
            CompanyApprovalStatus::changeCompanyApprovalStatus($company_id, ['general_liablity_insurance_file' => 'pending']);
            CompanyApprovalStatus::changeCompanyApprovalStatus($company_id, ['worker_comsensation_insurance_file' => 'not required']);
        }
        /* 12-3-2020 Added end */

        return response()->json(['status' => '1']);
    }

    public function postCustomerReferences(Request $request) {
        $requestArr = $request->all();
        $company_id = Auth::guard('company_user')->user()->company_id;

        $company_customer_ref_obj = CompanyCustomerReference::firstOrCreate(['company_id' => $company_id]);
        $new_temp_obj = CompanyCustomerReference::firstOrCreate(['company_id' => $company_id]);


        if (isset($requestArr['professional_affiliations'])) {
            $requestArr['professional_affiliations'] = json_encode($requestArr['professional_affiliations']);
        }
        if (isset($requestArr['customers'])) {
            $requestArr['customers'] = json_encode($requestArr['customers']);
        }


        if ($requestArr['ref_type'] == 'Customer References') {
            $requestArr['professional_affiliations'] = null;
            // Change Company Approval Status
            CompanyApprovalStatus::changeCompanyApprovalStatus($company_id, ['customer_references' => 'in process']);
        } else if ($requestArr['ref_type'] == 'Professional Affiliations') {
            $requestArr['customers'] = null;

            // Change Company Approval Status
            CompanyApprovalStatus::changeCompanyApprovalStatus($company_id, ['customer_references' => 'pending']);
        }

        // Check for data update
        $new_temp_obj->fill($requestArr);
        $result = array_diff_assoc($new_temp_obj->toArray(), $company_customer_ref_obj->toArray());

        if (count($result) > 0) {
            $company_customer_ref_obj->update($requestArr);
        }
        return response()->json(['status' => '1']);
    }

    public function postLeadNotification(Request $request) {
        $requestArr = $request->all();
        $company_id = Auth::guard('company_user')->user()->company_id;

        $company_lead_notification_obj = CompanyLeadNotification::firstOrCreate(['company_id' => $company_id]);
        $new_temp_obj = CompanyLeadNotification::firstOrCreate(['company_id' => $company_id]);

        // Check for data update
        $new_temp_obj->fill($requestArr);
        $result = array_diff_assoc($new_temp_obj->toArray(), $company_lead_notification_obj->toArray());

        if (count($result) > 0) {
            $company_lead_notification_obj->update($requestArr);
        }

        return response()->json(['status' => '1']);
    }

    public function postListingAgreement(Request $request) {
        $fileAttachments = [];
        $requestArr = $request->all();
        $companyUserObj = Auth::guard('company_user')->user();
        $companyObj = Company::find($companyUserObj->company_id);

        if ($companyObj->status != 'Paid Pending') {
            flash('Account application already done.')->warning();
            return back();
        }

        $company_listing_agreement_obj = CompanyListingAgreement::firstOrCreate(['company_id' => $companyObj->id]);
        $new_temp_obj = CompanyListingAgreement::firstOrCreate(['company_id' => $companyObj->id]);

        // Check for data update
        $new_temp_obj->fill($requestArr);
        $result = array_diff_assoc($new_temp_obj->toArray(), $company_listing_agreement_obj->toArray());

        if (count($result) > 0) {
            $company_listing_agreement_obj->update($requestArr);
        }
        //
        CompanyApprovalStatus::changeCompanyApprovalStatus($companyObj->id, ['online_application' => 'completed']);



        /* Application pdf generate start */
        $pdfCompanyObj = Company::with(['company_information', 'company_licensing', 'company_insurance', 'company_customer_references', 'company_lead_notification', 'company_listing_agreement'])->find($companyUserObj->company_id);
        $data['terms_page'] = Page::find('7');
        $data['company'] = $pdfCompanyObj;
        $category_service_list = Custom::company_service_category_list($pdfCompanyObj->id);
        $data['company_service_category_list'] = $category_service_list['company_service_category_list'];

        //return view('company.profile.application.pdf.pdf', $data);

        $pdf = PDF::loadView('company.profile.application.pdf.pdf', $data);
        $pdf_save_path = storage_path('app' . DIRECTORY_SEPARATOR . 'private' . DIRECTORY_SEPARATOR . 'media'. DIRECTORY_SEPARATOR . $pdfCompanyObj->company_name . '-' . $pdfCompanyObj->id . '-application.pdf');
        // $pdf->save('uploads/media/' . $pdfCompanyObj->company_name . '-' . $pdfCompanyObj->id . '-application.pdf');
        // $fileAttachments[] = 'uploads/media/' . $pdfCompanyObj->company_name . '-' . $pdfCompanyObj->id . '-application.pdf';

        $pdf->save($pdf_save_path);
        $fileAttachments[] = $pdf_save_path;
        $mediaObj = Media::create([
                    'file_name' => $pdfCompanyObj->company_name . '-' . $pdfCompanyObj->id . '-application.pdf',
                    'file_type' => 'application/pdf',
                    'file_extension' => 'pdf'
        ]);

        CompanyDocument::create([
            'company_id' => $companyObj->id,
            'document_type' => 'application_file',
            'file_id' => $mediaObj->id,
            'status' => 'completed'
        ]);
        /* Application pdf generate end */


        /* insurance pdf start */
        $company_insurance = $pdfCompanyObj->company_insurance;
        if ($company_insurance->general_liability_insurance_and_worker_compensation_insurance == 'Yes') {
            if ($company_insurance->same_insurance_agent_agency == 'yes') {
                $insurance_data = ['company' => $pdfCompanyObj];
                $insurance_pdf = PDF::loadView('company.profile.application.pdf.insurance_pdf', $insurance_data);
                $insurance_pdf_save_path = storage_path('app' . DIRECTORY_SEPARATOR . 'private' . DIRECTORY_SEPARATOR . 'company-application-documents'. DIRECTORY_SEPARATOR . $pdfCompanyObj->company_name . '-' . $pdfCompanyObj->id . '-insurance-file.pdf');
                // $insurance_pdf->save('uploads/company-application-documents/' . $pdfCompanyObj->company_name . '-' . $pdfCompanyObj->id . '-insurance-file.pdf');
                // $fileAttachments[] = 'uploads/company-application-documents/' . $pdfCompanyObj->company_name . '-' . $pdfCompanyObj->id . '-insurance-file.pdf';
                $insurance_pdf->save($insurance_pdf_save_path);
                $fileAttachments[] = $insurance_pdf_save_path;
            } else {
                $insurance_data = [
                    'company' => $pdfCompanyObj,
                    'insurance_type' => 'general_liability'
                ];
                $general_insurance_pdf = PDF::loadView('company.profile.application.pdf.insurance_pdf', $insurance_data);
                $general_insurance_pdf_save_path = storage_path('app' . DIRECTORY_SEPARATOR . 'private' . DIRECTORY_SEPARATOR . 'company-application-documents'. DIRECTORY_SEPARATOR . $pdfCompanyObj->company_name . '-' . $pdfCompanyObj->id . '-general-insurance-file.pdf');
                // $general_insurance_pdf->save('uploads/company-application-documents/' . $pdfCompanyObj->company_name . '-' . $pdfCompanyObj->id . '-general-insurance-file.pdf');
                // $fileAttachments[] = 'uploads/company-application-documents/' . $pdfCompanyObj->company_name . '-' . $pdfCompanyObj->id . '-general-insurance-file.pdf';
                $general_insurance_pdf->save($general_insurance_pdf_save_path);
                $fileAttachments[] = $general_insurance_pdf_save_path;

                if ($pdfCompanyObj->trade_id == 1) {
                    $insurance_data = [
                        'company' => $pdfCompanyObj,
                        'insurance_type' => 'worker_compensation'
                    ];

                    $worker_insurance_pdf = PDF::loadView('company.profile.application.pdf.insurance_pdf', $insurance_data);
                    $worker_insurance_pdf_save_path = storage_path('app' . DIRECTORY_SEPARATOR . 'private' . DIRECTORY_SEPARATOR . 'company-application-documents'. DIRECTORY_SEPARATOR . $pdfCompanyObj->company_name . '-' . $pdfCompanyObj->id . '-worker-insurance-file.pdf');
                    // $worker_insurance_pdf->save('uploads/company-application-documents/' . $pdfCompanyObj->company_name . '-' . $pdfCompanyObj->id . '-worker-insurance-file.pdf');
                    // $fileAttachments[] = 'uploads/company-application-documents/' . $pdfCompanyObj->company_name . '-' . $pdfCompanyObj->id . '-worker-insurance-file.pdf';
                    $worker_insurance_pdf->save($worker_insurance_pdf_save_path);
                    $fileAttachments[] = $worker_insurance_pdf_save_path;
                }
            }
        } else {
            $insurance_data = ['company' => $pdfCompanyObj];
            $insurance_pdf = PDF::loadView('company.profile.application.pdf.insurance_pdf', $insurance_data);
            $insurance_pdf_save_path = storage_path('app' . DIRECTORY_SEPARATOR . 'private' . DIRECTORY_SEPARATOR . 'company-application-documents'. DIRECTORY_SEPARATOR . $pdfCompanyObj->company_name . '-' . $pdfCompanyObj->id . '-insurance-file.pdf');
            // $insurance_pdf->save('uploads/company-application-documents/' . $pdfCompanyObj->company_name . '-' . $pdfCompanyObj->id . '-insurance-file.pdf');
            // $fileAttachments[] = 'uploads/company-application-documents/' . $pdfCompanyObj->company_name . '-' . $pdfCompanyObj->id . '-insurance-file.pdf';
            $insurance_pdf->save($insurance_pdf_save_path);
            $fileAttachments[] = $insurance_pdf_save_path;
        }
        /* insurance pdf end */


        /* Customer references pdf start */
        if ($pdfCompanyObj->company_customer_references->ref_type == 'Professional Affiliations') {
            $data = [
                'companyObj' => $pdfCompanyObj,
            ];

            $customer_reference_pdf = PDF::loadView('company.profile.application.pdf.customer_references_pdf', $data);
            $customer_reference_pdf_save_path = storage_path('app' . DIRECTORY_SEPARATOR . 'private' . DIRECTORY_SEPARATOR . 'company-application-documents'. DIRECTORY_SEPARATOR . $pdfCompanyObj->company_name . '-' . $pdfCompanyObj->id . '-customer-references.pdf');
            // $customer_reference_pdf->save('uploads/company-application-documents/' . $pdfCompanyObj->company_name . '-' . $pdfCompanyObj->id . '-customer-references.pdf');
            // $fileAttachments[] = 'uploads/company-application-documents/' . $pdfCompanyObj->company_name . '-' . $pdfCompanyObj->id . '-customer-references.pdf';
            $customer_reference_pdf->save($customer_reference_pdf_save_path);
            $fileAttachments[] = $customer_reference_pdf_save_path;
        } else if ($pdfCompanyObj->company_customer_references->ref_type == 'Customer References') {
            $data = [
                'companyObj' => $pdfCompanyObj,
            ];

            $customer_reference_pdf = PDF::loadView('company.profile.application.pdf.customer_references_pdf', $data);
            $customer_reference_pdf_save_path = storage_path('app' . DIRECTORY_SEPARATOR . 'private' . DIRECTORY_SEPARATOR . 'company-application-documents'. DIRECTORY_SEPARATOR . $pdfCompanyObj->company_name . '-' . $pdfCompanyObj->id . '-customer-references.pdf');
            // $customer_reference_pdf->save('uploads/company-application-documents/' . $pdfCompanyObj->company_name . '-' . $pdfCompanyObj->id . '-customer-references.pdf');
            $customer_reference_pdf->save($customer_reference_pdf_save_path);
            $customer_reference_pdf_media_save_path = storage_path('app' . DIRECTORY_SEPARATOR . 'private' . DIRECTORY_SEPARATOR . 'media'. DIRECTORY_SEPARATOR . $pdfCompanyObj->company_name . '-' . $pdfCompanyObj->id . '-customer-references.pdf');
            // $customer_reference_pdf->save('uploads/media/' . $pdfCompanyObj->company_name . '-' . $pdfCompanyObj->id . '-customer-references.pdf');
            $customer_reference_pdf->save($customer_reference_pdf_media_save_path);

            $mediaObj = Media::create([
                        'file_name' => $pdfCompanyObj->company_name . '-' . $pdfCompanyObj->id . '-customer-references.pdf',
                        'original_file_name' => $pdfCompanyObj->company_name . '-' . $pdfCompanyObj->id . '-customer-references.pdf',
                        'file_type' => 'application/pdf',
                        'file_extension' => 'pdf',
            ]);

            $companyDocumentObj = CompanyDocument::create([
                        'company_id' => $pdfCompanyObj->id,
                        'document_type' => 'references_form_file',
                        'file_id' => $mediaObj->id,
                        'upload_by' => 'Company Owner',
                        'company_owner_id' => $companyUserObj->id,
            ]);

            $company_customer_references = CompanyCustomerReference::firstOrCreate(['company_id' => $pdfCompanyObj->id]);
            $company_customer_references->customer_references_file_id = $companyDocumentObj->id;
            $company_customer_references->save();

            //$fileAttachments[] = 'uploads/company-application-documents/' . $pdfCompanyObj->company_name . '-' . $pdfCompanyObj->id . '-customer-references.pdf';
            $fileAttachments[] = $customer_reference_pdf_save_path;
        }
        /* Customer references pdf end */


        /* Required documents pdf start */
        $company_licensing = $pdfCompanyObj->company_licensing;
        $required_document_arr = [];
        if (!is_null($company_licensing)) {
            if ($company_licensing->legally_registered_within_state == 'yes' && $company_licensing->state_business_registeration == 'no') {
                $required_document_arr[] = "State Business Registration";
            }

            if ($company_licensing->legally_registered_within_state == 'no') {
                if ($company_licensing->copy_proof_of_ownership == 'no') {
                    $required_document_arr[] = "Copy Of Proof Of Ownership";
                }

                if ($company_licensing->income_tax_filling != 'Sole Proprietor' && $company_licensing->articles_of_incorporation == 'no') {
                    $required_document_arr[] = "Articles Of Incorporation";
                }
            }

            if ($company_licensing->state_licensed == 'yes' && $company_licensing->copy_state_licensed == 'no') {
                $required_document_arr[] = "State Licensing";
            }

            if ($company_licensing->country_licensed == 'yes' && $company_licensing->copy_country_licensed == 'no') {
                $required_document_arr[] = "County Licensing";
            }

            if ($company_licensing->city_licensed == 'yes' && $company_licensing->copy_city_licensed == 'no') {
                $required_document_arr[] = "City Licensing";
            }

            if ($company_licensing->provide_written_warrenty == 'yes' && $company_licensing->written_warrenty == 'no') {
                $required_document_arr[] = "Work Agreement Warranty";
            }

            if ($company_licensing->subcontractor_to_work_with_other_companies == 'yes' && $company_licensing->copy_of_subcontractor_agreement == 'no') {
                $required_document_arr[] = "Subcontractor Agreement";
            }

            $data = [
                'companyObj' => $pdfCompanyObj,
                'required_document_arr' => $required_document_arr
            ];

            $required_document_pdf = PDF::loadView('company.profile.application.pdf.required_documents_pdf', $data);
            $customer_reference_pdf_save_path = storage_path('app' . DIRECTORY_SEPARATOR . 'private' . DIRECTORY_SEPARATOR . 'company-application-documents'. DIRECTORY_SEPARATOR . $pdfCompanyObj->company_name . '-' . $pdfCompanyObj->id . '-required-documents-list.pdf');
            $required_document_pdf->save($customer_reference_pdf_save_path);
            $fileAttachments[] = $customer_reference_pdf_save_path;
        }
        /* Required documents pdf end */

        $companyObj->status = "Pending Approval";
        $companyObj->save();


        /* Application confirmation success mail to Company */
        $web_settings = $this->web_settings;
        $company_mail_id = "9"; /* Mail title: Company Online Application Submitted */
        $mailArr = Custom::generate_company_user_email_arr($pdfCompanyObj->company_information);
        $companyReplaceWithArr = [
            'company_name' => $pdfCompanyObj->company_name,
            'dashbord_link' => url('dashboard'),
            'phone_number' => ((isset($web_settings['phone_number']) && $web_settings['phone_number'] != '') ? $web_settings['phone_number'] : ''),
            'global_address' => ((isset($web_settings['office_address']) && $web_settings['office_address'] != '') ? $web_settings['office_address'] : ''),
            'global_email' => ((isset($web_settings['global_email']) && $web_settings['global_email'] != '') ? $web_settings['global_email'] : ''),
            'facebook_link' => ((isset($web_settings['facebook']) && $web_settings['facebook'] != '') ? $web_settings['facebook'] : ''),
            'twitter_link' => ((isset($web_settings['twitter']) && $web_settings['twitter'] != '') ? $web_settings['twitter'] : ''),
            'instagram_link' => ((isset($web_settings['instagram']) && $web_settings['instagram'] != '') ? $web_settings['instagram'] : ''),
            'rumble_link' => ((isset($web_settings['rumble']) && $web_settings['rumble'] != '') ? $web_settings['rumble'] : ''),
            'youtube_link' => ((isset($web_settings['youtube']) && $web_settings['youtube'] != '') ? $web_settings['youtube'] : ''),
            'unsubscription_link' => url('unsubscribe-page/company', ['company_slug' => $pdfCompanyObj->slug]),
            'request_generate_link' => $companyUserObj->email,
            'date' => $pdfCompanyObj->created_at->format(env('DATE_FORMAT')),
            'url' => url('account/application'),
            'email_footer' => $companyUserObj->email,
            'copyright_year' => date('Y'),
                //'main_service_category' => '',
        ];

        $messageArr = [
            'company_id' => $pdfCompanyObj->id,
            'message_type' => 'info',
            'link' => url('account/application'),
        ];
        Custom::companyMailMessageCreate($messageArr, $company_mail_id, $companyReplaceWithArr);

        if (!is_null($mailArr) && count($mailArr) > 0) {
            foreach ($mailArr AS $mail_item) {
                Mail::to($mail_item)->send(new CompanyMail($company_mail_id, $companyReplaceWithArr, $fileAttachments));
            }
        }


        if (isset($this->web_settings['global_email']) && $this->web_settings['global_email'] != '') {
            /* Application confirmation success mail to Admin */
            $admin_mail_id = "10"; /* Mail title: Company Online Application Submitted - Admin */
            $replaceWithArr = [
                'company_name' => $pdfCompanyObj->company_name
            ];

            Mail::to($this->web_settings['global_email'])->send(new AdminMail($admin_mail_id, $replaceWithArr, $fileAttachments));
        }

        flash('Your company application details has been updated.')->success();
        return redirect('dashboard');
    }

    /* Company customer references form download  */

    public function customer_references_download() {
        $company_id = Auth::guard('company_user')->user()->company_id;
        $companyObj = Company::with('company_customer_references')->find($company_id);

        if (!is_null($companyObj->company_customer_references) && $companyObj->company_customer_references->ref_type == 'Professional Affiliations') {
            $data = [
                'companyObj' => $companyObj,
            ];

            $customer_reference_pdf = PDF::loadView('company.profile.application.pdf.customer_references_pdf', $data);
            $customer_reference_pdf->download($companyObj->company_name . '-' . $companyObj->id . '-customer-references.pdf');
        } else {
            flash('No file available to download')->error();
            return back();
        }
    }

}

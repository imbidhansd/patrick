<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use PDF;
//use PDFWatermark;
use App\Models\Custom;
use App\Models\Company;
use App\Models\CompanyApplication;

class PDFController extends Controller {

    public function generate_pdf() {
        $data['company'] = Company::with(['company_information', 'company_licensing', 'company_insurance', 'company_customer_references', 'company_lead_notification', 'company_listing_agreement'])->find('18');

        $category_service_list = Custom::company_service_category_list('18');
        $data['company_service_category_list'] = $category_service_list['company_service_category_list'];

        //return view('company.profile.application.pdf.pdf', $data);

        $pdf = PDF::loadView('company.profile.application.pdf.pdf', $data);
        $pdf->download($data['company']->id . '.pdf');
    }

    public function generate_invoice_pdf() {
        $company_invoices = \App\Models\CompanyInvoice::with(['company', 'company_invoice_item'])->where('company_id', '18')->get();
        if (count($company_invoices) > 0) {
            foreach ($company_invoices as $company_invoice) {
                $data['company_invoice'] = $company_invoice;

                /* return view ('company.invoices.pdf', $data);
                  exit; */


                $pdf = PDF::loadView('company.invoices.pdf', $data);
                $pdf->mpdf->setWatermarkText('PAID');
                $pdf->mpdf->showWatermarkText = true;
                //$pdf->setWatermarkImage(url('/images/paid.jpg'));
                $uploadsPath = storage_path('app' . DIRECTORY_SEPARATOR . 'private' . DIRECTORY_SEPARATOR . 'company-invoices'. DIRECTORY_SEPARATOR. $data['company_invoice']->invoice_id . '.pdf');
                $pdf->save($uploadsPath);

                /* $watermark = new PDFWatermark('uploads/company-invoices/' . $data['company_invoice']->invoice_id . '.pdf');
                  exit; */


                $pdf->download();

                //$fileAttachments[] = 'uploads/company-invoices/' . $data['company_invoice']->invoice_id . '.pdf';
            }
        }
    }

    public function generate_test_pdf() {
        //dd(public_path('fonts/'));
        $data = [];
        $pdf = PDF::loadView('test-pdf', $data);
        $pdf->save('uploads/test-pdf/test.pdf');

        $pdf->download();
    }

    public function insurance_pdf() {
        $companyObj = Company::with(['company_insurance'])->find('35');
        $company_insurance = $companyObj->company_insurance;
        if ($company_insurance->general_liability_insurance_and_worker_compensation_insurance == 'Yes') {
            if ($company_insurance->same_insurance_agent_agency == 'yes') {
                $insurance_data = ['company' => $companyObj];
                $insurance_pdf = PDF::loadView('company.profile.application.pdf.insurance_pdf', $insurance_data);
                $insuranceUploadsPath = storage_path('app' . DIRECTORY_SEPARATOR . 'private' . DIRECTORY_SEPARATOR . 'company-insurances'. DIRECTORY_SEPARATOR. $companyObj->company_name . '-insurance_file.pdf');
                $insurance_pdf->save($insuranceUploadsPath);
                $fileAttachments[] = $insuranceUploadsPath;
            } else {
                $insurance_data = [
                    'company' => $companyObj,
                    'insurance_type' => 'general_liability'
                ];
                $general_insurance_pdf = PDF::loadView('company.profile.application.pdf.insurance_pdf', $insurance_data);
                $generalInsuranceUploadsPath = storage_path('app' . DIRECTORY_SEPARATOR . 'private' . DIRECTORY_SEPARATOR . 'company-insurances'. DIRECTORY_SEPARATOR. $companyObj->company_name . '-general-insurance_file.pdf');
                $general_insurance_pdf->save($generalInsuranceUploadsPath);
                $general_insurance_pdf->download($companyObj->company_name . '-general-insurance_file.pdf');

                $insurance_data = [
                    'company' => $companyObj,
                    'insurance_type' => 'worker_compensation'
                ];
                $worker_insurance_pdf = PDF::loadView('company.profile.application.pdf.insurance_pdf', $insurance_data);
                $workerInsuranceUploadsPath = storage_path('app' . DIRECTORY_SEPARATOR . 'private' . DIRECTORY_SEPARATOR . 'company-insurances'. DIRECTORY_SEPARATOR. $companyObj->company_name . '-worker-insurance_file.pdf');
                $worker_insurance_pdf->save($workerInsuranceUploadsPath);
                $worker_insurance_pdf->download($companyObj->company_name . '-worker-insurance_file.pdf');
            }
        } else {
            $insurance_data = ['company' => $companyObj];
            $insurance_pdf = PDF::loadView('company.profile.application.pdf.insurance_pdf', $insurance_data);
            $insuranceUploadsPath = storage_path('app' . DIRECTORY_SEPARATOR . 'private' . DIRECTORY_SEPARATOR . 'company-insurances'. DIRECTORY_SEPARATOR. $companyObj->company_name . '-insurance_file.pdf');
            $insurance_pdf->save( $insuranceUploadsPath);
            $insurance_pdf->download($companyObj->company_name . '-insurance_file.pdf');
        }
    }

    public function customer_reference_pdf() {
        $companyObj = Company::find('48');

        $data = [
            'companyObj' => $companyObj,
        ];

        //return view('company.profile.application.pdf.customer_references_pdf', $data);

        $customer_reference_pdf = PDF::loadView('company.profile.application.pdf.form_pdf', $data);
        $customer_reference_pdf->useActiveForms = true;
        $customer_reference_pdf->download($companyObj->company_name . '-customer-references.pdf');
    }

    public function customer_required_document_pdf() {
        $companyObj = Company::find('35');
        $company_licensing = $companyObj->company_licensing;

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
                $required_document_arr[] = "Country Licensing";
            }

            if ($company_licensing->city_licensed == 'yes' && $company_licensing->copy_city_licensed == 'no') {
                $required_document_arr[] = "City Licensing";
            }

            if ($company_licensing->provide_written_warrenty == 'yes' && $company_licensing->written_warrenty == 'no') {
                $required_document_arr[] = "Work Agreement Warranty";
            }
        }

        $data = [
            'companyObj' => $companyObj,
            'required_document_arr' => $required_document_arr
        ];


        //return view('company.profile.application.pdf.required_documents_pdf', $data);

        $required_document_pdf = PDF::loadView('company.profile.application.pdf.required_documents_pdf', $data);
        $required_document_pdf->download($companyObj->company_name . '-' . $companyObj->id . '-required-document-list.pdf');
    }

}

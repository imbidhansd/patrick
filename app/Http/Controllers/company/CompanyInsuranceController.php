<?php

namespace App\Http\Controllers\company;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use PDF;
use Auth;
use Validator;
use App\Models\Company;
use App\Models\CompanyInsurance;
use App\Models\CompanyApprovalStatus;

class CompanyInsuranceController extends Controller {

    public function mark_insurance_completed(Request $request) {
        $validator = Validator::make($request->all(), [
                    'insurance_type' => 'required',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        } else {
            $requestArr = $request->all();
            $company_id = Auth::guard('company_user')->user()->company_id;
            $today_date = now()->format(env('DB_DATE_FORMAT'));

            $company_insurance = CompanyInsurance::where('company_id', $company_id)->first();

            $company_approval_status = CompanyApprovalStatus::firstOrCreate(['company_id' => $company_id]);

            if ($requestArr['insurance_type'] == 'liability_insurance') {
                $company_insurance->general_liability_insurance_mark_as_completed_date = $today_date;
                $company_approval_status->general_liablity_insurance_file = 'in process';
            } else if ($requestArr['insurance_type'] == 'worker_compensation_insurance') {
                $company_insurance->workers_compensation_insurance_mark_as_completed_date = $today_date;
                $company_approval_status->worker_comsensation_insurance_file = 'in process';
            }

            $company_insurance->save();
            $company_approval_status->save();

            flash("Company Insurance marked as completed successfully.")->success();
            return back();
        }
    }

    public function liability_insurance_download() {
        $company_id = Auth::guard('company_user')->user()->company_id;
        $companyObj = Company::with('company_insurance')->find($company_id);

        $insurance_data = [
            'company' => $companyObj,
            'insurance_type' => 'general_liability'
        ];

        $worker_insurance_pdf = PDF::loadView('company.profile.application.pdf.insurance_pdf', $insurance_data);
        $worker_insurance_pdf->download($companyObj->company_name . '-' . $companyObj->id . '-general-insurance-file.pdf');
    }

    public function liability_insurance_view() {
        $company_id = Auth::guard('company_user')->user()->company_id;
        $companyObj = Company::with('company_insurance')->find($company_id);

        $insurance_data = [
            'company' => $companyObj,
            'insurance_type' => 'general_liability'
        ];

        $worker_insurance_pdf = PDF::loadView('company.profile.application.pdf.insurance_pdf', $insurance_data);
        return $worker_insurance_pdf->stream($companyObj->company_name . '-' . $companyObj->id . '-general-insurance-file.pdf');
    }

    public function worker_compensation_insurance_download() {
        $company_id = Auth::guard('company_user')->user()->company_id;
        $companyObj = Company::with('company_insurance')->find($company_id);

        $insurance_data = [
            'company' => $companyObj,
            'insurance_type' => 'worker_compensation'
        ];

        $worker_insurance_pdf = PDF::loadView('company.profile.application.pdf.insurance_pdf', $insurance_data);
        $worker_insurance_pdf->download($companyObj->company_name . '-' . $companyObj->id . '-worker-insurance-file.pdf');
    }

    public function worker_compensation_insurance_view() {
        $company_id = Auth::guard('company_user')->user()->company_id;
        $companyObj = Company::with('company_insurance')->find($company_id);

        $insurance_data = [
            'company' => $companyObj,
            'insurance_type' => 'worker_compensation'
        ];

        $worker_insurance_pdf = PDF::loadView('company.profile.application.pdf.insurance_pdf', $insurance_data);
        return $worker_insurance_pdf->stream($companyObj->company_name . '-' . $companyObj->id . '-worker-insurance-file.pdf');        
    }

}

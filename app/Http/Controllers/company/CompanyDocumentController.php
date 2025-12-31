<?php

namespace App\Http\Controllers\company;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use App\Models\Company;
use App\Models\CompanyDocument;

class CompanyDocumentController extends Controller {

    public function __construct() {
        $this->view_base = 'company.documents.';
    }

    public function company_documents(Request $request) {
        $companyObj = Company::find(Auth::guard('company_user')->user()->company_id);
        if ($companyObj->membership_level->paid_members == 'no') {
            flash("You haven't access to view page.")->warning();
            return redirect('dashboard');
        }

        $data['company_application_file'] = CompanyDocument::with('media')
                ->where([
                    ['company_id', $companyObj->id],
                    ['document_type', 'application_file'],
                    ['status', 'completed']
                ])
                ->latest()
                ->first();

        return view($this->view_base . 'company_documents', $data);
    }

}

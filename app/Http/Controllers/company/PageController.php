<?php

namespace App\Http\Controllers\company;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Mail\Admin\AdminMail;
use Illuminate\Support\Facades\Mail;
use Auth;
// Models
use App\Models\Custom;
use App\Models\Faq;
use App\Models\Company;
use App\Models\CompanyFaqQuestion;

class PageController extends Controller {

    public function __construct() {
        $this->web_settings = Custom::getSettings();
        $this->view_base = 'company.pages.';
    }

    // Profile Section
    public function faq() {
        if (Auth::guard('company_user')->check()) {
            $companyObj = Company::find(Auth::guard('company_user')->user()->company_id);
        }

        $faqs = Faq::select('faqs.*');

        if (isset($companyObj) && !is_null($companyObj)) {
            if (!is_null($companyObj->membership_level_id)) {
                $faqs->where(function ($query) use ($companyObj) {
                    $query->whereNull('faqs.membership_level_id');
                    $query->orWhere('faqs.membership_level_id', $companyObj->membership_level_id);
                });
            }

            if (!is_null($companyObj->status)) {
                $faqs->leftJoin('membership_statuses', 'faqs.membership_status_id', 'membership_statuses.id')
                        ->where(function ($query) use ($companyObj) {
                            $query->whereNull('faqs.membership_status_id');
                            $query->orWhere('membership_statuses.title', $companyObj->status);
                        });
            }


        }

        $data = ['faqs' => $faqs->active()->order()->get()];
        return view($this->view_base . 'faq', $data);
    }

    public function submit_faq(Request $request) {
        if (Auth::guard('company_user')->check()) {

            $requestArr = $request->all();
            $companyObj = Company::find(Auth::guard('company_user')->user()->company_id);
            $requestArr['company_id'] = Auth::guard('company_user')->user()->company_id;
            $requestArr['company_user_id'] = Auth::guard('company_user')->user()->id;

            CompanyFaqQuestion::create($requestArr);

            if (isset($this->web_settings['global_email']) && $this->web_settings['global_email'] != '') {
                /* Company faq created mail to Admin */
                $admin_mail_id = "38"; /* Mail title: Company FAQ Question Created - Admin */
                $adminReplaceArr = [
                    'company_name' => $companyObj->company_name,
                    'faq_question' => $requestArr['question'],
                    'faq_question_description' => $requestArr['content'],
                ];

                Mail::to($this->web_settings['global_email'])->send(new AdminMail($admin_mail_id, $adminReplaceArr));
            }

            flash('Your question has been place successfully, We will contact you in next 48 hours.')->success();
            return back();
        } else {
            flash('Kindly login first to ask a question')->error();
            return back();
        }
    }

}

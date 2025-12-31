<?php

namespace App\Observers;

use App\Models\Company;
use App\Models\CompanyApprovalStatus;

class CompanyObserver {

    /**
     * Handle the company "created" event.
     *
     * @param  \App\Models\Company  $company
     * @return void
     */
    public function created(Company $company) {
        //
        CompanyApprovalStatus::create([
            'company_id' => $company->id,
        ]);
    }

    /**
     * Handle the company "updated" event.
     *
     * @param  \App\Models\Company  $company
     * @return void
     */
    public function updating(Company $company) {
        //
        $original = $company->getOriginal();

        $diff_arr = array_diff_assoc($company->toArray(), $original);

        if (is_array($diff_arr) && count($diff_arr) > 0) {

            /* $company_approval_status_item = CompanyApprovalStatus::where('company_id', $company->id)->first();

              // For Company Logo [Start]
              if (in_array('company_logo_id', array_keys($diff_arr))) {
              $company_approval_status_item->company_logo = 'pending';
              }
              // For Company Logo [End]

              // For Company Bio [Start]
              if (in_array('company_bio', array_keys($diff_arr))) {
              $company_approval_status_item->company_bio = 'pending';
              }
              // For Company Bio [End]

              $company_approval_status_item->save(); */
        }
    }

    /**
     * Handle the company "deleted" event.
     *
     * @param  \App\Models\Company  $company
     * @return void
     */
    public function deleted(Company $company) {
        //
    }

    /**
     * Handle the company "restored" event.
     *
     * @param  \App\Models\Company  $company
     * @return void
     */
    public function restored(Company $company) {
        //
    }

    /**
     * Handle the company "force deleted" event.
     *
     * @param  \App\Models\Company  $company
     * @return void
     */
    public function forceDeleted(Company $company) {
        //
    }

}

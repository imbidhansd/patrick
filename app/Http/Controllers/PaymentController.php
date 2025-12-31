<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Company;
use App\Models\CompanyInvoice;
use App\Models\Custom;

use net\authorize\api\contract\v1 as AnetAPI;
use net\authorize\api\controller as AnetController;

class PaymentController extends Controller{
	public function update_subscription_status (){
		$companies = Company::select('companies.*')
			->with('membership_level')
			->leftJoin('membership_levels', 'companies.membership_level_id', 'membership_levels.id')
			->where([
				['membership_levels.charge_type', 'monthly_price'],
				['companies.renewal_date', now()->format(env('DB_DATE_FORMAT'))]
			])
			->whereNotNull('companies.subscription_id')
			->active()
			->order()
			->get();

		//dd($companies);

		if (count($companies) > 0){
			foreach ($companies AS $company_item){
				$company_invoice = CompanyInvoice::where([
					['company_id', $company_item->id],
					['payment_type', 'credit_card'],
					['subscription_id', $company_item->subscription_id]
				])
				->whereNull('transaction_id')
				->latest()
				->first();

				if (!is_null($company_invoice)){
					/* Get Subscription details */
					$subscription_transactions = Custom::get_subscription($company_item->subscription_id);

					if (!is_null($subscription_transactions) && !isset($subscription_transactions['success'])){
						foreach ($subscription_transactions AS $transaction_item){
							if ($transaction_item->getPayNum() == $company_invoice->subscription_pay_number){
								$company_invoice->transaction_id = $transaction_item->getTransId();
								$company_invoice->save();
							}
						}

						/* New Invoice generated */
						$new_company_invoice = $company_invoice->replicate();
						$new_company_invoice->transaction_id = null;
						$new_company_invoice->subscription_pay_number = $new_company_invoice->subscription_pay_number + 1;
						$new_company_invoice->invoice_paid_date = now()->format(env('DB_DATE_FORMAT'));
						$new_company_invoice->status = 'paid';
						$new_company_invoice->save();
						

						/* Company Approval/renewal date change */
						$daysToAdd = $company_item->membership_level->number_of_days;
		                $approval_date = \Carbon\Carbon::now();
		                $renewal_date = \Carbon\Carbon::now()->addDays($daysToAdd);

		                $company_item->approval_date = $approval_date;
		                $company_item->renewal_date = $renewal_date;
		                $company_item->status = "Active";
		                $company_item->save();
					}
				}
			}
		}
	}
}
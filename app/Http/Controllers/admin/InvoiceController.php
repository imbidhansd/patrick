<?php

	namespace App\Http\Controllers\admin;

	use App\Http\Controllers\Controller;
	use Illuminate\Http\Request;

	use View;
	use Validator;
	use Str;

	// Models [start]
	use App\Models\Custom;
	use App\Models\CompanyInvoice;
	use App\Models\CompanyInvoiceItem;
	use App\Models\Company;
	use App\Models\PackageServiceCategory;

	class InvoiceController extends Controller{

		public function generate_invoice(){
			//dd("123");

			$company = Company::find(1);
			$invoice_date =  date(env('DB_DATE_FORMAT'));
			$counter = '1';
			$invoice_id = str_pad($counter, '4', '0', STR_PAD_LEFT);

			$package_service_category = PackageServiceCategory::whereIn('package_id', ['1', '2', '3'])
				->with(['service_category_type', 'top_level_category', 'main_category', 'service_category'])
				->orderBy('service_category_type_id', 'ASC')
				->orderBy('top_level_category_id', 'ASC')
				->orderBy('main_category_id', 'ASC')
				->get();

			$final_amount = $price = 0;
			$description_txt = '<ul>';
			foreach ($package_service_category AS $service_category_item){
				$price += $service_category_item->fee;

				$description_txt .= '<li>'.$service_category_item->service_category_type->title.'</li>';
				$description_txt .= '<li>'.$service_category_item->top_level_category->title.'</li>';
				$description_txt .= '<li>'.$service_category_item->main_category->title.'</li>';
				$description_txt .= '<li>'.$service_category_item->service_category->title.'</li>';
			}
			$description_txt .= '</ul>';

			$company_invoice_insert_arr = [
				'company_id' => $company->id,
				'invoice_date' => $invoice_date,
				'invoice_id' => $invoice_id,
				'invoice_for' => $company->first_name.' '.$company->last_name,
				'final_amount' => $price,
				'status' => 'pending'
			];

			$company_invoice = CompanyInvoice::create($company_invoice_insert_arr);

			$company_invoice_item_insert_arr = [
				'company_invoice_id' => $company_invoice->id,
				'title' => 'Membership Fee',
				'description' => $description_txt,
				'amount' => $price,
				'qty' => 1,
				'total' => $price
			];

			CompanyInvoiceItem::create($company_invoice_item_insert_arr);

			dd('Company Invoice generated successfully.');
		}
	}

<?php

namespace App\Http\Controllers\company;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use App\Mail\Company\CompanyMail;
use App\Mail\Admin\AdminMail;
use Illuminate\Support\Facades\Mail;
use Auth;
use Session;
use Validator;
use PDF;
use DB;
// Models
use App\Models\Page;
use App\Models\State;
use App\Models\Custom;
use App\Models\ServiceCategory;
use App\Models\Trade;
use App\Models\Company;
use App\Models\CompanyUser;
use App\Models\CompanyServiceCategory;
use App\Models\CompanyZipcode;
use App\Models\CompanyInvoice;
use App\Models\ShoppingCart;
use App\Models\ShoppingCartServiceCategory;
use App\Models\Order;
use App\Models\Package;
use App\Models\PackageProduct;
use App\Models\PackageServiceCategory;
use App\Models\Product;
use App\Models\ProductServiceCategory;
use App\Models\RegisterationSession;
use App\Models\PreScreenSetting;
use App\Models\MembershipLevel;
use App\Models\CompanyApprovalStatus;
use App\Models\CompanyInformation;
use App\Models\CompanyInvoiceAddress;

class UpgradeController extends Controller {

    public function __construct() {
        $this->web_settings = Custom::getSettings();
        $this->view_base = 'company.';
    }

    /* Account Upgrade Page [Start] */

    public function full_listing_more() {
        $data = [
            'page' => Page::find('9'),
            'membership_fee' => PreScreenSetting::active()->find('3'),
            'membership_levels' => MembershipLevel::where([
                ['paid_members', 'yes'],
                ['lead_access', 'yes']
            ])
            ->whereIn('id', [6, 7])
            ->orderByRaw("
                            CASE
                                WHEN id = 6 THEN 1
                                WHEN id = 5 THEN 2
                                WHEN id = 4 THEN 3
                                ELSE 4
                            END")
            ->active()
            //->order()
            ->get(),
        ];
        return view($this->view_base . 'profile.upgrade.full_listing_more', $data);
    }

    public function credibility() {
        $data = [
            'page' => Page::find('12'),
        ];

        return view($this->view_base . 'profile.upgrade.credibility', $data);
    }

    public function index(Request $request) {
        $companyObj = Company::find(Auth::guard('company_user')->user()->company_id);
        $page = Page::find('8');

        if ($companyObj->trade_id == 2) {
            $page = Page::find('11');
        }
        $data = [
            'page' => $page,
        ];

        return view($this->view_base . 'profile.upgrade.application_process', $data);
    }

    public function terms_check(Request $request) {
        Session::put('upgrade_terms_check', 'yes');
    }

    public function step1(Request $request) {
        if (Session::has('upgrade_terms_check')) {
            $companyObj = Company::find(Auth::guard('company_user')->user()->company_id);
            if ($companyObj->membership_level->paid_members != 'no') {
                flash('Company Account has been upgraded already!.')->warning();
                return redirect('dashboard');
            }

            if (Session::has('promocode_applied')) {
                Session::forget('promocode_applied');
                return redirect('/account/upgrade/review');
            }

            $selected_include_rest_categories = $companyObj->include_rest_categories;
            $selected_top_level_categories = CompanyServiceCategory::where('company_id', $companyObj->id)->distinct()->pluck('top_level_category_id');


            $data = [
                'terms_page' => Page::find('7'),
                //
                'companyObj' => $companyObj,
                //
                'trades' => Trade::order()->pluck('title', 'id'),
                'states' => State::order()->pluck('name', 'id'),
                'company_service_categories' => CompanyServiceCategory::where('company_id', $companyObj->id)->where('status', 'active')->pluck('service_category_id'),
                'selected_top_level_categories' => !is_null($selected_top_level_categories) ? $selected_top_level_categories->toArray() : [],
                'selected_include_rest_categories' => $selected_include_rest_categories,
                'company_zip_codes' => CompanyZipcode::where('company_id', $companyObj->id)->get(),
            ];
            //dd($data);
            // check for accrediation member
            if ($companyObj->membership_level_id == '3') {
                $this->saveRegData('step1', ['membership_id' => '7']);
            }

            return view($this->view_base . 'profile.upgrade.index', $data);
        } else {
            flash('Please accept terms & conditions for upgrade')->error();
            return back();
        }
    }

    private function saveRegData($step, $requestArr) {
        $session_id = Session::getId();

        $reg_session = RegisterationSession::firstOrCreate(['session_id' => $session_id]);
        $content = json_decode($reg_session->content, true);

        $content[$step] = $requestArr;
        $content = json_encode($content);
        $reg_session->content = $content;
        $reg_session->save();
    }

    public function postStep1(Request $request) {
        $this->saveRegData('step1', $request->all());
        return ['status' => '1'];
    }

    public function postStep2(Request $request) {
        $this->saveRegData('step2', $request->all());
        return ['status' => '1'];
    }

    public function postStep3(Request $request) {
        $this->saveRegData('step3', $request->all());
        return ['status' => '1'];
    }

    public function postStep4(Request $request) {
        $this->saveRegData('step4', $request->all());
        return ['status' => '1'];
    }

    public function postStep5(Request $request) {
        $this->saveRegData('step5', $request->all());
        return ['status' => '1'];
    }

    public function postStep6(Request $request) {
        $this->saveRegData('step6', $request->all());
        return ['status' => '1'];
    }

    public function postStep7(Request $request) {
        $this->saveRegData('step7', $request->all());

        $companyUserObj = Auth::guard('company_user')->user();
        $companyObj = Company::find(Auth::guard('company_user')->user()->company_id);
        if ($companyObj->membership_level->paid_members != 'no') {
            flash('Company Account has been upgraded already!.')->warning();
            return redirect('dashboard');
        }

        $session_id = Session::getId();
        $reg_session = RegisterationSession::firstOrCreate(['session_id' => $session_id]);
        $content = json_decode($reg_session->content, true);

        //dd($content);

        $service_category_ids = [];
        $service_category_main_ids = [];
        $service_category_secondary_ids = [];
        $service_category_other_ids = [];

        if (isset($content['step4']['service_category_ids']) && is_array($content['step4']['service_category_ids'])) {
            $service_category_ids = array_merge($service_category_ids, $content['step4']['service_category_ids']);
            $service_category_main_ids = $content['step4']['service_category_ids'];
        }
        if (isset($content['step5']['service_category_ids']) && is_array($content['step5']['service_category_ids'])) {
            $service_category_ids = array_merge($service_category_ids, $content['step5']['service_category_ids']);
            $service_category_secondary_ids = $content['step5']['service_category_ids'];
        }


        //
        if (isset($content['step6']['include_rest_categories']) && $content['step6']['include_rest_categories'] == 'yes') {
            if (isset($content['step6']['service_category_ids']) && $content['step6']['include_rest_categories'] == 'yes' && is_array($content['step6']['service_category_ids'])) {
                $service_category_ids = array_merge($service_category_ids, $content['step6']['service_category_ids']);
                $service_category_other_ids = $content['step6']['service_category_ids'];
            }
        }

        /* Membership fee */
        $membership_level_item = MembershipLevel::find($content['step1']['membership_id']);

        $membership_fee = 0;
        if (!is_null($membership_level_item)) {
            $membership_fee = $membership_level_item->membership_fee;
        } else {
            dd("Error");
        }

        $pre_screen_settings = PreScreenSetting::pluck('price', 'slug')->toArray();
        //dd($content);
        // add data to Shopping cart table
        $shopping_cart_content = [
            "membership_level_id" => $membership_level_item->id,
            "membership_type" => $membership_level_item->charge_type,
            //"membership_fee" => $pre_screen_settings->membership_fee,
            "membership_fee" => $pre_screen_settings['annual-membership-fee'],
            "ownership_type" => $content['step2']['ownership_type'],
            "number_of_owners" => $content['step2']['number_of_owners'],
            //
            "trade_id" => $content['step3']['trade_id'],
            "top_level_category_ids" => $content['step3']['top_level_category_ids'],
            //
            "main_category_id" => $content['step4']['main_category_id'],
            //
            "secondary_main_category_id" => $content['step5']['secondary_main_category_id'],
            //
            "service_category_ids" => $service_category_ids,
            //
            "include_rest_categories" => isset($content['step6']) ? $content['step6']['include_rest_categories'] : null,
            "main_zipcode" => $content['step7']['main_zipcode'],
            "mile_range" => $content['step7']['mile_range'],
        ];

        //dd($shopping_cart_content);

        $shipping_cart_arr = [
            'session_id' => $session_id,
            'company_id' => Auth::guard('company_user')->user()->company_id,
            'content' => json_encode($shopping_cart_content),
        ];

        $companyObj = Company::find(Auth::guard('company_user')->user()->company_id);


        /* Session forget for upgrade terms check */
        if (Session::has('upgrade_terms_check')) {
            Session::forget('upgrade_terms_check');
        }


        // Delete Old Rows if available
        ShoppingCart::where('company_id', $companyObj->id)->delete();
        ShoppingCartServiceCategory::where('company_id', $companyObj->id)->delete();


        // Delete Old Users in available [Start]
        CompanyUser::where('company_id', $companyObj->id)->where('id', '!=', $companyUserObj->id)->delete();
        // Delete Old Users in available [End]


        ShoppingCart::create($shipping_cart_arr);

        if (is_array($service_category_ids) && count($service_category_ids) > 0) {
            $service_category_list = ServiceCategory::with(['main_category'])->whereIn('id', $service_category_ids)->get();


            if (!is_null($service_category_list)) {
                foreach ($service_category_list as $service_category_item) {

                    $fee = 0;
                    if ($membership_level_item->charge_type == 'annual_price') {
                        $fee = $service_category_item->main_category->annual_price;
                    } elseif ($membership_level_item->charge_type == 'monthly_price') {
                        $fee = $service_category_item->main_category->monthly_price;
                    } elseif ($membership_level_item->charge_type == 'ppl_price' && $service_category_item->ppl_price == null) {
                        $fee = $service_category_item->main_category->ppl_price;
                    } elseif ($membership_level_item->charge_type == 'ppl_price') {
                        $fee = $service_category_item->ppl_price;
                    }


                    $category_type = 'main';

                    if (in_array($service_category_item->id, $service_category_main_ids)) {
                        $category_type = 'main';
                    } elseif (in_array($service_category_item->id, $service_category_secondary_ids)) {
                        $category_type = 'sub';
                    } elseif (in_array($service_category_item->id, $service_category_other_ids)) {
                        $category_type = 'extra';
                    }

                    $insertArr = [
                        'company_id' => $companyObj->id,
                        'top_level_category_id' => $service_category_item->top_level_category_id,
                        'main_category_id' => $service_category_item->main_category_id,
                        'service_category_id' => $service_category_item->id,
                        'service_category_type_id' => $service_category_item->service_category_type_id,
                        'category_type' => $category_type,
                        'fee' => $fee,
                    ];

                    ShoppingCartServiceCategory::create($insertArr);
                }
            }

            /* Company Upgrade page submitted mail to Admin */
            $admin_mail_id = "80"; /* Mail title: Company Submitted Upgrade Page */
            $replaceWithArr = [
                'company_name' => $companyObj->company_name
            ];
            if (isset($this->web_settings['global_email']) && $this->web_settings['global_email'] != '') {
                //Mail::to('ajay.makwana87@gmail.com')->send(new AdminMail($admin_mail_id, $replaceWithArr));
                Mail::to($this->web_settings['global_email'])->send(new AdminMail($admin_mail_id, $replaceWithArr));
            }
            return redirect(route('account-upgrade-review'));
        }
    }

    public function accountUpgradePromocode(Request $request) {
        $validator = Validator::make($request->all(), [
                    'promocode' => 'required'
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        } else {
            $requestArr = $request->all();

            $companyObj = Company::find(Auth::guard('company_user')->user()->company_id);
            if ($companyObj->membership_level->paid_members != 'no') {
                flash('Company Account has been upgraded already!.')->warning();
                return redirect('dashboard');
            }

            /* check package is available */
            $package = Package::with(['membership_level', 'package_products', 'package_service_category'])
                    ->where([
                        ['package_code', $requestArr['promocode']],
                        ['company_id', $companyObj->id]
                    ])
                    ->active()
                    ->first();

            if (is_null($package)) {
                flash("Promotional code not found.")->error();
                return back();
            } else {
                $pre_screen_settings = PreScreenSetting::pluck('price', 'slug')->toArray();

                $jsonArr = [
                    'promotional_code' => $requestArr['promocode']
                ];

                /* Membership Type */
                $jsonArr['membership_type'] = $package->membership_level->charge_type;
                $jsonArr['membership_level_id'] = $package->membership_level_id;
                $jsonArr['membership_fee'] = str_replace(",", "", $package->membership_total_fee);
                /* Ownership type */
                $jsonArr['ownership_type'] = "private";
                $jsonArr['number_of_owners'] = $package->qty_of_owners;
                $jsonArr['trade_id'] = $package->trade_id;
                $jsonArr['top_level_category_ids'] = json_decode($package->top_level_categories);
                $jsonArr['main_category_id'] = $package->main_category_id;
                $jsonArr['service_category_ids'] = json_decode($package->service_categories);
                $jsonArr['secondary_main_category_id'] = $package->secondary_main_category_id;
                $jsonArr['include_rest_categories'] = $package->include_rest_categories;
                $jsonArr['main_zipcode'] = $companyObj->main_zipcode;
                $jsonArr['mile_range'] = $companyObj->mile_range;

                if ($package->membership_level->charge_type == 'ppl_price') {
                    $jsonArr['monthly_budget'] = $package->ppl_monthly_budget;
                }

                $first_owner_fee = $package->bg_pre_screen_first_owner_fee;
                if ($package->qty_of_owners > 1) {
                    $jsonArr['first_owner_fee'] = $first_owner_fee;
                    $jsonArr['other_owner_fee'] = $package->bg_pre_screen_other_owner_fee;

                    $jsonArr['bg_pre_screen_fee'] = $first_owner_fee + $package->bg_pre_screen_other_owner_fee;
                } else {
                    $jsonArr['first_owner_fee'] = $first_owner_fee;

                    $jsonArr['bg_pre_screen_fee'] = $first_owner_fee;
                }

                $jsonArr['onetime_setup_fee'] = $package->setup_fee;
                $jsonArr['setup_fee'] = $package->todays_total_fee;


                $service_fees = 0;
                if (count($package->package_service_category) > 0 && $package->membership_level_id != 7) {
                    $main_category_id = "";
                    foreach ($package->package_service_category as $service_category_item) {
                        if (($package->membership_level->charge_type != 'annual_price' || $package->membership_level->charge_type != 'monthly_price') && $main_category_id != $service_category_item->main_category_id) {

                            $main_category_id = $service_category_item->main_category_id;
                        }
                    }

                    if ($package->membership_level->charge_type != 'annual_price' || $package->membership_level->charge_type != 'monthly_price') {
                        $package_service = PackageServiceCategory::select(DB::raw('DISTINCT main_category_id, service_category_type_id, fee'))->where('package_id', $package->id)->get();

                        foreach ($package_service as $service_item) {
                            $service_fees += $service_item->fee;
                        }
                    }
                }

                $jsonArr['total_service_fees'] = $service_fees;

                $total_charges = (float) $package->todays_total_fee + str_replace(",", "", $package->membership_total_fee) + (float) $service_fees;
                $jsonArr['total_charge'] = $total_charges;


                if (count($package->package_products) > 0) {
                    $suggested_products = [];
                    foreach ($package->package_products AS $product_item) {
                        $suggested_products[$product_item->product_id]['title'] = $product_item->product->title;
                        $suggested_products[$product_item->product_id]['price'] = $product_item->product_price;
                    }

                    $jsonArr['suggested_products'] = $suggested_products;
                }

                $shipping_cart_arr = [
                    'session_id' => session()->getId(),
                    'company_id' => $companyObj->id,
                    'content' => json_encode($jsonArr),
                ];

                ShoppingCart::where('company_id', $companyObj->id)->delete();
                ShoppingCart::create($shipping_cart_arr);

                // Add all service Categories to shopping cart server categories
                ShoppingCartServiceCategory::where('company_id', $companyObj->id)->delete();

                if (count($package->package_service_category) > 0) {
                    foreach ($package->package_service_category as $service_category_item) {
                        $insertArr = [
                            'company_id' => $companyObj->id,
                            'top_level_category_id' => $service_category_item->top_level_category_id,
                            'main_category_id' => $service_category_item->main_category_id,
                            'service_category_id' => $service_category_item->service_category_id,
                            'service_category_type_id' => $service_category_item->service_category_type_id,
                            'fee' => $service_category_item->fee
                        ];

                        ShoppingCartServiceCategory::create($insertArr);
                    }
                }

                /* Company Upgrade page submitted mail to Admin */
                $admin_mail_id = "80"; /* Mail title: Company Submitted Upgrade Page */
                $replaceWithArr = [
                    'company_name' => $companyObj->company_name
                ];
                if (isset($this->web_settings['global_email']) && $this->web_settings['global_email'] != '') {
                    //Mail::to('ajay.makwana87@gmail.com')->send(new AdminMail($admin_mail_id, $replaceWithArr));
                    Mail::to($this->web_settings['global_email'])->send(new AdminMail($admin_mail_id, $replaceWithArr));
                }

                Session::put('promocode_applied', 'yes');

                flash('Promocode applied successfully.')->success();
                return redirect('/referral-list/application-process');
            }
        }
    }

    public function accountUpgradeReview(Request $request) {
        $companyObj = Company::find(Auth::guard('company_user')->user()->company_id);
        if ($companyObj->membership_level->paid_members != 'no') {
            flash('Company Account has been upgraded already!.')->warning();
            return redirect('dashboard');
        }

        $company_zip_codes = CompanyZipcode::where('company_id', $companyObj->id)->get();
        $shopping_cart_obj = ShoppingCart::where('company_id', $companyObj->id)->orderBy('id', 'DESC')->latest()->first();

        // To get selected membership level
        $content = json_decode($shopping_cart_obj->content, true);
        $membership_level_obj = null;
        if (isset($content['membership_level_id']) && $content['membership_level_id'] > 0) {
            $membership_level_obj = MembershipLevel::find($content['membership_level_id']);
        }

        $service_categories = ShoppingCartServiceCategory::with(['main_category', 'service_category', 'service_category_type'])
                ->where('company_id', $companyObj->id)
                ->orderBy('service_category_type_id', 'ASC')
                ->orderBy('top_level_category_id', 'ASC')
                ->orderBy('main_category_id', 'ASC')
                ->get();

        $pre_screen_settings = PreScreenSetting::pluck('price', 'slug')->toArray();

        $data = [
            'admin_page_title' => 'Review/Edit Selection',
            'companyObj' => $companyObj,
            'shopping_cart_obj' => $shopping_cart_obj,
            'service_categories' => $service_categories,
            'pre_screen_settings' => $pre_screen_settings,
            'company_zip_codes' => $company_zip_codes,
            'membership_level_obj' => $membership_level_obj,
        ];

        return view($this->view_base . 'profile.upgrade.review', $data);
    }

    public function update_review(Request $request) {
        $validator = Validator::make($request->all(), [
                    'membership_type' => 'required',
                    'ownership_type' => 'required',
                    'number_of_owners' => 'required',
                    'main_zipcode' => 'required',
                    //'mile_range' => 'required',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        } else {
            $requestArr = $request->all();
            $companyObj = Company::find(Auth::guard('company_user')->user()->company_id);
            $pre_screen_settings = PreScreenSetting::pluck('price', 'slug')->toArray();

            $shopping_cart_obj = ShoppingCart::where('company_id', $companyObj->id)->orderBy('id', 'DESC')->latest()->first();

            if (!is_null($shopping_cart_obj->content)) {
                $content = json_decode($shopping_cart_obj->content);
                $membership_level = MembershipLevel::active()->find($content->membership_level_id);
                $content->membership_type = $membership_level->charge_type;

                $content->ownership_type = $requestArr['ownership_type'];
                $content->number_of_owners = $requestArr['number_of_owners'];
                $content->main_zipcode = $requestArr['main_zipcode'];
                $content->mile_range = $requestArr['mile_range'];

                $content->first_owner_fee = $requestArr['first_owner_fee'];
                if ($content->number_of_owners > 1) {
                    $content->other_owner_fee = $requestArr['other_owner_fee'];
                }

                $content->onetime_setup_fee = $requestArr['onetime_setup_fee'];
                $content->setup_fee = $requestArr['setup_fee'];
                if (!isset($content->promotional_code)){
                    $content->membership_fee = $pre_screen_settings['annual-membership-fee'];
                }

                if ($content->membership_level_id != 7 && $content->membership_level_id != 6) {
                    $content->total_service_fees = $requestArr['total_service_fees'];
                } else {
                    $content->total_service_fees = 0;
                }

                $content->total_charge = $requestArr['total_charge'];

                $shopping_cart_obj->content = json_encode($content);
                $shopping_cart_obj->save();
            }

            /* Company Upgrade review page submitted mail to Admin */
            $admin_mail_id = "85"; /* Mail title: Company Submitted Upgrade Review Page */
            $replaceWithArr = [
                'company_name' => $companyObj->company_name
            ];
            if (isset($this->web_settings['global_email']) && $this->web_settings['global_email'] != '') {
                //Mail::to('ajay.makwana87@gmail.com')->send(new AdminMail($admin_mail_id, $replaceWithArr));
                Mail::to($this->web_settings['global_email'])->send(new AdminMail($admin_mail_id, $replaceWithArr));
            }

            flash("Review/Edit Selection updated successfully.")->success();
            return redirect('account/upgrade/suggested-products');
        }
    }

    public function suggested_products() {
        $package_products = [];
        $companyObj = Company::find(Auth::guard('company_user')->user()->company_id);
        if ($companyObj->membership_level->paid_members != 'no') {
            flash('Company Account has been upgraded already!.')->warning();
            return redirect('dashboard');
        }

        $shopping_cart_obj = ShoppingCart::where('company_id', $companyObj->id)->orderBy('id', 'DESC')->latest()->first();
        $content = json_decode($shopping_cart_obj->content, true);
        if (isset($content['promotional_code']) && $content['promotional_code'] != '') {
            /* get package id */
            $package = Package::where('package_code', $content['promotional_code'])->active()->first();
            $package_products = PackageProduct::where('package_id', $package->id)->pluck('product_id')->toArray();
        }

        /* products list get */
        $shopping_cart_service_categories = ShoppingCartServiceCategory::where('company_id', $companyObj->id)
                ->where(function ($query) {
                    $query->where('service_category_status', 'active');
                    $query->orWhere('main_category_status', 'active');
                })
                ->pluck('service_category_id');
        //dd($shopping_cart_service_categories);

        $product_service_categories = ProductServiceCategory::whereIn('service_category_id', $shopping_cart_service_categories)->pluck('product_id');
        //dd($product_service_categories);

        $products = Product::select('products.*')
                ->where(function ($query) use ($product_service_categories, $package_products) {
                    $query->whereIn('id', $product_service_categories);
                    $query->orWhereNull('service_category_type_id');

                    if (count($package_products) > 0) {
                        $query->orWhereIn('id', $package_products);
                    }
                })
                ->active()
                ->groupBy('id')
                ->get();
        //dd($products);

        $data = [
            'admin_page_title' => 'Suggested Products',
            'companyObj' => $companyObj,
            'shopping_cart_obj' => $shopping_cart_obj,
            //'company_charge_setting' => $company_charge_setting,
            'products' => $products
        ];

        return view($this->view_base . 'profile.upgrade.suggested_products', $data);
    }

    public function checkout(Request $request) {
        $companyObj = Company::find(Auth::guard('company_user')->user()->company_id);
        if ($companyObj->membership_level->paid_members != 'no') {
            flash('Company Account has been upgraded already!.')->warning();
            return redirect('dashboard');
        }

        $company_information_obj = CompanyInformation::where('company_id', $companyObj->id)->first();
        //dd($company_information_obj);

        $shopping_cart_obj = ShoppingCart::where('company_id', $companyObj->id)->orderBy('id', 'DESC')->latest()->first();
        $exp_month_list = [
            '01' => '01', '02' => '02', '03' => '03', '04' => '04',
            '05' => '05', '06' => '06', '07' => '07', '08' => '08',
            '09' => '09', '10' => '10', '11' => '11', '12' => '12',
        ];

        $exp_year_list = [];
        for ($i = date('Y'); $i <= date('Y') + 20; $i++) {
            $exp_year_list[$i] = (int) $i;
        }

        // To get selected membership level
        $content = json_decode($shopping_cart_obj->content, true);
        $membership_level_obj = null;
        if (isset($content['membership_level_id']) && $content['membership_level_id'] > 0) {
            $membership_level_obj = MembershipLevel::find($content['membership_level_id']);
        }

        $data = [
            'admin_page_title' => 'Submit Payment',
            'companyObj' => $companyObj,
            'company_user_obj' => Auth::guard('company_user')->user(),
            'company_information_obj' => $company_information_obj,
            'shopping_cart_obj' => $shopping_cart_obj,
            'membership_level_obj' => $membership_level_obj,
            //'company_charge_setting' => $company_charge_setting,
            'exp_month_list' => $exp_month_list,
            'exp_year_list' => $exp_year_list,
            'states' => State::order()->pluck('name', 'id'),
            'background_check_page' => Page::find(6),
            'terms_use_page' => Page::find(7),
        ];

        return view($this->view_base . 'profile.upgrade.checkout', $data);
    }

    public function postCheckout(Request $request) {
        if ($request->has('payment_option') && $request->get('payment_option') == 'credit_card') {
            $validator = Validator::make($request->all(), [
                        'company_id' => 'required',
                        'terms' => 'required',
                        'terms1' => 'required',
                        // Shipping
                        'ship.company_name' => 'required',
                        'ship.first_name' => 'required',
                        'ship.last_name' => 'required',
                        'ship.mailing_address' => 'required',
                        //'ship.suite' => 'required',
                        'ship.city' => 'required',
                        'ship.state_id' => 'required',
                        //'ship.county' => 'required',
                        'ship.zipcode' => 'required',
                        'ship.phone' => 'required',
                        // Billing
                        'bill.company_name' => 'required',
                        'bill.first_name' => 'required',
                        'bill.last_name' => 'required',
                        'bill.mailing_address' => 'required',
                        //'bill.suite' => 'required',
                        'bill.city' => 'required',
                        'bill.state_id' => 'required',
                        //'bill.county' => 'required',
                        'bill.zipcode' => 'required',
                        'bill.phone' => 'required',
            ]);
        } else if (!$request->has('payment_option')) {
            flash('Payment Method not found.')->error();
            return back();
        }


        if (isset($validator) && $validator->fails()) {
            return back()->withErrors($validator)->withInput();
        } else {


            $web_settings = $this->web_settings;
            $companyUserObj = Auth::guard('company_user')->user();

            $fileAttachments = [];
            $requestArr = $request->all();
            $companyObj = Company::with('membership_level')->find(Auth::guard('company_user')->user()->company_id);
            if ($companyObj->membership_level->paid_members != 'no') {
                flash('Company Account has been upgraded already!.')->warning();
                return redirect('dashboard');
            }

            $shopping_cart_obj = ShoppingCart::where('company_id', $requestArr['company_id'])->orderBy('id', 'DESC')->latest()->first();

            if (!is_null($shopping_cart_obj)) {
                $shopping_cart_content = json_decode($shopping_cart_obj->content);
                //dd($shopping_cart_content);

                $ship_address_arr = $requestArr['ship'];
                $ship_address_arr['company_id'] = $companyObj->id;
                $ship_address_arr['address_type'] = 'ship';
                $ship_address_obj = CompanyInvoiceAddress::firstOrCreate($ship_address_arr);
                $requestArr['ship_address_id'] = $ship_address_obj->id;

                $bill_address_arr = $requestArr['ship'];
                $bill_address_arr['company_id'] = $companyObj->id;
                $bill_address_arr['address_type'] = 'bill';
                $bill_address_obj = CompanyInvoiceAddress::firstOrCreate($bill_address_arr);
                $requestArr['bill_address_id'] = $bill_address_obj->id;

                //dd($requestArr);


                /* Company invoice create start */

                /* Company First Invoice create start */
                $company_invoice1 = Custom::generateFirstInvoice($requestArr, $shopping_cart_content);
                /* Company First Invoice create end */


                /* Company Second Invoice create start */
                $company_invoice2 = Custom::generateSecondInvoice($requestArr, $shopping_cart_content);
                /* Company Second Invoice create end */

                /* Company invoice create end */

                //dd("Company Invoice generated successfully.");

                if ($requestArr['payment_option'] == 'credit_card') {
                    $transaction_id = "";

                    $payment_fields = [
                        // 'card_number' => $requestArr['card_number'],
                        // 'exp_year' => $requestArr['exp_year'],
                        // 'exp_month' => $requestArr['exp_month'],
                        'final_amount' => $requestArr['setup_fee'],
                        // billing
                        'bill_first_name' => $bill_address_obj->first_name,
                        'bill_last_name' => $bill_address_obj->last_name,
                        'bill_company_name' => $bill_address_obj->company_name,
                        'bill_address' => $bill_address_obj->mailing_address,
                        'bill_city' => $bill_address_obj->city,
                        'bill_state' => $bill_address_obj->state->short_name,
                        'bill_zipcode' => $bill_address_obj->zipcode,
                        'bill_county' => $bill_address_obj->county,
                        // shipping
                        'ship_first_name' => $ship_address_obj->first_name,
                        'ship_last_name' => $ship_address_obj->last_name,
                        'ship_company_name' => $ship_address_obj->company_name,
                        'ship_address' => $ship_address_obj->mailing_address,
                        'ship_city' => $ship_address_obj->city,
                        'ship_state' => $ship_address_obj->state->short_name,
                        'ship_zipcode' => $ship_address_obj->zipcode,
                        'ship_county' => $ship_address_obj->county,
                        'company_id' => $requestArr['company_id'],
                        'payment_name' => 'Membership Upgrade',
                        'success_url' => env('APP_URL').'/account/upgrade/payment/checkout-success?session_id={CHECKOUT_SESSION_ID}',
                        'cancel_url' => env('APP_URL').'/account/upgrade/payment/checkout-cancel?session_id={CHECKOUT_SESSION_ID}'
                    ];
                    $payment_response = Custom::authorizeStripePayment($payment_fields);

                    //create order
                    $order = new Order();
                    $order->status = $payment_response->payment_status;
                    $order->total_price = ($payment_response->amount_total)/100;
                    $order->session_id = $payment_response->id;
                    $order->company_id = $payment_fields['company_id'];
                    $order->company_invoice1_id = $company_invoice1->id;
                    $order->company_invoice2_id = $company_invoice2->id;
                    $order->save();

                    return Redirect::away($payment_response->url);
                } else {
                    /* Check Payment mail to Company Mail */
                    $company_mail_id = '5'; // Mail title: Company Upgrade Check Payment Email
                    $companyReplaceWithArr = [
                        'company_name' => $companyObj->company_name,
                        'submit_application_link' => url('account/application'),
                        'phone_number' => ((isset($web_settings['phone_number']) && $web_settings['phone_number'] != '') ? $web_settings['phone_number'] : ''),
                        'global_domain' => ((isset($web_settings['global_domain']) && $web_settings['global_domain'] != '') ? $web_settings['global_domain'] : ''),
                        'global_address' => ((isset($web_settings['office_address']) && $web_settings['office_address'] != '') ? $web_settings['office_address'] : ''),
                        'global_email' => ((isset($web_settings['global_email']) && $web_settings['global_email'] != '') ? $web_settings['global_email'] : ''),
                        'facebook_link' => ((isset($web_settings['facebook']) && $web_settings['facebook'] != '') ? $web_settings['facebook'] : ''),
                        'twitter_link' => ((isset($web_settings['twitter']) && $web_settings['twitter'] != '') ? $web_settings['twitter'] : ''),
                        'instagram_link' => ((isset($web_settings['instagram']) && $web_settings['instagram'] != '') ? $web_settings['instagram'] : ''),
                        'rumble_link' => ((isset($web_settings['rumble']) && $web_settings['rumble'] != '') ? $web_settings['rumble'] : ''),
                        'youtube_link' => ((isset($web_settings['youtube']) && $web_settings['youtube'] != '') ? $web_settings['youtube'] : ''),
                        'unsubscription_link' => url('unsubscribe-page/company', ['company_slug' => $companyObj->slug]),
                        'request_generate_link' => $companyUserObj->email,
                        'date' => $companyObj->created_at->format(env('DATE_FORMAT')),
                        'url' => url('account/upgrade'),
                        'email_footer' => $companyUserObj->email,
                        'copyright_year' => date('Y'),
                            //'main_service_category' => '',
                    ];

                    $messageArr = [
                        'company_id' => $companyObj->id,
                        'message_type' => 'info',
                        'link' => url('account/upgrade'),
                    ];
                    Custom::companyMailMessageCreate($messageArr, $company_mail_id, $companyReplaceWithArr);

                    $admin_mail_id = '6'; // Mail title: Company Upgrade Check Payment Email - Admin
                }


                /* Company details update start */
                $category_reference = [
                    'top_level_category_ids' => $shopping_cart_content->top_level_category_ids,
                    'service_category_ids' => $shopping_cart_content->service_category_ids
                ];

                $companyUpdateArr = [
                    'membership_level_id' => $shopping_cart_content->membership_level_id,
                    'ownership_type' => $shopping_cart_content->ownership_type,
                    'number_of_owners' => $shopping_cart_content->number_of_owners,
                    'trade_id' => $shopping_cart_content->trade_id,
                    'main_category_id' => $shopping_cart_content->main_category_id,
                    'secondary_main_category_id' => $shopping_cart_content->secondary_main_category_id,
                    'category_reference' => json_encode($category_reference),
                    'include_rest_categories' => $shopping_cart_content->include_rest_categories,
                    'main_zipcode' => $shopping_cart_content->main_zipcode,
                    'mile_range' => $shopping_cart_content->mile_range,
                    'status' => 'Paid Pending'
                ];

                if (isset($shopping_cart_content->promotional_code) && $shopping_cart_content->promotional_code != '') {
                    /* get package id */
                    $package = Package::where('package_code', $shopping_cart_content->promotional_code)->active()->first();

                    $companyUpdateArr['package_id'] = $package->id;
                    $companyUpdateArr['package_code'] = $package->package_code;
                } else {
                    $companyUpdateArr['package_id'] = null;
                    $companyUpdateArr['package_code'] = null;
                }

                if (isset($shopping_cart_content->monthly_budget) && $shopping_cart_content->monthly_budget != '') {
                    $companyUpdateArr['permanent_budget'] = $shopping_cart_content->monthly_budget;
                    $companyUpdateArr['temporary_budget'] = $shopping_cart_content->monthly_budget;
                }

                if ($companyObj->main_zipcode != $shopping_cart_content->main_zipcode) {
                    try {
                        $mainZipcodeCity = Custom::getZipcodeDetail($shopping_cart_content->main_zipcode);
                        if (count($mainZipcodeCity) > 0) {
                            $companyUpdateArr['main_zipcode_city'] = $mainZipcodeCity['city'];
                        }
                    } catch (Exception $e) {
                        return 'fail';
                    }
                }

                $companyObj->update($companyUpdateArr);

                CompanyServiceCategory::where('company_id', $requestArr['company_id'])->delete();
                $category_listing_data = ShoppingCartServiceCategory::where('company_id', $requestArr['company_id'])
                        ->where(function ($query) {
                            $query->where('service_category_status', 'active');
                            $query->orWhere('main_category_status', 'active');
                        })
                        ->orderBy('service_category_type_id', 'ASC')
                        ->orderBy('top_level_category_id', 'ASC')
                        ->orderBy('main_category_id', 'ASC')
                        ->get();

                foreach ($category_listing_data as $service_category_item) {
                    $insertArr = [
                        'company_id' => $requestArr['company_id'],
                        'top_level_category_id' => $service_category_item->top_level_category_id,
                        'main_category_id' => $service_category_item->main_category_id,
                        'service_category_id' => $service_category_item->service_category_id,
                        'service_category_type_id' => $service_category_item->service_category_type_id,
                        'category_type' => $service_category_item->category_type,
                        'fee' => $service_category_item->fee,
                        'status' => 'active',
                    ];

                    CompanyServiceCategory::create($insertArr);
                }


                $main_zipcode = $shopping_cart_content->main_zipcode;
                $mile_range = $shopping_cart_content->mile_range;
                try {
                    $zipCodes = Custom::getZipCodeRange($main_zipcode, $mile_range);
                    $inactive_company_zipcodes = CompanyZipcode::where([
                                ['company_id', $companyObj->id],
                                ['status', 'inactive']
                            ])
                            ->pluck('zip_code')
                            ->toArray();

                    if (count($zipCodes) > 0) {
                        CompanyZipcode::where('company_id', $companyObj->id)->delete();

                        foreach ($zipCodes as $zipcode_item) {
                            $stateObj = State::where('short_name', $zipcode_item['state'])->first();

                            $insertZipcodeArr = [
                                'company_id' => $companyObj->id,
                                'zip_code' => $zipcode_item['zip_code'],
                                'distance' => $zipcode_item['distance'],
                                'city' => $zipcode_item['city'],
                                'state' => $zipcode_item['state'],
                                'state_id' => ((!is_null($stateObj)) ? $stateObj->id : null),
                            ];

                            if (count($inactive_company_zipcodes) > 0 && in_array($zipcode_item['zip_code'], $inactive_company_zipcodes)) {
                                $insertZipcodeArr['status'] = 'inactive';
                            } else {
                                $insertZipcodeArr['status'] = 'active';
                            }

                            CompanyZipcode::create($insertZipcodeArr);
                        }
                    }
                } catch (Exception $e) {
                    // fail
                }

                /* Company details update end */


                if ($requestArr['payment_option'] == 'credit_card') {
                    /* Generate PDF start */
                    /* Get Company Invoice data */
                    $company_invoices = CompanyInvoice::with(['company', 'company_invoice_item'])->where('company_id', $requestArr['company_id'])->whereIn('id', [$company_invoice1->id, $company_invoice2->id])->get();
                    if (count($company_invoices) > 0) {
                        foreach ($company_invoices as $company_invoice) {
                            $data['company_invoice'] = $company_invoice;
                            $pdf = PDF::loadView('company.invoices.pdf', $data);

                            if ($data['company_invoice']->status == 'paid') {
                                //$pdf->setWatermarkImage(env('APP_URL') . 'images/paid.png');
                                $pdf->mpdf->setWatermarkText('PAID');
                                $pdf->mpdf->showWatermarkText = true;
                            }
                            $uploadsPath = storage_path('app' . DIRECTORY_SEPARATOR . 'private' . DIRECTORY_SEPARATOR . 'company-invoices'. DIRECTORY_SEPARATOR. $data['company_invoice']->invoice_id . '.pdf');
                            // $pdf->save('uploads/company-invoices/' . $data['company_invoice']->invoice_id . '.pdf');
                            // $fileAttachments[] = 'uploads/company-invoices/' . $data['company_invoice']->invoice_id . '.pdf';
                            $pdf->save($uploadsPath);
                            $fileAttachments[] = $uploadsPath;

                        }
                    }
                    /* Generate PDF end */
                }

                /* Company upgrade account mail to Company */
                if (isset($company_mail_id) && $company_mail_id != '') {
                    $mailArr = Custom::generate_company_user_email_arr($companyObj->company_information);
                    if (!is_null($mailArr) && count($mailArr) > 0) {
                        foreach ($mailArr AS $mail_item) {
                            //Mail::to('ajay.makwana87@gmail.com')->send(new CompanyMail($mail_id, $replaceWithArr, $fileAttachments));
                            Mail::to($mail_item)->send(new CompanyMail($company_mail_id, $companyReplaceWithArr, $fileAttachments));
                        }
                    }
                }

                /* Company upgrade account mail to Admin */
                if (isset($admin_mail_id) && $admin_mail_id != '') {
                    $adminReplaceWithArr = [
                        'company_name' => $companyObj->company_name,
                        'account_type' => $companyObj->membership_level->title,
                    ];

                    if (isset($this->web_settings['global_email']) && $this->web_settings['global_email'] != '') {
                        Mail::to($this->web_settings['global_email'])->send(new AdminMail($admin_mail_id, $adminReplaceWithArr, $fileAttachments));
                    }
                }
            } else {
                flash("Shopping Cart data not found.")->error();
                return back();
            }

            $shopping_cart_obj->delete();
            ShoppingCartServiceCategory::where('company_id', $requestArr['company_id'])->delete();

            Session::put('invoice_number', $company_invoice2->invoice_id);
            return redirect('account/upgrade/payment/success');
        }
    }
    public function checkout_cancel(Request $request) {
        $companyObj = Company::with('membership_level')->find(Auth::guard('company_user')->user()->company_id);
        $session_id = $request->get('session_id');
        $stripe = new \Stripe\StripeClient(env('STRIPE_SECRET_KEY'));
        $session = $stripe->checkout->sessions->retrieve($session_id);
        //Get the order details
        $orderBySessionId = Order::where(
            [
                'session_id' => $session_id,
                'company_id' => $companyObj->id,
            ])->first();
        $orderBySessionId->status = 'cancelled';
        $orderBySessionId->save();
        $company_invoice1 = CompanyInvoice::where('id', $orderBySessionId->company_invoice1_id)->first();
        $company_invoice2 = CompanyInvoice::where('id', $orderBySessionId->company_invoice2_id)->first();

        if(!is_null($company_invoice1))
        {
            $company_invoice1->delete();
        }
        if(!is_null($company_invoice2))
        {
            $company_invoice2->delete();
        }
        return redirect('/account/upgrade/checkout');
        //flash($error_msg)->error();
        //return back()->withErrors($validator)->withInput();
    }
    public function checkout_success(Request $request) {
        $companyObj = Company::with('membership_level')->find(Auth::guard('company_user')->user()->company_id);
        $session_id = $request->get('session_id');
        $stripe = new \Stripe\StripeClient(env('STRIPE_SECRET_KEY'));
        $session = $stripe->checkout->sessions->retrieve($session_id);
        //dd($session);
        //$customer =  $stripe->customers->retrieve($session->customer);
        //Get the order details
        $orderBySessionId = Order::where(
            [
                'session_id' => $session_id,
                'company_id' => $companyObj->id,
            ])->first();
        $orderBySessionId->status = 'paid';
        $orderBySessionId->save();
        $company_invoice1 = CompanyInvoice::where('id', $orderBySessionId->company_invoice1_id)->first();
        $company_invoice2 = CompanyInvoice::where('id', $orderBySessionId->company_invoice2_id)->first();

        //Save invoice
        $company_approval_status = CompanyApprovalStatus::firstOrCreate(['company_id' => $companyObj->id]);
        $company_approval_status->one_time_setup_fee = 'completed';
        $company_approval_status->background_check_pre_screen_fees = 'completed';


        if ($companyObj->number_of_owners >= 1) {
            $company_approval_status->owner_1_bg_check_document_status = 'pending';
        }
        if ($companyObj->number_of_owners >= 2) {
            $company_approval_status->owner_2_bg_check_document_status = 'pending';
        }
        if ($companyObj->number_of_owners >= 3) {
            $company_approval_status->owner_3_bg_check_document_status = 'pending';
        }
        if ($companyObj->number_of_owners == 4) {
            $company_approval_status->owner_4_bg_check_document_status = 'pending';
        }

        $company_approval_status->save();

        $company_invoice1->status = "paid";
        $company_invoice1->invoice_paid_date = now()->format(env('DATE_FORMAT'));
        $company_invoice1->payment_type = "credit_card";
        $company_invoice1->transaction_id = $session->payment_intent;
        $company_invoice1->note = "Stripe payment";
        $company_invoice1->save();

        /* Credit Card Payment mail to Company Mail */
        $company_mail_id = '7';  // Mail title: Company Upgrade Credit Card Payment Email
        $companyReplaceWithArr = [
            'company_name' => $companyObj->company_name,
            'submit_application_link' => url('account/application'),
            'phone_number' => ((isset($web_settings['phone_number']) && $web_settings['phone_number'] != '') ? $web_settings['phone_number'] : ''),
            'global_domain' => ((isset($web_settings['global_domain']) && $web_settings['global_domain'] != '') ? $web_settings['global_domain'] : ''),
            'global_address' => ((isset($web_settings['office_address']) && $web_settings['office_address'] != '') ? $web_settings['office_address'] : ''),
            'global_email' => ((isset($web_settings['global_email']) && $web_settings['global_email'] != '') ? $web_settings['global_email'] : ''),
            'facebook_link' => ((isset($web_settings['facebook']) && $web_settings['facebook'] != '') ? $web_settings['facebook'] : ''),
            'twitter_link' => ((isset($web_settings['twitter']) && $web_settings['twitter'] != '') ? $web_settings['twitter'] : ''),
            'instagram_link' => ((isset($web_settings['instagram']) && $web_settings['instagram'] != '') ? $web_settings['instagram'] : ''),
            'rumble_link' => ((isset($web_settings['rumble']) && $web_settings['rumble'] != '') ? $web_settings['rumble'] : ''),
            'youtube_link' => ((isset($web_settings['youtube']) && $web_settings['youtube'] != '') ? $web_settings['youtube'] : ''),
            'unsubscription_link' => url('unsubscribe-page/company', ['company_slug' => $companyObj->slug]),
            'request_generate_link' => $companyObj->email,
            'date' => $companyObj->created_at->format(env('DATE_FORMAT')),
            'url' => url('account/upgrade'),
            'email_footer' => $companyObj->email,
            'copyright_year' => date('Y'),
                //'main_service_category' => '',
        ];

        $messageArr = [
            'company_id' => $companyObj->id,
            'message_type' => 'info',
            'link' => url('account/upgrade'),
        ];
        Custom::companyMailMessageCreate($messageArr, $company_mail_id, $companyReplaceWithArr);

        $admin_mail_id = '8'; // Mail title: Company Upgrade Credit Card Payment Email - Admin

        $shopping_cart_obj = ShoppingCart::where('company_id', $companyObj->id)->orderBy('id', 'DESC')->latest()->first();

        if (!is_null($shopping_cart_obj)) {
            $shopping_cart_content = json_decode($shopping_cart_obj->content);
            /* Company details update start */
            $category_reference = [
                'top_level_category_ids' => $shopping_cart_content->top_level_category_ids,
                'service_category_ids' => $shopping_cart_content->service_category_ids
            ];

            $companyUpdateArr = [
                'membership_level_id' => $shopping_cart_content->membership_level_id,
                'ownership_type' => $shopping_cart_content->ownership_type,
                'number_of_owners' => $shopping_cart_content->number_of_owners,
                'trade_id' => $shopping_cart_content->trade_id,
                'main_category_id' => $shopping_cart_content->main_category_id,
                'secondary_main_category_id' => $shopping_cart_content->secondary_main_category_id,
                'category_reference' => json_encode($category_reference),
                'include_rest_categories' => $shopping_cart_content->include_rest_categories,
                'main_zipcode' => $shopping_cart_content->main_zipcode,
                'mile_range' => $shopping_cart_content->mile_range,
                'status' => 'Paid Pending'
            ];

            if (isset($shopping_cart_content->promotional_code) && $shopping_cart_content->promotional_code != '') {
                /* get package id */
                $package = Package::where('package_code', $shopping_cart_content->promotional_code)->active()->first();

                $companyUpdateArr['package_id'] = $package->id;
                $companyUpdateArr['package_code'] = $package->package_code;
            } else {
                $companyUpdateArr['package_id'] = null;
                $companyUpdateArr['package_code'] = null;
            }

            if (isset($shopping_cart_content->monthly_budget) && $shopping_cart_content->monthly_budget != '') {
                $companyUpdateArr['permanent_budget'] = $shopping_cart_content->monthly_budget;
                $companyUpdateArr['temporary_budget'] = $shopping_cart_content->monthly_budget;
            }

            if ($companyObj->main_zipcode != $shopping_cart_content->main_zipcode) {
                try {
                    $mainZipcodeCity = Custom::getZipcodeDetail($shopping_cart_content->main_zipcode);
                    if (count($mainZipcodeCity) > 0) {
                        $companyUpdateArr['main_zipcode_city'] = $mainZipcodeCity['city'];
                    }
                } catch (Exception $e) {
                    return 'fail';
                }
            }

            $companyObj->update($companyUpdateArr);

            CompanyServiceCategory::where('company_id', $companyObj->id)->delete();
            $category_listing_data = ShoppingCartServiceCategory::where('company_id', $companyObj->id)
                    ->where(function ($query) {
                        $query->where('service_category_status', 'active');
                        $query->orWhere('main_category_status', 'active');
                    })
                    ->orderBy('service_category_type_id', 'ASC')
                    ->orderBy('top_level_category_id', 'ASC')
                    ->orderBy('main_category_id', 'ASC')
                    ->get();

            foreach ($category_listing_data as $service_category_item) {
                $insertArr = [
                    'company_id' => $companyObj->id,
                    'top_level_category_id' => $service_category_item->top_level_category_id,
                    'main_category_id' => $service_category_item->main_category_id,
                    'service_category_id' => $service_category_item->service_category_id,
                    'service_category_type_id' => $service_category_item->service_category_type_id,
                    'category_type' => $service_category_item->category_type,
                    'fee' => $service_category_item->fee,
                    'status' => 'active',
                ];

                CompanyServiceCategory::create($insertArr);
            }

            $main_zipcode = $shopping_cart_content->main_zipcode;
            $mile_range = $shopping_cart_content->mile_range;
            try {
                $zipCodes = Custom::getZipCodeRange($main_zipcode, $mile_range);
                $inactive_company_zipcodes = CompanyZipcode::where([
                            ['company_id', $companyObj->id],
                            ['status', 'inactive']
                        ])
                        ->pluck('zip_code')
                        ->toArray();

                if (count($zipCodes) > 0) {
                    CompanyZipcode::where('company_id', $companyObj->id)->delete();

                    foreach ($zipCodes as $zipcode_item) {
                        $stateObj = State::where('short_name', $zipcode_item['state'])->first();

                        $insertZipcodeArr = [
                            'company_id' => $companyObj->id,
                            'zip_code' => $zipcode_item['zip_code'],
                            'distance' => $zipcode_item['distance'],
                            'city' => $zipcode_item['city'],
                            'state' => $zipcode_item['state'],
                            'state_id' => ((!is_null($stateObj)) ? $stateObj->id : null),
                        ];

                        if (count($inactive_company_zipcodes) > 0 && in_array($zipcode_item['zip_code'], $inactive_company_zipcodes)) {
                            $insertZipcodeArr['status'] = 'inactive';
                        } else {
                            $insertZipcodeArr['status'] = 'active';
                        }

                        CompanyZipcode::create($insertZipcodeArr);
                    }
                }
            } catch (Exception $e) {
                // fail
            }
            /* Company details update end */

            /* Generate PDF start */
            /* Get Company Invoice data */
            $company_invoices = CompanyInvoice::with(['company', 'company_invoice_item'])->where('company_id', $companyObj->id)->whereIn('id', [$company_invoice1->id, $company_invoice2->id])->get();
            if (count($company_invoices) > 0) {
                foreach ($company_invoices as $company_invoice) {
                    $data['company_invoice'] = $company_invoice;
                    $pdf = PDF::loadView('company.invoices.pdf', $data);

                    if ($data['company_invoice']->status == 'paid') {
                        $pdf->mpdf->setWatermarkText('PAID');
                        $pdf->mpdf->showWatermarkText = true;
                        //$pdf->setWatermarkImage(env('APP_URL') . 'images/paid.png');
                    }
                     $uploadsPath = storage_path('app' . DIRECTORY_SEPARATOR . 'private' . DIRECTORY_SEPARATOR . 'company-invoices'. DIRECTORY_SEPARATOR. $data['company_invoice']->invoice_id . '.pdf');
                    // $pdf->save('uploads/company-invoices/' . $data['company_invoice']->invoice_id . '.pdf');
                    // $fileAttachments[] = 'uploads/company-invoices/' . $data['company_invoice']->invoice_id . '.pdf';
                    $pdf->save($uploadsPath);
                    $fileAttachments[] = $uploadsPath;
                }
            }
            /* Generate PDF end */

            /* Company upgrade account mail to Company */
            if (isset($company_mail_id) && $company_mail_id != '') {
                $mailArr = Custom::generate_company_user_email_arr($companyObj->company_information);
                if (!is_null($mailArr) && count($mailArr) > 0) {
                    foreach ($mailArr AS $mail_item) {
                       Mail::to($mail_item)->send(new CompanyMail($company_mail_id, $companyReplaceWithArr, $fileAttachments));
                    }
                }
            }

            /* Company upgrade account mail to Admin */
            if (isset($admin_mail_id) && $admin_mail_id != '') {
                $adminReplaceWithArr = [
                    'company_name' => $companyObj->company_name,
                    'account_type' => $companyObj->membership_level->title,
                ];

                if (isset($this->web_settings['global_email']) && $this->web_settings['global_email'] != '') {
                    Mail::to($this->web_settings['global_email'])->send(new AdminMail($admin_mail_id, $adminReplaceWithArr, $fileAttachments));
                }
            }

            $shopping_cart_obj->delete();
            ShoppingCartServiceCategory::where('company_id', $companyObj->id)->delete();
        }

        $data = [
            'admin_page_title' => 'Payment Submitted',
            'companyObj' => $companyObj,
            //'payment_customer' => $customer
        ];
        return view($this->view_base . 'profile.upgrade.payment_success', $data);
    }
    public function payment_success() {
        $invoice_number = Session::get('invoice_number');
        if ($invoice_number == null || $invoice_number == '') {
            return redirect('dashboard');
        }

        $companyObj = Company::find(Auth::guard('company_user')->user()->company_id);

        $data = [
            'admin_page_title' => 'Payment Submitted',
            'companyObj' => $companyObj,
        ];

        return view($this->view_base . 'profile.upgrade.payment_success', $data);
    }

    /* Account Upgrade Page [End] */


    /* Ajax call functions */

    public function remove_category_from_cart(Request $request) {
        if (
                ($request->has('category_id') && $request->get('category_id') != '') && ($request->has('category_type') && $request->get('category_type') != '' && $request->get('status') != '')
        ) {
            $companyObj = Company::find(Auth::guard('company_user')->user()->company_id);
            $shopping_cart_obj = ShoppingCart::where('company_id', $companyObj->id)->orderBy('id', 'DESC')->latest()->first();
            if (!is_null($shopping_cart_obj)) {
                $category_id = $request->get('category_id');
                $category_type = $request->get('category_type');
                $service_category_type_id = $request->get('service_category_type_id');
                $main_category_id = $request->get('main_category_id');

                if ($category_type == 'main_category') {
                    ShoppingCartServiceCategory::where([
                                ['main_category_id', $category_id],
                                ['service_category_type_id', $service_category_type_id],
                                ['company_id', $shopping_cart_obj->company_id]
                            ])
                            ->update([
                                'main_category_status' => $request->get('status'),
                                'service_category_status' => $request->get('status')
                    ]);
                } else {
                    ShoppingCartServiceCategory::where([
                                ['service_category_id', $category_id],
                                ['service_category_type_id', $service_category_type_id],
                                ['company_id', $shopping_cart_obj->company_id]
                            ])
                            ->update(['service_category_status' => $request->get('status')]);


                    if ($request->get('status') == 'active') {
                        ShoppingCartServiceCategory::where([
                                    ['main_category_id', $main_category_id],
                                    ['service_category_type_id', $service_category_type_id],
                                    ['company_id', $shopping_cart_obj->company_id]
                                ])
                                ->update(['main_category_status' => $request->get('status')]);
                    }
                }

                $message = 'Category removed successfully';
                if ($request->get('status') == 'active') {
                    $message = 'Category added successfully';
                }


                /* Company Upgrade page submitted mail to Admin */
                $admin_mail_id = "82"; /* Mail title: Company Change Service Categories In Upgrade Review Page */
                $replaceWithArr = [
                    'company_name' => $companyObj->company_name
                ];
                if (isset($this->web_settings['global_email']) && $this->web_settings['global_email'] != '') {
                    Mail::to($this->web_settings['global_email'])->send(new AdminMail($admin_mail_id, $replaceWithArr));
                }

                return [
                    'success' => 1,
                    'type' => 'success',
                    'title' => 'Success',
                    'message' => $message
                ];
            } else {
                return [
                    'success' => 0,
                    'type' => 'warning',
                    'title' => 'Warning',
                    'message' => 'Shopping Cart is empty.'
                ];
            }
        } else {
            return [
                'success' => 0,
                'type' => 'warning',
                'title' => 'Warning',
                'message' => 'Category ID not found.'
            ];
        }
    }

    public function update_cart(Request $request) {
        if ($request->has('upgrade_type') && $request->get('upgrade_type') != '') {
            $companyObj = Company::find(Auth::guard('company_user')->user()->company_id);

            $replaceWithArr = [
                'company_name' => $companyObj->company_name
            ];
            $shopping_cart_obj = ShoppingCart::where('company_id', $companyObj->id)->orderBy('id', 'DESC')->latest()->first();

            if (!is_null($shopping_cart_obj)) {
                if (!is_null($shopping_cart_obj->content)) {
                    $content = json_decode($shopping_cart_obj->content);

                    $pre_screen_settings = PreScreenSetting::pluck('price', 'slug')->toArray();

                    if ($request->get('upgrade_type') == 'monthly_budget') {
                        $content->monthly_budget = $request->get('monthly_budget');

                        $admin_mail_id = "84"; /* Mail title: Company Change Monthly Budget In Upgrade Review Page */
                    } else if ($request->has('owners_selection') && $request->get('owners_selection') != '') {
                        $content->number_of_owners = $request->get('owners_selection');
                    } else if ($request->has('membership_level_id') && $request->get('membership_level_id') != '') {
                        $admin_mail_id = "81"; /* Mail title: Company Change Membership In Upgrade Review Page */


                        $membership_level_item = MembershipLevel::active()->find($request->get('membership_level_id'));
                        $content->membership_level_id = $membership_level_item->id;
                        $content->membership_type = $membership_level_item->charge_type;
                        $content->membership_fee = $pre_screen_settings['annual-membership-fee'];

                        $shopping_cart_service_category_list = ShoppingCartServiceCategory::with(['main_category', 'service_category'])->where('company_id', $companyObj->id)->get();
                        if (!is_null($shopping_cart_service_category_list)) {
                            foreach ($shopping_cart_service_category_list as $shopping_cart_service_category_item) {
                                $fee = 0;
                                if ($membership_level_item->charge_type == 'annual_price') {
                                    $fee = $shopping_cart_service_category_item->main_category->annual_price;
                                } elseif ($membership_level_item->charge_type == 'monthly_price') {
                                    $fee = $shopping_cart_service_category_item->main_category->monthly_price;
                                } elseif ($membership_level_item->charge_type == 'ppl_price' && $shopping_cart_service_category_item->service_category->ppl_price == null) {
                                    $fee = $shopping_cart_service_category_item->main_category->ppl_price;
                                } elseif ($membership_level_item->charge_type == 'ppl_price') {
                                    $fee = $shopping_cart_service_category_item->service_category->ppl_price;
                                }

                                $shopping_cart_service_category_item->fee = $fee;
                                $shopping_cart_service_category_item->save();
                            }
                        }
                    } else if (
                            ($request->has('main_zipcode') && $request->get('main_zipcode') != '') && ($request->has('mile_range') && $request->get('mile_range') != '')
                    ) {

                        $requestArr = $request->all();
                        $content->main_zipcode = $requestArr['main_zipcode'];
                        $content->mile_range = $requestArr['mile_range'];

                        try {
                            $zipCodes = Custom::getZipCodeRange($request->get('main_zipcode'), $request->get('mile_range'));

                            if (count($zipCodes) > 0) {
                                CompanyZipcode::where('company_id', $companyObj->id)->delete();

                                foreach ($zipCodes as $zipcode_item) {
                                    $stateObj = State::where('short_name', $zipcode_item['state'])->first();

                                    $insertZipcodeArr = [
                                        'company_id' => $companyObj->id,
                                        'zip_code' => $zipcode_item['zip_code'],
                                        'distance' => $zipcode_item['distance'],
                                        'city' => $zipcode_item['city'],
                                        'state' => $zipcode_item['state'],
                                        'state_id' => ((!is_null($stateObj)) ? $stateObj->id : null),
                                    ];

                                    if (isset($requestArr['zipcode_item']) && count($requestArr['zipcode_item']) > 0 && in_array($zipcode_item['zip_code'], $requestArr['zipcode_item'])) {
                                        $insertZipcodeArr['status'] = 'active';
                                    } else {
                                        $insertZipcodeArr['status'] = 'inactive';
                                    }

                                    CompanyZipcode::create($insertZipcodeArr);
                                }
                            }
                        } catch (Exception $e) {
                            return 'fail';
                        }

                        $admin_mail_id = "83"; /* Mail title: Company Change Zipcode List In Upgrade Review Page */
                    } else if ($request->get('upgrade_type') == 'add_suggested_products') {
                        $requestArr = $request->all();

                        $content = (array) $content;
                        if (isset($content['suggested_products'])) {
                            unset($content['suggested_products']);
                        }

                        $total_charge = $products_fee = 0;
                        $membership_fee = $pre_screen_settings['annual-membership-fee'];
                        $setup_fee = $content['setup_fee'];
                        $total_service_fees = $content['total_service_fees'];

                        foreach ($requestArr['suggested_product_include'] as $key => $product_include_item) {
                            if ($product_include_item == "yes") {
                                $id = $requestArr['suggested_product_id'][$key];
                                $title = $requestArr['suggested_product_title'][$key];
                                $price = $requestArr['suggested_product_price'][$key];

                                $content['suggested_products'][$id] = [
                                    'title' => $title,
                                    'price' => $price
                                ];

                                $products_fee += $price;
                            }
                        }

                        if ($content['membership_type'] == 'ppl_price') {
                            $content['total_charge'] = $membership_fee + $products_fee;
                        } else {
                            $content['total_charge'] = $membership_fee + $total_service_fees + $products_fee;
                        }

                        $shopping_cart_obj->content = json_encode($content);
                        $shopping_cart_obj->save();

                        return redirect("account/upgrade/checkout");
                    }
                } else {
                    return [
                        'success' => 0,
                        'type' => 'error',
                        'title' => 'Error',
                        'message' => 'Please select which you want to update in your Shopping Cart.'
                    ];
                }

                $shopping_cart_obj->content = json_encode($content);
                $shopping_cart_obj->save();

                /* Company update upgrade review page content mail to Admin */
                if (isset($this->web_settings['global_email']) && $this->web_settings['global_email'] != '' && isset($admin_mail_id) && $admin_mail_id != '') {
                    Mail::to($this->web_settings['global_email'])->send(new AdminMail($admin_mail_id, $replaceWithArr));
                }

                return [
                    'success' => 0,
                    'type' => 'success',
                    'title' => 'Success',
                    'message' => 'Shopping Cart updated successfully.'
                ];
            } else {
                return [
                    'success' => 0,
                    'type' => 'error',
                    'title' => 'Error',
                    'message' => 'Company not found in Shopping Cart.'
                ];
            }
        } else {
            return [
                'success' => 0,
                'type' => 'error',
            ];
        }
    }

}
